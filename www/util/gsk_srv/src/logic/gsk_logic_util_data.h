/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 *
 * ======================================================================*/
#ifndef __YS_GSK_LOGIC_DATA_H__
#define __YS_GSK_LOGIC_DATA_H__

#include "gsk_logic_common.h"
#include "gsk_logic_init.h"

/*======================================================================
 *
 *  用户数据UTIL
 *
 ======================================================================*/


inline void V_Clear()
{
    g_l_pb_user_t_data.Clear();
    g_l_pb_user_t_data_bak.Clear();
    g_l_pb_report_t_report.Clear();
    g_l_pb_clt_t_user.Clear();
    
    g_bSaveUserData = false;
    g_iUserInfo = 0;
    g_strLog = "";
}

inline int V_GetUserData( uint32_t dwUin ,  pb_user_t_data &l_pb_user_t_data)
{
    LOG_INFO("V_GetUserData %u", dwUin);

    l_pb_user_t_data.Clear();
    mongoc_cursor_t *pCursor = g_pMongo->find(MONGO_USER,
                                              BCON_NEW("f_uin", BCON_INT32 (dwUin)) ,
                                              1 );
    
    if( pCursor == NULL )
    {
        LOG_BUG_RET( -101 );
    }
    
    const bson_t *pDoc;
    while (mongoc_cursor_next (pCursor, &pDoc))
    {
        if(dwUin != V_BsonIntField(pDoc,"f_uin"))
        {
            LOG_BUG_RET( -102 );
        }
        
//        uint32_t dwPbLen;
//        const uint8_t *pPb;
//        bson_subtype_t subtype;
//        if(!BCON_EXTRACT (const_cast< bson_t* >(pDoc), "f_pb", BCONE_BIN (subtype, pPb, dwPbLen)))
//        {
//            LOG_BUG_RET( -103 );
//        }
//        if( subtype != BSON_SUBTYPE_BINARY )
//        {
//            LOG_BUG_RET( -104 );
//        }
//        if( !l_pb_user_t_data.ParseFromArray( pPb, dwPbLen ) )
//        {
//            LOG_BUG_RET( -105 );
//        }
//        if( l_pb_user_t_data.f_info().f_uin() != dwUin )
//        {
//            LOG_BUG_RET( -106 );
//        }
        
        //<-- Generated by system . DO NOT EDIT!
        l_pb_user_t_data.mutable_f_info()->set_f_uin(V_BsonIntField(pDoc,"f_uin"));
        l_pb_user_t_data.mutable_f_info()->set_f_province(V_BsonStrField(pDoc,"f_province"));
        l_pb_user_t_data.mutable_f_info()->set_f_city(V_BsonStrField(pDoc,"f_city"));
        l_pb_user_t_data.mutable_f_info()->set_f_phone(V_BsonStrField(pDoc,"f_phone"));
        l_pb_user_t_data.mutable_f_info()->set_f_name(V_BsonStrField(pDoc,"f_name"));
        l_pb_user_t_data.mutable_f_info()->set_f_company_type(V_BsonStrField(pDoc,"f_company_type"));
        l_pb_user_t_data.mutable_f_info()->set_f_years_of_working(V_BsonStrField(pDoc,"f_years_of_working"));
        l_pb_user_t_data.mutable_f_info()->set_f_job_type(V_BsonStrField(pDoc,"f_job_type"));
        l_pb_user_t_data.mutable_f_info()->set_f_job_title(V_BsonStrField(pDoc,"f_job_title"));
        l_pb_user_t_data.mutable_f_extra()->set_f_auto_inc_id(V_BsonIntField(pDoc,"f_auto_inc_id"));
        l_pb_user_t_data.mutable_f_extra()->set_f_last_req_time(V_BsonIntField(pDoc,"f_last_req_time"));
        //--> Generated by system . DO NOT EDIT!
    }
    
   
    if( l_pb_user_t_data.f_info().f_uin() != dwUin )
    {
        LOG_BUG_RET( -106 );
    }
    
    return 0;
}

