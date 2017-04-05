package com.gsk.mongo;

import org.bson.Document;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.mongodb.BasicDBObject;
import com.mongodb.client.MongoCollection;

/**
 * Mongo数据管理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class MongoDAO {

	private static final Logger Log = LoggerFactory
			.getLogger(MongoDAO.class);

	/**
	 * 获取用户信息
	 * 
	 * @param userId
	 * @param field
	 * @return
	 */
	public static Document getUserInfo(int userId) {
		MongoCollection<Document> collection = null;
		Document userDoc = null;
		try {
			collection = MongoManager.getCollection(MongoConstant.MONGO_DB_USER);
			if(collection != null)
			{
				BasicDBObject whereQuery = new BasicDBObject();
				whereQuery.put("f_uin", userId);
				userDoc = collection.find(whereQuery).first();

			}
		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		} 
		return userDoc;
	}

}
