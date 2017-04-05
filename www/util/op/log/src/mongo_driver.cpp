#include"mongo_driver.h"

#ifndef LOG_MONGO
#define LOG_MONGO(fmt, args...) do{ OI_Log(&(m_stLog), 2, "[MONGO] %s:%d(%s): " fmt, __FILE__, __LINE__, __FUNCTION__ , ## args); } while(0)
#endif

MongoDriver* MongoDriver::m_pInstance = NULL;
MongoDriver* MongoDriver::instance(const char* sHost)
{
    if(!m_pInstance){
        m_pInstance = new MongoDriver(sHost);
    }
    return m_pInstance;
}

void MongoDriver::destrory()
{
    if (m_pInstance)
    {
        delete m_pInstance;
        m_pInstance = NULL;
    }
    
}

MongoDriver::MongoDriver(const char* sHost)
{
    m_strHost = sHost;
    
    OI_InitLogFile(&(m_stLog), "../log/mongo_", 3, 10, 10000000);
    
    mongoc_init();
    m_pClt = mongoc_client_new (m_strHost.c_str());
    LOG_MONGO( "[CONNECT] %s" , m_strHost.c_str() );
    if(!m_pClt)
    {
        LOG_MONGO( "[CONNECT] Failed to parse URI(%s)" , m_strHost.c_str() );
    }
    
    m_bulk = NULL;
}

MongoDriver::~MongoDriver()
{
    if ( m_bulk ){
        mongoc_bulk_operation_destroy(m_bulk);
    }
    destrory();
}

mongoc_collection_t* MongoDriver::collection( const char* sCollName )
{
    if (!sCollName)
    {
        return NULL;
    }
    if(!m_pClt)
    {
        m_pClt = mongoc_client_new (m_strHost.c_str());
        LOG_MONGO( "[CONNECT] %s" , m_strHost.c_str() );
    }
    mongoc_collection_t *pColl = NULL;
    if(m_pClt)
    {
        if(!m_Colls[sCollName])
        {
            pColl = mongoc_client_get_collection (m_pClt, "gsk", sCollName);
            if (pColl)
            {
                m_Colls[sCollName] = pColl;
            }
        }
        else{
            pColl = m_Colls[sCollName];
        }
    }
    return pColl;
}


bool MongoDriver::remove( const char* sCollName,  bson_t* pDoc )
{
#if 1
    char *str = bson_as_json (pDoc, NULL);
    LOG_MONGO( "[REMOVE (%s)] %s" , sCollName, str );
    bson_free (str);
#endif
    
    bson_error_t stError;
    bool bResult = false;
    if (!(bResult = mongoc_collection_remove (collection(sCollName), MONGOC_REMOVE_NONE , pDoc, NULL, &stError)))
    {
        LOG_MONGO( "[REMOVE (%s)] FAILED(%s)!" , sCollName, stError.message );
    }
    
    if( pDoc ){ bson_destroy (pDoc); }
    return bResult;
}

bool MongoDriver::insert( const char* sCollName,  bson_t* pDoc )
{
#if 1
    char *str = bson_as_json (pDoc, NULL);
    LOG_MONGO( "[INSERT (%s)] %s" , sCollName, str );
    bson_free (str);
#endif
    
    bson_error_t stError;
    bool bResult = false;
    if (!(bResult = mongoc_collection_insert (collection(sCollName), MONGOC_INSERT_NONE, pDoc, NULL, &stError)))
    {
        LOG_MONGO( "[INSERT (%s)] FAILED(%s)!" , sCollName,  stError.message );
    }
    
    if( pDoc ){ bson_destroy (pDoc); }
    return bResult;
}

bool MongoDriver::update( const char* sCollName,  bson_t* pQuery , bson_t* pDoc , mongoc_update_flags_t iFlag  )
{
#if 1
    char *str1 = bson_as_json (pQuery, NULL);
    char *str2 = bson_as_json (pDoc, NULL);
    LOG_MONGO( "[UPDATE (%s)] %s -- %s" , sCollName, str1, str2 );
    bson_free (str1);
    bson_free (str2);
#endif
    
    bson_error_t stError;
    bool bResult = false;
    if (!(bResult = mongoc_collection_update (collection(sCollName), iFlag, pQuery, pDoc, NULL, &stError)))
    {
        LOG_MONGO( "[UPDATE (%s)] FAILED(%s)!" , sCollName, stError.message );
    }
    
    if( pQuery ){ bson_destroy (pQuery); }
    if( pDoc ){ bson_destroy (pDoc); }
    return bResult;
}

