/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


#ifndef __YS_DEFINE_H__
#define __YS_DEFINE_H__

#include <stdio.h>
#include <stdint.h>
#include <time.h>
#include <sys/types.h>
#include <sys/types.h>
#include <vector>
#include <unordered_map>

//COMMON DEFINE

#define CRC_KEY                 798

#define 	IP_LEN					16
#define  	RESERVE_LEN             76
#define 	MINS_OF_DAY 			1440		// 1440 = 24 * 3600 minutes of one day
#define 	SECS_OF_DAY 			86400		// 86400 = 24*3600 seconds of one day
#define     SECS_OF_FIX             28800
#define 	SECS_OF_MIN 			60
#define     SECS_OF_HOUR            3600
#define 	SECS_OF_21_OCLOCK 			75600		// 86400 = 24*3600 seconds of one day
#define 	SECS_OF_24_OCLOCK 			86400
#define 	SECS_OF_WEEK 			604800		
#define 	HOURS_OF_DAY 			24

#define 	MAX_FILE_PATH 			1024
#define     MAX_BUFF_LEN             2048000

#define 	SRV_TIMEOUT_SEC 1
#define 	SRV_TIMEOUT_USEC 0
#define 	SRV_SEND_TRY_TIMES 2


//PORT DEFINE
#define TCP_SRV_PORT_IO_FOR_CLT         18100
#define TCP_SRV_PORT_IO_FOR_LOGIC       19100

#define TCP_SRV_PORT_LOGIC_FOR_II       22100
#define UDP_SRV_PORT_LOGIC              22150

#define UDP_SRV_PORT_PUSH_FILTER               18887
#define UDP_SRV_PORT_PUSH_SYNC               18888
#define UDP_SRV_PORT_PUSH_GATE               18889





#define UDP_SRV_PORT_MONI              16100
#define UDP_SRV_PORT_LOG               16200


//UTIL DEFINE
#define ENCRYPT_BUF_LEN(size) ((((size) + 17)/8 + 1) * 8)

#define RET_RSP_TO_CLT                          100
#define RET_RSP_TO_CLT_AND_SAVE_USERDATA        101
#define RET_WITHOUT_RSP                         102

#define RET_WITH_USERDATA                                           200
#define RET_WITHOUT_USERDATA                                        201

#define CLT_ID_OP                   999


#define SQM_STAT_TYPE_REQ                                           1
#define SQM_STAT_TYPE_RSP                                           2
#define SQM_STAT_TYPE_ERROR                                         3
#define SQM_STAT_TYPE_TIMEOUT                                       4




//CONFIG
#ifndef CHECK_PTR
#define CHECK_PTR(p,ret) do { if( p == NULL ){ LOG_BUG_RET(ret); } } while (0)
#endif  // CHECK_PTR

#define CAST(t, exp)    ((t)(exp))
#define CAST_BYTE(i)    CAST(uint8_t, (i))
#define CAST_UINT16(i)     CAST(uint16_t, (i))
#define CAST_INT(i)     CAST(int, (i))
#define CAST_UINT(i)    CAST(uint32_t, (i))
#define CAST_LONG(i)    CAST(uint64_t, (i))
#define CAST_FLOAT(i)    CAST(float, (i))

#define LOAD_LUA_SCRIPT(name) V_LoadLua(lua_##name,"../lua/"#name".lua");
#define HANDLE(func) int Handle##func(void *pPkg, int iPkgLen , SessionCached *pMgCached, string &strBody)

//BSON
#define BSON_STRING_TYPE 2
#define BSON_OBJECT_TYPE 3
#define BSON_ARRAY_TYPE 4
#define BSON_OBJECTID_TYPE 7
#define BSON_INT_TYPE 16
#define BSON_TIMESTAMP_TYPE 17
#define BSON_LONG_LONG_TYPE 18

#define MONGO_USER              "user"
#define MONGO_CHAT              "chat"


#define GSK_COMMON_DEFINE \
static int32_t g_iSignal = 0;\
static CONFIG g_stConfig;\
static SVRLOG g_stLog;\
static GUser g_stUser;\
static string g_strLog;\
static int g_tCurTime;


#define GSK_INIT_LOG(name)     do {\
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,\
                     "LOG_LEVEL", CFG_INT, &(g_stLog.iLogLevel), 5,\
                     "LOG_SHIFT_TYPE", CFG_INT, &(g_stLog.iLogShiftType), 3,\
                     "LOG_MAX_NUM", CFG_INT, &(g_stLog.iMaxLogNum), 30,\
                     "LOG_MAX_SIZE", CFG_INT, &(g_stLog.iMaxLogSize), 10000000,\
                     NULL\
                     );\
    char szLogFilePath[256];\
    memset(szLogFilePath, 0, sizeof(szLogFilePath));\
    snprintf(szLogFilePath, sizeof(szLogFilePath) - 1,"../log/"#name"_");\
    OI_InitLogFile(&(g_stLog.stSvrLog), szLogFilePath, g_stLog.iLogShiftType, g_stLog.iMaxLogNum, g_stLog.iMaxLogSize);\
} while(0)


#endif
