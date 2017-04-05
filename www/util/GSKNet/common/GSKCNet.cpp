#include "GSKCNet.h"
#include "GSKPacket.h"
#include "GLD_OS.h"
#include <stdlib.h>
#include "GSKEncryption.h"

#define STX     0x02
#define ETX     0x03

static GSKCNetCallbackType g_pushCallback;
static GSKCNetCallbackType g_heartCallback;
static int g_channel = 0;
static int g_cltVersion = 0;

GSKCNet * GSKCNet::m_pInstance = nullptr;
int GSKCNet::m_iSeq = 1;

std::mutex g_mutexInstance;

GSKCNet * GSKCNet::sharedInstance()
{
    if(m_pInstance == nullptr)
    {
        g_mutexInstance.lock();
        
        if (m_pInstance == nullptr)
            m_pInstance = new GSKCNet();
        
        g_mutexInstance.unlock();
    }
    return m_pInstance;
}

void GSKCNet::SetPushMsgHandler(GSKCNetCallbackType c)
{
    g_pushCallback = c;
}

void GSKCNet::SetHeartBeatHandler(GSKCNetCallbackType c)
{
    g_heartCallback = c;
}

void GSKCNet::SetChannel(int channel)
{
    g_channel = channel;
}

int GSKCNet::GetChannel()
{
    return g_channel;
}

void GSKCNet::SetClientVersion(int iVersion)
{
    g_cltVersion = iVersion;
}

GSKCNet::GSKCNet()
{
    m_bInit = false;
    m_iNetStatus = GSKNET_STATUS_DISCONNECT;
    m_pGSKSocket = nullptr;
    m_bWorking = false;
    m_bHeartSendStart = false;
    m_iHeartSequence = 0;
    m_iHeartUin = 0;
    m_iLastSendHeartTime = 0;
    m_iLastRspTime = 0;
    
    srand( time(NULL) % 7951 );
    m_iSeq = rand();
}

GSKCNet::~GSKCNet()
{
    for( auto iter : m_hasSendedMap )
    {
        delete iter.second;
    }
    m_hasSendedMap.clear();
    
    
    while(!m_sendQueue.empty() )
    {
        GSKRequest* pRequest = m_sendQueue.front();
        m_sendQueue.pop();
        if(pRequest)
        {
            delete pRequest;
        }
    }
    
    if (m_pGSKSocket)
        delete m_pGSKSocket;
}

void GSKCNet::Open(std::string strIp, int iPort, int iVersion)
{
    if (m_bInit) {
        return;
    }
    
    m_bInit = true;
    m_strIp = strIp;
    m_iPort = iPort;
    g_cltVersion = iVersion;
    
    if (!m_pGSKSocket)
        m_pGSKSocket = new GSKSocket( m_strIp , m_iPort );
    
    
    std::queue<GSKRequest*> tmpQ(m_sendQueue);
    while(!tmpQ.empty() ) {
        
        m_lock.lock();
        GSKRequest* pRequest = tmpQ.front();
        tmpQ.pop();
        pRequest->SetSendTime((int)time(NULL));
        m_lock.unlock();
    }
    
    Start();
}

void GSKCNet::Close()
{
    g_mutexInstance.lock();
    Stop();
    
    if (!m_bInit)
        ClearConext();
    m_pInstance = nullptr;
    
    g_mutexInstance.unlock();
}

