/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author   :   yongshengzhao@vip.qq.com
 * Date     :   2014-08-15
 *
 * ======================================================================*/
#include "inc.h"
#include "def.h"
#include "pb_gsk_report.pb.h"

#define SRV_NAME_UDP_LOG   1

typedef struct
{
    char szConfigFilePath[256];

    char szLocalIP[16];
    SrvFrameworkAddrDefine astUdpAddr[2];

    int32_t iMaxSocketNum;
    int32_t iMaxSecKeepClt;

    int32_t iMaxSessionTime;
} CONFIG;


typedef struct
{
    char cTmp;
} CltInfo;

static pb_report_t_report g_l_pb_report_t_report;

GSK_COMMON_DEFINE

/*======================================================================
 *  REDIS
 ======================================================================*/
static CRedisInfo *g_pRedis;
static char g_szRedisIp[16];
static int g_iRedisPort;
static redisContext *g_pRedisContext;
void V_InitRedis()
{
    //REDIS
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
        "REDIS_HOST", CFG_STRING, g_szRedisIp, "192.168.165.241", sizeof(g_szRedisIp),
        "REDIS_PORT", CFG_INT, &(g_iRedisPort), 6380,
        NULL);
    g_pRedis = new CRedisInfo( NULL );
    if( g_pRedis == NULL )
    {
        LOG_ANY("[REDIS] Init failed! ");
    }
    LOG_ANY("[REDIS] %s:%d" , g_szRedisIp , g_iRedisPort);
}


/*======================================================================
 *  MONGO
 ======================================================================*/

static char g_szMongoIp[16];
static int g_iMongoPort;
static MongoDriver *g_pMongo = NULL;

void V_InitMongo()
{
    //MONGO
    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
        "MONGO_HOST", CFG_STRING, g_szMongoIp, "192.168.165.240", sizeof(g_szMongoIp),
        "MONGO_PORT", CFG_INT, &(g_iMongoPort), 27017,
        NULL);

    char szBind[64];
    memset(szBind, 0, sizeof(szBind));
    snprintf(szBind, sizeof(szBind) - 1, "mongodb://%s:%d", g_szMongoIp, g_iMongoPort);
    g_pMongo = MongoDriver::instance( szBind );
    if( g_pMongo == NULL )
    {
        LOG_ANY("[MONGO] Init failed! %s" , szBind);
    }
    LOG_ANY("[MONGO] %s" , szBind);
}
/* ==========================================================
 * network service logic processing
 *
 * ==========================================================
 */
