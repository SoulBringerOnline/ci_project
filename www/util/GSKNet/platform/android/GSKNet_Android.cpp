#include <jni.h>
#include "JniHelper.h"
#include "GSKCNet.h"
#include "GSKNet_Android.h"
#include "GSKEncryption.h"

extern "C"
{

jint JNI_OnLoad(JavaVM *vm, void *reserved)
{
    JniHelper::setJavaVM(vm);
	
    LOGI("[JNI_OnLoad]");
    return JNI_VERSION_1_4;
}

JNIEXPORT void JNICALL Java_com_grandsoft_gsk_common_GSKNetUtil_InitJNIContext(JNIEnv*  env, jobject thiz, jobject context) {
    JniHelper::setClassLoaderFrom(context);

    GSKCNet::sharedInstance()->SetPushMsgHandler([=](__GSKNetArg stArg) {
        LOGI("[GSKPushMsgHandler]");
        
        JniMethodInfo t;
        if (JniHelper::getStaticMethodInfo(t, "com/grandsoft/gsk/common/GSKNetUtil", "GSKNetHandlePushMsgCallback", "(II[B)V")) {
        	const char *pData = stArg.strMsg.c_str();
        	int iLen = stArg.strMsg.size();

        	LOGI("[GSKPushMsgHandler] call GSKNetUtil msglen:%d", iLen);

        	jbyteArray jarray = t.env->NewByteArray(iLen);

        	if (iLen > 0)
        	{
        		t.env->SetByteArrayRegion(jarray, 0, iLen, (const jbyte*)pData);
        	}

        	int iCode = stArg.iCode;
        	int iSeq = stArg.iSeq;

        	t.env->CallStaticVoidMethod(t.classID, t.methodID, iCode, iSeq, jarray);

        	t.env->DeleteLocalRef(jarray);
        	t.env->DeleteLocalRef(t.classID);
        }
    });
}


int Java_com_grandsoft_gsk_common_GSKNetUtil_Request(JNIEnv *_env, jobject _thiz, jint _uin,  jint _cmd, jint _timeOut, jbyteArray _msg)
{
	jsize len  = _env->GetArrayLength(_msg);
	jbyte *pMsg = (jbyte*)_env->GetByteArrayElements(_msg, 0);
	uint32_t dwUin = (uint32_t)_uin;
	int iCmd = (int)_cmd;
	int iTimeout = (int)_timeOut;

	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Request], uin:%d cmd:%x timeout:%d msglen:%d", _uin, _cmd, _timeOut, (int)len);

	char* buffer = (char*)malloc(len+1);
	int iRet = -1;
	if (buffer != nullptr) {
		memcpy(buffer, pMsg, len);
		buffer[len] = '\0';
		// pass data to edittext's delegate
		iRet = GSKCNet::sharedInstance()->Request(dwUin , iCmd, iTimeout, buffer, (int)len, [=](__GSKNetArg stArg) {
			LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Request] Request callback, code:%d", stArg.iCode);

			JniMethodInfo t;
			if (JniHelper::getStaticMethodInfo(t, "com/grandsoft/gsk/common/GSKNetUtil", "GSKNetRequestCallback", "(III[B)V")) {
				const char *pData = stArg.strMsg.c_str();
				int iLen = stArg.strMsg.size();

				LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Request] call GSKNetUtil msglen:%d", iLen);

				jbyteArray jarray = t.env->NewByteArray(iLen);

				if (iLen > 0)
				{
					t.env->SetByteArrayRegion(jarray, 0, iLen, (const jbyte*)pData);
				}

				int iCode = stArg.iCode;
        			int iSeq = stArg.iSeq;
        			int iCmd = stArg.iCmd;

				t.env->CallStaticVoidMethod(t.classID, t.methodID, iCode, iSeq, iCmd, jarray);

				t.env->DeleteLocalRef(jarray);
				t.env->DeleteLocalRef(t.classID);
			}
		});
		free(buffer);
	}

	_env->ReleaseByteArrayElements(_msg, pMsg, 0);

	return iRet;
}

void Java_com_grandsoft_gsk_common_GSKNetUtil_Open(JNIEnv *_env, jobject _thiz, jstring _ip, jint _port, jint _version)
{
	std::string strIp = JniHelper::jstring2string(_ip);
	int iPort = (int)_port;
	int iVersion = (int)_version;
	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Open], ip:%s port:%d iVersion:%d", strIp.c_str(), iPort, iVersion);
	GSKCNet::sharedInstance()->Open(strIp, iPort, iVersion);
}

