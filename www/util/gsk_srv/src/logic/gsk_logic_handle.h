/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#ifndef __YS_GSK_LOGIC_HANDLE_H__
#define __YS_GSK_LOGIC_HANDLE_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

typedef int (*HandleFunc)(void *pPkg, int iPkgLen , SessionCached *pMgCached, string &strBody);
unordered_map< uint16_t , HandleFunc > mapHandle{
    { CMD_USER_INFO , HandleGetUserInfo },
    { CMD_USER_CREATE, HandleCreateUser },
    { CMD_USER_MODIFY, HandleModifyUser }
} ;

unordered_set<int> setFilter{
   
};

int ResponseToClt( void *pPkg, int iPkgLen, SessionCached *pMgCached, string &strBody , int iResult, int iFrom)
{
    g_l_pb_report_t_report.set_f_msg_type( REPORT_MSG_RSP );
    
    char *sIP = inet_ntoa(pMgCached->stRspSrvAddr.sin_addr);
    uint16_t wPort = ntohs(pMgCached->stRspSrvAddr.sin_port);
    if( wPort && g_stUser.stUin.dwUin == g_l_pb_user_t_data.f_info().f_uin() )
    {
//        g_l_pb_report_t_report.mutable_f_info()->set_f_vip_to( g_l_pb_user_t_data.f_info().f_vip() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_team_level_to( g_l_pb_user_t_data.f_info().f_team_level() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_team_exp_to( g_l_pb_user_t_data.f_info().f_team_exp() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_tili_to( g_l_pb_user_t_data.f_info().f_tili() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_qili_to( g_l_pb_user_t_data.f_info().f_qili() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_gold_to( g_l_pb_user_t_data.f_info().f_gold() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_silver_to( g_l_pb_user_t_data.f_info().f_silver() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_rmb_to( g_l_pb_user_t_data.f_info().f_rmb() );
//        g_l_pb_report_t_report.mutable_f_info()->set_f_value_to( g_l_pb_user_t_data.f_info().f_value() );
    }
    
    int iRet = 0;
    GSKPkgHead* pHead =(GSKPkgHead*)((char*)pPkg + 1);

    szSendBuf[0] = STX;
    memcpy(&szSendBuf[1], (char*)&pMgCached->stRecvHead, sizeof(GSKPkgHead));
    iPkgLen = sizeof(GSKPkgHead) + 1;
    pHead =( GSKPkgHead*) (szSendBuf + 1);
    
    char* pBody = pHead->body;
    int iBodyLen = strBody.length();
    if( iBodyLen > 0 && iBodyLen < (int)( MAX_BUFF_LEN - sizeof(GSKPkgHead) - 2 ) )
    {
        memcpy(pBody, strBody.c_str(), strBody.length());
        iPkgLen += strBody.length() ;
    }
    
    if( iResult >= 0 )
    {
        pHead->cResult = 0;
    }
    else
    {
        pHead->cResult = -iResult;
    }
    szSendBuf[iPkgLen] = ETX;
    iPkgLen += 1;
    pHead->dwLen = htonl( iPkgLen );
    
    if( iFrom == PROCESS_TCP )
    {
        int iSocket = -1;
        iSocket = g_pCltInfo->Get(sIP, wPort);
        iRet = retry_send(&iSocket,szSendBuf, iPkgLen,SRV_TIMEOUT_SEC, SRV_TIMEOUT_USEC, sIP, wPort,sErrMsg, sizeof(sErrMsg));
        g_pCltInfo->Set(sIP,  wPort, iSocket);
      
//        LOG_DEBUG("send uin(%u)pkg to (%s:%u)", pMgCached->dwUin, sIP, wPort);
        if (iRet < 0)
        {
            LOG_WARN("send uin(%u)pkg to (%s:%u) falied(%d)", pMgCached->dwUin, sIP, wPort, iRet);
            return -1;
        }
    }
    else if( iFrom == PROCESS_UDP )
    {
//        LOG_DEBUG("send uin(%u)pkg to (%s:%u) iRet:%d", pMgCached->dwUin, sIP, wPort, iRet);

        memset(&g_stUDPAddr, 0, sizeof(struct sockaddr_in));
        g_stUDPAddr.sin_family = AF_INET;
        inet_aton(sIP, &g_stUDPAddr.sin_addr);
        g_stUDPAddr.sin_port = htons(wPort);
        SrvFrameworkSendUdp(SRV_NAME_UDP, &g_stUDPAddr, szSendBuf, iPkgLen);
    }
  
    return 0;
}