int HandleAccept(SCTX *pstSctx, void *pCltInfo, int iListenAddrName){ return 0; }
int HandlePkgHead(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iBytesRecved, int *piPkgLen){ return 0; }
int HandlePkg(SCTX *pstSctx, void *pCltInfo, int iAddrName, void *pPkg, int iPkgLen){ return 0; }
int HandleConnect(SCTX * pstSctx, void *pCltInfo, int iAddrName){ return 0; }
int HandleCloseConn(SCTX *pstSctx, void *pCltInfo, int iAddrName, char *sErrInfo){ return 0; }
int HandleUdpPkg(SCTX * pstSctx, void *pCltInfo, int iUdpName, void *pPkg, int iPkgLen)
{
    char* pCur = (char*)pPkg;
    if (pCur[0] != STX) { LOG_BUG_RET(-1); }
    if (pCur[iPkgLen-1] != ETX) { LOG_BUG_RET(-2); }

    switch (iUdpName)
    {
        case SRV_NAME_UDP_LOG:
            {
                g_tCurTime = time(NULL) + 28800;

                g_l_pb_report_t_report.Clear();
                if( g_l_pb_report_t_report.ParseFromArray( (uint8_t *)(&pCur[1]), iPkgLen-2 ) )
                {
                    // --[[ 数据预处理
                    time_t tCurTime = g_l_pb_report_t_report.f_time();
                    if(tCurTime == 0)
                    {
                        tCurTime = time(NULL) + 28800;
                    }

                    int iCurDay = tCurTime / 86400 ;
                    int iCurHour = tCurTime / 3600 ;
                    int iCurMin = tCurTime / 60 ;

                    LOG_ANY("[REPORT] cmd:%d uin:%u phone:%s" , 
                            g_l_pb_report_t_report.f_i_cmd() ,
                            g_l_pb_report_t_report.f_info().f_uin(),
                            g_l_pb_report_t_report.f_info().f_phone().c_str());
                    // 数据预处理 ]]--


                    // --[[ 日志入库
                    do{
                        bson_t bo_report;
                        bson_init(&bo_report);
                        bson_append_int32(&bo_report, "f_cmd", -1,  g_l_pb_report_t_report.f_i_cmd() );
                        bson_append_utf8(&bo_report, "f_cmd_info", -1,  g_l_pb_report_t_report.f_s_cmd().c_str() , -1 );
                        bson_append_int32(&bo_report, "f_time", -1, tCurTime );
                        bson_append_int64(&bo_report, "f_uin", -1,  g_l_pb_report_t_report.f_info().f_uin() );
                        bson_append_utf8(&bo_report, "f_phone", -1,  g_l_pb_report_t_report.f_info().f_phone().c_str() , -1 );
                        bson_append_int32(&bo_report, "f_dye", -1,  g_l_pb_report_t_report.f_info().f_dye() );
                        bson_append_utf8(&bo_report, "f_ip", -1,  g_l_pb_report_t_report.f_info().f_ip().c_str() , -1);
                        bson_append_utf8(&bo_report, "f_name", -1,  g_l_pb_report_t_report.f_info().f_name().c_str(),-1);
                        bson_append_utf8(&bo_report, "f_log", -1,  g_l_pb_report_t_report.f_log().c_str(),-1);
                        bson_append_int32(&bo_report, "f_msg_type", -1, g_l_pb_report_t_report.f_msg_type() );

                        bson_append_utf8(&bo_report, "f_phone_os", -1,  g_l_pb_report_t_report.f_client().f_os().c_str(),-1);
                        bson_append_utf8(&bo_report, "f_phone_sp", -1,  g_l_pb_report_t_report.f_client().f_sp().c_str(),-1);
                        bson_append_utf8(&bo_report, "f_phone_network", -1,  g_l_pb_report_t_report.f_client().f_network().c_str(),-1);
                        bson_append_utf8(&bo_report, "f_phone_info", -1,  g_l_pb_report_t_report.f_client().f_phone_info().c_str(),-1);
                        bson_append_int32(&bo_report, "f_client_version", -1,  g_l_pb_report_t_report.f_client().f_version() );
                        bson_append_int32(&bo_report, "f_client_id", -1,  g_l_pb_report_t_report.f_client().f_client_id() );
                        bson_append_int32(&bo_report, "f_client_channel", -1,  g_l_pb_report_t_report.f_client().f_channel_id() );

                        string strReport ;
                        if( g_l_pb_report_t_report.SerializeToString(&strReport) && strReport.size())
                        {
                            bson_append_binary(&bo_report, "f_pb", -1, BSON_SUBTYPE_BINARY, (uint8_t *)strReport.data(), strReport.size());
                        }
                        g_pMongo->insert( "log" , &bo_report );
                    }while(0);
                    // 日志入库 ]]--

                    //STAT
                    if( g_l_pb_report_t_report.f_i_cmd() != 0xEEEE && g_l_pb_report_t_report.f_client().f_channel_id() > 0 && g_l_pb_report_t_report.f_client().f_client_id() > 0 )
                    {
                        g_pRedisContext = g_pRedis->Get( g_szRedisIp, g_iRedisPort );
                        if( g_pRedisContext )
                        {
                        string strUin = Common::tostr( g_l_pb_report_t_report.f_info().f_uin() );
                        string strChannel = Common::tostr( g_l_pb_report_t_report.f_client().f_channel_id() );
                        
                        string strUserKey = strUin;
                        strUserKey.append( "#" );
                        strUserKey.append( strChannel ) ;

                        string strUsers = "V_USERS";
                        bool bNewUser = false;
                        redisReply* pRedisReply = (redisReply*)redisCommand(g_pRedisContext, "SISMEMBER %b %b" , strUsers.data(), strUsers.size() , strUin.data(), strUin.size() );
                        if( pRedisReply )
                        {
                            if( pRedisReply->type == REDIS_REPLY_INTEGER && pRedisReply->integer == 0 )
                            {
                                bNewUser = true;
                            }
                            freeReplyObject(pRedisReply);
                        }
                        redisCommand(g_pRedisContext, "SADD %b %b" , strUsers.data(), strUsers.size() , strUin.data(), strUin.size() );
                        
                        string strChannelUsers = "V_CHANNEL_USERS#";
                        strChannelUsers.append( strChannel ) ;
                        bool bNewChannelUser = false;
                        pRedisReply = (redisReply*)redisCommand(g_pRedisContext, "SISMEMBER %b %b" , strChannelUsers.data(), strChannelUsers.size() , strUin.data(), strUin.size() );
                        if( pRedisReply )
                        {
                            if( pRedisReply->type == REDIS_REPLY_INTEGER && pRedisReply->integer == 0 )
                            {
                                bNewChannelUser = true;
                            }
                            freeReplyObject(pRedisReply);
                        }
                        redisCommand(g_pRedisContext, "SADD %b %b" , strChannelUsers.data(), strChannelUsers.size() , strUin.data(), strUin.size() );

                        // --[[ 在线统计
                        if( g_l_pb_report_t_report.f_info().f_name().size() )
                        {
                            string strKey = "V_OL#" ;
                            strKey.append( strUserKey );
                            
                            string strValue = g_l_pb_report_t_report.f_info().f_name();
                            if( g_l_pb_report_t_report.f_info().f_phone().size() )
                            {
                                strValue.append( " 手机:");
                                strValue.append( g_l_pb_report_t_report.f_info().f_phone() ); 
                            }
                            if( g_l_pb_report_t_report.f_info().f_ip().size() )
                            {
                                strValue.append( " IP:");
                                strValue.append( g_l_pb_report_t_report.f_info().f_ip() ); 
                            }
                            if( g_l_pb_report_t_report.f_client().f_os().size() )
                            {
                                strValue.append( " OS:");
                                strValue.append( g_l_pb_report_t_report.f_client().f_os() ); 
                            }
                            if( g_l_pb_report_t_report.f_client().f_sp().size() )
                            {
                                strValue.append( " SP:");
                                strValue.append( g_l_pb_report_t_report.f_client().f_sp() ); 
                            }
                            if( g_l_pb_report_t_report.f_client().f_network().size() )
                            {
                                strValue.append( " NET:");
                                strValue.append( g_l_pb_report_t_report.f_client().f_network() ); 
                            }
                            if( g_l_pb_report_t_report.f_client().f_phone_info().size() )
                            {
                                strValue.append( " 设备信息:");
                                strValue.append( g_l_pb_report_t_report.f_client().f_phone_info() ); 
                            }
                            if( g_l_pb_report_t_report.f_client().f_version() > 0 )
                            {
                                strValue.append( " 客户端版本:");
                                strValue.append( Common::tostr(  g_l_pb_report_t_report.f_client().f_version() )) ; 
                            }
                            redisCommand(g_pRedisContext, "SETEX %b 300 %b" , strKey.data(), strKey.size() , strValue.data(), strValue.size() );

                            strKey = "V_CHANNEL_OL_STAT#" ;
                            strKey.append( strChannel ) ;
                            strKey.append( "#" );
                            strKey.append( Common::tostr( iCurMin ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strKey.data(), strKey.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 3600" , strKey.data(), strKey.size() );

                            strKey = "V_OL_STAT#" ;
                            strKey.append( Common::tostr( iCurMin ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strKey.data(), strKey.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 3600" , strKey.data(), strKey.size() );
                        }
                        // 在线统计 ]]--

                        // --[[ DNU NHU
                        if( bNewChannelUser == true )
                        {
                            //CHANNEL DNU
                            string strChannelDNU = "V_CHANNEL_DNU#";
                            strChannelDNU.append( strChannel );
                            strChannelDNU.append( "#" );
                            strChannelDNU.append( Common::tostr( iCurDay ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strChannelDNU.data(), strChannelDNU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strChannelDNU.data(), strChannelDNU.size() );
                            
                            //CHANNEL HNU
                            string strChannelHNU = "V_CHANNEL_HNU#";
                            strChannelHNU.append( strChannel );
                            strChannelHNU.append( "#" );
                            strChannelHNU.append( Common::tostr( iCurHour ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strChannelHNU.data(), strChannelHNU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strChannelHNU.data(), strChannelHNU.size() );
                        }
                        if( bNewUser == true )
                        {
                            //CHANNEL DNU
                            string strDNU = "V_DNU#";
                            strDNU.append( Common::tostr( iCurDay ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strDNU.data(), strDNU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strDNU.data(), strDNU.size() );
                            
                            //CHANNEL HNU
                            string strHNU = "V_HNU#";
                            strHNU.append( Common::tostr( iCurHour ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strHNU.data(), strHNU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strHNU.data(), strHNU.size() );
                        }
                        // DNU NHU ]]--

                        // --[[ DAU HAU
                        do{
                            string strChannelDAU = "V_CHANNEL_DAU#";
                            strChannelDAU.append( strChannel );
                            strChannelDAU.append( "#" );
                            strChannelDAU.append( Common::tostr( iCurDay ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strChannelDAU.data(), strChannelDAU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strChannelDAU.data(), strChannelDAU.size() );
                            
                            string strChannelHAU = "V_CHANNEL_HAU#";
                            strChannelHAU.append( strChannel );
                            strChannelHAU.append( "#" );
                            strChannelHAU.append( Common::tostr( iCurHour ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strChannelHAU.data(), strChannelHAU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strChannelHAU.data(), strChannelHAU.size() );

                            string strDAU = "V_DAU#";
                            strDAU.append( Common::tostr( iCurDay ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strDAU.data(), strDAU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strDAU.data(), strDAU.size() );
                            
                            string strHAU = "V_HAU#";
                            strHAU.append( Common::tostr( iCurHour ) );
                            redisCommand(g_pRedisContext, "SADD %b %b" , strHAU.data(), strHAU.size() , strUin.data(), strUin.size() );
                            redisCommand(g_pRedisContext, "EXPIRE %b 8640000" , strHAU.data(), strHAU.size() );
                        }while(0);
                        // DAU HAU ]]--
                    }
                    }
                }
            }
            break;

        default:
            break;
    }

    return 0;
}

int32_t HandleLoop(void)
{
    int32_t iSig = g_iSignal;

    g_iSignal = 0;
    switch (iSig)
    {
        case SIGTERM:
            LOG_INFO("catch SIGTERM. server stop");
            exit(0);
            break;
        case SIGUSR1:
            break;
        case SIGUSR2:
            break;
    }

    return 0;
}


/* ==========================================================
 * necessary initialization
 *
 * ==========================================================
 */

int32_t InitSrv()
{
    static SRV_CALLBACK stCallback;
    stCallback.HandleLoop = HandleLoop;
    stCallback.HandlePkgHead = HandlePkgHead;
    stCallback.HandlePkg = HandlePkg;
    stCallback.HandleUdpPkg = HandleUdpPkg;
    stCallback.HandleAccept = HandleAccept;
    stCallback.HandleConnect = HandleConnect;
    stCallback.HandleCloseConn = HandleCloseConn;

    g_stConfig.astUdpAddr[0].iAddrName = SRV_NAME_UDP_LOG;
    g_stConfig.astUdpAddr[0].nServerPort = UDP_SRV_PORT_LOG ;
    strcpy(g_stConfig.astUdpAddr[0].sServerIP, g_stConfig.szLocalIP);

    if (SrvFrameworkInit(NULL, 0, sizeof(CltInfo),
                &(g_stLog.stSvrLog), g_stLog.iLogLevel,
                0, NULL, g_stConfig.iMaxSocketNum, g_stConfig.iMaxSecKeepClt,
                1, &g_stConfig.astUdpAddr[0],
                0, NULL,
                &stCallback))
    {
        LOG_FATAL("srv_framework init failed!");
        return -1;
    }

    return 0;
}



void SigHandler(int32_t iSig)
{
    g_iSignal = iSig;
}

void InitSigHandler(void)
{
    struct sigaction act;

    memset(&act, 0, sizeof(act));
    act.sa_handler = SigHandler;
    sigemptyset(&act.sa_mask);
    act.sa_flags = SA_RESTART;

    sigaction(SIGTERM, &act, NULL);
    sigaction(SIGUSR1, &act, NULL);
    sigaction(SIGUSR2, &act, NULL);
    sigaction(SIGALRM, &act, NULL);
}


int32_t Init(int32_t argc, char *argv[])
{
    if(argc != 3)
    {
        printf("Usage: %s ServerID ConfigFile\n", argv[0]);
        return -1;
    }

    GetAbsPath(g_stConfig.szConfigFilePath, argv[2], sizeof(g_stConfig.szConfigFilePath) );

    OI_Cfg_GetConfig(g_stConfig.szConfigFilePath,
            "MAX_SOCKET_NUM", CFG_INT, &(g_stConfig.iMaxSocketNum), 20,
            "MAX_SEC_KEEP_CLT", CFG_INT, &(g_stConfig.iMaxSecKeepClt), 180,
            "LOCAL_IP", CFG_STRING, g_stConfig.szLocalIP, "192.168.128.128", sizeof(g_stConfig.szLocalIP),

            "MAX_SESSION_TIME", CFG_INT, &(g_stConfig.iMaxSessionTime), 360,

            NULL
            );
    DaemonInit();

    GSK_INIT_LOG(gsk_log);


    V_InitMongo();
    V_InitRedis();

    if(InitSrv() < 0)
    {
        return -1;
    }
    InitSigHandler();
    srand(time(NULL));
    return 0;
}



int main(int argc, char* argv[])
{

    if(Init(argc, argv) < 0)
    {
        LOG_FATAL("Init failed!");
        exit(-1);
    }

    LOG_ANY("-------------------------");
    LOG_ANY("SRV START!");
    LOG_ANY("-------------------------");

    SrvFrameworkLoop();

    return 0;
}

