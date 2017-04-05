/* ======================================================================
 * gsk project
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
#include <string>
#include <unordered_map>
#include "Common.h"

#ifndef __YS_CLIENT_INFO_H__
#define __YS_CLIENT_INFO_H__

using namespace std;

typedef struct
{
	int iSocket;
	time_t tLastTime;
}ClientFd;

class CClientInfo 
{
	public:
		CClientInfo() { };
		
		int Get(string strIP, uint16_t wPort);
		void Set(string strIP, uint16_t wPort, int iSocket);
		void Del(string strIP, uint16_t wPort);
		size_t size();
        void Clear();
		
    ~CClientInfo();
		


	private:

		CClientInfo(const CClientInfo&);
		CClientInfo& operator=(const CClientInfo&);		

		inline string GetKey(string strIP, uint16_t wPort);
		
        unordered_map< string , ClientFd > m_map;
        unordered_map< string , ClientFd >::iterator m_mapIter;
    
        static int m_iCounter;
};
#endif
