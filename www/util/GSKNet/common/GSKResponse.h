#ifndef __GSK_RESPONSE_H__
#define __GSK_RESPONSE_H__


#include <string>
#include "GSKPacket.h"

class GSKResponse
{
public:	
    GSKResponse( ){}
	
    void SetMsg(const std::string & strMsg){m_strMsg = strMsg;}
    std::string GetMsg() const {return m_strMsg;}
    
    void SetSequence(int iSeq){m_iSequence = iSeq;}
    int  GetSequence() const {return m_iSequence;}
    
    void SetResult(int iResult){m_iResult = iResult;}
    int  GetResult() const {return m_iResult;}
    
    int  GetTime() const {return stHead.dwTime;}
       
private:
    std::string m_strMsg;
    int m_iSequence;
    int m_iResult;

public:
	GSKPkgHead stHead;
};

#endif
