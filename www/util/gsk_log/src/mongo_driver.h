/* ======================================================================
 * taal project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongsheng.zhao@lavaclan.com
 * Date   	: 	2014-08-15
 *
 * Copyright (c) 2014年 http://www.lavaclan.com All rights reserved.
 * ======================================================================*/


#ifndef __YS_MONGO_H__
#define __YS_MONGO_H__

#include "oi_log.h"

#include <mongoc.h>
#include <unordered_map>
#include <string>
#include <vector>

class MongoDriver{
public:
    MongoDriver( const char* sHost );
    ~MongoDriver();
    static MongoDriver* instance (const char* sHost = "mongodb://192.168.1.106/");
    static void destrory();

    mongoc_collection_t* collection( const char* sCollName );
    bool insert( const char* sCollName,  bson_t* pDoc );
private:
    std::unordered_map<std::string, mongoc_collection_t*>	m_Colls;
    mongoc_client_t*		m_pClt;
    static MongoDriver*	m_pInstance;
    std::string m_strHost;
    LogFile m_stLog;



};
#endif
