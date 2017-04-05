/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/


#ifndef __YS_GSK_LOGIC_COMMON_H__
#define __YS_GSK_LOGIC_COMMON_H__

#include "inc.h"
#include "def.h"
#include "util.h"

#include "cmd_info.h"

#include "data_init.h"
#include "data_init_ex.h"

#include "cfg.h"
#include "client_info.h"

#include "Common.h"
#include "Pack.h"
#include "dlb_api.h"

#include "pb_gsk_report.pb.h"
#include "pb_gsk.pb.h"
#include "pb_gsk_req.pb.h"

#include "MyDB.h"

#define SRV_NAME_TCP_FROM_II		1
#define SRV_NAME_UDP                2

typedef struct
{
    char cTmp;
} CltInfo;

typedef struct
{
    char szConfigFilePath[256];
    
    int iSrvID;
    char szLocalIP[16];
    
    int32_t aiTcpPort[3];
    SrvFrameworkAddrDefine astTcpAddr[3];
    SrvFrameworkAddrDefine astUdpAddr[3];
    int32_t iMaxSocketNum;
    
    int iMaxSecKeepClt;
    int iTimeOutUnit ;

    int iTrace;
    int iTestEnv;
    
    char szMQIP[16];
    int  iMQPort;
} CONFIG;

typedef struct
{
    struct sockaddr_in stRspSrvAddr;
    uint16_t wCmd;
    uint32_t dwSeq;
    uint32_t dwSessionSeq;
    
    uint32_t dwUin ;
    uint16_t wClt;

    int iArg1;
    int iArg2;
    int iArg3;
    int iArg4;
    int iArg5;
    int iArg6;
    int iArg7;
    int iArg8;
    int iArg9;
    int iArg10;
    GSKPkgHead stRecvHead;
    
    volatile uint8_t cStatus;
} SessionCached;

GSK_COMMON_DEFINE

static char sErrMsg[1024];
static char szSendBuf[MAX_BUFF_LEN] = {0};
struct sockaddr_in g_stUDPAddr;

static pb_clt_t_user g_l_pb_clt_t_user;
static pb_user_t_data g_l_pb_user_t_data;
static pb_user_t_data g_l_pb_user_t_data_bak;
static pb_report_t_report g_l_pb_report_t_report;
static pb_req_t_req g_l_pb_req_t_req;

static CClientInfo *g_pCltInfo = NULL;// new CClientInfo();
static void *g_pZMQPublisher = NULL;
static void *g_pZMQContext = NULL;
static CConfig *g_pCfg = NULL;// CConfig::Instance()  ;

static unordered_map< int , string >    g_mapCmdInfo;
static string g_strCmdName = "" ;
// --[[用户数据
static bool g_bSaveUserData;
static int  g_iUserInfo ;

#endif
