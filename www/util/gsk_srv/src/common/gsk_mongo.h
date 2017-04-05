/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/
#ifndef __YS_GSK_UTIL_MONGO_H__
#define __YS_GSK_UTIL_MONGO_H__

#include "inc.h"
#include "gsk_push.h"
#include "data_init.h"

/*======================================================================
 *  MONGO相关
 ======================================================================*/
static MongoDriver *g_pMongo = NULL;

void V_InitMongo()
{
    char szIp[32] = {0};
    int iPort;
    
    OI_Cfg_GetConfig("../conf/gsk.conf",
                     "MONGO_IP", CFG_STRING, szIp, "192.168.1.106", sizeof(szIp),
                     "MONGO_PORT", CFG_INT, &iPort , 27017,
                     NULL
                     );
    
    string strHost = "mongodb://";
    strHost.append(szIp);
    strHost.append(":");
    strHost.append( Common::tostr( iPort ) );
    LOG_DEBUG("[MONGO] %s", strHost.c_str() );
    g_pMongo = MongoDriver::instance( strHost.c_str() );
    if( g_pMongo == NULL ) { exit(-1); }
}


/*======================================================================
 *
 *  BSON PARSER
 *
 ======================================================================*/
inline int V_BsonIntField(const bson_t* pBson, const char *sField)
{
    int iValue = 0;
    if( !BCON_EXTRACT ( const_cast< bson_t* >(pBson) , sField, BCONE_INT32 ( iValue ) ) ){ iValue = 0; }
    return iValue;
};

inline long V_BsonLongField(const bson_t* pBson, const char *sField)
{
    long lValue = 0;
    if( !BCON_EXTRACT ( const_cast< bson_t* >(pBson) , sField, BCONE_INT64 ( lValue ) ) ){ lValue = 0; }
    return lValue;
};

inline string V_BsonStrField(const bson_t* pBson, const char *sField)
{
    const char *sValue;
    if( !BCON_EXTRACT ( const_cast< bson_t* >(pBson) , sField, BCONE_UTF8 ( sValue ) ) ){ return ""; }
    return sValue;
};



    
#endif
