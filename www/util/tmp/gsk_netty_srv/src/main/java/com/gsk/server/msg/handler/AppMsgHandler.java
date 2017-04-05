package com.gsk.server.msg.handler;

import io.netty.channel.Channel;

import com.gsk.protobuf.PbGsk.pb_user_t_user;
import com.gsk.protobuf.PbGskReq.pb_req_t_req;

/**
 * 消息处理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public interface AppMsgHandler {
	/**
	 * 处理方法
	 * 
	 * @param channel
	 * @param msg
	 */
	public void process(Channel channel);

}
