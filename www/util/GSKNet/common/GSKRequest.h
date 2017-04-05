#ifndef __GSK_REQUEST_H__
#define __GSK_REQUEST_H__

#include <queue>
#include <string>



class GSKRequest
{
public:	
    GSKRequest(std::string strMsg, int iTimeout, int iSeq, GSKCNetCallbackType &callback )
    :m_strMsg(strMsg),
    m_funcCallback(callback),
    m_iTimeout(iTimeout),
    m_iSequence(iSeq),
    m_iSendTime(static_cast<int>(time(NULL))),
    m_iReqCmd(0)
	{
	}
    
    std::string GetMsg(){return m_strMsg;}
    int GetTimeout(){return m_iTimeout;}
    int GetSendTime(){return m_iSendTime;}
    int GetSequence() {return m_iSequence;}
    
    void SetSendTime(int iTime) { m_iSendTime = iTime; }
    
    GSKCNetCallbackType & GetCallback() {return m_funcCallback;}
    
    void SetReqCmd(int iCmd) { m_iReqCmd = iCmd; }
    int GetReqCmd() { return m_iReqCmd; }
private:
    std::string m_strMsg;
    GSKCNetCallbackType  m_funcCallback;
    int m_iTimeout;
    int m_iSendTime;
    int m_iSequence;
    int m_iReqCmd;
};

#endif
