/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/
#ifndef __YS_GSK_UTIL_REDIS_H__
#define __YS_GSK_UTIL_REDIS_H__

#include "inc.h"

/*======================================================================
 *  REDIS相关
 ======================================================================*/
typedef struct
{
    char sServerIP[16];
    uint16_t nServerPort;
} SrvAddr;

static unordered_map< int , SrvAddr > g_mapRedis;
static string lua_world_born;
static string lua_fight;
static string lua_fight_report;
static string lua_pvp_rank;
static string lua_user_info;
static redisContext *g_pRedisContext;
static CRedisInfo *g_pRedisInfo = NULL;

//REDIS
inline void V_LoadLua( string &strScript , string strFileName )
{
    strScript = "";
    strScript = Load2Str( strFileName.c_str() );
    if( strScript.length() <= 0 )
    {
        LOG_PRINT("can not find %s " , strFileName.c_str());
    }
}

void V_InitRedis()
{
    g_pRedisInfo = new CRedisInfo(&(g_stLog.stSvrLog));
    if( g_pRedisInfo == NULL )
    {
        LOG_PRINT("REDISINFO INIT FAILED!");
        exit(-1);
    }
    
    g_mapRedis.clear();
    for (int32_t i = 0; i < 1000; i++)
    {
        char szHostName[240] = {0};
        char szPortName[240] = {0};
        SrvAddr  stCacheAddr ;
        memset(&stCacheAddr , 0 , sizeof(SrvAddr) );
        snprintf(szHostName, sizeof(szHostName), "REDIS_HOST_%d", i);
        snprintf(szPortName, sizeof(szPortName), "REDIS_PORT_%d", i);
        OI_Cfg_GetConfig("../conf/gsk.conf",
                         szHostName,CFG_STRING, &(stCacheAddr.sServerIP), "192.168.1.106", sizeof(stCacheAddr.sServerIP),
                         szPortName,CFG_INT, &(stCacheAddr.nServerPort), 0,
                         NULL);
        if( stCacheAddr.nServerPort == 0 )
        {
            break;
        }
        g_mapRedis.insert( make_pair( i, stCacheAddr ) );
        LOG_PRINT("[REDIS] %d %s:%d", i, stCacheAddr.sServerIP , stCacheAddr.nServerPort);
    }
    
//    LOAD_LUA_SCRIPT(world_born);
//    LOAD_LUA_SCRIPT(fight);
//    LOAD_LUA_SCRIPT(fight_report);
//    LOAD_LUA_SCRIPT(pvp_rank);
//    LOAD_LUA_SCRIPT(user_info);
}


redisReply* V_HandleRedis( const string &strLua , vector< string > &vecKeys, vector< string > &vecArgs, int iRedisId, int iRspType = 0  )
{
    string strLog;
    strLog.append( strLua );
    if( strLua.length() == 0 )
    {
        strLog.append( " LUA异常" );
        LOG_BUG_RET_NULL(  );
    }
    
    auto mapIter = g_mapRedis.find( iRedisId );
    if( mapIter == g_mapRedis.end() )
    {
        strLog.append( " RedisID(" );
        strLog.append( Common::tostr( iRedisId ) );
        strLog.append( ") 未注册!" );
        LOG_BUG_RET_NULL(  );
    }
    
    g_pRedisContext = g_pRedisInfo->Get( mapIter->second.sServerIP , mapIter->second.nServerPort );
    if( g_pRedisContext == NULL)
    {
        strLog.append( " REDIS_ADDR(");
        strLog.append( mapIter->second.sServerIP );
        strLog.append( ":");
        strLog.append( Common::tostr( mapIter->second.nServerPort ) );
        strLog.append( ")访问失败！");
        LOG_BUG_RET_NULL(  );
    }
    
    int iKeysNum = vecKeys.size();
    int iArgsNum = vecArgs.size();
    
    if( ( iKeysNum + iArgsNum ) > 99 )
    {
        strLog.append( " KEYS+ARGS TOO LONG" );
        LOG_BUG_RET_NULL(  );
    }
    
    const char *ppArgv[100];
    size_t alArgvLen[100];
    
    ppArgv[0] = "EVAL";
    alArgvLen[0] = 4;
    
    ppArgv[1] = strLua.c_str();
    alArgvLen[1] = strLua.length();
    
    string strKeyNum = Common::tostr( iKeysNum );
    ppArgv[2] = strKeyNum.c_str();
    alArgvLen[2] = ( iKeysNum < 10 ) ? 1 : 2;
    
    int iNum = 3;
    for (int i = 0; i < iKeysNum; i++)
    {
        ppArgv[iNum] = vecKeys[i].c_str();
        alArgvLen[iNum] = vecKeys[i].size();
        iNum++;
    }
    
    for (int i = 0; i < iArgsNum; i++)
    {
        ppArgv[iNum] = vecArgs[i].c_str();
        alArgvLen[iNum] = vecArgs[i].size();
        iNum++;
    }
    
    redisReply* pRedisReply = (redisReply*) redisCommandArgv(g_pRedisContext, iNum , ppArgv , alArgvLen );
    if( pRedisReply == NULL )
    {
        strLog.append( " pRedisReply NULL" );
        LOG_BUG_RET_NULL(  );
    }
    
    if( iRspType )
    {
        if( pRedisReply->type  != iRspType )
        {
            strLog.append( " RedisReply返回类型不对!" );
            strLog.append( Common::tostr( pRedisReply->type ) );
            if( pRedisReply->str && pRedisReply->len )
            {
                strLog.append( pRedisReply->str );
            }
            
            freeReplyObject(pRedisReply);
            LOG_BUG_RET_NULL(  );
        }
    }
    
    return pRedisReply;
}



#endif
