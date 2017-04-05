/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/
/*
82 #define REDIS_REPLY_STRING 1
83 #define REDIS_REPLY_ARRAY 2
84 #define REDIS_REPLY_INTEGER 3
85 #define REDIS_REPLY_NIL 4
86 #define REDIS_REPLY_STATUS 5
87 #define REDIS_REPLY_ERROR 6
 */

#include <stdint.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <sys/types.h>
#include <string.h>
#include <string>
#include <unordered_map>
#include "hiredis.h"
#include "Common.h"
#include "oi_log.h"

#ifndef __YS_REDIS_INFO_H__
#define __YS_REDIS_INFO_H__

using namespace std;

typedef struct
{
	redisContext *pContext;
	time_t tLastTime;
}RedisContextInfo;

class CRedisInfo 
{
	public:
        CRedisInfo(LogFile *pstLog) {  m_pstLog = pstLog; };
    
		redisContext* Get(string strIP, uint16_t wPort);
		void Del(string strIP, uint16_t wPort);
		size_t size();
		
		~CRedisInfo()
		{
            for (m_mapIter = m_map.begin(); m_mapIter != m_map.end(); m_mapIter++)
            {
                redisFree( m_mapIter->second.pContext );
            }
			m_map.clear();
		};


	private:

        LogFile *m_pstLog;

		CRedisInfo(const CRedisInfo&);
		CRedisInfo& operator=(const CRedisInfo&);		

		inline string GetKey(string strIP, uint16_t wPort);

        unordered_map< string , RedisContextInfo > m_map;
        unordered_map< string , RedisContextInfo >::iterator m_mapIter;
};
#endif
