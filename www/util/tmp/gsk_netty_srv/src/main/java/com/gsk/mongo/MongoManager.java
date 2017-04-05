package com.gsk.mongo;

import org.apache.log4j.Logger;
import org.bson.Document;

import com.mongodb.MongoClient;
import com.mongodb.client.MongoCollection;
import com.mongodb.client.MongoDatabase;

/**
 * Mongo连接池管理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class MongoManager {

	/**
	 * 日志输出类
	 */
	private static Logger Log = Logger.getLogger(MongoManager.class.getName());

	/**
	 * 服务器地址
	 */
	public static String SERVER_HOST = "localhost";

	/**
	 * 端口
	 */
	public static int SERVER_PORT = 53999;

	/**
	 * 数据库
	 */
	public static String MONGO_DB = "gsk";

	private static MongoClient mongoClient = null;

	private static MongoClient getInstance() {

		if (mongoClient == null) {
			// TODO mongoclient options
			mongoClient = new MongoClient(SERVER_HOST, SERVER_PORT);
		}

		return mongoClient;
	}

	/**
	 * 获取database
	 * 
	 * @return MongoDatabase
	 */
	public static MongoDatabase getDatabase() {

		MongoDatabase database = null;

		try {
			database = getInstance().getDatabase(MONGO_DB);
		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		}

		return database;

	}

	/**
	 * 获取database
	 * 
	 * @return MongoCollection<Document>
	 */
	public static MongoCollection<Document> getCollection(String collectionName) {

		MongoCollection<Document> collection = null;

		try {
			collection = getDatabase().getCollection(collectionName);
		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		}

		return collection;

	}
}
