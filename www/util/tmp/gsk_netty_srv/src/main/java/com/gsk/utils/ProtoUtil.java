package com.gsk.utils;

import org.bson.Document;
import org.bson.types.Binary;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.google.protobuf.InvalidProtocolBufferException;
import com.gsk.manager.ConstantManager;
import com.gsk.protobuf.PbGsk.pb_clt_t_user;
import com.gsk.protobuf.PbGsk.pb_user_t_info;
import com.gsk.protobuf.PbGsk.pb_user_t_user;

public class ProtoUtil {
	private final static Logger Log = LoggerFactory.getLogger(ProtoUtil.class);

	public static pb_user_t_user UserDoc2Pb(Document userDoc) {
		pb_user_t_user pb_user = null ;
		try {
			Binary userObj = (Binary) userDoc.get("f_pb");

			pb_user_t_user.Builder pbUserBuilder = pb_user_t_user.newBuilder();
			pbUserBuilder.mergeFrom(userObj.getData());
			
			pb_user_t_info.Builder pbUserInfoBuilder = pbUserBuilder.getFInfoBuilder();
			pbUserInfoBuilder.setFUin(userDoc.getInteger("f_uin"));
			pbUserInfoBuilder.setFProvince(userDoc.getString("f_province"));
			pbUserInfoBuilder.setFCity(userDoc.getString("f_city"));
			pbUserInfoBuilder.setFPhone(userDoc.getString("f_phone"));
			pbUserInfoBuilder.setFName(userDoc.getString("f_name"));
			pbUserInfoBuilder.setFCompanyType (userDoc.getString("f_company_type"));
			pbUserInfoBuilder.setFYearsOfWorking(userDoc.getString("f_years_of_working"));
			pbUserInfoBuilder.setFJobType(userDoc.getString("f_job_type"));
			pbUserInfoBuilder.setFJobTitle(userDoc.getString("f_job_title"));
			pbUserInfoBuilder.build();
			
			pb_user = pbUserBuilder.build();
		} catch (InvalidProtocolBufferException e) {
			Log.error(e.getMessage(), e);
		}
		return pb_user;
	}
	
	
	public static pb_clt_t_user PromptUser2Clt( int iFlag, pb_user_t_user userInfo , pb_clt_t_user cltInfo)
	{
		pb_clt_t_user pbCltInfo = null ;
		pb_clt_t_user.Builder pbCltBuilder = pb_clt_t_user.newBuilder();
		try {
			
			if(cltInfo != null)
			{
				pbCltBuilder.mergeFrom(cltInfo);
			}
			
			//BASEINFO
			if((iFlag & ConstantManager.USER_INFO_BASEINFO) == ConstantManager.USER_INFO_BASEINFO )
			{
				pbCltBuilder.setFInfo(userInfo.getFInfo());
			}
			
			//RELEATION
			if((iFlag & ConstantManager.USER_INFO_RELEATION) == ConstantManager.USER_INFO_RELEATION )
			{
				//TODO releation
			}			
			
			//build
			pbCltInfo = pbCltBuilder.build();
		} catch (Exception e) {
			Log.error(e.getMessage(), e);
		}
		return pbCltInfo;
	}

}