int GSKCNet::HandleResponse(GSKResponse* pGSKResponse)
{
    char *pBuff = (char*)malloc(GSK_PKG_HEAD_SIZE+1);
    int iRet = m_pGSKSocket->Recv(pBuff , GSK_PKG_HEAD_SIZE+1);
    if( iRet < 0 )
    {
        m_pGSKSocket->Close();
        LOGI("[HandlePacket] recv head err:%d" , iRet );
        free(pBuff);
        return -1;
    }
    
    if( pBuff[0] != STX )
    {
        m_pGSKSocket->Close();
        LOGI("[HandlePacket] stx err: %d" , pBuff[0] );
        free(pBuff);
        return -2;
    }
    
    int m = 1 ;
    int n = 0 ;
    GSKPkgHead stHead;
    n = 4 ;memcpy(&(stHead.dwLen),pBuff + m, n); m+=n; stHead.dwLen  = ntohl(stHead.dwLen);
    n = 2 ;memcpy(&(stHead.wCmd),pBuff + m, n); m+=n; stHead.wCmd  = ntohs(stHead.wCmd);
    n = 4 ;memcpy(&(stHead.dwUin),pBuff + m, n); m+=n; stHead.dwUin  = ntohl(stHead.dwUin);
    n = 2 ;memcpy(&(stHead.wClt),pBuff + m, n); m+=n; stHead.wClt  = ntohs(stHead.wClt);
    n = 4 ;memcpy(&(stHead.dwSeq),pBuff + m, n); m+=n; stHead.dwSeq  = ntohl(stHead.dwSeq);
    n = 2 ;memcpy(&(stHead.wChannelId),pBuff + m, n); m+=n; stHead.wChannelId  = ntohs(stHead.wChannelId);
    n = 4 ;memcpy(&(stHead.dwCRC),pBuff + m, n); m+=n; stHead.dwCRC  = ntohl(stHead.dwCRC);
    n = 1 ;memcpy(&(stHead.cResult),pBuff + m, n); m+=n;
    n = 4 ;memcpy(&(stHead.dwTime),pBuff + m, n); m+=n; stHead.dwTime  = ntohl(stHead.dwTime);
    n = 4 ;memcpy(&(stHead.dwCltIP),pBuff + m, n); m+=n; stHead.dwCltIP  = ntohl(stHead.dwCltIP);
    n = 2 ;memcpy(&(stHead.wCltPort),pBuff + m, n); m+=n; stHead.wCltPort  = ntohs(stHead.wCltPort);
    n = 4 ;memcpy(&(stHead.iFd),pBuff + m, n); m+=n; stHead.iFd  = ntohl(stHead.iFd);
    n = 4 ;memcpy(&(stHead.dwRspIP),pBuff + m, n); m+=n; stHead.dwRspIP  = ntohl(stHead.dwRspIP);
    n = 2 ;memcpy(&(stHead.wRspPort),pBuff + m, n); m+=n; stHead.wRspPort  = ntohs(stHead.wRspPort);
    free(pBuff);
    
//    LOGI("[HandleResponse] dwLength %d" , stHead.dwLen );
    LOGI("[HandleResponse] wCommand %x cResult %d" , stHead.wCmd,  stHead.cResult);
//    LOGI("[HandleResponse] dwClientIP %d" , stHead.dwCltIP );
//    LOGI("[HandleResponse] wClientPort %d" , stHead.wCltPort );
//    LOGI("[HandleResponse] wClt %d" , stHead.wClt );
//    LOGI("[HandleResponse] dwUin %d" , stHead.dwUin );
//    LOGI("[HandleResponse] dwSeq %d" , stHead.dwSeq );
//    LOGI("[HandleResponse] iFd %d" , stHead.iFd );
//    LOGI("[HandleResponse] dwTime %d" , stHead.dwTime );

//    LOGI("[HandleResponse] dwRspIP %d" , stHead.dwRspIP );
//    LOGI("[HandleResponse] wRspPort %d" , stHead.wRspPort );
//    LOGI("[HandleResponse] dwChecksum %d" , stHead.dwCRC );
    
    
    unsigned int dwLen = stHead.dwLen - GSK_PKG_HEAD_SIZE - 1 ;
    if( dwLen <= 0 || dwLen > 2048000 )
    {
        m_pGSKSocket->Close();
        LOGI("[HandleResponse] len err:%d  " , dwLen );
        return -3;
    }
    
    pBuff = (char*)malloc(dwLen);
    iRet = m_pGSKSocket->Recv(pBuff , dwLen);
    if( iRet < 0 )
    {
        m_pGSKSocket->Close();
        LOGI("[HandleResponse] recv body err:%d " , iRet );
        free(pBuff);
        pBuff = nullptr;
        return -4;
    }
    if( pBuff[dwLen-1] != ETX )
    {
        m_pGSKSocket->Close();
        LOGI("[HandleResponse] etxerr:%d" , pBuff[dwLen-1] );
        free(pBuff);
        pBuff = nullptr;
        return -5;
    }
    
    std::string strBody( pBuff, dwLen - 1 );
    
    free( pBuff );
    pBuff = nullptr;
    pGSKResponse->SetResult(stHead.cResult);
    pGSKResponse->SetMsg(strBody);
    pGSKResponse->SetSequence(stHead.dwSeq);
    memcpy(&pGSKResponse->stHead, &stHead, sizeof(stHead) );
    
    m_iLastRspTime = pGSKResponse->GetTime();
    return 0;
}

