/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/

#ifndef __YS_DATA_DEFINE_EX_H__
#define __YS_DATA_DEFINE_EX_H__

#include "def.h"

#pragma pack(1)

/*======================================================================
 *
 *  DEFINE
 *
 ======================================================================*/
typedef enum
{
    USER_INFO_BASEINFO              = 0x0001 ,
    USER_INFO_CHATROOM              = 0x0002 ,
    USER_INFO_CONTACT               = 0x0004 ,
}DefineUser;

typedef enum
{
    REPORT_MSG_REQ              = 1 ,
    REPORT_MSG_RSP              = 2 ,
    
    
    
    PROCESS_UDP             =   1,
    PROCESS_TCP             =   2,

    
}DefineGlobal;




//用户
typedef struct{
    uint32_t dwUin;
    uint16_t wClt;
    uint16_t wTmp;
} UserInfo;


typedef union{
    uint64_t lUin;
    UserInfo stUin;
} GUser ;


#pragma pack()

#endif