int32_t HandleCommand( void *pPkg, int iPkgLen, uint16_t wCmd , int iFrom )
{  
    int iRet = 0;
    string strBody = "";
    
    int iPkgHeadLen = sizeof( GSKPkgHead );
    GSKPkgHead* pHead =(GSKPkgHead*)((char*)pPkg + 1);
    char *pBody = (char*)pPkg + iPkgHeadLen + 1;
    int iBodyLen = iPkgLen - iPkgHeadLen - 2;
   
    //SESSION CACHE
    static SessionCached stSessionCached, *pMgCached;
    pMgCached = &stSessionCached;
    memset(pMgCached, 0, sizeof(SessionCached));
    pMgCached->dwUin = ntohl(pHead->dwUin);
    pMgCached->wClt =  ntohs(pHead->wClt);
    pMgCached->dwSeq = ntohl(pHead->dwSeq);
    pMgCached->wCmd =  ntohs(pHead->wCmd);
    pMgCached->stRspSrvAddr.sin_family = AF_INET;
    pMgCached->stRspSrvAddr.sin_addr = *(in_addr*)&pHead->dwRspIP;
    pMgCached->stRspSrvAddr.sin_port = pHead->wRspPort;
    pMgCached->dwSessionSeq = 0;
    pMgCached->cStatus = 0;
    // pMgCached->iLang = ntohl(pHead->iArg3); // language
    memcpy( &pMgCached->stRecvHead , pHead, sizeof(GSKPkgHead) );

    //REQ_PB
    g_l_pb_req_t_req.Clear();
    if( pBody && iBodyLen && !g_l_pb_req_t_req.ParseFromArray( pBody, iBodyLen ) )
    {
        LOG_ERROR("package body parse err! iBodyLen:%d", iBodyLen);
        LOG_BUG_RET( -201 );
    }
    if( g_l_pb_req_t_req.f_cmd() != wCmd )
    {
        LOG_ERROR("cmd differ ( %d:%d )!" ,  g_l_pb_req_t_req.f_cmd() , wCmd );
        LOG_BUG_RET( -202 );
    }
    
    //ARGS
    int iArgSize = g_l_pb_req_t_req.f_i_args_size();
    if( iArgSize > 0 ){ pMgCached->iArg1 = g_l_pb_req_t_req.f_i_args( 0 ); }
    if( iArgSize > 1 ){ pMgCached->iArg2 = g_l_pb_req_t_req.f_i_args( 1 ); }
    if( iArgSize > 2 ){ pMgCached->iArg3 = g_l_pb_req_t_req.f_i_args( 2 ); }
    if( iArgSize > 3 ){ pMgCached->iArg4 = g_l_pb_req_t_req.f_i_args( 3 ); }
    if( iArgSize > 4 ){ pMgCached->iArg5 = g_l_pb_req_t_req.f_i_args( 4 ); }
    if( iArgSize > 5 ){ pMgCached->iArg6 = g_l_pb_req_t_req.f_i_args( 5 ); }
    if( iArgSize > 6 ){ pMgCached->iArg7 = g_l_pb_req_t_req.f_i_args( 6 ); }
    if( iArgSize > 7 ){ pMgCached->iArg8 = g_l_pb_req_t_req.f_i_args( 7 ); }
    if( iArgSize > 8 ){ pMgCached->iArg9 = g_l_pb_req_t_req.f_i_args( 8 ); }
    if( iArgSize > 9 ){ pMgCached->iArg10 = g_l_pb_req_t_req.f_i_args( 9 ); }
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg1 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg2 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg3 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg4 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg5 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg6 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg7 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg8 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg9 );
//    g_l_pb_report_t_report.add_f_args()->set_f_id( pMgCached->iArg10 );

    string strArgs = "";
    for( int i = 0 ; i <  g_l_pb_req_t_req.f_s_args_size() ; i++ )
    {
        strArgs.append( "[" );
        strArgs.append( Common::tostr( i+1 ) );
        strArgs.append( "]" );
        strArgs.append( g_l_pb_req_t_req.f_s_args(i) );
        strArgs.append( " " );
    }
    LOG_ANY("CMD(0x%X %s) ARGS %d %d %d %d %d %d %d %d %d %d %s", pMgCached->wCmd, g_strCmdName.c_str(), pMgCached->iArg1, pMgCached->iArg2, pMgCached->iArg3, pMgCached->iArg4, pMgCached->iArg5, pMgCached->iArg6, pMgCached->iArg7, pMgCached->iArg8, pMgCached->iArg9, pMgCached->iArg10 , strArgs.c_str());


    bool bHandle = true;
    if( setFilter.find( pMgCached->wCmd ) == setFilter.end() )
    {
        iRet = V_GetUserData( g_stUser.stUin.dwUin ,  g_l_pb_user_t_data );
        if( iRet < 0 )
        {
            if( pMgCached->wCmd == CMD_USER_CREATE )
            {
                g_l_pb_user_t_data.mutable_f_info()->set_f_uin( g_stUser.stUin.dwUin );
                g_l_pb_user_t_data_bak.mutable_f_info()->set_f_uin( g_stUser.stUin.dwUin );

            }
            else
            {
                bHandle = false;
                ResponseToClt( pPkg, iPkgLen, pMgCached, strBody, iRet, iFrom );
                LOG_ERROR("[BUG] CMD(0x%X) Ret(%d)", pMgCached->wCmd , iRet);
            }
            
        }
        else
        {
            g_l_pb_user_t_data_bak.CopyFrom( g_l_pb_user_t_data );
        }
    }
    
    if( bHandle )
    {
        if( g_stUser.stUin.dwUin == g_l_pb_user_t_data.f_info().f_uin() )
        {
            V_InitUserData();
        }
        
        auto mapHandleIter = mapHandle.find( pMgCached->wCmd );
        if( mapHandleIter != mapHandle.end() )
        {
            iRet = mapHandleIter->second( pPkg, iPkgLen, pMgCached, strBody);
        }
        else
        {
            LOG_WARN("unexpected request command:%#x", pMgCached->wCmd);
            iRet = -200;
        }
        
        if( iRet < 0 )
        {
            ResponseToClt( pPkg, iPkgLen, pMgCached, strBody, iRet, iFrom );
            LOG_ERROR("[BUG] CMD(0x%X) Ret(%d) Len(%ld)", pMgCached->wCmd , iRet, strBody.length() );
        }
        else
        {
            if( iRet == RET_RSP_TO_CLT_AND_SAVE_USERDATA )
            {
                g_bSaveUserData = true;
            }
            
            switch (iRet)
            {
                case RET_RSP_TO_CLT_AND_SAVE_USERDATA:
                case RET_RSP_TO_CLT:
                {
                    if( g_bSaveUserData && setFilter.find( pMgCached->wCmd ) == setFilter.end() )
                    {
                        if( V_SetUserData(g_l_pb_user_t_data, g_l_pb_user_t_data_bak) < 0 )
                        {
                            LOG_BUG_RET( -203 );
                        }
                        
                    }
                    ResponseToClt(pPkg, iPkgLen, pMgCached, strBody, iRet, iFrom);
                    break;
                }
                default:
                    break;
            }
        }
    }
    
    V_PushMsg2MQ(wCmd, g_l_pb_report_t_report);
    return 0;
}


#endif
