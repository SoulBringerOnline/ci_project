package com.gsk;

import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;

import com.gsk.mongo.MongoManager;
import com.gsk.redis.RedisManager;
import com.gsk.server.AppServer;
import com.gsk.utils.Configuration;

/**
 * 服务器启动类
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class GSKMain {

	private static Logger Log = Logger.getLogger(GSKMain.class.getName());

	public static void main(String[] args) {
		// 配置日志
		PropertyConfigurator.configure("gsk_log4j.properties");

		Configuration configuration = new Configuration("gsk_config.properties");
//		ConstantManager.HEART_BEAT = Integer.valueOf(configuration.getValue("heartBeat"));

		// 配置Redis
		RedisManager.SERVER_HOST = configuration.getValue("REDIS_HOST");
		RedisManager.SERVER_PORT = Integer.valueOf(configuration.getValue("REDIS_PORT"));
		RedisManager.returnJedis(RedisManager.getJedis());
		Log.info("Redis 初始化成功");

		//配置MONGO
		MongoManager.SERVER_HOST = configuration.getValue("MONGO_HOST");
		MongoManager.SERVER_PORT = Integer.valueOf(configuration.getValue("MONGO_PORT"));
		MongoManager.getDatabase();
		Log.info("MONGO 初始化成功");

		
		// 启动APP服务器
		new AppServer(8).startServer(configuration.getValue("SERVER_HOST"), Integer.valueOf(configuration.getValue("SERVER_PORT")));
	}

}
