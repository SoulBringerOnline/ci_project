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
#include "client_info.h"
#include "antisnow.h"
#include "dlb_api.h"
#include <chrono>

#define SRV_NAME_TCP_FROM_CLT_TO_LOGIC	 				1
#define SRV_NAME_TCP_FROM_LOGIC_TO_CLT			 		2
#define SRV_NAME_UDP                                    3

typedef struct
{
    char szConfigFilePath[256];
    
    int iSrvID;
    char szLocalIP[16];
    char szRspIP[16];

    int aiTcpPort[2];
    SrvFrameworkAddrDefine astTcpAddr[2];
    
    int aiUdpPort[2];
    SrvFrameworkAddrDefine astUdpAddr[2];
    
    struct sockaddr_in  stSyncAddr;
    struct sockaddr_in  stSQMAddr;
    
    int iTimeOutUnit;
    int iMaxSocketNum;
    int iMaxSecKeepClt;
    
    DLB stDlbLogic;
} CONFIG;

typedef struct
{
    uint32_t  dwCltIP;
    uint16_t wCltPort;
    int iSocket;
    uint32_t  dwSocketCreateTime;
    uint32_t  dwUin;
    uint16_t  wClt;
} CltInfo;

typedef struct
{
    GSKPkgHead stHead;
    std::chrono::high_resolution_clock::time_point start;
} SessionCached;

char key[17] = "tkffffffffffffff";
static size_t auNodeNum[] = {  7927, 7919, 7907, 7901 };

static int g_iSignal = 0;
static CONFIG g_stConfig;
static SVRLOG g_stLog;
static string g_strLog;
static GUser g_stUser;
static CClientInfo *g_pCltInfo = new CClientInfo();
static char sErrMsg[1024];
static char szSendBuf[MAX_BUFF_LEN] = {0};
static unordered_map< int , int > g_mapCmdSet;
static unordered_map< int , int > g_mapCltSet;
static unordered_map< int , int > g_mapChannelSet;
static unordered_map< int , int >::iterator g_mapIter;
static unordered_set< int > g_setWhiteCrc;
static unordered_set< int > g_setBlockedCmd;

static GSKPkg  g_stGSKPkg;
static uint32_t g_dwPkgHeadLen = sizeof(GSKPkgHead);

void DoInit();


/* ==========================================================
 * srv set distributive rules
 *
 * ==========================================================
 */

int GetSetID( uint16_t wClt,  uint16_t wChannel, uint16_t wCmd )
{
    g_mapIter = g_mapCmdSet.find(wCmd);
    if( g_mapIter != g_mapCmdSet.end() )
    {
        return g_mapIter->second;
    }
    
    g_mapIter = g_mapChannelSet.find(wChannel);
    if( g_mapIter != g_mapChannelSet.end() )
    {
        return g_mapIter->second;
    }
    
    g_mapIter = g_mapCltSet.find(wClt);
    if( g_mapIter != g_mapCltSet.end() )
    {
        return g_mapIter->second;
    }
    
    return 0;
}

/* ==========================================================
 * decrypt/encrypt package
 *
 * ==========================================================
 */