mongoc_cursor_t* MongoDriver::find( const char* sCollName, bson_t* pQuery , int iLimit, bson_t* pFields, int iSkip)
{
    
#if 1
    char *str = bson_as_json (pQuery, NULL);
    LOG_MONGO( "[FIND (%s)] %s" , sCollName, str );
    bson_free (str);
#endif
    
    mongoc_cursor_t* pCursor = mongoc_collection_find (collection(sCollName), MONGOC_QUERY_NONE, iSkip, iLimit, 0, pQuery, pFields, NULL );
    bson_error_t stError;
    if (mongoc_cursor_error (pCursor, &stError)) {
        LOG_MONGO( "[FIND (%s)] FAILED(%s)!" , sCollName, stError.message );
    }
    if( pQuery ){ bson_destroy (pQuery); }
    return pCursor;
}



int64_t MongoDriver::count( const char* sCollName,  bson_t* pQuery )
{
#if 1
    char *str = bson_as_json (pQuery, NULL);
    LOG_MONGO( "[COUNT (%s)] %s" , sCollName, str );
    bson_free (str);
#endif
    
    bson_error_t stError;
    int64_t lCount = mongoc_collection_count (collection(sCollName), MONGOC_QUERY_NONE, pQuery, 0, 0, NULL, &stError);
    if (lCount < 0) {
        LOG_MONGO( "[COUNT (%s)] FAILED(%s)!" , sCollName, stError.message );
    }
    if( pQuery ){ bson_destroy (pQuery); }
    
    return lCount;
}

void MongoDriver::bulkInit( const char* sCollName ){
    if( m_bulk ){
        mongoc_bulk_operation_destroy( m_bulk );
    }
    m_bulk = mongoc_collection_create_bulk_operation( collection(sCollName), true, NULL );
    LOG_MONGO( "[BULKINIT (%s)]" , sCollName );
}

void MongoDriver::bulkAddInsert( bson_t *query ){
    if( m_bulk != NULL && query != NULL ){
        mongoc_bulk_operation_insert( m_bulk, query );
        char *str = bson_as_json( query, NULL );
        LOG_MONGO( "[BULK INSERT] %s" , str );
    }
}

void MongoDriver::bulkAddRemove( bson_t *query ){
    if( m_bulk!=NULL && query != NULL ){
        mongoc_bulk_operation_remove( m_bulk, query );
        char *str = bson_as_json( query, NULL );
        LOG_MONGO( "[BULK REMOVE] %s" , str );
    }
}
void MongoDriver::bulkAddUpdate( bson_t *query, bson_t *doc, bool upsert = true ){
    if( m_bulk!=NULL && query != NULL && doc != NULL ){
        mongoc_bulk_operation_update(m_bulk, query, doc, upsert);
        char *str = bson_as_json( query, NULL );
        char *str2 = bson_as_json( doc, NULL );
        LOG_MONGO( "[BULK UPDATE] query:%s, doc:%s" , str, str2 );
    }
}
void MongoDriver::bulkAddReplaceOne( bson_t *query, bson_t *doc, bool upsert = true ){
    if( m_bulk!=NULL && query != NULL && doc != NULL ){
        mongoc_bulk_operation_replace_one(m_bulk, query, doc, upsert);
        char *str = bson_as_json( query, NULL );
        char *str2 = bson_as_json( doc, NULL );
        LOG_MONGO( "[BULK REPLACE ONE] query:%s, doc:%s" , str, str2 );
    }
}
bool MongoDriver::bulkExecute(){
    
    if( m_bulk ){
        bson_error_t error;
        bool ret = mongoc_bulk_operation_execute ( m_bulk, NULL, &error );
        if (!ret) {
            LOG_MONGO ("Bulk Insert Error: %s", error.message);
        }
        mongoc_bulk_operation_destroy(m_bulk);
        m_bulk = NULL;
        return ret;
    }
    return false;
    
}



#undef LOG_MONGO
