#include "GSKPacket.h"
#include <iostream>
#include "GLD_OS.h"

GSKPacket::GSKPacket(){ m_strBody = ""; }
GSKPacket::~GSKPacket(){}

GSKPacket* GSKPacket::create()
{
    GSKPacket *p = new GSKPacket();
    if( p )
    {
        return p;
    }
    
    return nullptr;
}

void GSKPacket::setCmd( unsigned short wCmd ){ m_wCmd = wCmd; }
short GSKPacket::getCmd( ){ return m_wCmd; }

void GSKPacket::setUin( unsigned int dwUin ){ m_dwUin = dwUin; }
unsigned int GSKPacket::getUin(){ return m_dwUin; }

void GSKPacket::setSeq( unsigned int dwSeq ){ m_dwSeq = dwSeq; }
int GSKPacket::getSeq(){ return m_dwSeq; }

void GSKPacket::setChannel( unsigned short wChannel ){ m_wChannel = wChannel; }
short GSKPacket::getChannel( ){ return m_wChannel; }

void GSKPacket::setResult( char cResult ){ m_cResult = cResult; }
int GSKPacket::getResult(){ return (int)m_cResult; }

int GSKPacket::length(){ return (int)m_strBuffer.size(); }

void GSKPacket::setBody( const std::string &strBody ){ m_strBody = strBody; }
void GSKPacket::setBody( const char* pBody , int iBodyLen ){ m_strBody.assign(pBody, iBodyLen); }
std::string GSKPacket::getBody(){ return m_strBody; }

std::string GSKPacket::encode ( )
{
    m_strBuffer = "";
    packIn8( m_strBuffer, 0x2 );
    uint32_t dwLen = GSK_PKG_HEAD_SIZE + 2 + (int)m_strBody.size();
    packIn32( m_strBuffer, dwLen ); //dwLength
    packIn16( m_strBuffer, m_wCmd ); //wCommand
   packIn32( m_strBuffer, m_dwUin ); //dwUin

#if defined(__APPLE__)
    packIn16( m_strBuffer, 2 ); //wClt 1:test 2:iOS 3:android
#elif defined(WIN32) //... 不能仅凭宏来确定，有可能PC会出mac版
	packIn16( m_strBuffer, 4 ); //wClt 1:test 2:iOS 3:android 4:PC
#else
    packIn16( m_strBuffer, 3 ); //wClt 1:test 2:iOS 3:android
#endif // __ANDROID__
    packIn32( m_strBuffer, m_dwSeq ); //dwSeq
    packIn16( m_strBuffer, m_wChannel ); //wChannel
    int iCrcPos = m_strBuffer.size();
    int iCrcLen = 4;
    packIn32( m_strBuffer,  0); //dwCrc

    packIn8( m_strBuffer, 0 ); // cResult
    packIn32( m_strBuffer, 0 ); // dwTime
    packIn32( m_strBuffer, 0 ); // dwcltip
    packIn16( m_strBuffer, 0 ); // wcltport
    packIn32( m_strBuffer, 0 ); // iFd
    packIn32( m_strBuffer, 0 ); // rspip
    packIn16( m_strBuffer, 0 ); // rspport
    packIn32( m_strBuffer, m_iVersion ); // version
    
    m_strBuffer.append( 17 , '\0' );
    if( m_strBody.size() )
    {
        m_strBuffer.append(m_strBody);
    }
    packIn8( m_strBuffer, 0x3 );
    
    const char *data = m_strBuffer.c_str();
    uint32_t tmp = (uint32_t)udc_crc32(CHECK_SUM, (const unsigned char*)data, dwLen);
    
    std::string strPackage = m_strBuffer.substr(0, iCrcPos);
    packIn32( strPackage,  tmp);
    strPackage.append(m_strBuffer.substr(iCrcPos + iCrcLen));
    
    return strPackage;
}

//packIn
void GSKPacket::packIn8 ( std::string &strBuf, char t)
{
    strBuf.append(sizeof(char), (char)t);
}
void GSKPacket::packIn8 (std::string &strBuf, unsigned char t)
{
    packIn8( strBuf, (char)t );
}
void GSKPacket::packIn8 (std::string &strBuf, int t)
{
    packIn8( strBuf, (char)t );
}

void GSKPacket::packIn16 (std::string &strBuf, short t)
{
    t = htons(t);
    strBuf.append((const char *)&t, sizeof(t));
}
void GSKPacket::packIn16 (std::string &strBuf, unsigned short t)
{
    packIn16( strBuf, (short)t );
}
void GSKPacket::packIn16 (std::string &strBuf, int t)
{
    packIn16( strBuf, (short)t );
}

