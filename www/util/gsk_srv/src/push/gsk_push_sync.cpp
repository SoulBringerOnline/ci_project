/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * ======================================================================*/

#include "inc.h"
#include "def.h"
#include "util.h"

#define SRV_NAME_UDP_PUSH_SYNC   1

typedef struct
{
    char szConfigFilePath[256];
    
    char szLocalIP[16];
    SrvFrameworkAddrDefine astUdpAddr[2];
    
    int32_t iMaxSocketNum;
    int32_t iMaxSecKeepClt;
    
    int32_t iMaxSessionTime;
} CONFIG;

#pragma pack(1)
typedef struct
{
    uint32_t dwIP;
    uint16_t wPort;
    int32_t iSocket;
    uint32_t dwSocketCreateTime;
    uint32_t dwSrvIp;
    uint16_t wSrvPort;
    int iTime;
} GateInfo;
#pragma pack(0)

typedef struct
{
    char cTmp;
} CltInfo;

GSK_COMMON_DEFINE


#include "gsk_redis.h"
redisContext* GetRedisHandle()
{
    g_strLog = "";
    auto mapIter = g_mapRedis.find( 0 );
    if( mapIter == g_mapRedis.end() )
    {
        g_strLog.append( " RedisID(" );
        g_strLog.append( Common::tostr( 0 ) );
        g_strLog.append( ") 未注册!" );
        LOG_BUG_RET_NULL(  );
    }
    
    redisContext* pRedisContext = g_pRedisInfo->Get( mapIter->second.sServerIP , mapIter->second.nServerPort );
    if( pRedisContext == NULL)
    {
        g_strLog.append( " REDIS_ADDR(");
        g_strLog.append( mapIter->second.sServerIP );
        g_strLog.append( ":");
        g_strLog.append( Common::tostr( mapIter->second.nServerPort ) );
        g_strLog.append( ")访问失败！");
        LOG_BUG_RET_NULL(  );
    }
    return pRedisContext;
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
    if (!pCur || iPkgLen < ((int32_t)sizeof(GSKPkgHead)+2) ) { LOG_BUG_RET(-1); }
    if (pCur[0] != STX) { LOG_BUG_RET(-2); }
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    uint32_t dwLen = ntohl( pHead->dwLen );
    if (iPkgLen != (int)dwLen) { LOG_BUG_RET(-3); }
    if (pCur[dwLen-1] != ETX) { LOG_BUG_RET(-4); }
    
    g_stUser.stUin.dwUin = ntohl(pHead->dwUin);
    g_stUser.stUin.wClt = ntohs(pHead->wClt);
    g_tCurTime = time(NULL);
    
    switch (iUdpName)
    {
        case SRV_NAME_UDP_PUSH_SYNC:
        {
            if( g_stUser.stUin.dwUin && g_stUser.stUin.wClt && g_stUser.stUin.wClt != CLT_ID_OP )
            {
                GateInfo stGateInfo;
                stGateInfo.dwIP =  ntohl(pHead->dwCltIP) ;
                stGateInfo.wPort =  ntohs(pHead->wCltPort) ;
                stGateInfo.iSocket =  ntohl(pHead->iFd) ;
                stGateInfo.dwSocketCreateTime =  ntohl(pHead->dwTime) ;
                stGateInfo.dwSrvIp =  ntohl(pHead->dwRspIP) ;
                stGateInfo.wSrvPort =  ntohs(pHead->wRspPort) ;
                stGateInfo.iTime = g_tCurTime;
                
                LOG_DEBUG("%s:%hu fd:%d", inet_ntoa( *(in_addr*)(&stGateInfo.dwSrvIp) ) , stGateInfo.wSrvPort , stGateInfo.iSocket);
                
                redisContext* pRedisContext = GetRedisHandle();
                if( pRedisContext )
                {
                    string strRedisCmd = "HSET PUSH_U#"; //push user-<clt:gateinfo>
                    strRedisCmd.append(Common::tostr( g_stUser.stUin.dwUin ));
                    strRedisCmd.append(" ");
                    strRedisCmd.append(Common::tostr( g_stUser.stUin.wClt ));
                    strRedisCmd.append(" ");
                    strRedisCmd.append(Common::tostr(stGateInfo.dwIP));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.wPort));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.iSocket));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.dwSocketCreateTime));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.dwSrvIp));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.wSrvPort));
                    strRedisCmd.append("#");
                    strRedisCmd.append(Common::tostr(stGateInfo.iTime));

                    redisCommand(pRedisContext, strRedisCmd.c_str());
                }
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
            LOG_INFO("catch SIGTERM. push server stop");
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
    
    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP_PUSH_SYNC;
    g_stConfig.astUdpAddr[0].nServerPort = UDP_SRV_PORT_PUSH_SYNC ;
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
                     
                     "MAX_SESSION_TIME", CFG_INT, &(g_stConfig.iMaxSessionTime), 300,
                     
                     NULL
                     );
    DaemonInit();
    
    GSK_INIT_LOG(gsk_push_sync);
    
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
