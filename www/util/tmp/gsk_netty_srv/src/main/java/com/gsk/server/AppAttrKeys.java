package com.gsk.server;

import io.netty.util.AttributeKey;

import com.gsk.protobuf.PbGsk.pb_user_t_user;
import com.gsk.protobuf.PbGskReq.pb_req_t_req;

/**
 * 键管理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public interface AppAttrKeys {
	//通过包头获取
	public AttributeKey<Integer> CMD = AttributeKey.valueOf("command");
	public AttributeKey<Integer> USER_ID = AttributeKey.valueOf("userId");
	public AttributeKey<Integer> CLT_ID = AttributeKey.valueOf("clientId");
	public AttributeKey<Integer> SEQ_ID = AttributeKey.valueOf("seqId");
	public AttributeKey<Integer> CHANNEL_ID = AttributeKey.valueOf("channelId");
	public AttributeKey<Integer> CRC_ID = AttributeKey.valueOf("crcId");
	public AttributeKey<Integer> CLT_IP = AttributeKey.valueOf("clientIp");
	public AttributeKey<Integer> CLT_PORT = AttributeKey.valueOf("clientPort");
	public AttributeKey<Integer> GATE_TIME = AttributeKey.valueOf("gateTime");
	public AttributeKey<Integer> GATE_IP = AttributeKey.valueOf("gateIp");
	public AttributeKey<Integer> GATE_PORT = AttributeKey.valueOf("gatePort");
	public AttributeKey<Integer> GATE_FD = AttributeKey.valueOf("gateFd");
	public AttributeKey<Integer> RESULT = AttributeKey.valueOf("result");
	
	//会话数据
	public AttributeKey<pb_user_t_user> USER_INFO = AttributeKey.valueOf("userInfo"); 
	public AttributeKey<Integer> USER_INFO_FLAG = AttributeKey.valueOf("userInfoFlag"); //just for prompt clt data

	public AttributeKey<pb_req_t_req> REQ_INFO = AttributeKey.valueOf("reqInfo"); 

}