void GSKCNet::Loop()
{
    m_pGSKSocket->Connect();
    
    while(m_bWorking)
    {
        time_t now = time(NULL);
        if (m_bHeartSendStart && m_sendQueue.empty()
            && m_iHeartSequence == 0
            && now - m_iLastSendHeartTime>= SENDHEART_INTERVAL_S)
        {
            m_iLastSendHeartTime = now;
        
            GSKPacket *pPacket = GSKPacket::create();
            pPacket->setCmd(CMD_HEART);
            pPacket->setUin(m_iHeartUin);
            pPacket->setSeq(m_iSeq++);
            
            m_iHeartSequence = pPacket->getSeq();
            
            GSKRequest* pRequest = new GSKRequest(pPacket->encode(), 3, pPacket->getSeq(), g_heartCallback);
            pRequest->SetReqCmd(pPacket->getCmd());
            
            delete pPacket;
            
            m_lock.lock();
            m_sendQueue.push( pRequest );
            m_lock.unlock();
            
            LOGI("[GSKNet] [HEARTBEAT] SEND  uin:%d, sequence:%d sendtime:%ld", m_iHeartUin, m_iHeartSequence, m_iLastSendHeartTime);
        }
        
        while(!m_sendQueue.empty() )
        {
            m_lock.lock();
            GSKRequest* pRequest = m_sendQueue.front();
            m_sendQueue.pop();
             m_lock.unlock();
            
            if(pRequest)
            {
                std::string msg = pRequest->GetMsg();
                int iRet = m_pGSKSocket->Send(msg.data(), (int)msg.size(), pRequest->GetTimeout() );
                
                struct timeval now;
				glodon::getCurrentTime( now );
               
                double s = now.tv_sec * 1000 + now.tv_usec / 1000.0;
                
                LOGI("[GSKNet] send cmd:%x iCd %d send Len %lu sendTime:%f", pRequest->GetReqCmd(), pRequest->GetSequence(), (long)msg.size(), s);
                
                if(iRet < 0)
                {
                    LOGI("[GSKNet] Failed iCd %d iRet:%d", pRequest->GetSequence(), iRet );
                    __GSKNetArg stArg;
                    stArg.iCode = iRet;
                    stArg.iSeq = pRequest->GetSequence();
                    stArg.iCmd = pRequest->GetReqCmd();
                    
                    GSKCNetCallbackType funcCb = pRequest->GetCallback();
                    if (funcCb)
                        funcCb(stArg);
                    
                    delete pRequest;
                    pRequest = nullptr;
                }
                else
                {
                    m_lock.lock();
                    m_hasSendedMap.insert(std::pair<int, GSKRequest*>(pRequest->GetSequence(), pRequest) );
                    m_lock.unlock();
                }
            }
           
        }
        
        int iSocketStatus = m_pGSKSocket->Select();
        if (iSocketStatus == GSK_SELECT_ERROR)
        {
            m_iNetStatus = GSKNET_STATUS_DISCONNECT;
            LOGI("[GSKNet] net work error:%s", strerror(errno));
			glodon::sleep( 1 );
        }
        else if (iSocketStatus == GSK_SELECT_READ)
        {
            m_iNetStatus = GSKNET_STATUS_OK;
            GSKResponse stGSKResponse;
            int iRet = HandleResponse(&stGSKResponse);
            if(iRet == 0)
            {
                if (stGSKResponse.stHead.wCmd == CMD_PUSH)
                {
                    __GSKNetArg stArg;
                    stArg.iCode = stGSKResponse.GetResult();
                    stArg.iSeq = stGSKResponse.GetSequence();
                    stArg.iCmd = stGSKResponse.stHead.wCmd;
                    
                    int iCrc = stGSKResponse.stHead.dwCRC;
                    if (iCrc == CRC_MAGIC_NUM)
                        stArg.strMsg = stGSKResponse.GetMsg();
                    else {
                        string strOutData;
                        int iRet = GSKEncryptionUtil::decryptData(stGSKResponse.GetMsg(), KEY, strOutData);
                        if (iRet == 0) {
                            stArg.strMsg = strOutData;
                        } else {
                            stArg.iCode = iRet;
                        }
                    }
                    
                    if (g_pushCallback)
                        g_pushCallback(stArg);
                }
                else
                {
                    m_lock.lock();
                    std::unordered_map<int, GSKRequest*>::iterator iter = m_hasSendedMap.find(stGSKResponse.GetSequence() );
                    GSKCNetCallbackType funcCb = nullptr;
                    if(iter != m_hasSendedMap.end() )
                    {
                        GSKRequest* pSendRequest = iter->second;
                        funcCb = pSendRequest->GetCallback();
                        int iSendCmd = pSendRequest->GetReqCmd();
                        delete pSendRequest;
                        m_hasSendedMap.erase(iter);
                        
                        struct timeval now;
                        glodon::getCurrentTime( now );
                        double s = now.tv_sec * 1000 + now.tv_usec / 1000.0;
                        
                        if (stGSKResponse.GetSequence() == m_iHeartSequence)
                        {
                            LOGI("[GSKNet] [HEARTBEAT] good! uin:%d, sequence:%d", m_iHeartUin, m_iHeartSequence);
                            m_iHeartSequence = 0;
                        }
                        else
                            LOGI("[GSKNet] iCmd:%x iCb %d recv time %f",iSendCmd, stGSKResponse.GetSequence() , s );
                    }
                    
                    m_lock.unlock();
                    
                    if (funcCb)
                    {
                        __GSKNetArg stArg;
                        stArg.iCode = stGSKResponse.GetResult();
                        stArg.iSeq = stGSKResponse.GetSequence();
                        stArg.iCmd = stGSKResponse.stHead.wCmd;
                        
                        int iCrc = stGSKResponse.stHead.dwCRC;
                        if (iCrc == CRC_MAGIC_NUM)
                            stArg.strMsg = stGSKResponse.GetMsg();
                        else {
                            string strOutData;
                            int iRet = GSKEncryptionUtil::decryptData(stGSKResponse.GetMsg(), KEY, strOutData);
                            if (iRet == 0) {
                                stArg.strMsg = strOutData;
                            } else {
                                stArg.iCode = iRet;
                            }
                        }
                        
                        LOGI("[GSKNet] call callback code:%d msglen:%ld", stArg.iCode, stArg.strMsg.size());
                        funcCb( stArg );
                    }
                }
            }
        }
        
        //    ºÏ≤È≥¨ ±
        std::vector<GSKRequest*> timeoutVector;
        timeoutVector.clear();
        m_lock.lock();
        for(std::unordered_map<int, GSKRequest*>::iterator iter = m_hasSendedMap.begin();
            iter != m_hasSendedMap.end();)
        {
            GSKRequest* pRequest = iter->second;
            if(pRequest && (pRequest->GetTimeout() + pRequest->GetSendTime() < now ))
            {
                //TODO : handle timeout
                LOGI("[GSKNet] timeout cmd:%x iCb:%d", pRequest->GetReqCmd(), pRequest->GetSequence());
                
                GSKCNetCallbackType funcCb = pRequest->GetCallback();
                if (funcCb)
                {
                    __GSKNetArg stArg;
                    stArg.iCode = GSKNET_CLIENT_REQUEST_TIMEOUT_CODE;
                    stArg.iSeq = pRequest->GetSequence();
                    stArg.iCmd = pRequest->GetReqCmd();
                    funcCb( stArg );
                }
                
                if (pRequest->GetSequence() == m_iHeartSequence) {
                    m_iHeartSequence = 0;
                }
                
                delete pRequest;
                m_hasSendedMap.erase(iter++);
            }
            else
            {
                iter++;
            }
        }
        m_lock.unlock();
    }
    
    ClearConext();
}

