package com.gsk.server.codec;

import io.netty.buffer.ByteBuf;
import io.netty.buffer.Unpooled;
import io.netty.channel.ChannelHandlerContext;
import io.netty.handler.codec.ByteToMessageDecoder;

import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.ConstantManager;
import com.gsk.protobuf.PbGskReq.pb_req_t_req;
import com.gsk.server.AppAttrKeys;

/**
 * 解码器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class MsgDecoder extends ByteToMessageDecoder {
	/**
	 * 日志
	 */
	private final Logger Log = LoggerFactory.getLogger(getClass());

	@Override
	protected void decode(ChannelHandlerContext ctx, ByteBuf in,
			List<Object> out) throws Exception {
		// 标记一下当前的readIndex的位置
		in.markReaderIndex();
		// 判断包头长度
		if (in.readableBytes() < (ConstantManager.GSK_PKG_HEADER_SIZE+1)) {// 不够包头 + STX
			in.resetReaderIndex();
			return;
		}

		// 读取传送过来的消息的长度。
		byte STX = in.readByte();
		int length = in.readInt();

		// STX异常，非法数据，关闭连接
		if (STX != ConstantManager.STX) {
			Log.error(ConstantManager.CLIENT_SERVER_INFO + "STX错误！" + STX);
			ctx.close();
			return;

		}

		// 长度如果小于0，// 非法数据，关闭连接
		if (length < ConstantManager.GSK_PKG_MIN_SIZE) {
			Log.error(ConstantManager.CLIENT_SERVER_INFO + "包长错误！" + length);
			ctx.close();
			return;
		}

		short cmd = in.readShort();
		Log.info(ConstantManager.CLIENT_SERVER_INFO + "CMD:" + cmd + " 包长:"
				+ length);
		ctx.channel().attr(AppAttrKeys.CMD).set((int) cmd);
		ctx.channel().attr(AppAttrKeys.USER_ID).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.CLT_ID).set((int) in.readShort());
		ctx.channel().attr(AppAttrKeys.SEQ_ID).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.CHANNEL_ID).set((int) in.readShort());
		ctx.channel().attr(AppAttrKeys.CRC_ID).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.RESULT).set((int)in.readByte());
		ctx.channel().attr(AppAttrKeys.GATE_TIME).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.CLT_IP).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.CLT_PORT).set((int) in.readShort());
		ctx.channel().attr(AppAttrKeys.GATE_FD).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.GATE_IP).set(in.readInt());
		ctx.channel().attr(AppAttrKeys.GATE_PORT).set((int) in.readShort());
		in.readBytes(21);
		
		int bodyLength = length - 1 - ConstantManager.GSK_PKG_HEADER_SIZE;
		if (bodyLength > in.readableBytes()) {// 读到的消息体长度如果小于传送过来的消息长度
			// 重置读取位置
			Log.error(ConstantManager.CLIENT_SERVER_INFO + "包长:" + length
					+ " 可读:" + in.readableBytes());
			in.resetReaderIndex();
			return;
		}

		ByteBuf frame = Unpooled.buffer(bodyLength - 1);
		in.readBytes(frame);

		try {
			byte[] inByte = frame.array();

			// 字节转成对象
			pb_req_t_req msg = pb_req_t_req.parseFrom(inByte);
			Log.info("[GSK-SERVER][RECV][remoteAddress:"
					+ ctx.channel().remoteAddress() + "][cmd:" + cmd
					+ "][total length:" + length + "][bare length:"
					+ msg.getSerializedSize() + "]:\r\n" + msg.toString());

			if (msg != null) {
				// 获取业务消息头
				out.add(msg);
			}
		} catch (Exception e) {
			Log.info(ctx.channel().remoteAddress() + ",decode failed.", e);
		}

	}
}