bool DecryptPkg(void *pPkg , int iPkgLen , void **ppBuf, int &iBufLen)
{
    GSKPkgHead* pHead = (GSKPkgHead *)((char*)pPkg + 1);
    uint32_t dwCltCrc = ntohl(pHead->dwCRC);

    if( g_setWhiteCrc.find( dwCltCrc )  == g_setWhiteCrc.end() )
    {
        pHead->dwCRC = 0;
        uint32_t  dwSrvCrc = udc_crc32( CRC_KEY, (const unsigned char*)pPkg , iPkgLen );
        if( dwCltCrc != dwSrvCrc )
        {
            LOG_ERROR("crc(%u , %u) check failed!", dwCltCrc , dwSrvCrc );
            return false;
        }
        
        int iBodyLen = iPkgLen - g_dwPkgHeadLen - 2;
        if( iBodyLen > 0 )
        {
            char *pBody = pHead->body;

            char *pBuf = (char*)*ppBuf;
            memcpy(pBuf, pPkg, g_dwPkgHeadLen+1);
            
            pHead = (GSKPkgHead *)(pBuf + 1);
            char *pNewBody = pHead->body;
            int iNewBodyLen = ENCRYPT_BUF_LEN(iBodyLen);
            int iNeedBuf = iNewBodyLen + g_dwPkgHeadLen + 2;
            if( iNeedBuf >= iBufLen )
            {
                LOG_ERROR("not enough buf(cur:%u need:%u)!", iBufLen, iNeedBuf);
                return false;
            }
            
            if (!OicqDecrypt(1, (BYTE *)pBody, iBodyLen, (BYTE *)key, (BYTE *)pNewBody, &iNewBodyLen))
            {
                LOG_ERROR("package body decrypt failed!");
                return false;
            }
            iBufLen = g_dwPkgHeadLen + iNewBodyLen + 2;
            pBuf[iBufLen-1] = ETX;
            pHead->dwLen = htonl( iBufLen );
            
            return true;
        }
    }
   
    *ppBuf = pPkg ;
    iBufLen = iPkgLen;
    
    return true;
}
bool EncryptPkg(void *pPkg , int iPkgLen , void **ppBuf, int &iBufLen)
{
    GSKPkgHead* pHead = (GSKPkgHead *)((char*)pPkg + 1);
    uint32_t  dwCltCrc = ntohl(pHead->dwCRC);
    if( g_setWhiteCrc.find( dwCltCrc )  == g_setWhiteCrc.end() )
    {
        pHead->dwCRC = 0;
        int iBodyLen = iPkgLen - g_dwPkgHeadLen - 2;
        if( iBodyLen > 0 )
        {
            char *pBody = pHead->body;
            char *pBuf = (char*)*ppBuf;
            memcpy(pBuf, pPkg, g_dwPkgHeadLen+1);
            
            pHead = (GSKPkgHead *)(pBuf + 1);
            char *pNewBody = pHead->body;
            int iNewBodyLen =  ENCRYPT_BUF_LEN(iBodyLen);
            int iNeedBuf = iNewBodyLen + g_dwPkgHeadLen + 2;
            if( iNeedBuf >= iBufLen )
            {
                LOG_ERROR("not enough buf(cur:%u need:%u)!", iBufLen, iNeedBuf);
                return false;
            }
            
            OicqEncrypt(1, (BYTE *)pBody, iBodyLen, (BYTE *)key, (BYTE *)pNewBody, &iNewBodyLen);
            iBufLen = g_dwPkgHeadLen + iNewBodyLen + 2;
            pBuf[iBufLen-1] = ETX;
            pHead->dwLen = htonl( iBufLen );
            pHead->dwCRC = htonl(udc_crc32( CRC_KEY, (const unsigned char*)pBuf , iBufLen ));
        }
        else
        {
            *ppBuf = pPkg ;
            iBufLen = iPkgLen;
            pHead->dwCRC = htonl(udc_crc32( CRC_KEY, (const unsigned char*)pPkg , iPkgLen ));
        }
    }
    else
    {
        *ppBuf = pPkg ;
        iBufLen = iPkgLen;
    }
    
    return true;
}
/* ==========================================================
 * response to client user request
 *
 * ==========================================================
 */
