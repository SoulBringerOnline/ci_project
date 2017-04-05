/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * 
 * ======================================================================*/


#ifndef __YS_CONFIG_H__
#define __YS_CONFIG_H__

#include "def.h"
#include "inc.h"

class CConfig
{
public:
    
    static CConfig* Instance( CData<ConfigInfo> *p );
    int Get( int iKey , int iDefault = 0 );

    string Get( int iKey , string strDefault = "" );
    
private:
    
    CConfig();
    CConfig(const CConfig&);
    CConfig& operator=(const CConfig&);
    
    ConfigInfo *m_pConfig;
    static CConfig *m_pInstance ;
    
    static CData<ConfigInfo> *m_pstConfigInfo;
};

#endif
