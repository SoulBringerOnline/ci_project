#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <arpa/inet.h>
#include <signal.h>
#include <fcntl.h>
#include <time.h>
#include <stdarg.h>
#include <sys/param.h>
#include <sys/stat.h>
#include <zmq.h>
#include <iostream>
#include <sstream>

#include "oi_log.h"
#include "mongo_driver.h"

#include "gsk_report.pb.h"

using namespace std;

static char g_szMQIp[16];
static int g_iMQPort;

static char g_szMongoIp[16];
static int g_iMongoPort;
static MongoDriver *g_pMongo = NULL;

static pb_report_t_report g_l_pb_report_t_report;

int g_iSignal = 0 ;

#define LOG(fmt, args...) do { OI_Log(&(stLog), 2, fmt, ## args); } while (0)

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

void DaemonInit()
{
    int fd;
    signal(SIGALRM, SIG_IGN);
    signal(SIGINT, SIG_IGN);
    signal(SIGHUP, SIG_IGN);
    signal(SIGQUIT, SIG_IGN);
    signal(SIGPIPE, SIG_IGN);
    signal(SIGTTOU, SIG_IGN);
    signal(SIGTTIN, SIG_IGN);
    signal(SIGCHLD, SIG_IGN);
    signal(SIGTERM, SIG_IGN);
    if(fork())
        exit(0);
    if(setsid() == -1)
        exit(1);
    for(fd = 3; fd < NOFILE; fd++)
        close(fd);
    umask(0);
    return;
}

int main (int argc, char *argv[])
{
    if ((argc < 3) )
    {
        printf("Usage: %s mq_host:port mongo_host:port \n", argv[0]);
        return -1;
    }

    DaemonInit();

    InitSigHandler();

    LogFile stLog;
    OI_InitLogFile(&(stLog), "report_", 3, 10, 10000000);

    char *sHost, *sPort;
    char szBind[128] = {0};

    //MQ
    sHost = argv[1];
    if ((sPort = strchr(sHost, ':'))) { *sPort++ = '\0'; } else { return -1; }
    strcpy(g_szMQIp, sHost);
    g_iMQPort = (int)atoi(sPort);
    void *context = zmq_ctx_new ();
    void *subscriber = zmq_socket (context, ZMQ_SUB);
    memset(szBind, 0, sizeof(szBind));
    snprintf(szBind, sizeof(szBind) - 1, "tcp://%s:%d", g_szMQIp, g_iMQPort);
    zmq_connect (subscriber, szBind);
    zmq_setsockopt (subscriber, ZMQ_SUBSCRIBE, "", 0);
    LOG("[ZMQ] %s" , szBind);

    //MONGO
    sHost = argv[2];
    if ((sPort = strchr(sHost, ':'))) { *sPort++ = '\0'; } else { return -1; }
    strcpy(g_szMongoIp, sHost);
    g_iMongoPort = (int)atoi(sPort);
    memset(szBind, 0, sizeof(szBind));
    snprintf(szBind, sizeof(szBind) - 1, "%s:%d", g_szMongoIp, g_iMongoPort);
    g_pMongo = MongoDriver::instance( szBind );
    if( g_pMongo == NULL )
    {
        LOG("[MONGO] Init failed! %s" , szBind);
    }
    LOG("[MONGO] %s" , szBind);



    //SUB
    while (true)
    {
        zmq_msg_t msg;
        zmq_msg_init(&msg);

        if( zmq_msg_recv(&msg, subscriber, 0) >= 0 )
        {
            g_l_pb_report_t_report.Clear();
            if( g_l_pb_report_t_report.ParseFromArray( zmq_msg_data(&msg), zmq_msg_size(&msg) ) )
            {
                time_t tCurTime = g_l_pb_report_t_report.f_time();
                if(tCurTime == 0)
                {
                    tCurTime = time(NULL) + 28800;
                }
                LOG("[REPORT] cmd:%d uin:%lu phone:%s" , 
                        g_l_pb_report_t_report.f_i_cmd() ,
                        g_l_pb_report_t_report.f_info().f_uin(),
                        g_l_pb_report_t_report.f_info().f_phone().c_str());

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
                /*
                   for (int i = 0 ; i < g_l_pb_report_t_report.f_log_size(); i++)
                   {
                   char sItemKey[16] = {0};
                   sprintf(sItemKey,"f_log_%d", i );
                   bson_t bo_item;
                   bson_init(&bo_item);
                   bson_append_utf8(&bo_item, "f_info", -1,  g_l_pb_report_t_report.f_log(i).f_info().c_str() ,-1);
                   bson_append_document(&bo_report, sItemKey, -1, &bo_item );
                   bson_destroy(&bo_item);
                   }
                   */
                string strReport ;
                if( g_l_pb_report_t_report.SerializeToString(&strReport) && strReport.size())
                {
                    bson_append_binary(&bo_report, "f_pb", -1, BSON_SUBTYPE_BINARY, (uint8_t *)strReport.data(), strReport.size());
                }

                g_pMongo->insert( "log" , &bo_report );
                zmq_msg_close(&msg);
            }
        }

        //handel signal
        {
            int32_t iSig = g_iSignal;
            g_iSignal = 0;
            switch (iSig)
            {
                case SIGTERM:
                    zmq_close (subscriber);
                    zmq_ctx_destroy (context);
                    exit(0);
                    break;
                case SIGUSR1:
                    {
                        break;
                    }
                case SIGUSR2:
                    {
                        break;
                    }
            }
        }
    }
    zmq_close (subscriber);
    zmq_ctx_destroy (context);

    return 0;
}