int SQMReportElapsedtime( uint16_t wCmd , int elapsedtime , int iPkgLen)
{
    char szSQMBuf[64] = {0};
    char *pCur = szSQMBuf;
    int iLeftLen = 64;
    int iLen = 0;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, STX);
    iLen+=1;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, 1);
    ADD_WORD_RET(&pCur, &iLeftLen, 4);
    ADD_DWORD_RET(&pCur, &iLeftLen, wCmd);
    iLen+=7;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, 2);
    ADD_WORD_RET(&pCur, &iLeftLen, 4);
    ADD_DWORD_RET(&pCur, &iLeftLen, elapsedtime);
    iLen+=7;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, 4);
    ADD_WORD_RET(&pCur, &iLeftLen, 4);
    ADD_DWORD_RET(&pCur, &iLeftLen, iPkgLen);
    iLen+=7;

    ADD_CHAR_RET(&pCur, &iLeftLen, ETX);
    iLen+=1;
    
    SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stConfig.stSQMAddr, szSQMBuf, iLen);
    return 0;
}
int SQMReportStatistics( uint16_t wCmd , int iType )
{
    char szSQMBuf[64] = {0};
    char *pCur = szSQMBuf;
    int iLeftLen = 64;
    int iLen = 0;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, STX);
    iLen+=1;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, 1);
    ADD_WORD_RET(&pCur, &iLeftLen, 4);
    ADD_DWORD_RET(&pCur, &iLeftLen, wCmd);
    iLen+=7;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, 3);
    ADD_WORD_RET(&pCur, &iLeftLen, 4);
    ADD_DWORD_RET(&pCur, &iLeftLen, iType);
    iLen+=7;
    
    ADD_CHAR_RET(&pCur, &iLeftLen, ETX);
    iLen+=1;
    
    SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stConfig.stSQMAddr, szSQMBuf, iLen);
    return 0;
}
int ResponseToClient(void *pPkg, int iPkgLen)
{
    char* pCur = (char*)pPkg;
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    
    int iSocket = ntohl(pHead->iFd);
    uint32_t dwCreateTime = ntohl(pHead->dwTime);
    uint32_t dwCltIP = ntohl(pHead->dwCltIP);
    uint16_t wCltPort = ntohs(pHead->wCltPort);
    uint16_t wCmd = ntohs( pHead->wCmd ) ;
    
    switch( pHead->cResult )
    {
        case 0:
            SQMReportStatistics(wCmd , SQM_STAT_TYPE_RSP);
            break;
        case ERROR_TIMEOUT:
            SQMReportStatistics(wCmd , SQM_STAT_TYPE_TIMEOUT);
            break;
        default:
            SQMReportStatistics(wCmd , SQM_STAT_TYPE_ERROR);
            break;
    }

    
    SCTX *pstDstSctx = NULL;
    CltInfo *pstDstUserInfo = NULL;
    SrvFrameworkGetContext(iSocket, &pstDstSctx, (void **)&pstDstUserInfo);
    
    if((pstDstSctx->Stat != SOCKET_TCP_ACCEPT)
       || (pstDstUserInfo->dwCltIP != dwCltIP)
       || (pstDstUserInfo->wCltPort != wCltPort)
//       || (pstDstUserInfo->dwSocketCreateTime != dwCreateTime)
       || (pstDstUserInfo->iSocket != iSocket))
    {
        LOG_ERROR("TCP socket not match. drop response. cmd:%x stat(%d:%d) ip(%u:%u %u ) port(%hu:%hu) time(%u:%u) sock(%d:%d)" ,  wCmd, pstDstSctx->Stat, SOCKET_TCP_ACCEPT, pstDstUserInfo->dwCltIP,  dwCltIP ,pHead->dwCltIP , pstDstUserInfo->wCltPort , wCltPort ,pstDstUserInfo->dwSocketCreateTime , dwCreateTime,pstDstUserInfo->iSocket, iSocket  );
        LOG_BUG_RET(-1);
    }
    
    pHead->dwTime = htonl(time(NULL));
    void *pSendBuf = szSendBuf;
    int iSendPkgLen = MAX_BUFF_LEN;
    if(  EncryptPkg( pPkg , iPkgLen ,  &pSendBuf,  iSendPkgLen) == false )
    {
        LOG_ERROR("encrypt pkg failed!");
        LOG_BUG_RET(-2);
    }

    int iRet = SrvFrameworkSend(pstDstSctx, pstDstUserInfo, pSendBuf, iSendPkgLen);
    if( iRet < 0 )
    {
        LOG_ERROR("rsp to clt(%s:%d) failed(%d)" ,  inet_ntoa(pstDstSctx->stClientAddr.sin_addr), ntohs(pstDstSctx->stClientAddr.sin_port) , iRet);
    }
    LOG_DEBUG("[RSP %d_2] <-- PKGLEN(%d) CMD(0x%X)",ntohl(pHead->dwSeq) ,iSendPkgLen, ntohs( pHead->wCmd ));
    return 0;
}
void ExpireTrigger(uint32_t  dwDataLen, char *sData)
{
    SessionCached* pMgCached = (SessionCached*)sData;
    int iPkgLen = g_dwPkgHeadLen + 2;
    
    szSendBuf[0] = STX;
    char *pCur = &(szSendBuf[1]);
    memcpy( pCur , (void*)(&pMgCached->stHead), g_dwPkgHeadLen);
    szSendBuf[iPkgLen-1] = ETX;
    
    GSKPkgHead *pHead = (GSKPkgHead*)(&szSendBuf[1]);
    pHead->cResult = ERROR_TIMEOUT;
    pHead->dwLen = htonl( iPkgLen );
    
    g_stUser.stUin.dwUin = ntohl(pHead->dwUin);
    g_stUser.stUin.wClt = ntohs(pHead->wClt);
    uint16_t wCmd = ntohs( pHead->wCmd ) ;
    uint16_t wChannel = ntohs( pHead->wChannelId ) ;
    uint32_t dwSeq = ntohl( pHead->dwSeq );
    
    LOG_ERROR("[TIMEOUT] cmd(0x%X) seq(%u) channel(%hu)", wCmd, dwSeq , wChannel);
    ResponseToClient(szSendBuf , iPkgLen);
}
int ForwardPkgToClt(SCTX *pstSctx, void *pCltInfo, void *pPkg, int iPkgLen)
{
    GSKPkgHead* pHead = (GSKPkgHead*)((char*)pPkg + 1);
    uint16_t wCmd = ntohs( pHead->wCmd ) ;
    uint32_t dwCltSeq = ntohl(pHead->dwCltSeq);
    SessionCached* pMgCached = NULL;
    uint32_t  dwMgCachedLen = 0;
    if(GetTimer(dwCltSeq, &dwMgCachedLen, (char**)&pMgCached) < 0)
    {
        LOG_ERROR("[GetTimer] get timer failed cmd(0x%X) seq(%u)", wCmd , dwCltSeq);
        LOG_BUG_RET(-1);
    }
    if(pMgCached && dwCltSeq)
    {
        std::chrono::high_resolution_clock::time_point end = std::chrono::high_resolution_clock::now();
        auto elapsedtime = chrono::duration_cast<chrono::milliseconds>(end - pMgCached->start).count();
        SQMReportElapsedtime(wCmd ,elapsedtime , iPkgLen);
        DelTimer(dwCltSeq);
    }
    
    ResponseToClient(pPkg, iPkgLen);
    
    return 0;
}
/* ==========================================================
 * forwards any messages  it receives from client from client
 * to _inner
 *
 * ==========================================================
 */

