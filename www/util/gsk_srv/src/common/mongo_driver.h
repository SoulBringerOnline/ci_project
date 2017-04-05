/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
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
    
    bool remove( const char* sCollName,  bson_t* pDoc );
    bool insert( const char* sCollName,  bson_t* pDoc );
    bool update( const char* sCollName,  bson_t* pQuery , bson_t* pDoc , mongoc_update_flags_t iFlag = MONGOC_UPDATE_UPSERT );

    int64_t count( const char* sCollName,  bson_t* pQuery );
        mongoc_cursor_t* find( const char* sCollName, bson_t* pQuery , int iLimit = 0, bson_t* pFields = NULL , int iSkip = 0);

    void bulkInit( const char* sCollName );
    void bulkAddInsert( bson_t *doc );
    void bulkAddRemove( bson_t *query );
    void bulkAddUpdate( bson_t *query, bson_t *doc, bool upsert );
    void bulkAddReplaceOne( bson_t *query, bson_t *doc, bool upsert );
    bool bulkExecute();


private:
    std::unordered_map<std::string, mongoc_collection_t*>	m_Colls;
    mongoc_client_t*		m_pClt;
    static MongoDriver*	m_pInstance;
    LogFile m_stLog;
    mongoc_bulk_operation_t *m_bulk;
    std::string m_strHost;
};
#endif
