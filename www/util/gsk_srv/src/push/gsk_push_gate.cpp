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


#define SRV_NAME_UDP_PUSH_GATE   1

typedef struct
{
    char szConfigFilePath[256];
    
    char szLocalIP[16];
    SrvFrameworkAddrDefine astUdpAddr[2];
    
    
    struct sockaddr_in  stJPushAddr;

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

static char sErrMsg[512] = {0};

#include "client_info.h"
static CClientInfo *g_pCltInfo = new CClientInfo();


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

void JPush(string strUser , string strMsg)
{
    if(strMsg.size() && strUser.size())
    {
        char sSendBuf[10240] = {0};
        int iLen = sprintf (sSendBuf, "%s#%s", strUser.c_str() , strMsg.c_str());
        SrvFrameworkSendUdp(SRV_NAME_UDP_PUSH_GATE, &g_stConfig.stJPushAddr, sSendBuf, iLen);
        LOG_DEBUG("[JPUSH] %s %s",  strUser.c_str() , strMsg.c_str());
    }
}

void HandlePush(unordered_map< uint64_t, GateInfo > &mapGateInfo , void *pPkg, int iPkgLen , string strMsg )
{
    for( auto iter = mapGateInfo.begin() ; iter != mapGateInfo.end(); iter++ )
    {
        g_stUser.lUin = iter->first;
        GateInfo *pGateInfo = &(iter->second);
        if( pGateInfo->iSocket == 0 || ( g_tCurTime - pGateInfo->iTime ) > g_stConfig.iMaxSessionTime )
        {
            if(strMsg.size())
            {
                bool bPush = true;
                string strUser = Common::tostr( g_stUser.stUin.dwUin );
                strUser.append("_");
                strUser.append(Common::tostr( g_stUser.stUin.wClt ));

                redisContext* pRedisContext = GetRedisHandle();
                if( pRedisContext )
                {
                    redisReply* pRedisReply = (redisReply*)redisCommand(pRedisContext, "SISMEMBER PUSH_SWITCH %b" , strUser.data(), strUser.size());
                    if( pRedisReply )
                    {
                        if( pRedisReply->type == REDIS_REPLY_INTEGER && pRedisReply->integer == 1 )
                        {
                            bPush = false;
                        }
                        freeReplyObject(pRedisReply);
                    }
                }
                if(bPush)
                {
                    JPush( strUser, strMsg );
                }
            }
        }
        else
        {
            GSKPkgHead* pHead = (GSKPkgHead*)((char*)pPkg + 1);
            pHead->iFd = htonl(pGateInfo->iSocket);
            pHead->dwTime = htonl(pGateInfo->dwSocketCreateTime);
            pHead->dwCltIP = htonl(pGateInfo->dwIP);
            pHead->wCltPort = htons(pGateInfo->wPort);
            pHead->dwUin = htonl( g_stUser.stUin.dwUin );
            pHead->wClt = htons(  g_stUser.stUin.wClt );
            
            char *sIP = inet_ntoa( *(in_addr*)(&  pGateInfo->dwSrvIp) );
            uint16_t wPort = pGateInfo->wSrvPort;
            int iSocket = g_pCltInfo->Get(sIP, wPort);
            int iRet = retry_send(&iSocket, pPkg, iPkgLen, SRV_TIMEOUT_SEC, SRV_TIMEOUT_USEC, sIP, wPort,sErrMsg, sizeof(sErrMsg));
            g_pCltInfo->Set(sIP,  wPort, iSocket);
            if (iRet < 0)
            {
                LOG_ERROR("send pkg to io(%s:%hu) falied(%d)",  sIP, wPort, iRet);
                g_pCltInfo->Del(sIP,  wPort);
            }
            
            LOG_DEBUG("[PUSH] %s:%hu %u_%hu %d", sIP , wPort , g_stUser.stUin.dwUin , g_stUser.stUin.wClt , iPkgLen);
        }
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
    
    char sMsg[102400] = {0};
    size_t dwMsgLen = 0;
    
    string strPushMsg = "";
    
    uint16_t wTlvLen = iPkgLen - 2 ;
    tlv_list_t *pTlvList = tlv_parse((uint8_t *)(&pCur[1]), wTlvLen);
    if (!pTlvList){LOG_BUG_RET(-3); }
    
    auto funcParseTlv = [&]( tlv_list_t *pList )->int{
        //TLV : T  1  UIN
        if (get_uint32_from_tlv_chain(pList, 1, &g_stUser.stUin.dwUin) < 0 || g_stUser.stUin.dwUin == 0 )
        {
            LOG_BUG_RET(-10);
        }
        
        //TLV : T  2  CLT
        get_uint32_from_tlv_chain(pList, 2, &g_stUser.stUin.wClt) ;
        
        
        tlv_t *pTlv = NULL;
        //TLV : T  3  消息
        pTlv = tlv_find(pTlvList, 3, 1);
        if (pTlv)
        {
            memcpy(sMsg, pTlv->sVal, pTlv->wLen);
            dwMsgLen = pTlv->wLen;
        }
        
        //TLV : T  5  jpush消息
        pTlv = tlv_find(pTlvList, 5, 1);
        if (pTlv)
        {
            strPushMsg.assign((const char*)pTlv->sVal , (size_t)pTlv->wLen);
        }
        
        return 0;
    };
    int iRet = funcParseTlv( pTlvList );
    tlv_list_free(pTlvList);
    pTlvList = NULL;
    if(iRet < 0)
    {
        LOG_BUG_RET(-4);
    }


    g_tCurTime = time(NULL);
    
    switch (iUdpName)
    {
        case SRV_NAME_UDP_PUSH_GATE:
        {
            if( g_stUser.stUin.dwUin && g_stUser.stUin.wClt != CLT_ID_OP )
            {
                redisContext* pRedisContext = GetRedisHandle();
                if( pRedisContext )
                {
                    string strRedisCmd = "";
                    unordered_map< uint64_t, GateInfo > mapGateInfo;
                    if( g_stUser.stUin.wClt )//特定设备
                    {
                        strRedisCmd = "HGET PUSH_U#"; //push user-<clt:gateinfo>
                        strRedisCmd.append(Common::tostr( g_stUser.stUin.dwUin ));
                        strRedisCmd.append(" ");
                        strRedisCmd.append(Common::tostr( g_stUser.stUin.wClt ));
                        
                        redisReply* pRedisReply = (redisReply*) redisCommand(pRedisContext, strRedisCmd.c_str());
                        if(pRedisReply)
                        {
                            if( pRedisReply->type == REDIS_REPLY_STRING )
                            {
                                GateInfo stGateInfo ;
                                string strInfo(pRedisReply->str, pRedisReply->len);
                                
                                LOG_DEBUG("%s", pRedisReply->str);
                                
                                
                                vector< uint32_t > vecItem = Common::sepstr< uint32_t >( strInfo , "#" , true );
                                if( vecItem.size() == 7 )
                                {
                                    stGateInfo.dwIP = vecItem[0];
                                    stGateInfo.wPort = vecItem[1];
                                    stGateInfo.iSocket = vecItem[2];
                                    stGateInfo.dwSocketCreateTime = vecItem[3];
                                    stGateInfo.dwSrvIp = vecItem[4];
                                    stGateInfo.wSrvPort = vecItem[5];
                                    stGateInfo.iTime = vecItem[6];
                                    mapGateInfo.insert( make_pair( g_stUser.lUin, stGateInfo ));
                                }
                            }
                            freeReplyObject(pRedisReply);
                        }
                    }
                    else//全设备
                    {
                        strRedisCmd = "HGETALL PUSH_U#"; //push user-<clt:gateinfo>
                        strRedisCmd.append(Common::tostr( g_stUser.stUin.dwUin ));
                        
                        redisReply* pRedisReply = (redisReply*) redisCommand(pRedisContext, strRedisCmd.c_str());
                        if(pRedisReply)
                        {
                            if( pRedisReply->type == REDIS_REPLY_ARRAY && pRedisReply->elements % 2 == 0)
                            {
                                for( size_t j = 0 ; j < pRedisReply->elements;  )
                                {
                                    
                                    LOG_DEBUG("%s %s", pRedisReply->element[j]->str, pRedisReply->element[j+1]->str);
                                    
                                    string strClt(pRedisReply->element[j]->str, pRedisReply->element[j]->len);
                                    g_stUser.stUin.wClt = Common::strto< uint16_t >( strClt );
                                    
                                    GateInfo stGateInfo ;
                                    string strInfo(pRedisReply->element[j+1]->str, pRedisReply->element[j+1]->len);
                                    vector< uint32_t > vecItem = Common::sepstr< uint32_t >( strInfo , "#" , true );
                                    if( vecItem.size() == 7 )
                                    {
                                        stGateInfo.dwIP = vecItem[0];
                                        stGateInfo.wPort = vecItem[1];
                                        stGateInfo.iSocket = vecItem[2];
                                        stGateInfo.dwSocketCreateTime = vecItem[3];
                                        stGateInfo.dwSrvIp = vecItem[4];
                                        stGateInfo.wSrvPort = vecItem[5];
                                        stGateInfo.iTime = vecItem[6];
                                        if( g_stUser.stUin.wClt )
                                        {
                                            mapGateInfo.insert( make_pair( g_stUser.lUin, stGateInfo ));
                                        }
                                        j+=2;
                                    }
                                }
                                
                            }
                        }
                    }
                    
                    char sSendBuf[102400] = {0};
                    size_t dwSendLen = sizeof(GSKPkgHead) + 2 + dwMsgLen;
                    if(dwSendLen >= 102400)
                    {
                        LOG_BUG_RET(-100);
                    }
                    
                    sSendBuf[0] = STX;
                    sSendBuf[dwSendLen-1] = ETX;
                    
                    GSKPkgHead* pHead = (GSKPkgHead*)(&sSendBuf[1]);
                    pHead->dwLen = htonl(dwSendLen);
                    pHead->wCmd = htons( CMD_PUSH );
                    pHead->dwCRC = htonl(81208878);
                    memcpy(pHead->body, sMsg, dwMsgLen);
                    
                    HandlePush(mapGateInfo, sSendBuf, dwSendLen, strPushMsg);
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
            LOG_INFO("catch -SIGTERM- , srv stop");
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
    
    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP_PUSH_GATE;
    g_stConfig.astUdpAddr[0].nServerPort = UDP_SRV_PORT_PUSH_GATE ;
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
    char szJPushIP[16];
    int iJPushPort;
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "MAX_SOCKET_NUM", CFG_INT, &(g_stConfig.iMaxSocketNum), 20,
                     "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 180,
                     "LOCAL_IP", CFG_STRING, g_stConfig.szLocalIP, "0.0.0.0", sizeof(g_stConfig.szLocalIP),
                     
                     "MAX_SESSION_TIME", CFG_INT, &(g_stConfig.iMaxSessionTime), 360,
                     
                     "JPUSH_IP", CFG_STRING, szJPushIP, "127.0.0.1", sizeof(szJPushIP),
                     "JPUSH_PORT", CFG_INT, &(iJPushPort), 18006,
                     
                     NULL
                     );
    DaemonInit();
    
    GSK_INIT_LOG(gsk_push_gate);
    
    V_InitRedis();
    
    memset(&g_stConfig.stJPushAddr, 0, sizeof(struct sockaddr_in));
    g_stConfig.stJPushAddr.sin_family = AF_INET;
    inet_aton(szJPushIP, &g_stConfig.stJPushAddr.sin_addr);
    g_stConfig.stJPushAddr.sin_port = htons(iJPushPort);
    
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