void GSKCNet::ClearConext()
{
    m_bWorking = false;
    
    if (m_pGSKSocket)
        m_pGSKSocket->Close();
    
    m_hasSendedMap.clear();
    
    std::queue<GSKRequest*> q;
    m_sendQueue.swap(q);
    
    m_iHeartSequence = 0;
    m_iLastSendHeartTime = 0;
    delete this;
}

void GSKCNet::Start()
{
	m_bWorking = true;
	m_workThread = std::thread(std::bind( &GSKCNet::Loop, this));
	m_workThread.detach();
}

void GSKCNet::Stop()
{
    m_bWorking = false;
}


int GSKCNet::Request(GSKPacket* pGSKPacket, int iTimeOut, GSKCNetCallbackType callback)
{
    pGSKPacket->setSeq(m_iSeq++);
    pGSKPacket->setChannel(g_channel);
    
    int iRetSeq = pGSKPacket->getSeq();
    
    LOGI("[Request] UIN:%u CMD:%d %s:%d" , pGSKPacket->getUin(), pGSKPacket->getCmd(), m_strIp.c_str(), m_iPort );

    GSKRequest* pRequest = new GSKRequest(pGSKPacket->encode(), iTimeOut, pGSKPacket->getSeq(), callback);
    pRequest->SetReqCmd(pGSKPacket->getCmd());
    
    delete pGSKPacket;
    pGSKPacket = nullptr;

	m_lock.lock();
	m_sendQueue.push( pRequest );
	m_lock.unlock();
    
    if (!m_bInit) {
        __GSKNetArg stArg;
        stArg.iCode = GSKNET_CLIENT_GSK_NET_NOT_WORK;
        
        callback(stArg);
        return iRetSeq;
    }
    
    return iRetSeq;
}

