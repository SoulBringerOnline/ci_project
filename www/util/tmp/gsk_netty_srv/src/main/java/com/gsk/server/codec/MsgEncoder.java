package com.gsk.server.codec;

import io.netty.buffer.ByteBuf;
import io.netty.buffer.Unpooled;
import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.MessageToByteEncoder;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.ConstantManager;
import com.gsk.protobuf.PbGsk.pb_clt_t_user;
import com.gsk.server.AppAttrKeys;

/**
 * 编码器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class MsgEncoder extends MessageToByteEncoder<pb_clt_t_user> {
	/**
	 * 日志对象
	 */
	private final Logger Log = LoggerFactory.getLogger(getClass());

	protected void encode(ChannelHandlerContext ctx, pb_clt_t_user msg,
			ByteBuf out) throws Exception {
		byte[] bytes = msg.toByteArray();
		int length = ConstantManager.GSK_PKG_MIN_SIZE + bytes.length;// 读取消息的长度

		ByteBuf buf = Unpooled.buffer(length);

		buf.writeByte(ConstantManager.STX); // 1 1
		buf.writeInt(length); // 4 5
		buf.writeShort(ctx.channel().attr(AppAttrKeys.CMD).get());// 2 7
		buf.writeInt(ctx.channel().attr(AppAttrKeys.USER_ID).get());// 4 11
		buf.writeShort(ctx.channel().attr(AppAttrKeys.CLT_ID).get());// 2 13
		buf.writeInt(ctx.channel().attr(AppAttrKeys.SEQ_ID).get());// 4 17
		buf.writeShort(ctx.channel().attr(AppAttrKeys.CHANNEL_ID).get());// 2 19
		buf.writeInt(ctx.channel().attr(AppAttrKeys.CRC_ID).get());// 4 23
		buf.writeByte(ctx.channel().attr(AppAttrKeys.RESULT).get());// 1 24
		buf.writeInt(ctx.channel().attr(AppAttrKeys.GATE_TIME).get());// 4 28
		buf.writeInt(ctx.channel().attr(AppAttrKeys.CLT_IP).get());// 4 32
		buf.writeShort(ctx.channel().attr(AppAttrKeys.CLT_PORT).get());// 2 34
		buf.writeInt(ctx.channel().attr(AppAttrKeys.GATE_FD).get());// 4 38
		buf.writeInt(ctx.channel().attr(AppAttrKeys.GATE_IP).get());// 4 42
		buf.writeShort(ctx.channel().attr(AppAttrKeys.GATE_PORT).get());// 2 44
		buf.writeZero(21);// 21 65
		buf.writeBytes(bytes);
		buf.writeByte(ConstantManager.ETX);// 1 
		out.writeBytes(buf);

		Log.info("[GSK-SERVER][SEND][remoteAddress:"
				+ ctx.channel().remoteAddress() + "][total length:" + length
				+ "]:\r\n" + msg.toString() + "  " + buf.toString());
	}
}
