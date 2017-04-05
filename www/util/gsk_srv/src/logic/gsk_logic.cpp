/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

#include "gsk_util.h"
#include "gsk_redis.h"
#include "gsk_mongo.h"
#include "gsk_push.h"

#include "gsk_logic_util_comm.h"
#include "gsk_logic_util_data.h"
#include "gsk_logic_util_prompt.h"


#include "gsk_logic_user.h"


#include "gsk_logic_handle.h"

#include <chrono>

extern void ReInit() ;




/*************************************************************
 handle single
 *************************************************************/
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

void ExpireTrigger(uint32_t dwDataLen, char *sData)
{
    SessionCached* pMgCached = (SessionCached*)sData;
    LOG_WARN("[Expire] UIN[%u %hu] SEQ:%u ",pMgCached->dwUin, pMgCached->wClt, pMgCached->dwSeq );
}

/* ==========================================================
 * network service logic processing
 *
 * ==========================================================
 */
int HandleLogic( void *pPkg, int iPkgLen , int iFrom )
{
    V_Clear();
    if(!pPkg) { LOG_BUG_RET(-1); }
    if (iPkgLen < ((int32_t)sizeof(GSKPkgHead)+2) ) { LOG_BUG_RET(-2); }
    
    char* pCur = (char*)pPkg;
    if (pCur[0] != STX){LOG_BUG_RET(-3);}
    
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    uint32_t dwLen  = ntohl( pHead->dwLen );
    uint16_t wCmd  = ntohs( pHead->wCmd );
    if (iPkgLen != (int)dwLen) { LOG_BUG_RET(-4);}
    if (pCur[dwLen-1] != ETX){LOG_BUG_RET(-5);}
    
    std::chrono::time_point<std::chrono::high_resolution_clock> start, end;
    start = std::chrono::high_resolution_clock::now();
    
    g_tCurTime = time(NULL);
    srand( g_tCurTime % 7951 * 13 + pHead->dwSeq % 7907 * 31 );
    
    g_stUser.stUin.dwUin = ntohl(pHead->dwUin);
    g_stUser.stUin.wClt = ntohs(pHead->wClt);
    
    g_l_pb_report_t_report.mutable_f_info()->set_f_uin( g_stUser.stUin.dwUin );
    g_l_pb_report_t_report.mutable_f_info()->set_f_clt( g_stUser.stUin.wClt );
    g_l_pb_report_t_report.set_f_time( g_tCurTime );
    
    g_l_pb_report_t_report.set_f_i_cmd( wCmd );
    
    g_strCmdName = "未知命令";
    if( g_mapCmdInfo.find( wCmd) != g_mapCmdInfo.end() )
    {
        g_strCmdName = g_mapCmdInfo[wCmd];
    }
    g_l_pb_report_t_report.set_f_s_cmd( g_strCmdName  );
    
    if( pHead->dwCltIP )
    {
        g_l_pb_report_t_report.mutable_f_info()->set_f_ip( inet_ntoa( *(in_addr*)(&pHead->dwCltIP) ) );
    }
    
    HandleCommand(pPkg, iPkgLen, wCmd, iFrom);

    end = std::chrono::high_resolution_clock::now();
    std::size_t total  = std::chrono::duration_cast<std::chrono::microseconds>(end - start).count();
    LOG_DEBUG("[ELAPSED_TIME] CMD(0x%X %s) %f ms", wCmd, g_strCmdName.c_str(), total/1000.f );
    
    return 0;
}