inline int V_SetUserData( pb_user_t_data &l_pb_user_t_data , pb_user_t_data &l_pb_user_t_data_bak  )
{
    if(l_pb_user_t_data.f_info().f_uin() &&
       l_pb_user_t_data.f_info().f_uin() == l_pb_user_t_data_bak.f_info().f_uin() )
    {
        char sItemKey[32] = {0};
        
        bson_t* bo_user = bson_new();
        bson_append_int32(bo_user, "f_uin", -1, l_pb_user_t_data.f_info().f_uin() );

        //<-- Generated by system . DO NOT EDIT!
        if( l_pb_user_t_data_bak.f_info().f_province() != l_pb_user_t_data.f_info().f_province() )
        {
            bson_append_utf8(bo_user, "f_province", -1, l_pb_user_t_data.f_info().f_province().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_city() != l_pb_user_t_data.f_info().f_city() )
        {
            bson_append_utf8(bo_user, "f_city", -1, l_pb_user_t_data.f_info().f_city().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_phone() != l_pb_user_t_data.f_info().f_phone() )
        {
            bson_append_utf8(bo_user, "f_phone", -1, l_pb_user_t_data.f_info().f_phone().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_name() != l_pb_user_t_data.f_info().f_name() )
        {
            bson_append_utf8(bo_user, "f_name", -1, l_pb_user_t_data.f_info().f_name().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_company_type() != l_pb_user_t_data.f_info().f_company_type() )
        {
            bson_append_utf8(bo_user, "f_company_type", -1, l_pb_user_t_data.f_info().f_company_type().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_years_of_working() != l_pb_user_t_data.f_info().f_years_of_working() )
        {
            bson_append_utf8(bo_user, "f_years_of_working", -1, l_pb_user_t_data.f_info().f_years_of_working().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_job_type() != l_pb_user_t_data.f_info().f_job_type() )
        {
            bson_append_utf8(bo_user, "f_job_type", -1, l_pb_user_t_data.f_info().f_job_type().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_info().f_job_title() != l_pb_user_t_data.f_info().f_job_title() )
        {
            bson_append_utf8(bo_user, "f_job_title", -1, l_pb_user_t_data.f_info().f_job_title().c_str() , -1 );
        }
        if( l_pb_user_t_data_bak.f_extra().f_auto_inc_id() != l_pb_user_t_data.f_extra().f_auto_inc_id() )
        {
            bson_append_int32(bo_user, "f_auto_inc_id", -1, l_pb_user_t_data.f_extra().f_auto_inc_id() );
        }
        if( l_pb_user_t_data_bak.f_extra().f_last_req_time() != l_pb_user_t_data.f_extra().f_last_req_time() )
        {
            bson_append_int32(bo_user, "f_last_req_time", -1, l_pb_user_t_data.f_extra().f_last_req_time() );
        }
        //--> Generated by system . DO NOT EDIT!
        
        //pb_user_t_data
//        string strUser ;
//        if( !l_pb_user_t_data.SerializeToString(&strUser) )
//        {
//            LOG_BUG_RET( -101 );
//        }
//        
//        size_t dwLen = strUser.size();
//        if( dwLen == 0 )
//        {
//            LOG_BUG_RET( -102 );
//        }
//        bson_append_binary(bo_user, "f_pb", -1, BSON_SUBTYPE_BINARY, (uint8_t *)strUser.c_str(), dwLen );
//        
        bool bSucc = g_pMongo->update(MONGO_USER,
                                   BCON_NEW("f_uin", BCON_INT32 (l_pb_user_t_data.f_info().f_uin())),
                                   BCON_NEW( "$set" , "{" , BCON(bo_user) , "}" ) );
        bson_destroy( bo_user );
        if( !bSucc ){LOG_BUG_RET( -103 );}
        
    }
    
    return 0;
}

void V_InitUserData(pb_user_t_data &l_pb_user_t_data = g_l_pb_user_t_data)
{

}

inline int V_GetGid(  pb_user_t_data &l_pb_user_t_data = g_l_pb_user_t_data )
{
    int id = l_pb_user_t_data.f_extra().f_auto_inc_id() + 1;
    l_pb_user_t_data.mutable_f_extra()->set_f_auto_inc_id( id );
    return id;
}

#endif