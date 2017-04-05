/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/
#ifndef __YS_GSK_UTIL_PUSH_H__
#define __YS_GSK_UTIL_PUSH_H__

#include "inc.h"
#include "pb_gsk_req.pb.h"
#include "ClientSocket.h"
 

/*======================================================================
 *  推送相关
 ======================================================================*/
extern char szSendBuf[MAX_BUFF_LEN];

static struct sockaddr_in g_stPushAddr;
static struct sockaddr_in g_stFightPushAddr;
static struct sockaddr_in g_stLianmengPushAddr;
static struct sockaddr_in g_stWorldPushAddr;
static struct sockaddr_in g_stSquadPushAddr;

static char g_szLogicIp[32];
static int g_iLogicPort;
static struct sockaddr_in g_stLogicAddr;
//~jpush所用encode函数



inline int V_JPush( uint32_t dwUin, string &strMsg )
{
    if( strMsg.size() && dwUin )
    {
        string strUrl = "http://localhost:5010/jpush/";
        strUrl.append( Common::tostr(  dwUin ) );
        strUrl.append( "/" );
        strUrl.append( Common::tostr(  UrlEncode( strMsg ) ) );
        
        LavaHttpRequest stHttpReq;
        stHttpReq.setCacheControl("no-cache");
        stHttpReq.setUserAgent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; InfoPath.1; .NET CLR 1.1.4322)");
        
        stHttpReq.setGetRequest(strUrl);
        string sSendBuffer = stHttpReq.encode();
        
        LavaHttpResponse stHttpRsp;
        int iRet = stHttpReq.doRequest(stHttpRsp, 3000);
        if( iRet < 0 )
        {
            return -101;
        }
        
        if(stHttpRsp.getContent().length() <= 0)
        {
            return -102;
        }
    }
    return 0;
}



void V_InitPush()
{
    char szPushIp[32];
    int iPushPort;
    char szFightPushIp[32];
    int iFightPushPort;
    char szLianmengPushIp[32];
    int iLianmengPushPort;
    char szWorldPushIp[32];
    int iWorldPushPort;
    char szSquadPushIp[32];
    int iSquadPushPort;

    OI_Cfg_GetConfig("../conf/gsk.conf",
                     "PUSH_IP", CFG_STRING, szPushIp, "192.168.1.106", sizeof(szPushIp),
                     "PUSH_PORT", CFG_INT, &iPushPort , UDP_SRV_PORT_PUSH_GATE,
                     
                     "PUSH_FIGHT_IP", CFG_STRING, szFightPushIp, "192.168.1.106", sizeof(szFightPushIp),
                     "PUSH_FIGHT_PORT", CFG_INT, &iFightPushPort , UDP_SRV_PORT_PUSH_FIGHT,
                     
                     "PUSH_LIANMENG_IP", CFG_STRING, szLianmengPushIp, "192.168.1.106", sizeof(szLianmengPushIp),
                     "PUSH_LIANMENG_PORT", CFG_INT, &iLianmengPushPort , UDP_SRV_PORT_PUSH_LIANMENG,
                     
                     "PUSH_WORLD_IP", CFG_STRING, szWorldPushIp, "192.168.1.106", sizeof(szWorldPushIp),
                     "PUSH_WORLD_PORT", CFG_INT, &iWorldPushPort , UDP_SRV_PORT_PUSH_WORLD,
                     
                     "PUSH_SQUAD_IP", CFG_STRING, szSquadPushIp, "192.168.1.106", sizeof(szSquadPushIp),
                     "PUSH_SQUAD_PORT", CFG_INT, &iSquadPushPort , UDP_SRV_PORT_PUSH_SQUAD,
                     
                     "LOGIC_IP", CFG_STRING, g_szLogicIp, "192.168.1.106", sizeof(g_szLogicIp),
                     "LOGIC_PORT", CFG_INT, &g_iLogicPort , UDP_SRV_PORT_LOGIC,
                     
                     NULL
                     );
    memset(&g_stPushAddr, 0, sizeof(struct sockaddr_in));
    g_stPushAddr.sin_family = AF_INET;
    inet_aton(szPushIp, &g_stPushAddr.sin_addr);
    g_stPushAddr.sin_port = htons(iPushPort);
    
    memset(&g_stFightPushAddr, 0, sizeof(struct sockaddr_in));
    g_stFightPushAddr.sin_family = AF_INET;
    inet_aton(szFightPushIp, &g_stFightPushAddr.sin_addr);
    g_stFightPushAddr.sin_port = htons(iFightPushPort);
    
    memset(&g_stLianmengPushAddr, 0, sizeof(struct sockaddr_in));
    g_stLianmengPushAddr.sin_family = AF_INET;
    inet_aton(szLianmengPushIp, &g_stLianmengPushAddr.sin_addr);
    g_stLianmengPushAddr.sin_port = htons(iLianmengPushPort);
    
    memset(&g_stWorldPushAddr, 0, sizeof(struct sockaddr_in));
    g_stWorldPushAddr.sin_family = AF_INET;
    inet_aton(szWorldPushIp, &g_stWorldPushAddr.sin_addr);
    g_stWorldPushAddr.sin_port = htons(iWorldPushPort);
    
    memset(&g_stSquadPushAddr, 0, sizeof(struct sockaddr_in));
    g_stSquadPushAddr.sin_family = AF_INET;
    inet_aton(szSquadPushIp, &g_stSquadPushAddr.sin_addr);
    g_stSquadPushAddr.sin_port = htons(iSquadPushPort);
    
    memset(&g_stLogicAddr, 0, sizeof(struct sockaddr_in));
    g_stLogicAddr.sin_family = AF_INET;
    inet_aton(g_szLogicIp, &g_stLogicAddr.sin_addr);
    g_stLogicAddr.sin_port = htons(g_iLogicPort);
}

inline int V_Push( GUser &stUser, string &strBody , uint16_t wCmd = CMD_PUSH )
{
//    if( strBody.size() )
//    {
//        memset(szSendBuf, 0 , MAX_BUFF_LEN);
//        int iLen = sizeof( GSKPkgHead ) + 2 + strBody.size();
//        szSendBuf[0] = STX;
//        szSendBuf[iLen - 1] = ETX;
//        
//        GSKPkgHead *pHead = (GSKPkgHead*)(&szSendBuf[1]);
//        pHead->wCmd = htons( wCmd );
//        pHead->dwUin = htonl( stUser.stUin.dwUin );
//        pHead->wServerId = htons( stUser.stUin.wSrv );
//        pHead->wPlatformId = htons( stUser.stUin.wPlt );
//        pHead->dwLen = htonl( iLen );
//        memcpy( pHead->body , strBody.data(), strBody.size());
//        SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stPushAddr, szSendBuf, iLen);
//        
//        LOG_DEBUG("[PUSH] BodyLen:%lu" , strBody.size());
//    }
    return 0;
}

inline int V_Push( GUser &stUser, pb_clt_t_user &l_pb_clt_t_user , uint16_t wCmd = CMD_PUSH )
{
//    string strBody ;
//    l_pb_clt_t_user.SerializeToString(&strBody);
//    V_Push( stUser , strBody , wCmd );
    return 0;
}


#endif
