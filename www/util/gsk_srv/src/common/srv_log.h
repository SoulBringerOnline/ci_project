/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


#ifndef __YS_SRV_LOG_H__
#define __YS_SRV_LOG_H__

#ifdef __cplusplus
extern "C" {
#endif

#include "oi_log.h"
#include <stdio.h>
#include <stdarg.h>

#ifdef __cplusplus
}
#endif

typedef struct
{
    LogFile stSvrLog;
    LogFile stTraceLog;

    int iLogLevel;
    int iLogShiftType;
    int iMaxLogSize;
    int iMaxLogNum;
}SVRLOG;


#define LOG_LEVEL_ALL    6
#define LOG_LEVEL_DEBUG  5
#define LOG_LEVEL_INFO   4
#define LOG_LEVEL_WARN   3
#define LOG_LEVEL_ERROR  2
#define LOG_LEVEL_FATAL  1
#define LOG_LEVEL_OFF    0



//ANY
#define LOG_ANY(fmt, args...) do { OI_Log(&(g_stLog.stSvrLog), 2, "[LOG %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args); } while (0)

//FATAL
#define LOG_FATAL(fmt, args...) do { if (g_stLog.iLogLevel >= LOG_LEVEL_FATAL) OI_Log(&(g_stLog.stSvrLog), 2, "[FATAL %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args); } while (0)

//ERROR
#define LOG_ERROR(fmt, args...) do { if (g_stLog.iLogLevel >= LOG_LEVEL_ERROR) OI_Log(&(g_stLog.stSvrLog), 2, "[ERROR %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args); } while (0)

//WARN
#define LOG_WARN(fmt, args...) do { if (g_stLog.iLogLevel >= LOG_LEVEL_WARN) OI_Log(&(g_stLog.stSvrLog), 2, "[WARN %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args);} while (0)

//INFO
#define LOG_INFO(fmt, args...) do { if (g_stLog.iLogLevel >= LOG_LEVEL_INFO) OI_Log(&(g_stLog.stSvrLog), 2, "[INFO %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args);} while (0)

//DEBUG
#define LOG_DEBUG(fmt, args...) do { if (g_stLog.iLogLevel >= LOG_LEVEL_DEBUG) OI_Log(&(g_stLog.stSvrLog), 2, "[DEBUG %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args);} while (0)

//PRINT
#define LOG_PRINT(fmt, args...) do { OI_Log(&(g_stLog.stSvrLog), 2, "[DEBUG %u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  __FILE__, __LINE__, __FUNCTION__ , ## args);  printf("# %s:%d(%s): \033[1;33m " fmt " \033[0m \n", __FILE__, __LINE__, __FUNCTION__ , ## args);  } while (0)

//TRACE
#define LOG_TRACE(fmt, args...) do { OI_Log(&(g_stLog.stTraceLog), 2, "[%u %hu %lu] %s:%d(%s): " fmt, g_stUser.stUin.dwUin,  g_stUser.stUin.wClt, g_stUser.lUin,  ## args);} while (0)

//BUG
#define LOG_BUG_RET(retval) do { if( g_strLog.length() ){ LOG_DEBUG("BUG! - %s", g_strLog.c_str() )  ; } else { LOG_DEBUG("BUG!"); } return (retval); } while (0)

//BUG
#define LOG_BUG_RET_NULL() do { if( g_strLog.length() ){ LOG_DEBUG("BUG! - %s", g_strLog.c_str() )  ; } else { LOG_DEBUG("BUG!"); } return (NULL); } while (0)


//LOG_TEST
#ifndef LOG_TEST
#define LOG_TEST(fmt, args...) if(m_pstLog) { Log(m_pstLog, 2, "[TEST] %s:%d(%s): " fmt, __FILE__, __LINE__, __FUNCTION__ , ## args); }
#endif

#endif