int TrySendPkgToProc(char *sAddr, uint16_t wCltPort, void *pPkg, int iPkgLen)
{
    int iRet = 0;
    int iSocket = g_pCltInfo->Get( sAddr, wCltPort);
    memset(sErrMsg, 0, sizeof(sErrMsg));
    iRet = try_send(&iSocket , pPkg, iPkgLen , SRV_TIMEOUT_SEC, SRV_TIMEOUT_USEC, sAddr, wCltPort, sErrMsg, sizeof(sErrMsg));
    if(iRet == 0 && iSocket > 0)
    {
        g_pCltInfo->Set( sAddr, wCltPort, iSocket);
        return iSocket;
    }
    
    g_pCltInfo->Del( sAddr, wCltPort);
    LOG_ERROR("try_send to(%s:%hu) failed(%d) socket(%d) err:%s", sAddr, wCltPort, iRet, iSocket, sErrMsg);
    return -1;
}

bool SendPkgToProc( int iSetID, void *pPkg, int iPkgLen)
{
    int iRet = 0;
    int iTryTimes = 0;
    while( iTryTimes++ < SRV_SEND_TRY_TIMES)
    {
        struct sockaddr_in stProcAddr;
        iRet = g_stConfig.stDlbLogic.DLB_GetOneServerEx(&(stProcAddr), 0, iSetID, 0);
        
        //LOG_DEBUG("sendto %s:%hu", inet_ntoa(stProcAddr.sin_addr),  ntohs(stProcAddr.sin_port) );
        
        if( iRet < 0 )
        {
            LOG_FATAL("[DLB] get srv failed! set_id(%d)", iSetID );
            continue;
        }
        iRet = TrySendPkgToProc( inet_ntoa(stProcAddr.sin_addr),  ntohs(stProcAddr.sin_port), pPkg, iPkgLen);
        if (iRet < 0)
        {
            LOG_INFO("[DLB] try_send set_id(%d) %s:%d", iSetID , inet_ntoa(stProcAddr.sin_addr),  ntohs(stProcAddr.sin_port));
            continue;
        }
        else
        {
            g_stConfig.stDlbLogic.DLB_UpdateOneServer(&(stProcAddr));
            return true;
        }
    }
    
    return false;
}