void Java_com_grandsoft_gsk_common_GSKNetUtil_CloseGSKNet(JNIEnv *_env, jobject _thiz)
{
	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_CloseGSKNet]");
	GSKCNet::sharedInstance()->Close();
}

void Java_com_grandsoft_gsk_common_GSKNetUtil_StartSendHeartBeat(JNIEnv *_env, jobject _thiz, jint _uin)
{
	int iUin = (int)_uin;
	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_StartSendHeartBeat], uin:%d", iUin);

	int iRet = -1;
	GSKCNet::sharedInstance()->SetHeartBeatHandler([=](__GSKNetArg stArg) {
		LOGI("[HEARTBEAT] code:%d", stArg.iCode);
		JniMethodInfo t;
		if (JniHelper::getStaticMethodInfo(t, "com/grandsoft/gsk/common/GSKNetUtil", "GSKNetSendHeartBeatCallback", "(II)V")) {
			int iCode = stArg.iCode;
    			int iSeq = stArg.iSeq;

			t.env->CallStaticVoidMethod(t.classID, t.methodID, iCode, iSeq);

			t.env->DeleteLocalRef(t.classID);
		}

	});

	GSKCNet::sharedInstance()->StartHeartBeat(iUin);
}

void Java_com_grandsoft_gsk_common_GSKNetUtil_StopSendHeartBeat(JNIEnv *_env)
{
	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_StopSendHeartBeat]");
	GSKCNet::sharedInstance()->StopHeartBeat();
}

void Java_com_grandsoft_gsk_common_GSKNetUtil_SetChannel(JNIEnv *_env, jobject _thiz, jint _channel)
{
	int iChannel = (int)_channel;
	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_SetChannel] channle:%d", iChannel);

	GSKCNet::sharedInstance()->SetChannel(iChannel);
}

int Java_com_grandsoft_gsk_common_GSKNetUtil_GetLastRspTime(JNIEnv *_env, jobject _thiz)
{
	return GSKCNet::sharedInstance()->GetTime();
}

JNIEXPORT jbyteArray JNICALL Java_com_grandsoft_gsk_common_GSKNetUtil_Encrypt(JNIEnv *_env, jobject _thiz, jstring _key, jbyteArray _data)
{
	jsize len  = _env->GetArrayLength(_data);
	jbyte *pMsg = (jbyte*)_env->GetByteArrayElements(_data, 0);
	std::string strKey = JniHelper::jstring2string(_key);

	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Encrypt], datalen:%d", (int)len);

	char* buffer = (char*)malloc(len+1);
	int iRet = -1;
	if (buffer != nullptr) {
		memcpy(buffer, pMsg, len);
		buffer[len] = '\0';

		std::string strOutData;
		iRet = GSKEncryptionUtil::encryptData(buffer, strKey, strOutData);

		if (iRet == 0) {
			const char *pData = strOutData.c_str();
			int iLen = strOutData.size();

			jbyteArray jarray = (_env)->NewByteArray(iLen);

			if (iLen > 0) {
				(_env)->SetByteArrayRegion(jarray, 0, iLen, (const jbyte*)pData);
			}

			free(buffer);
			return jarray;
		}
	}

	return NULL;
}

JNIEXPORT jbyteArray JNICALL Java_com_grandsoft_gsk_common_GSKNetUtil_Decrypt(JNIEnv *_env, jobject _thiz, jstring _key, jbyteArray _data)
{
	jsize len  = _env->GetArrayLength(_data);
	jbyte *pMsg = (jbyte*)_env->GetByteArrayElements(_data, 0);
	std::string strKey = JniHelper::jstring2string(_key);

	LOGI("[Java_com_grandsoft_gsk_common_GSKNetUtil_Decrypt], datalen:%d", (int)len);

	char* buffer = (char*)malloc(len+1);
	int iRet = -1;
	if (buffer != nullptr) {
		memcpy(buffer, pMsg, len);
		buffer[len] = '\0';

		std::string strOutData;
		iRet = GSKEncryptionUtil::decryptData(buffer, strKey, strOutData);

		if (iRet == 0) {
			const char *pData = strOutData.c_str();
			int iLen = strOutData.size();

			jbyteArray jarray = (_env)->NewByteArray(iLen);

			if (iLen > 0) {
				(_env)->SetByteArrayRegion(jarray, 0, iLen, (const jbyte*)pData);
			}

			free(buffer);
			return jarray;
		}
	}

	return NULL;
}

}