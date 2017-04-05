/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/
#ifndef __YS_GSK_LOGIC_PROMPT_H__
#define __YS_GSK_LOGIC_PROMPT_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

/*======================================================================
 *
 *  客户端数据包装UTIL
 *
 ======================================================================*/
bool V_PromptBaseInfo(pb_clt_t_user &l_pb_clt_t_user ,pb_user_t_data &l_pb_user_t_data = g_l_pb_user_t_data)
{
    l_pb_clt_t_user.mutable_f_info()->CopyFrom( l_pb_user_t_data.f_info() );
    return true;
}

void V_PromptUser2Clt( pb_clt_t_user &l_pb_clt_t_user , int iType = 0xFFFF , bool bOnline = false , pb_user_t_data &l_pb_user_t_data = g_l_pb_user_t_data)
{
    //BASEINFO
    if( (iType & USER_INFO_BASEINFO) == USER_INFO_BASEINFO)
    {
        V_PromptBaseInfo( l_pb_clt_t_user , l_pb_user_t_data );
    }
}

inline void V_Serialize( pb_clt_t_user &l_pb_clt_t_user, string &strBody , int iFlag = 0 )
{
    iFlag = iFlag | g_iUserInfo;
    if( iFlag )
    {
        V_PromptUser2Clt( l_pb_clt_t_user, iFlag );
    }
    l_pb_clt_t_user.SerializeToString(&strBody);
}

#endif
