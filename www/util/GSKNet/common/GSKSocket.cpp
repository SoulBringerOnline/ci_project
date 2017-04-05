#include <iostream>
#include <string>
#include "GLD_OS.h"
#include <fcntl.h>
#include <sys/stat.h>
#include <sys/types.h>
#include <fcntl.h>
#include "GSKSocket.h"

GSKSocket::GSKSocket( std::string strIP, int iPort ) 
{
    m_strIP = strIP;
    m_iPort = iPort;
    m_iSocket = INVALID_SOCKET;
}

GSKSocket::~GSKSocket()
{
    Close();
}

bool GSKSocket::IsConnected()
{
    if( m_iSocket == INVALID_SOCKET )
    {
        return false;
    }
    return true;
}

int GSKSocket::Connect( int iTimeout ) 
{
    if( IsConnected() )
    {
        return m_iSocket;
    }

	if ( !glodon::initilizeSocket() )
		return -10;

    struct sockaddr_in stSrvAddr;
    stSrvAddr.sin_family = AF_INET;
    stSrvAddr.sin_addr.s_addr = inet_addr( m_strIP.c_str() );
	stSrvAddr.sin_port =  htons( m_iPort );

    int iCount = 0;
    int iRet = -1;
    while  (iCount++ < 1 && iRet < 0) {
        iRet = 0;
        if ((m_iSocket = socket(AF_INET, SOCK_STREAM, 0)) == -1)
        {
            glodon::unInitlizeSocket();
            iRet = -11;  // 返回值无意义，负值即可
            continue;
        }
        
        int iNoDelay = 0;
        if (setsockopt(m_iSocket, IPPROTO_TCP, TCP_NODELAY,
                       reinterpret_cast<const char*>(&iNoDelay), (socklen_t)sizeof(iNoDelay)) != 0)
        {
            Close();
            iRet = -12; // 返回值无意义，负值即可
            continue;
        }
        
        auto result = glodon::setSocketNBlocked( m_iSocket );
        if ( result != 0 )
        {
            Close();
            iRet = result;
            continue;
        }
        
        if ( !glodon::connect( m_iSocket, (const struct sockaddr *)&stSrvAddr ) )
        {
            Close();
            iRet = -15; // 返回值无意义，负值即可
            continue;
        }
        
       
        fd_set stFdSet;
        FD_ZERO(&stFdSet);
        FD_SET(m_iSocket, &stFdSet);
        struct timeval stTimeout;
        stTimeout.tv_sec = iTimeout;
        stTimeout.tv_usec = 0;
        if ((iRet = select(m_iSocket + 1, NULL, &stFdSet, NULL, &stTimeout)) == -1)
        {
            Close();
            iRet = -16; // 返回值无意义，负值即可
            continue;
        }
        else if (iRet == 0)
        {
            errno = ETIME;
            Close();
            iRet = -17; // 返回值无意义，负值即可
            continue;
        }
        
        int iSockErr;
        socklen_t iSockErrLen = sizeof(iSockErr);
        if (getsockopt(m_iSocket, SOL_SOCKET, SO_ERROR, reinterpret_cast<char*>(&iSockErr), &iSockErrLen) == -1)
        {
            Close();
            iRet = -18; // 返回值无意义，负值即可
            continue;
        }
        if (iSockErr)
        {
            errno = iSockErr;
            Close();
            
            iRet = -19; // 返回值无意义，负值即可
            continue;
        }

        if (iRet > 0)
            return m_iSocket;
    }
    
    return iRet;
}

int GSKSocket::Select()
{
    if( IsConnected() )
    {
        struct timeval tv;
        tv.tv_sec   = 0;
        tv.tv_usec  = 1000 * 10;

        fd_set stReadfds;
        FD_ZERO(&stReadfds);
        FD_SET(m_iSocket, &stReadfds);

        if(select(m_iSocket + 1, &stReadfds, NULL, NULL, &tv) > 0)
        {
            if(FD_ISSET(m_iSocket, &stReadfds))
            {
                return GSK_SELECT_READ;
            }
        }
        
        return 0;
    }
    else
    {
        Connect( 1 );
    }
    return GSK_SELECT_ERROR; 
}


