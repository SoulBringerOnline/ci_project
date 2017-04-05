/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#ifndef __YS_GSK_LOGIC_TEMP_H__
#define __YS_GSK_LOGIC_TEMP_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

//----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
HANDLE( Temp )
{
    V_Serialize( g_l_pb_clt_t_user, strBody );
    return RET_RSP_TO_CLT;
}

#endif
