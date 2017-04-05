/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/
#ifndef __YS_GSK_LOGIC_UTIL_H__
#define __YS_GSK_LOGIC_UTIL_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"


/*======================================================================
 *
 *  工具UTIL
 *
 ======================================================================*/
//MQ
void V_PushMsg2MQ( uint16_t wCmd , pb_report_t_report &l_pb_report_t_report )
{
    if( l_pb_report_t_report.has_f_i_cmd() == false || l_pb_report_t_report.f_i_cmd() == 0 )
    {
        l_pb_report_t_report.set_f_i_cmd( wCmd );
        if( g_mapCmdInfo.find( wCmd ) != g_mapCmdInfo.end() )
        {
            l_pb_report_t_report.set_f_s_cmd( g_mapCmdInfo[wCmd]  );
        }
    }
    
    string strMsg = "";
    l_pb_report_t_report.SerializeToString( &strMsg );
    
    if( g_pZMQPublisher )
    {
        int iRet = zmq_send (g_pZMQPublisher, strMsg.data(), strMsg.size(), 0);
        if( iRet < 0 )
        {
            zmq_close (g_pZMQPublisher);
            zmq_ctx_destroy (g_pZMQContext);
            
            g_pZMQContext = zmq_ctx_new ();
            g_pZMQPublisher = zmq_socket (g_pZMQContext, ZMQ_PUB);
            char szBind[128] = {0};
            snprintf(szBind, sizeof(szBind) - 1, "tcp://%s:%d", g_stConfig.szMQIP, g_stConfig.iMQPort );
            iRet = zmq_connect (g_pZMQPublisher, szBind);
            iRet = zmq_send (g_pZMQPublisher, strMsg.data(), strMsg.size(), 0);
        }
    }
    else
    {
        LOG_DEBUG("[MQ] ZMQ ERROR!" );
    }
}



#endif
