package com.gsk.server;

import io.netty.bootstrap.ServerBootstrap;
import io.netty.channel.ChannelInitializer;
import io.netty.channel.ChannelOption;
import io.netty.channel.ChannelPipeline;
import io.netty.channel.EventLoopGroup;
import io.netty.channel.nio.NioEventLoopGroup;
import io.netty.channel.socket.SocketChannel;
import io.netty.channel.socket.nio.NioServerSocketChannel;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.gsk.manager.ConstantManager;
import com.gsk.server.codec.MsgDecoder;
import com.gsk.server.codec.MsgEncoder;

/**
 * 客户端服务
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class AppServer {
	/**
	 * 生成日志对象
	 */
	private final Logger Log = LoggerFactory.getLogger(getClass());
	private EventLoopGroup bossGroup = null;
	private EventLoopGroup workerGroup = null;

	public AppServer(int workerNum) {
		bossGroup = new NioEventLoopGroup();
		workerGroup = new NioEventLoopGroup(workerNum);
	}

	public void startServer(String host, int port) {
		// Configure the server.
		try {
			Log.info(ConstantManager.CLIENT_SERVER_INFO
					+ "正在启动GSK服务器,监听端口[" + host + ":" + port + "]");
			ServerBootstrap bootstrap = new ServerBootstrap();
			bootstrap.group(bossGroup, workerGroup);
			
			bootstrap.channel(NioServerSocketChannel.class);
			bootstrap.childHandler(new ChannelInitializer<SocketChannel>() {
				@Override
				protected void initChannel(SocketChannel ch) throws Exception {
					ChannelPipeline pipeline = ch.pipeline();
					pipeline.addLast("MsgDecoder", new MsgDecoder());// 解码器
					pipeline.addLast("MsgEncoder", new MsgEncoder());// 编码器
					pipeline.addLast("handler", new AppServerHandler());// 消息处理器
				}
			});

			//配置连接属性
			bootstrap.option(ChannelOption.SO_BACKLOG, 1024);
			bootstrap.childOption(ChannelOption.SO_LINGER, 0);
			bootstrap.childOption(ChannelOption.SO_REUSEADDR, true);
			bootstrap.childOption(ChannelOption.TCP_NODELAY, true);
			bootstrap.childOption(ChannelOption.SO_KEEPALIVE, true);
			
			bootstrap.bind(host,port).sync().channel().closeFuture().sync();

		} catch (Exception e) {
			Log.error(ConstantManager.CLIENT_SERVER_INFO + "GSK服务器异常 " + e.getMessage(), e);
		} finally {
			Log.error(ConstantManager.CLIENT_SERVER_INFO + "GSK服务器终止");
			workerGroup.shutdownGracefully();
			bossGroup.shutdownGracefully();
		}
	}
}
