/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


	 
#include "client_info.h"
#define CLT_MAX_CONN_SPAN	180

int CClientInfo::m_iCounter = 0;

inline string CClientInfo::GetKey(string strIP, uint16_t wPort)
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


int  CClientInfo::Get(string strIP, uint16_t wPort) 
{
	m_mapIter = m_map.find(GetKey(strIP, wPort));
	if( m_mapIter != m_map.end() )
	{
		time_t tNow = time(NULL);
		if((tNow - m_mapIter->second.tLastTime) < CLT_MAX_CONN_SPAN )
		{
			m_mapIter->second.tLastTime = tNow;
			return m_mapIter->second.iSocket;
		}
		else
		{
            if( m_mapIter->second.iSocket > 0 )
            {
                close( m_mapIter->second.iSocket );
            }
			m_map.erase(m_mapIter);
		}
	}
    
    m_iCounter++;
    m_iCounter = m_iCounter % 500;
    if( m_iCounter == 0 )
    {
        time_t tNow = time(NULL);
        for( m_mapIter = m_map.begin(); m_mapIter != m_map.end();  )
        {
            if((tNow - m_mapIter->second.tLastTime) > CLT_MAX_CONN_SPAN )
            {
                if( m_mapIter->second.iSocket > 0 )
                {
                    close( m_mapIter->second.iSocket );
                }
                m_map.erase(m_mapIter++);
            }
            else
            {
                m_mapIter++ ;
            }
        }
    }
    
    
	return -1;
};

void CClientInfo::Set(string strIP, uint16_t wPort, int iSocket)
{
    string strKey = GetKey(strIP, wPort);
	m_mapIter = m_map.find(strKey);
	time_t tNow = time(NULL);
	if(m_mapIter != m_map.end())
	{
		m_mapIter->second.tLastTime = tNow;
		m_mapIter->second.iSocket = iSocket;
	}
	else
	{
		ClientFd stFd ;
		stFd.iSocket = iSocket;
		stFd.tLastTime = tNow;
		m_map.insert( pair< string , ClientFd >(strKey, stFd) );
	}
};
void CClientInfo::Del(string strIP, uint16_t wPort)
{
    m_mapIter = m_map.find(GetKey(strIP, wPort));
	if( m_mapIter != m_map.end() )
	{
        if( m_mapIter->second.iSocket > 0 )
        {
            close( m_mapIter->second.iSocket );
        }
        m_map.erase(m_mapIter);
	}
}

size_t CClientInfo::size()
{
	return m_map.size();
};

void CClientInfo::Clear()
{
    for( m_mapIter = m_map.begin(); m_mapIter != m_map.end(); m_mapIter++ )
    {
        if( m_mapIter->second.iSocket > 0 )
        {
            close( m_mapIter->second.iSocket );
        }
    }
    
    m_map.clear();
}

CClientInfo::~CClientInfo()
{
    for( m_mapIter = m_map.begin(); m_mapIter != m_map.end(); m_mapIter++ )
    {
        if( m_mapIter->second.iSocket > 0 )
        {
            close( m_mapIter->second.iSocket );
        }
    }
    
    m_map.clear();
}