int32_t HandleAccept(SCTX *pstSctx, void *pCltInfo, int iListenAddrName)
{
    memset(pCltInfo, 0, sizeof(CltInfo));
    LOG_DEBUG("[ACCEPT] fd=%d ip=%s port=%hu", pstSctx->iSocket, inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    return 0;
}

int32_t HandlePkgHead(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iBytesRecved, int *piPkgLen)
{
    if(!pPkg || !piPkgLen ) { LOG_BUG_RET(-1); }
    
    char* pCur = (char*)pPkg;
    switch( iAddrName )
    {
        case SRV_NAME_TCP_FROM_II:
        {
            if(pCur[0] != STX) { LOG_BUG_RET(-2); }
            GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
            *piPkgLen = ntohl(pHead->dwLen);
            break;
        }
    }
    return 0;
}

int32_t HandlePkg(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iPkgLen)
{
    return HandleLogic(pPkg , iPkgLen, PROCESS_TCP);
}

int32_t HandleLoop(void)
{
    int32_t iSig = g_iSignal;
    
    g_iSignal = 0;
    switch (iSig)
    {
        case SIGTERM:
            LOG_INFO("catch -SIGTERM- , srv stop");
            zmq_close (g_pZMQPublisher);
            zmq_ctx_destroy (g_pZMQContext);
            
            delete g_pCltInfo;
            
            exit(0);
            break;
        case SIGUSR1:
            ReInit();
            LoadData();
            break;
        case SIGUSR2:
            break;
    }
    
    
    return 0;
}

int HandleUdpPkg(SCTX * pstSctx, void *pCltInfo, int iUdpName, void *pPkg, int iPkgLen)
{
    char* pCur = (char*)pPkg;
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    //Head
    if( pHead->dwRspIP == 0 || pHead->wRspPort == 0 )
    {
        pHead->dwRspIP = *(uint32_t*)&pstSctx->stClientAddr.sin_addr.s_addr;
        pHead->wRspPort = pstSctx->stClientAddr.sin_port;
    }
    return HandleLogic(pPkg , iPkgLen, PROCESS_UDP);
}

int HandleCloseConn(SCTX *pstSctx, void *pCltInfo, int iAddrName, char *sErrInfo)
{
    LOG_DEBUG("close a connection. fd=%d ip=%s port=%hu", pstSctx->iSocket, inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    g_pCltInfo->Del( inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    return 0;
}
int HandleConnect(SCTX * pstSctx, void *pCltInfo, int iAddrName)
{
    return 0;
}

int32_t InitSrv()
{
    //初始化srv_framework
    static SRV_CALLBACK stCallback;
    stCallback.HandleLoop = HandleLoop;
    stCallback.HandlePkgHead = HandlePkgHead;
    stCallback.HandlePkg = HandlePkg;
    stCallback.HandleUdpPkg = HandleUdpPkg;
    stCallback.HandleAccept = HandleAccept;
    stCallback.HandleConnect = HandleConnect;
    stCallback.HandleCloseConn = HandleCloseConn;
    
    //TCP SERVER
    g_stConfig.astTcpAddr[0].iAddrName = SRV_NAME_TCP_FROM_II;
    g_stConfig.astTcpAddr[0].iIsHttpProtocol = 0;
    g_stConfig.astTcpAddr[0].iPkgHeadLen = sizeof(GSKPkgHead)+1;
    g_stConfig.astTcpAddr[0].nServerPort = g_stConfig.aiTcpPort[0] + g_stConfig.iSrvID;
    strcpy(g_stConfig.astTcpAddr[0].sServerIP, g_stConfig.szLocalIP);
    
    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP;
    g_stConfig.astUdpAddr[0].nServerPort = UDP_SRV_PORT_LOGIC + g_stConfig.iSrvID;
    strcpy(g_stConfig.astUdpAddr[0].sServerIP, g_stConfig.szLocalIP);
    
    if (SrvFrameworkInit(NULL, 0, sizeof(CltInfo),
                         &(g_stLog.stSvrLog), g_stLog.iLogLevel,
                         1, &g_stConfig.astTcpAddr[0], g_stConfig.iMaxSocketNum, g_stConfig.iMaxSecKeepClt, 	//Tcp Server
                         1, &g_stConfig.astUdpAddr[0],
                         0, NULL,													//TCP Client
                         &stCallback))
    {
        LOG_FATAL("srv_framework init failed!");
        return -1;
    }
    return 0;
}

int32_t Init(int32_t argc, char *argv[])
{
    memset(&g_stConfig , 0 , sizeof(CONFIG));
    
    int iRet = 0;
    if (argc != 3)
    {
        printf("Usage: %s srv_id config_file\n", argv[0]);
        return -1;
    }
    
    g_stConfig.iSrvID = atoi(argv[1]);
    
    iRet = GetAbsPath(g_stConfig.szConfigFilePath ,argv[2], sizeof(g_stConfig.szConfigFilePath) );
    if(iRet < 0)
    {
        printf("get config_file(%s) failed! ret(%d)\n", argv[0], iRet);
        return -2;
    }
    
    //读取配置文件内容
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "MAX_SOCKET_NUM", CFG_INT, &(g_stConfig.iMaxSocketNum), 100,
                     "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 86400,//5分钟
                     
                     "LOCAL_IP", CFG_STRING, g_stConfig.szLocalIP, "192.168.128.128", sizeof(g_stConfig.szLocalIP),
                     "TCP_SRV_PORT_LOGIC_FOR_II", CFG_INT, &(g_stConfig.aiTcpPort[0]), TCP_SRV_PORT_LOGIC_FOR_II,
                     
                     "TRACE_FLAG", CFG_INT, &g_stConfig.iTrace , 0,
                     
                     "TEST_ENV" ,CFG_INT, &g_stConfig.iTestEnv , 0,
                     
                     
                     "MQ_IP", CFG_STRING, g_stConfig.szMQIP, "192.168.1.106", sizeof(g_stConfig.szMQIP),
                     "MQ_PORT", CFG_INT, &g_stConfig.iMQPort , 11500,
                  
                     NULL
                     );
 
    
    DaemonInit();

    GSK_INIT_LOG(gsk_logic);
    
    V_InitRedis();
    V_InitMongo();
    V_InitPush();
    InitCmdInfo( g_mapCmdInfo );
    
    if( InitSrv() < 0)
    {
        LOG_BUG_RET( -3 );
    }
    InitSigHandler();
    LoadData();
        
    //MQ
    g_pZMQContext = zmq_ctx_new ();
    g_pZMQPublisher = zmq_socket (g_pZMQContext, ZMQ_PUB);
    char szBind[128] = {0};
    snprintf(szBind, sizeof(szBind) - 1, "tcp://%s:%d", g_stConfig.szMQIP, g_stConfig.iMQPort );
    zmq_connect (g_pZMQPublisher, szBind);
    
    //PB
    GOOGLE_PROTOBUF_VERIFY_VERSION;
    
    return 0;
}


void ReInit()
{
    //读取配置文件内容
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 86400,//5分钟
                     "TRACE_FLAG", CFG_INT, &g_stConfig.iTrace , 0,
                     NULL
                     );

    GSK_INIT_LOG(gsk_logic);
    
    V_InitRedis();
    V_InitMongo();
    V_InitPush();
}


int main(int32_t argc, char *argv[])
{
    if (Init(argc, argv) < 0)
    {
        LOG_FATAL("Init failed!");
        exit(-1);
    }
    LOG_ANY("SRV START!");
    
    SrvFrameworkLoop();
    
    return -1;
}

