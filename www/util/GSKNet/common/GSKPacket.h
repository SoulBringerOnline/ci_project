#ifndef __GSK_PACKET_H__
#define __GSK_PACKET_H__


#include <string>
#include <stdint.h>

extern "C" {
#include "crc.h"
}

#ifdef WIN32
#pragma warning(disable : 4200)
#endif

#define CHECK_SUM   798
#define CRC_MAGIC_NUM 81208878
#define KEY "tkffffffffffffff"

typedef struct
{
    uint32_t dwLen;  //包头+包体总长
    uint16_t wCmd;  //命令字
    uint32_t dwUin;     //userid
    uint16_t wClt; //ios/android/pc/mac....
    uint32_t dwSeq;     //客户端生成的seq
    uint16_t wChannelId;//小米、360、appstore....
    uint32_t dwCRC;     //请求包crc
    
    uint8_t cResult;    //response时的error code
    uint32_t dwTime;    //服务端生成-clt请求时的服务端时间
    uint32_t dwCltIP;//服务端生成-clt请求时的ip
    uint16_t wCltPort;//服务端生成-clt请求时的port
    int     iFd;
    uint32_t dwRspIP;
    uint16_t wRspPort;
    uint32_t dwVersion;
    char sResv[17];

#ifdef WIN32
#else
	char body[0];
#endif // WIN32

} GSKPkgHead; // 64

typedef struct{
    uint8_t cSTX;
    GSKPkgHead stHead;
	uint8_t cETX;
}GSKPkg; //66

#pragma pack()

#define GSK_PKG_HEAD_SIZE 64

class  GSKPacket
{
public:
    static GSKPacket* create();
    
    void setCmd( unsigned short wCmd );
    short getCmd();
     
    void setChannel( unsigned short wChannel );
    short getChannel();
    
    void setUin( unsigned int dwUin );
    unsigned int getUin();
    
    void setSeq( unsigned int dwSeq );
    int getSeq();
    
    void setResult( char cResult );
    int getResult();
    
    void setVersion(int iVersion) { m_iVersion = iVersion;}
    int getVersion() { return m_iVersion; }
    
    int length( );
    
    void setBody( const std::string &strBody );
    void setBody( const char* pBody , int iBodyLen );
    std::string getBody();
    
    void packIn8 (std::string &strBuf, char t);
    void packIn8 (std::string &strBuf, unsigned char t);
    void packIn8 (std::string &strBuf, int t);
    
    void packIn16 (std::string &strBuf, short t);
    void packIn16 (std::string &strBuf, unsigned short t);
    void packIn16 (std::string &strBuf, int t);
    
    void packIn32 (std::string &strBuf, int t);
    void packIn32 (std::string &strBuf, unsigned int t);
    
    void packInStr (std::string &strBuf , const char *sBuffer);
    void packInBytes (std::string &strBuf , const std::string& sBuffer);
    
    std::string encode();
    //		bool decode();
    
    GSKPacket();
    ~GSKPacket();
    
private:
    std::string m_strBody;
    unsigned int m_dwUin;

    short m_wCmd;
    short m_wClt;
    short m_wChannel;
    int m_iVersion;

    int m_dwSeq;
    std::string m_strBuffer;
    char m_cResult;
};

#endif