int ForwardPkgToProc(SCTX *pstSctx, void *pCltInfo, void *pPkg, int iPkgLen)
{
    //crc & decrypt pkg
    void *pSendBuf = szSendBuf;
    int iSendPkgLen = MAX_BUFF_LEN;
    if( DecryptPkg( pPkg , iPkgLen , &pSendBuf , iSendPkgLen ) == false )
    {
        LOG_BUG_RET( -1 );
    }
    char* pCur = (char*)pSendBuf;
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    uint16_t wCmd = ntohs( pHead->wCmd ) ;
    uint16_t wChannel = ntohs( pHead->wChannelId ) ;
    uint32_t dwSeq = ntohl( pHead->dwSeq );
    SQMReportStatistics(wCmd , SQM_STAT_TYPE_REQ);

    //HEAD
    pHead->dwCltIP = *(uint*)&pstSctx->stClientAddr.sin_addr.s_addr;
    pHead->wCltPort = pstSctx->stClientAddr.sin_port;
    pHead->dwTime = htonl(pstSctx->tCreateTime);
    pHead->iFd = htonl(pstSctx->iSocket);
    pHead->dwRspIP = inet_addr(g_stConfig.szRspIP);
    pHead->wRspPort = htons(g_stConfig.aiTcpPort[1] + g_stConfig.iSrvID);
    LOG_DEBUG("[REQ %u_1] --> CMD(0x%X) CHANNEL(%d) PKGLEN(%d) ", dwSeq, wCmd, wChannel, iPkgLen);
    
    //CLTINFO
    CltInfo* pstCltInfo = (CltInfo*)pCltInfo;
    pstCltInfo->dwCltIP = ntohl(pstSctx->stClientAddr.sin_addr.s_addr);
    pstCltInfo->wCltPort = ntohs(pstSctx->stClientAddr.sin_port);
    pstCltInfo->dwSocketCreateTime = pstSctx->tCreateTime;
    pstCltInfo->iSocket = pstSctx->iSocket;
    pstCltInfo->dwUin = g_stUser.stUin.dwUin;
    pstCltInfo->wClt = g_stUser.stUin.wClt;
    
    if( g_setBlockedCmd.find( wCmd ) != g_setBlockedCmd.end() )
    {
        ResponseToClient(pPkg, iPkgLen);
    }
    else
    {
        //SESSION
        static SessionCached stSessionCached, *pMgCached;
        pMgCached = &stSessionCached;
        memset(pMgCached, 0, sizeof(SessionCached));
        memcpy( &(pMgCached->stHead), pHead, g_dwPkgHeadLen);
        pMgCached->start = std::chrono::high_resolution_clock::now();
        
        uint32_t dwSessionID = 0;
        int iRet = AddTimer(&(dwSessionID), g_stConfig.iTimeOutUnit, ExpireTrigger, sizeof(SessionCached), (char*)pMgCached);
        if(iRet < 0)
        {
            LOG_ERROR("add time failed(%d)" , iRet );
            LOG_BUG_RET(-3);
        }
        pHead->dwCltSeq = htonl(dwSessionID);
    
        int iSetID = GetSetID(g_stUser.stUin.wClt, wChannel, wCmd );
        if ( SendPkgToProc(iSetID, pSendBuf , iSendPkgLen) == false )
        {
            pHead->cResult = ERROR_LOGIC_SRV;
            ResponseToClient( pPkg, iPkgLen);
            LOG_BUG_RET(-5);
        }
    }
    
    //SYNC FD
    if( g_stUser.stUin.wClt && g_stUser.stUin.wClt != CLT_ID_OP )
    {
        memcpy( &g_stGSKPkg.stHead , pHead, g_dwPkgHeadLen);
        g_stGSKPkg.cSTX = STX;
        g_stGSKPkg.cETX = ETX;
        g_stGSKPkg.stHead.iFd = htonl(pstSctx->iSocket);
        g_stGSKPkg.stHead.dwRspIP = htonl( inet_addr(g_stConfig.szRspIP) );
        g_stGSKPkg.stHead.wRspPort = htons(g_stConfig.aiTcpPort[1] + g_stConfig.iSrvID);
        g_stGSKPkg.stHead.dwUin = htonl(g_stUser.stUin.dwUin);
        g_stGSKPkg.stHead.wClt = htons(g_stUser.stUin.wClt);
        g_stGSKPkg.stHead.dwTime = htonl(pstSctx->tCreateTime);
        g_stGSKPkg.stHead.dwLen = htonl( g_dwPkgHeadLen+2 );
        SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stConfig.stSyncAddr, (char*)(&g_stGSKPkg), g_dwPkgHeadLen+2);
        //        LOG_DEBUG("[PUSH_SYNC] fd=%d ip=%s port=%hu", pstSctx->iSocket, inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    }
    
    return 0;
}

/* ==========================================================
 * network service logic processing
 *
 * ==========================================================
 */

