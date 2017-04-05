package com.gsk.manager;

import io.netty.channel.Channel;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.protobuf.PbGsk.pb_clt_t_user;
import com.gsk.protobuf.PbGsk.pb_user_t_user;
import com.gsk.server.AppAttrKeys;
import com.gsk.utils.ProtoUtil;

/**
 * 消息发送管理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class ResponseManager {

	/**
	 * 生成日志对象
	 */
	private final static Logger Log = LoggerFactory
			.getLogger(ResponseManager.class);

	/**
	 * 发送错误
	 * 
	 * @param channel
	 * @param code
	 */
	public static void responseError(Channel channel, int code) {

	}

	/**
	 * 直接发送消息
	 * 
	 * @param channel
	 *            通道
	 */
	public static void responseToClient(Channel channel) 
	{
		responseToClient(channel, null);
	}

	
	/**
	 * 直接发送消息
	 * 
	 * @param channel
	 *            通道
	 * @param pbCltInfo
	 *            要发送的消息
	 */
	public static void responseToClient(Channel channel, pb_clt_t_user pbCltInfo) {
		try {
			int userInfoFlag = channel.attr( AppAttrKeys.USER_INFO_FLAG ).get();
			pb_user_t_user userInfo = channel.attr( AppAttrKeys.USER_INFO ).get();

			pb_clt_t_user msgSend = ProtoUtil.PromptUser2Clt(userInfoFlag, userInfo, pbCltInfo);
			
			channel.writeAndFlush(msgSend);
			Log.info(msgSend.toString());

		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		}
	}

}
