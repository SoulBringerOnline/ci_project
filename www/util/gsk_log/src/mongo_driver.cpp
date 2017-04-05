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
    m_strHost = "mongodb://";
    m_strHost.append(sHost);

    OI_InitLogFile(&(m_stLog), "mongo_", 3, 10, 10000000);

    mongoc_init();
    m_pClt = mongoc_client_new (m_strHost.c_str());
    LOG_MONGO( "[CONNECT] %s" , m_strHost.c_str() );
    if(!m_pClt)
    {
        LOG_MONGO( "[CONNECT] Failed to parse URI(%s)" , m_strHost.c_str() );
    }
}

MongoDriver::~MongoDriver()
{
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
            pColl = mongoc_client_get_collection (m_pClt, "gsk_log", sCollName);
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


bool MongoDriver::insert( const char* sCollName,  bson_t* pDoc )
{
#if 1
    char *str = bson_as_json (pDoc, NULL);
    LOG_MONGO( "[INSERT (%s)] %s" , sCollName, str );
    bson_free (str);
#endif

    bson_error_t stError;
    bool bResult = false;
    mongoc_collection_t* pColl = collection(sCollName);
    if(pColl)
    {
        if (!(bResult = mongoc_collection_insert ( pColl, MONGOC_INSERT_NONE, pDoc, NULL, &stError)))
        {
            LOG_MONGO( "[INSERT (%s)] FAILED(%s)!" , sCollName,  stError.message );
        }

        if( pDoc ){ bson_destroy (pDoc); }
    }
    return bResult;
}

