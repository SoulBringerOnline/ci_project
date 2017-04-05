/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * ======================================================================*/
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>
#include <signal.h>
#include <unistd.h>
#include <stdarg.h>
#include <sys/time.h>
#include <sys/shm.h>
#include "redis_info.h"
#include "Common.h"

#ifdef  __cplusplus
extern "C" {
#endif
#include "oi_cfg.h"
#include "oi_misc.h"
#include "oi_file.h"
#include "oi_tlv.h"
#include "srv_log.h"
#include "multi_file_processing.h"
#include "srv_framework.h"
#ifdef  __cplusplus
}
#endif
#define SRV_NAME_UDP_MONI   1

typedef struct
{
    char szConfigFilePath[256];

    char szLocalIP[16];
    SrvFrameworkAddrDefine astUdpAddr[2];

    int32_t iMaxSocketNum;
    int32_t iMaxSecKeepClt;

    int32_t iMaxSessionTime;
} CONFIG;


typedef struct
{
    char cTmp;
} CltInfo;


static int32_t g_iSignal = 0;
static CONFIG g_stConfig;
static SVRLOG g_stLog;
static string g_strLog;
static int g_tCurTime;


#define GSK_INIT_LOG(name)     do {\
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,\
            "LOG_LEVEL", CFG_INT, &(g_stLog.iLogLevel), 5,\
            "LOG_SHIFT_TYPE", CFG_INT, &(g_stLog.iLogShiftType), 3,\
            "LOG_MAX_NUM", CFG_INT, &(g_stLog.iMaxLogNum), 30,\
            "LOG_MAX_SIZE", CFG_INT, &(g_stLog.iMaxLogSize), 10000000,\
            NULL\
            );\
    char szLogFilePath[256];\
    memset(szLogFilePath, 0, sizeof(szLogFilePath));\
    snprintf(szLogFilePath, sizeof(szLogFilePath) - 1,"../log/"#name"_");\
    OI_InitLogFile(&(g_stLog.stSvrLog), szLogFilePath, g_stLog.iLogShiftType, g_stLog.iMaxLogNum, g_stLog.iMaxLogSize);\
} while(0)

#define LOG(fmt, args...) do { OI_Log(&(g_stLog.stSvrLog), 2, "%s:%d(%s): " fmt, __FILE__, __LINE__, __FUNCTION__ , ## args); } while (0)


/*======================================================================
 *  REDIS相关
 ======================================================================*/
static redisContext *g_pRedisContext = NULL;

void V_InitRedis()
{
    CRedisInfo *pRedisInfo = new CRedisInfo(&(g_stLog.stSvrLog));
    if( pRedisInfo == NULL )
    {
        LOG("[REDIS INIT] FAILED" );
        exit(-1);
    }

    char sServerIP[16] = {0};
    int iServerPort = 0;
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
            "REDIS_HOST_0",CFG_STRING, &(sServerIP), "192.168.1.106", sizeof(sServerIP),
            "REDIS_PORT_0",CFG_INT, &(iServerPort), 0,
            NULL);
    g_pRedisContext = pRedisInfo->Get( sServerIP , iServerPort);
    if( g_pRedisContext == NULL)
    {
        g_strLog = "";
        g_strLog.append( " REDIS_ADDR(");
        g_strLog.append( sServerIP );
        g_strLog.append( ":");
        g_strLog.append( Common::tostr( iServerPort ) );
        g_strLog.append( ")访问失败！");
        LOG("[REDIS INIT] %s" , g_strLog.c_str());
        exit(-1);
    }
}

/* ==========================================================
 * network service logic processing
 *
 * ==========================================================
 */