int HandleAccept(SCTX *pstSctx, void *pCltInfo, int iListenAddrName)
{
    g_stUser.stUin.dwUin = 0;
    g_stUser.stUin.wClt = 0;
    memset(pCltInfo, 0, sizeof(CltInfo));
    LOG_DEBUG("[ACCEPT] fd=%d ip=%s port=%hu", pstSctx->iSocket, inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    return 0;
}

int HandlePkgHead(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iBytesRecved, int *piPkgLen)
{
    char* pCur = (char*)pPkg;
    if(!pCur || !piPkgLen ) { LOG_BUG_RET(-1); }
    if(pCur[0] != STX){LOG_BUG_RET(-2); }
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    *piPkgLen = ntohl(pHead->dwLen);
    return 0;
}

int HandlePkg(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iPkgLen)
{
    char* pCur = (char*)pPkg;
    if (!pCur || iPkgLen < ((int)g_dwPkgHeadLen+2) ) { LOG_BUG_RET(-1); }
    if (pCur[0] != STX) { LOG_BUG_RET(-2); }
    GSKPkgHead* pHead = (GSKPkgHead*)(pCur + 1);
    uint32_t  dwLen = ntohl( pHead->dwLen );
    if (iPkgLen != (int)dwLen) { LOG_BUG_RET(-3); }
    if (pCur[dwLen-1] != ETX) { LOG_BUG_RET(-4); }
    
    g_stUser.stUin.dwUin = ntohl(pHead->dwUin);
    g_stUser.stUin.wClt = ntohs(pHead->wClt);
    
    switch (iAddrName)
    {
        case SRV_NAME_TCP_FROM_CLT_TO_LOGIC:
        {
            ForwardPkgToProc(pstSctx, pCltInfo, pPkg, iPkgLen);
            break;
        }
        case SRV_NAME_TCP_FROM_LOGIC_TO_CLT:
        {
            ForwardPkgToClt(pstSctx, pCltInfo, pPkg, iPkgLen);
            break;
        }
        default:
        {
            LOG_ERROR("error addrname %d", iAddrName);
            break;
        }
    }
    
    return 0;
}

int HandleUdpPkg(SCTX * pstSctx, void *pCltInfo, int iUdpName, void *pPkg, int iPkgLen)
{
    return 0;
}

int HandleCloseConn(SCTX *pstSctx, void *pCltInfo, int iAddrName, char *sErrInfo)
{
    CltInfo* pstCltInfo = (CltInfo*)pCltInfo;
    g_stUser.stUin.dwUin = pstCltInfo->dwUin;
    g_stUser.stUin.wClt = pstCltInfo->wClt;
    
    if( g_stUser.stUin.wClt && g_stUser.stUin.wClt != CLT_ID_OP )
    {
        memset( &g_stGSKPkg.stHead , 0, g_dwPkgHeadLen);
        g_stGSKPkg.cSTX = STX;
        g_stGSKPkg.cETX = ETX;
        g_stGSKPkg.stHead.iFd = 0;
        g_stGSKPkg.stHead.dwRspIP = 0;
        g_stGSKPkg.stHead.wRspPort = 0;
        g_stGSKPkg.stHead.dwUin = htonl(pstCltInfo->dwUin);
        g_stGSKPkg.stHead.wClt = htons(pstCltInfo->wClt);
        g_stGSKPkg.stHead.dwLen = htonl( g_dwPkgHeadLen+2 );
        SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stConfig.stSyncAddr, (char*)(&g_stGSKPkg), g_dwPkgHeadLen+2);
        LOG_DEBUG("[CLOSE] fd=%d ip=%s port=%hu", pstSctx->iSocket, inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    }
    
    g_pCltInfo->Del( inet_ntoa(pstSctx->stClientAddr.sin_addr), ntohs(pstSctx->stClientAddr.sin_port));
    return 0;
}

int HandleConnect(SCTX * pstSctx, void *pCltInfo, int iAddrName)
{
    return 0;
}

int HandleLoop(void)
{
    int iSig = g_iSignal;
    
    g_iSignal = 0;
    switch (iSig)
    {
        case SIGTERM:
            LOG_ANY("-------------------------");
            LOG_ANY("CATCH [ SIGTERM ]");
            LOG_ANY("SRV STOP!");
            LOG_ANY("-------------------------");
            delete g_pCltInfo;
            
            exit(0);
            break;
            
        case SIGUSR1:
            LOG_ANY("-------------------------");
            LOG_ANY("CATCH [ SIGUSR1 ]");
            LOG_ANY("SRV CONFIG RELOAD!");
            LOG_ANY("-------------------------");
            DoInit();
            break;
            
        case SIGUSR2:
            LOG_ANY("-------------------------");
            LOG_ANY("CATCH [ SIGUSR2 ]");
            LOG_ANY("-------------------------");
            break;
    }
    
    CheckTimer();
    return 0;
}

/* ==========================================================
 * initialization
 *
 * ==========================================================
 */
void InitLog()
{
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "LOG_LEVEL", CFG_INT, &(g_stLog.iLogLevel), 5,
                     "LOG_SHIFT_TYPE", CFG_INT, &(g_stLog.iLogShiftType), 3,
                     "LOG_MAX_NUM", CFG_INT, &(g_stLog.iMaxLogNum), 30,
                     "LOG_MAX_SIZE", CFG_INT, &(g_stLog.iMaxLogSize), 10000000,
                     NULL
                     );
    
    char szLogFilePath[256];
    memset(szLogFilePath, 0, sizeof(szLogFilePath));
    snprintf(szLogFilePath, sizeof(szLogFilePath) - 1,"../log/gsk_gate_%d_", g_stConfig.iSrvID);
    OI_InitLogFile(&(g_stLog.stSvrLog), szLogFilePath, g_stLog.iLogShiftType, g_stLog.iMaxLogNum, g_stLog.iMaxLogSize);
}

