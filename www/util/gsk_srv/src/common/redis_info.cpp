/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/

#include "redis_info.h"

#define REDIS_MAX_CONN_SPAN	180

inline string CRedisInfo::GetKey(string strIP, uint16_t wPort)
{
    if( strIP.length() == 0 || wPort == 0 )
    {
        return "";
    }
    
	string strKey ;
	strKey.append( Common::trim(strIP) );
	strKey.append( ":" );
	strKey.append( Common::tostr(wPort) );
    return strKey;
}

redisContext*  CRedisInfo::Get(string strIP, uint16_t wPort) 
{
    time_t tNow = time(NULL);
    string strKey = GetKey(strIP, wPort);
    
	m_mapIter = m_map.find(strKey);
	if( m_mapIter != m_map.end() && m_mapIter->second.pContext  )
	{
		if((tNow - m_mapIter->second.tLastTime) < REDIS_MAX_CONN_SPAN )
		{
			m_mapIter->second.tLastTime = tNow;
			return m_mapIter->second.pContext;
		}
		else
		{
            redisFree( m_mapIter->second.pContext );
            m_map.erase(m_mapIter);
		}
	}

    struct timeval stTimeOut = {1, 0};
    RedisContextInfo stInfo ;
    stInfo.tLastTime = tNow;
    stInfo.pContext = redisConnectWithTimeout(strIP.c_str(), wPort, stTimeOut);
    
    if (stInfo.pContext == NULL)
    {
        if( m_pstLog )
        {
            OI_Log( m_pstLog, 2 , "Connection(%s:%hd) error: can't allocate redis context" , strIP.c_str(), wPort );
        }
    }
    else if (stInfo.pContext->err)
    {
        if( m_pstLog )
        {
            OI_Log( m_pstLog, 2 , "Connection(%s:%hd) error: %s" , strIP.c_str(), wPort , stInfo.pContext->errstr);
        }
        redisFree(stInfo.pContext);
        stInfo.pContext = NULL;
    }
    
    if( stInfo.pContext )
    {
        m_map.insert( pair< string , RedisContextInfo >(strKey, stInfo) );
        return stInfo.pContext ;
    }
    
	return NULL;
};

void CRedisInfo::Del(string strIP, uint16_t wPort)
{
    m_mapIter = m_map.find(GetKey(strIP, wPort));
	if( m_mapIter != m_map.end() )
    {
        redisFree( m_mapIter->second.pContext );
        m_map.erase(m_mapIter);
    }
}

size_t CRedisInfo::size()
{
	return m_map.size();
};
