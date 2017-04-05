

/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author   :   yongshengzhao@vip.qq.com
 * Date     :   2014-08-15
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
#include "mongo_driver.h"
#include "pb_gsk_report.pb.h"
#ifdef  __cplusplus
extern "C" {
#endif
#include "oi_cfg.h"
#include "oi_misc.h"
#include "oi_file.h"
#include "oi_tlv.h"
#include "oi_log.h"
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
static pb_report_t_report g_l_pb_report_t_report;


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
 *  MONGO
 ======================================================================*/

static char g_szMongoIp[16];
static int g_iMongoPort;
static MongoDriver *g_pMongo = NULL;

void V_InitMongo()
{
    //MONGO
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
        "MONGO_HOST", CFG_STRING, g_szMongoIp, "192.168.165.241", sizeof(g_szMongoIp),
        "MONGO_PORT", CFG_INT, &(g_iMongoPort), 27017,
        NULL);

    char szBind[64];
    memset(szBind, 0, sizeof(szBind));
    snprintf(szBind, sizeof(szBind) - 1, "%s:%d", g_szMongoIp, g_iMongoPort);
    g_pMongo = MongoDriver::instance( szBind );
    if( g_pMongo == NULL )
    {
        LOG("[MONGO] Init failed! %s" , szBind);
    }
    LOG("[MONGO] %s" , szBind);
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

    switch (iUdpName)
    {
        case SRV_NAME_UDP_MONI:
            {
                g_tCurTime = time(NULL) + 28800;

                g_l_pb_report_t_report.Clear();
                if( g_l_pb_report_t_report.ParseFromArray( (uint8_t *)(&pCur[1]), iPkgLen-2 ) )
                {
                    time_t tCurTime = g_l_pb_report_t_report.f_time();
                    if(tCurTime == 0)
                    {
                        tCurTime = time(NULL) + 28800;
                    }
                    LOG("[REPORT] cmd:%d uin:%lu phone:%s" , 
                            g_l_pb_report_t_report.f_i_cmd() ,
                            g_l_pb_report_t_report.f_info().f_uin(),
                            g_l_pb_report_t_report.f_info().f_phone().c_str());

                    bson_t bo_report;
                    bson_init(&bo_report);
                    bson_append_int32(&bo_report, "f_cmd", -1,  g_l_pb_report_t_report.f_i_cmd() );
                    bson_append_utf8(&bo_report, "f_cmd_info", -1,  g_l_pb_report_t_report.f_s_cmd().c_str() , -1 );
                    bson_append_int32(&bo_report, "f_time", -1, tCurTime );
                    bson_append_int64(&bo_report, "f_uin", -1,  g_l_pb_report_t_report.f_info().f_uin() );
                    bson_append_utf8(&bo_report, "f_phone", -1,  g_l_pb_report_t_report.f_info().f_phone().c_str() , -1 );
                    bson_append_int32(&bo_report, "f_dye", -1,  g_l_pb_report_t_report.f_info().f_dye() );
                    bson_append_utf8(&bo_report, "f_ip", -1,  g_l_pb_report_t_report.f_info().f_ip().c_str() , -1);
                    bson_append_utf8(&bo_report, "f_name", -1,  g_l_pb_report_t_report.f_info().f_name().c_str(),-1);
                    bson_append_utf8(&bo_report, "f_log", -1,  g_l_pb_report_t_report.f_log().c_str(),-1);
                    /*
                       for (int i = 0 ; i < g_l_pb_report_t_report.f_log_size(); i++)
                       {
                       char sItemKey[16] = {0};
                       sprintf(sItemKey,"f_log_%d", i );
                       bson_t bo_item;
                       bson_init(&bo_item);
                       bson_append_utf8(&bo_item, "f_info", -1,  g_l_pb_report_t_report.f_log(i).f_info().c_str() ,-1);
                       bson_append_document(&bo_report, sItemKey, -1, &bo_item );
                       bson_destroy(&bo_item);
                       }
                       */
                    string strReport ;
                    if( g_l_pb_report_t_report.SerializeToString(&strReport) && strReport.size())
                    {
                        bson_append_binary(&bo_report, "f_pb", -1, BSON_SUBTYPE_BINARY, (uint8_t *)strReport.data(), strReport.size());
                    }

                    g_pMongo->insert( "log" , &bo_report );
                }

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

    V_InitMongo();

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

