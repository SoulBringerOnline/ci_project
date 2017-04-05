#ifndef __GSK_SOCKET_H__
#define __GSK_SOCKET_H__

#include <string>

#ifndef WIN32
#define INVALID_SOCKET	-1
#define SOCKET_ERROR	-1
#endif

#define GSK_SELECT_ERROR	-1
#define GSK_SELECT_READ	1
#define GSK_SELECT_WRITE   2
#define GSK_SELECT_NULL	0

#define GSK_TIMEOUT 3

class GSKSocket 
{
    public:
        GSKSocket(std::string strIP, int iPort);
        ~GSKSocket();
    
    void ChangeIpAndPort(std::string strIP, int iPort) {
        m_strIP = strIP;
        m_iPort = iPort;
    }

        int Connect( int iTimeout = GSK_TIMEOUT ) ;
        void Close();

        int Select();

        int Send( const char* szBuf, int iLen , int iTimeout = 3);
        int Recv( char* szBuf, int iLen , int iTimeout = 3);
        void SetWrite();

    private:
        int m_iSocket;
        std::string m_strIP;
        int m_iPort;
        bool IsConnected();
        int TrySend(const char* szBuf, int iBufLen , int iTimeout = GSK_TIMEOUT );

};

#endif