void GSKPacket::packIn32 (std::string &strBuf, int t)
{
    t = htonl(t);
    strBuf.append((const char *)&t, sizeof(int));
}
void GSKPacket::packIn32 (std::string &strBuf, unsigned int t)
{
    packIn32( strBuf, (int)t );
}

void GSKPacket::packInStr (std::string &strBuf , const char *sBuffer)
{
    strBuf.append(sBuffer, strlen(sBuffer) + 1);
}

void GSKPacket::packInBytes (std::string &strBuf , const std::string& sBuffer  )
{
    short wLen = ( short )sBuffer.size();
    packIn16( strBuf, wLen );
    strBuf.append(sBuffer);
}


//void GSKPacket::decode ( )
//{
    // int dwLength = 130 + m_strBody.size();

    // std::string strHead = "";
    // packIn32( strHead, dwLength ); //dwLength
    // packIn16( strHead, m_wCmd ); //wCommand
    // packIn32( strHead, 0 ); //dwClientIP
    // packIn16( strHead, 0 ); //wClientPort
    // packIn16( strHead, m_wSrv ); //wServerId
    // packIn16( strHead, m_wPlt ); //wPlatformId
    // packIn32( strHead, m_dwUin ); //dwUin
    // packIn32( strHead, m_dwSeq ); //dwSeq
    // packIn8( strHead, 0 ); //cType
    // packIn32( strHead, 0 ); //iFd
    // packIn32( strHead, 0 ); //dwTime
    // packIn8( strHead, 0 ); //cResult
    // packIn32( strHead, 0 ); //dwRspIP
    // packIn16( strHead, 0 ); //wRspPort
    // packIn32( strHead, 81208878 ); //dwChecksum
    // packIn8( strHead, 0 ); //cFrom
    // packIn32( strHead, 0 ); //dwFlag
    // packIn32( strHead, 0 ); //iArg1
    // packIn32( strHead, 0 ); //iArg2
    // packIn32( strHead, 0 ); //iArg3
    // packIn32( strHead, 0 ); //iArg4
    // strHead.append( 63 , '0' );

    // // cout << dwLength << "|" << m_wCmd << "|" << m_dwUin << "|" << m_wPlt << "|" << m_wSrv << "|" << m_dwSeq <<  endl;

    // // fish: jia mi {
    // // int iContextLen = ((body.size()+17)/8 + 1)*8;
    // // char szKey[17] = KEY;

    // // int iPackageLen = 2 + sizeof(GSKPkgHead) + iContextLen;
    // // helper->checkResizeBuf(iPackageLen);

    // //  (helper->m_socketBuf)[0] = STX;
    // // GSKPkgHead *pHead = (GSKPkgHead *)((helper->m_socketBuf) + 1);
    // // // header
    // // memcpy(pHead, bodyData.data(), sizeof(GSKPkgHead));
    // // pHead->dwChecksum = htonl(CHECK_SUM);

    // // if(body.size() > 0)
    // // {
    // //     char *contextBuf = new char[iContextLen];
    // //     OicqEncrypt(1, (const BYTE*)body.c_str(), body.size(), (const BYTE*)szKey, (BYTE*)contextBuf, &iContextLen);
    // //     memcpy(pHead->body, contextBuf, iContextLen);
        
    // //     delete contextBuf;
    // // }
    // // else
    // // {
    // //     memcpy(pHead->body, body.c_str(), body.size());
    // //     iContextLen = body.size();
    // // }
    
    // // int iSendLen = 1 + sizeof(GSKPkgHead) + iContextLen + 1;
    // // (helper->m_socketBuf)[iSendLen - 1] = ETX;
    
    // // pHead->dwLength = htonl(iSendLen);
    // // long tmp = udc_crc32(CHECK_SUM, (const unsigned char*)helper->m_socketBuf, iSendLen);
    // // pHead->dwChecksum = htonl( tmp );
    // // }


    // m_strBuffer = "";
    // packIn8( m_strBuffer, 0x2 );
    // m_strBuffer.append(strHead);
    // if( m_strBody.size() )
    // {
    //     m_strBuffer.append(m_strBody);
    // }
    // packIn8( m_strBuffer, 0x3 );
    // cout << strHead.size() << "|" <<  m_strBuffer.size() << endl;

//}