void InitDlb()
{
    char szDlbFile[256];
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "DLB_II", CFG_STRING, szDlbFile, "../conf/dlb_logic.conf", sizeof(szDlbFile),
                     NULL
                     );
    g_stConfig.stDlbLogic.DLB_InitServer(szDlbFile);
}

int InitSrv()
{
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "MAX_SOCKET_NUM", CFG_INT, &(g_stConfig.iMaxSocketNum), 1000,
                     "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 300,
                     
                     "LOCAL_IP", CFG_STRING, g_stConfig.szLocalIP, "0.0.0.0", sizeof(g_stConfig.szLocalIP),
                     "RSP_IP", CFG_STRING, g_stConfig.szRspIP, "127.0.0.1", sizeof(g_stConfig.szRspIP),
                     "TCP_SRV_PORT_IO_FOR_CLT", CFG_INT, &(g_stConfig.aiTcpPort[0]), TCP_SRV_PORT_IO_FOR_CLT,
                     "TCP_SRV_PORT_IO_FOR_LOGIC", CFG_INT, &(g_stConfig.aiTcpPort[1]), TCP_SRV_PORT_IO_FOR_LOGIC,
                     
                     "TIMEOUT_UNIT", CFG_INT, &(g_stConfig.iTimeOutUnit), 10,
                     NULL
                     );
    
    static SRV_CALLBACK stCallback;
    stCallback.HandleLoop = HandleLoop;
    stCallback.HandlePkgHead = HandlePkgHead;
    stCallback.HandlePkg = HandlePkg;
    stCallback.HandleUdpPkg = HandleUdpPkg;
    stCallback.HandleAccept = HandleAccept;
    stCallback.HandleConnect = HandleConnect;
    stCallback.HandleCloseConn = HandleCloseConn;
    
    g_stConfig.astTcpAddr[0].iAddrName = SRV_NAME_TCP_FROM_CLT_TO_LOGIC;
    g_stConfig.astTcpAddr[0].iPkgHeadLen = g_dwPkgHeadLen+1;
    g_stConfig.astTcpAddr[0].nServerPort = g_stConfig.aiTcpPort[0] + g_stConfig.iSrvID;
    strcpy(g_stConfig.astTcpAddr[0].sServerIP, g_stConfig.szLocalIP);
    
    g_stConfig.astTcpAddr[1].iAddrName = SRV_NAME_TCP_FROM_LOGIC_TO_CLT;
    g_stConfig.astTcpAddr[1].iPkgHeadLen = g_dwPkgHeadLen+1;
    g_stConfig.astTcpAddr[1].nServerPort = g_stConfig.aiTcpPort[1] + g_stConfig.iSrvID;
    strcpy(g_stConfig.astTcpAddr[1].sServerIP, g_stConfig.szLocalIP);
    
    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP;
    g_stConfig.astUdpAddr[0].nServerPort = UDP_SRV_PORT_LOGIC + g_stConfig.iSrvID;
    strcpy(g_stConfig.astUdpAddr[0].sServerIP, g_stConfig.szLocalIP);
    
    if (SrvFrameworkInit( 2048000, 2048000, sizeof(CltInfo),
                         &(g_stLog.stSvrLog), g_stLog.iLogLevel,
                         2, &g_stConfig.astTcpAddr[0], g_stConfig.iMaxSocketNum, g_stConfig.iMaxSecKeepClt,
                         1, &g_stConfig.astUdpAddr[0],
                         &stCallback))
    {
        LOG_FATAL("srv_framework init failed!");
        return -1;
    }
    
    return 0;
}

