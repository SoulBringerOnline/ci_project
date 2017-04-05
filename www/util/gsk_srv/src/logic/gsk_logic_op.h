/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#ifndef __YS_GSK_LOGIC_OP_H__
#define __YS_GSK_LOGIC_OP_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

//----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
HANDLE( OP )
{
    int iOPType = pMgCached->iArg1;
    switch (iOPType)
    {
        case 0:
            g_l_pb_user_t_data.SerializeToString(&strBody);
            break;
        default:
            break;
    }
    
    return RET_RSP_TO_CLT;
}

#endif