int  GSKCNet::Request(uint32_t dwUin , int iCmd, int iTimeout, const char *pMsg, int iLen, GSKCNetCallbackType callback)
{
    std::string strMsg(pMsg, iLen);
    return Request( dwUin , iCmd , iTimeout , strMsg , callback );
}

int GSKCNet::Request(uint32_t dwUin , int iCmd, int iTimeout, std::string &strMsg, GSKCNetCallbackType callback)
{
    LOGI("[GSKCNet] request cmd:%x timeout:%d msglen:%d", iCmd, iTimeout, (int)strMsg.size());

    GSKPacket *pPacket = GSKPacket::create();
    pPacket->setCmd(iCmd);
    pPacket->setUin(dwUin);
    pPacket->setVersion(g_cltVersion);
//    pPacket->setBody(strMsg);

    string strOutData;
    int iRet = GSKEncryptionUtil::encryptData(strMsg, KEY, strOutData);
    if (iRet == 0) {
        pPacket->setBody(strOutData);
        return Request(pPacket, iTimeout, callback);
    }
    
    __GSKNetArg stArg;
    if (callback) {
        stArg.iCode = iRet;
        
        delete pPacket;
        callback(stArg);
    }
    
    return iRet;
}

void GSKCNet::StartHeartBeat(int iUin)
{
    LOGI("[GSKCNet] [HEARTBEAT] StartHeartBeat uin:%d", iUin);
    m_bHeartSendStart = true;
    m_iHeartUin = iUin;
}

void GSKCNet::StopHeartBeat()
{
    m_bHeartSendStart = false;
}


