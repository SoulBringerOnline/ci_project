
#ifndef __GSK_NET_H__
#define __GSK_NET_H__

#include <iostream>
#include <thread>
#include <queue>
#include <mutex>
#include <vector>
#include <fcntl.h>
#include <errno.h>
#include <unordered_map>

#define CMD_PUSH 0xEEEE
#define CMD_HEART 0xFFFF
#define SENDHEART_INTERVAL_S 180


struct __GSKNetArg
{
    int iCode;   // ״̬��
    int iSeq; //���кš�callbackid
    int iCmd;
    std::string strMsg; // �ر�����
};

typedef std::function<void (__GSKNetArg)> GSKCNetCallbackType;

#include "logging.h"
#include "GSKRequest.h"
#include "GSKResponse.h"
#include "GSKSocket.h"
#include "GSKPacket.h"

#define GSKNET_STATUS_OK 0
#define GSKNET_STATUS_DISCONNECT 1

#define GSKNET_CLIENT_REQUEST_TIMEOUT_CODE -1234
#define GSKNET_CLIENT_GSK_NET_NOT_WORK -2345

class GSKCNet
{
public:
	GSKCNet();
	~GSKCNet();
    
	static GSKCNet * sharedInstance();
    //��ʼ��, gsknetҪ������ǰ����Ҫ������open
    void Open(std::string strIp, int iPort, int iVersion);
    void Close();
	
    void Stop();
    void SetChannel(int channel);
    
    int GetChannel();
    
    // ����
    int Request( uint32_t dwUin, int iCmd, int iTimeout, const char *pMsg, int iLen, GSKCNetCallbackType callback);
    int Request( uint32_t dwUin, int iCmd, int iTimeout, std::string &strMsg, GSKCNetCallbackType callback);
    
    // ���ûص�
    void SetPushMsgHandler(GSKCNetCallbackType c);
    void SetHeartBeatHandler(GSKCNetCallbackType c);
    
    // ����
    void StartHeartBeat(int iUin);
    void StopHeartBeat();
    
    // ���ð汾��
    void SetClientVersion(int iVersion);
    
    // ������rsp time
    int GetTime() { return m_iLastRspTime; }
private:
    void ClearConext();
	void Start();
	void Loop();
    int HandleResponse(GSKResponse* pGSKResponse);
    int Request( GSKPacket* pGSKPacket, int iTimeout, GSKCNetCallbackType callback);

	static GSKCNet * m_pInstance;
    static int m_iSeq;

    GSKSocket* m_pGSKSocket;
	std::string m_strIp;
	int m_iPort;
    
    bool m_bWorking;
	std::thread m_workThread;	
	std::queue<GSKRequest*> m_sendQueue;
    std::unordered_map<int, GSKRequest*> m_hasSendedMap;
    std::mutex m_lock;
//    GSKCNetCallbackType m_pushCallback;
    int m_iNetStatus;
    int m_bHeartSendStart;
    int m_iHeartSequence;
    time_t m_iLastSendHeartTime;
    int m_iHeartUin;
//    GSKCNetCallbackType m_sendHeartCallback;
    
//    int m_iChannel;
//    int m_iClientVersion;
    int m_iLastRspTime;
    bool m_bInit;
};

#endif
