/* ======================================================================
 * vesuvio project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


#include <stdint.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <sys/types.h>
#include <string.h>
#include <unordered_map>
#include <typeinfo>

#include "data_def.h"
#include "oi_log.h"

#ifndef __YS_DATA_H__
#define __YS_DATA_H__

using namespace std;

template <typename TValue>
class CData
{
	public:
		CData()
		{
            OI_InitLogFile(&(m_stLog), "../log/gsk_data_", 3, 10, 10000000);
            m_map.clear();
		}
    
    
		TValue* GetNode( uint64_t id )
		{
			m_mapIter = m_map.find(id);
			if( m_mapIter != m_map.end() )
			{
				return &(m_mapIter->second);
			}
            else
            {
                OI_Log( &m_stLog, 2 , "[CDATA_%s] Key:%lu not found!", typeid(TValue).name() ,id );
            }
			return nullptr;
		}
    
		void SetNode(uint64_t id, const TValue &stValue)
		{
            m_map[id] = stValue;
        }
    
		size_t size()
		{
            return m_map.size();
        }
    
        void clear()
        {
            m_map.clear();
        }
    
        typename unordered_map< uint64_t , TValue >::iterator begin( )
        {
            return m_map.begin();
        }

        typename unordered_map< uint64_t , TValue >::iterator end( )
        {
            return m_map.end();
        }
		
		~CData()
		{
            m_map.clear();
        };

	private:
        LogFile m_stLog;

		CData(const CData&);
		CData& operator=(const CData&);		
    
        typename unordered_map< uint64_t , TValue >::iterator m_mapIter;
        unordered_map< uint64_t , TValue > m_map;
};
#endif

