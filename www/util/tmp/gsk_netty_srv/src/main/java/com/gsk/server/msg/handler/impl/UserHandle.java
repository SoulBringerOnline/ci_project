package com.gsk.server.msg.handler.impl;

import io.netty.channel.Channel;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.ResponseManager;
import com.gsk.server.AppAttrKeys;
import com.gsk.server.msg.handler.AppMsgHandler;

/**
 * 登录消息处理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class UserHandle implements AppMsgHandler {
	private final Logger Log = LoggerFactory.getLogger(getClass());

	public void process(Channel channel) {
	
		channel.attr(AppAttrKeys.USER_INFO_FLAG).set( 0xFFFF ); //FIXME 0xFFFF just for test

		ResponseManager.responseToClient(channel, null);
	}
}
