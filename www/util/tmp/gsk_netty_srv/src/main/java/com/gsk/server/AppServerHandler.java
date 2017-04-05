package com.gsk.server;

import io.netty.channel.ChannelHandlerAdapter;
import io.netty.channel.ChannelHandlerContext;

import org.bson.Document;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.ConstantManager;
import com.gsk.mongo.MongoDAO;
import com.gsk.protobuf.PbGsk.pb_user_t_user;
import com.gsk.protobuf.PbGskReq.pb_req_t_req;
import com.gsk.server.msg.handler.AppMsgHandler;
import com.gsk.utils.ProtoUtil;

/**
 * 服务器处理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class AppServerHandler extends ChannelHandlerAdapter {

	/**
	 * 创建日志对象
	 */
	private final Logger Log = LoggerFactory.getLogger(getClass());

	@Override
	public void channelRead(final ChannelHandlerContext ctx, Object msg)
			throws Exception {

		// 初始化用户数据
		int userId = ctx.channel().attr(AppAttrKeys.USER_ID).get();
		if (userId == 0) {
			Log.error("userId异常");
			return;
		}
		Document userDoc = MongoDAO.getUserInfo(userId);
		if (userDoc == null) {
			Log.error("获取用户[" + userId + "]数据失败！");
			return;
		}
		pb_user_t_user pbUser = ProtoUtil.UserDoc2Pb(userDoc);
		ctx.channel().attr(AppAttrKeys.USER_INFO).set(pbUser);
		Log.info(pbUser.toString());

		pb_req_t_req pbReq = (pb_req_t_req) msg;
		ctx.channel().attr(AppAttrKeys.REQ_INFO).set(pbReq);
		Log.info(pbReq.toString());

		// Handler
		AppMsgHandler msgHandler = AppMsgHandlerFactory
				.getAppMsgHandler(pbReq.getFCmd());
		if (msgHandler != null) {
			msgHandler.process(ctx.channel());
		} else {
			// 没找到相应的handler
			Log.error("no handler,msg:" + pbReq.toString());
			return;
		}
	}

	@Override
	public void channelActive(ChannelHandlerContext ctx) throws Exception {
		Log.info(ConstantManager.CLIENT_CONNECTED + "remoteAddress:"
				+ ctx.channel().remoteAddress());
		ctx.channel().attr(AppAttrKeys.USER_ID).set(0);
	}

	@Override
	public void channelInactive(ChannelHandlerContext ctx) throws Exception {

		try {
			long userId = ctx.channel().attr(AppAttrKeys.USER_ID).get();
			Log.info(ConstantManager.CLIENT_DISCONNECTED + "userId:" + userId
					+ ConstantManager.LOG_SEPARATE_PARAMS + "remoteAddress:"
					+ ctx.channel().remoteAddress());

			// 关闭连接
			if (ctx.channel().isOpen()) {
				ctx.channel().close();
			}
		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		}
	}

	@Override
	public void exceptionCaught(ChannelHandlerContext ctx, Throwable cause)
			throws Exception {
		cause.printStackTrace();
		ctx.close();
	}

}