int GSKSocket::TrySend(const char* szBuf, int iBufLen , int iTimeout )
{
    int iRet = 0;
    fd_set stFdSet;
    FD_ZERO(&stFdSet);
    FD_SET(m_iSocket, &stFdSet);

    struct timeval stTimeOut;
    stTimeOut.tv_sec = iTimeout;
    stTimeOut.tv_usec = 0;

    int iSendBytes = 0;
    while (iSendBytes < iBufLen)
    {
        if ((iRet = select(m_iSocket + 1, NULL, &stFdSet, NULL, &stTimeOut)) == -1)
        {
            return -21; // 返回值无意义，负值即可
        }
        else if (iRet == 0)
        {
            errno = ETIME;
            return -22; // 返回值无意义，负值即可
        }

        if ((iRet = send(m_iSocket, szBuf+iSendBytes, iBufLen-iSendBytes, 0)) == -1)
        {
            if (errno == EWOULDBLOCK || errno == EINPROGRESS)
            {
                continue;
            }
            else
            {
                return -23; // 返回值无意义，负值即可
            }
        }
        iSendBytes += iRet;
    }

    return iSendBytes;
}

int GSKSocket::Send(const char* szBuf, int iBufLen , int iTimeout ) 
{
    int iRet = 0;
    if( m_iSocket < 0 )
    {
        iRet = Connect( iTimeout );
        if( iRet < 0 )
        {
            std::cout  << "[S1]" << iRet << std::endl ;
            return iRet;
        }
    }

    if( IsConnected() != 1 )
    {
        Close();
        iRet = Connect( iTimeout );
        if( iRet < 0 )
        {
            std::cout  << "[S2]" << iRet << std::endl ;
            return iRet;
        }
    }

    iRet = TrySend( szBuf , iBufLen , iTimeout ) ;
    if( iRet < 0 )
    {
        Close();
        iRet = Connect( iTimeout );
        if( iRet < 0 )
        {
            std::cout  << "[S3]" << iRet << std::endl ;
            return iRet;
        }
        return TrySend( szBuf , iBufLen , iTimeout ) ;
    }
    return iRet;
}

int GSKSocket::Recv( char* szBuf, int iBufLen , int iTimeout ) 
{
    if( IsConnected() )
    {
        fd_set stFdSet;
        FD_ZERO(&stFdSet);
        FD_SET(m_iSocket, &stFdSet);

        struct timeval stTimeout;
        stTimeout.tv_sec = iTimeout;
        stTimeout.tv_usec = 0;

        int iRet = 0;

        int iRecvBytes = 0;
        while (iRecvBytes < iBufLen)
        {
            if ((iRet = select(m_iSocket + 1, &stFdSet, NULL, NULL, &stTimeout)) == -1)
            {
                return -31;
            }
            else if (iRet == 0)
            {
                errno = ETIME;
                return -32;
            }

            if ((iRet = recv(m_iSocket, szBuf+iRecvBytes, iBufLen-iRecvBytes, 0)) == -1)
            {
                if (errno == EINPROGRESS || errno == EWOULDBLOCK)
                {
                    continue;
                }
                else
                {
                    return -33;
                }
            }
            else if (iRet == 0)
            {
                Close();
                return -34;
            }

            iRecvBytes += iRet;
        }

        return iRecvBytes;
    }

    return 0;
}

void GSKSocket::Close() 
{
    if( m_iSocket != INVALID_SOCKET )
    {
        glodon::closeSocket(m_iSocket);
		glodon::unInitlizeSocket();
        m_iSocket = INVALID_SOCKET;	
    }
}
