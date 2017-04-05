/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#ifndef __YS_GSK_LOGIC_USER_H__
#define __YS_GSK_LOGIC_USER_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

//----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
HANDLE( GetUserInfo )
{
    g_iUserInfo |= pMgCached->iArg1;
    V_Serialize( g_l_pb_clt_t_user, strBody );
    return RET_RSP_TO_CLT;
}

HANDLE( CreateUser )
{
    g_l_pb_user_t_data.mutable_f_info()->set_f_province("北京");
    g_l_pb_user_t_data.mutable_f_info()->set_f_city("海淀");
    g_l_pb_user_t_data.mutable_f_info()->set_f_phone("13401031602");
    g_l_pb_user_t_data.mutable_f_info()->set_f_name("KK");
    g_l_pb_user_t_data.mutable_f_info()->set_f_company_type("设计单位");
    g_l_pb_user_t_data.mutable_f_info()->set_f_years_of_working("13-15年");
    g_l_pb_user_t_data.mutable_f_info()->set_f_job_type("项目经理");
    g_l_pb_user_t_data.mutable_f_info()->set_f_job_title("工程师");

    g_iUserInfo |= pMgCached->iArg1;
    V_Serialize( g_l_pb_clt_t_user, strBody );
    return RET_RSP_TO_CLT_AND_SAVE_USERDATA;
}


HANDLE( ModifyUser )
{
    if(g_l_pb_req_t_req.f_s_args_size() == 0 || g_l_pb_req_t_req.f_s_args_size() % 2 != 0)
    {
        LOG_BUG_RET(-10);
    }
    for( int i = 0 ; i < g_l_pb_req_t_req.f_s_args_size();  )
    {
        if( g_l_pb_req_t_req.f_s_args(i).compare( "f_name" ) == 0 )
        {
            g_l_pb_user_t_data.mutable_f_info()->set_f_name(g_l_pb_req_t_req.f_s_args(i+1));
        }
        else if( g_l_pb_req_t_req.f_s_args(i).compare( "f_province" ) == 0 )
        {
            g_l_pb_user_t_data.mutable_f_info()->set_f_province(g_l_pb_req_t_req.f_s_args(i+1));
        }
        else if( g_l_pb_req_t_req.f_s_args(i).compare( "f_city" ) == 0 )
        {
            g_l_pb_user_t_data.mutable_f_info()->set_f_city(g_l_pb_req_t_req.f_s_args(i+1));
        }
        else if( g_l_pb_req_t_req.f_s_args(i).compare( "f_company_type" ) == 0 )
        {
            g_l_pb_user_t_data.mutable_f_info()->set_f_company_type(g_l_pb_req_t_req.f_s_args(i+1));
        }
        
        i+=2;
    }
    g_iUserInfo |= USER_INFO_BASEINFO;
    V_Serialize( g_l_pb_clt_t_user, strBody );
    return RET_RSP_TO_CLT_AND_SAVE_USERDATA;
}

#endif