void InitHost()
{
    char szSyncIp[32] = {0};
    int iSyncPort = UDP_SRV_PORT_PUSH_SYNC ;
    
    char szSQMIp[32] = {0};
    int iSQMPort = 16101 ;
    
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
                     "SYNC_IP", CFG_STRING, szSyncIp, "192.168.164.200", sizeof(szSyncIp),
                     "SYNC_PORT", CFG_INT, &iSyncPort , UDP_SRV_PORT_PUSH_SYNC,
                     
                     "SQM_IP", CFG_STRING, szSQMIp, "192.168.164.200", sizeof(szSQMIp),
                     "SQM_PORT", CFG_INT, &iSQMPort , 16101,
                     
                     "TIMEOUT_UNIT", CFG_INT, &(g_stConfig.iTimeOutUnit), 1000,
                     
                     NULL
                     );
    
    
    memset(&g_stConfig.stSyncAddr, 0, sizeof(struct sockaddr_in));
    g_stConfig.stSyncAddr.sin_family = AF_INET;
    inet_aton(szSyncIp, &g_stConfig.stSyncAddr.sin_addr);
    g_stConfig.stSyncAddr.sin_port = htons(iSyncPort);
    
    memset(&g_stConfig.stSQMAddr, 0, sizeof(struct sockaddr_in));
    g_stConfig.stSQMAddr.sin_family = AF_INET;
    inet_aton(szSQMIp, &g_stConfig.stSQMAddr.sin_addr);
    g_stConfig.stSQMAddr.sin_port = htons(iSQMPort);
    
}

void InitMapping()
{
    ifstream ifs;
    string strLine ;
    
    //白名单CRC
    g_setWhiteCrc.clear();
    g_setWhiteCrc.insert( 81208878 );
    ifs.open("../data/t_checksum.dat", ifstream::in);
    while( getline(ifs, strLine) ){
        g_setWhiteCrc.insert( Common::strto< int >( strLine ) );
    }
    ifs.close();

    //关闭的命令字
    g_setBlockedCmd.clear();
    g_setBlockedCmd.insert( 0xFFFF );
    ifs.open("../conf/blocked_cmd.dat", ifstream::in);
    while( getline(ifs, strLine) ){
        g_setBlockedCmd.insert( Common::strto< int >( strLine ) );
    }
    ifs.close();
    
    //命令字SET信息
    g_mapCmdSet.clear();
    g_mapCltSet.clear();
    g_mapChannelSet.clear();
    
    ifs.open("../conf/set.conf", ifstream::in);
    while( getline(ifs, strLine) ){
        vector<int> vecItem = Common::sepstr<int>(strLine, ":", true);
        if( vecItem.size() == 3 )
        {
            switch( vecItem[0] )
            {
                case 1:
                    g_mapCmdSet.insert( pair< int , int >( vecItem[1], vecItem[2] ) );
                    LOG_INFO("[SET CMD] type:%d cmd:%d => set:%d", vecItem[0], vecItem[1] , vecItem[2] );
                    break;
                    
                case 2:
                    g_mapCltSet.insert( pair< int , int >( vecItem[1], vecItem[2] ) );
                    LOG_INFO("[SET CLT] type:%d clt:%d => set:%d", vecItem[0], vecItem[1] , vecItem[2] );
                    break;
                    
                case 3:
                    g_mapChannelSet.insert( pair< int , int >( vecItem[1], vecItem[2] ) );
                    LOG_INFO("[SET CHANNEL] type:%d channel:%d => set:%d", vecItem[0], vecItem[1] , vecItem[2] );
                    break;
            }
        }
    }
    ifs.close();
}

void DoInit()
{
    InitHost();
    InitLog();
    InitMapping();
    InitDlb();
}

void SigHandler(int iSig)
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

int Init(int argc, char *argv[])
{
    if(argc != 3)
    {
        printf("Usage: %s ServerID ConfigFile\n", argv[0]);
        return -1;
    }
    
    g_stConfig.iSrvID = atoi(argv[1]);
    GetAbsPath(g_stConfig.szConfigFilePath, argv[2], sizeof(g_stConfig.szConfigFilePath) );
    
    DaemonInit();
    
    DoInit();
    
    if( InitTimer(&g_stLog.stSvrLog, g_stLog.iLogLevel,
                  sizeof(SessionCached), DIM(auNodeNum), auNodeNum,
                  TIMER_CHECK_INTERVAL_US, CONST_DATA_LEN_TIMER ) < 0 )
    {
        return -1;
    }
    
    if(InitSrv() < 0)
    {
        return -1;
    }
    InitSigHandler();
    
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