int HandleAccept(SCTX *pstSctx, void *pCltInfo, int iListenAddrName){ return 0; }
int HandlePkgHead(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iBytesRecved, int *piPkgLen){ return 0; }
int HandlePkg(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iPkgLen){ return 0; }
int HandleConnect(SCTX * pstSctx, void *pCltInfo, int iAddrName){ return 0; }
int HandleCloseConn(SCTX *pstSctx, void *pCltInfo, int iAddrName, char *sErrInfo){ return 0; }
int HandleUdpPkg(SCTX * pstSctx, void *pCltInfo, int iUdpName, void *pPkg, int iPkgLen)
{
    char* pCur = (char*)pPkg;
    if (pCur[0] != STX) { LOG_BUG_RET(-1); }
    if (pCur[iPkgLen-1] != ETX) { LOG_BUG_RET(-2); }

    uint32_t dwAttrID = 0;
    uint32_t dwAttrValue = 0;
    char sIP[32] = {0};
    size_t dwIPLen = 0;

    uint16_t wTlvLen = iPkgLen - 2 ;
    tlv_list_t *pTlvList = tlv_parse((uint8_t *)(&pCur[1]), wTlvLen);
    if (!pTlvList){LOG_BUG_RET(-3); }

    auto funcParseTlv = [&]( tlv_list_t *pList )->int{
        if (get_uint32_from_tlv_chain(pList, 1, &dwAttrID) < 0 || dwAttrID == 0 )
        {
            LOG_BUG_RET(-10);
        }
        if (get_uint32_from_tlv_chain(pList, 2, &dwAttrValue) < 0 || dwAttrValue == 0 )
        {
            LOG_BUG_RET(-11);
        }
        tlv_t *pTlv = tlv_find(pTlvList, 3, 1);
        if (pTlv)
        {
            memcpy(sIP, pTlv->sVal, pTlv->wLen);
            dwIPLen = pTlv->wLen;
        }
        if( dwIPLen == 0 )
        {
            LOG_BUG_RET(-12);
        }

        return 0;
    };
    int iRet = funcParseTlv( pTlvList );
    tlv_list_free(pTlvList);
    pTlvList = NULL;
    if(iRet < 0 )
    {
        LOG_BUG_RET(iRet);
    }

    LOG("%s %d %d" , sIP , dwAttrID, dwAttrValue);
    
    switch (iUdpName)
    {
        case SRV_NAME_UDP_MONI:
            {
                g_tCurTime = time(NULL) + 28800;

                int iCurDay = g_tCurTime / 86400 ;
                int iCurMin = g_tCurTime % 86400 / 60;

                string strMin = Common::tostr( iCurMin );
                string strDay = Common::tostr( iCurDay );
                string strValue = Common::tostr( dwAttrValue );
                string strKey = "moni_attr#";
                strKey.append( Common::tostr( dwAttrID ) );
                strKey.append( "#" );
                strKey.append( strDay );
                redisCommand(g_pRedisContext, "HINCRBY %b %b %b" , strKey.data(), strKey.size() , strMin.data(), strMin.size() , strValue.data(), strValue.size() );
                strKey = "moni_srv_attr#";
                strKey.append( Common::tostr( dwAttrID ) );
                strKey.append( "#" );
                strKey.append( strDay );
                strKey.append( "#" );
                strKey.append( sIP );
                redisCommand(g_pRedisContext, "HINCRBY %b %b %b" , strKey.data(), strKey.size() , strMin.data(), strMin.size() , strValue.data(), strValue.size() );

            }
            break;

        default:
            break;
    }

    return 0;
}

int32_t HandleLoop(void)
{
    int32_t iSig = g_iSignal;

    g_iSignal = 0;
    switch (iSig)
    {
        case SIGTERM:
            LOG_INFO("catch SIGTERM. server stop");
            exit(0);
            break;
        case SIGUSR1:
            break;
        case SIGUSR2:
            break;
    }

    return 0;
}


/* ==========================================================
 * necessary initialization
 *
 * ==========================================================
 */

int32_t InitSrv()
{
    static SRV_CALLBACK stCallback;
    stCallback.HandleLoop = HandleLoop;
    stCallback.HandlePkgHead = HandlePkgHead;
    stCallback.HandlePkg = HandlePkg;
    stCallback.HandleUdpPkg = HandleUdpPkg;
    stCallback.HandleAccept = HandleAccept;
    stCallback.HandleConnect = HandleConnect;
    stCallback.HandleCloseConn = HandleCloseConn;

    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP_MONI;
    g_stConfig.astUdpAddr[0].nServerPort = 16100 ;
    strcpy(g_stConfig.astUdpAddr[0].sServerIP, g_stConfig.szLocalIP);

    if (SrvFrameworkInit(NULL, 0, sizeof(CltInfo),
                &(g_stLog.stSvrLog), g_stLog.iLogLevel,
                0, NULL, g_stConfig.iMaxSocketNum, g_stConfig.iMaxSecKeepClt,
                1, &g_stConfig.astUdpAddr[0],
                0, NULL,
                &stCallback))
    {
        LOG_FATAL("srv_framework init failed!");
        return -1;
    }

    return 0;
}



void SigHandler(int32_t iSig)
{
    g_iSignal = iSig;
}

void InitSigHandler(void)
{
    struct sigaction act;

    memset(&act, 0, sizeof(act));
    act.sa_handler = SigHandler;
    sigemptyset(&act.sa_mask);
    act.sa_flags = SA_RESTART;

    sigaction(SIGTERM, &act, NULL);
    sigaction(SIGUSR1, &act, NULL);
    sigaction(SIGUSR2, &act, NULL);
    sigaction(SIGALRM, &act, NULL);
}


int32_t Init(int32_t argc, char *argv[])
{
    if(argc != 3)
    {
        printf("Usage: %s ServerID ConfigFile\n", argv[0]);
        return -1;
    }

    GetAbsPath(g_stConfig.szConfigFilePath, argv[2], sizeof(g_stConfig.szConfigFilePath) );

    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
            "MAX_SOCKET_NUM", CFG_INT, &(g_stConfig.iMaxSocketNum), 20,
            "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 180,
            "LOCAL_IP", CFG_STRING, g_stConfig.szLocalIP, "192.168.128.128", sizeof(g_stConfig.szLocalIP),

            "MAX_SESSION_TIME", CFG_INT, &(g_stConfig.iMaxSessionTime), 360,

            NULL
            );
    DaemonInit();

    GSK_INIT_LOG(gsk_moni);

    V_InitRedis();

    if(InitSrv() < 0)
    {
        return -1;
    }
    InitSigHandler();
    srand(time(NULL));
    return 0;
}



int main(int argc, char* argv[])
{

    if(Init(argc, argv) < 0)
    {
        LOG_FATAL("Init failed!");
        exit(-1);
    }

    LOG_ANY("-------------------------");
    LOG_ANY("SRV START!");
    LOG_ANY("-------------------------");

    SrvFrameworkLoop();

    return 0;
}
