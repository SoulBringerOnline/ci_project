package com.gsk.server;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.CommandManager;
import com.gsk.server.msg.handler.AppMsgHandler;
import com.gsk.server.msg.handler.impl.UserHandle;

/**
 * 获取消息处理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class AppMsgHandlerFactory {
	private final static Logger Log = LoggerFactory
			.getLogger(AppMsgHandlerFactory.class);

	public static AppMsgHandler getAppMsgHandler(int cmd) {
		Log.info("请求[" + cmd + "]");

		if (cmd == CommandManager.CMD_USER_INFO) {
			return new UserHandle();
		} else {
			Log.info("未注册命令[" + cmd + "]");
			return null;
		}
	}

}
