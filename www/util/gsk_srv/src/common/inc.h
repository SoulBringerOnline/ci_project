/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


#ifndef __YS_INCLUDE_H__
#define __YS_INCLUDE_H__

#include <stdint.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <float.h>
#include <limits.h>
#include <time.h>
#include <ctype.h>
#include <assert.h>
#include <sys/types.h>
#include <sys/file.h>
#include <sys/resource.h>
#include <sys/socket.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <sys/msg.h>
#include <sys/stat.h>
#include <sys/time.h>

#include <stdexcept>
#include <execinfo.h>
#include <errno.h>
#include <cerrno>

#include <netinet/in.h>
#include <arpa/inet.h>
#include <signal.h>

#include <math.h>

#include <string.h>
#include <string>
#include <fstream>
#include <iostream>
#include <sstream>

#include <vector>
#include <list>
#include <unordered_map>
#include <map>
#include <unordered_set>
#include <set>


//#include <snappy.h>


#include "Common.h"
#include "LavaHttp.h"
#include "LavaMD5.h"
#include "MyHash.h"

#include "cmd_def.h"
#include "err_def.h"
#include "cfg_def.h"
#include "attr_def.h"

#include "data_def.h"
#include "data_def_ex.h"

#include "data_info.h"


#include "mongo_driver.h"

#include "hiredis.h"
#include "redis_info.h"


using namespace std;

#ifdef  __cplusplus
extern "C" {
#endif

#include "oi_common.h"
#include "oi_cfg.h"
#include "oi_shm.h"
#include "oi_log.h"
#include "oi_file.h"
#include "oi_str2.h"
#include "oi_misc.h"
#include "oi_str.h"
#include "oi_net.h" 
#include "oi_mysql.h" 
#include "oi_timer.h" 
#include "oi_crypt.h"
#include "oi_tlv.h"

#include "crc.h"

#include "multi_file_processing.h"
#include "srv_framework.h"

#include "def.h"
#include "srv_log.h"
#include "macro.h"

#include <bcon.h>
#include <mongoc.h>


#ifdef  __cplusplus
}
#endif



#endif
