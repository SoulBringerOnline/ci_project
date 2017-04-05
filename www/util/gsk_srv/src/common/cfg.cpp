/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * ======================================================================*/

#include "cfg.h"

CConfig* CConfig::m_pInstance = NULL;
CData<ConfigInfo>* CConfig::m_pstConfigInfo = NULL;


/*************************************************************
 ** Init
 **************************************************************/
CConfig* CConfig::Instance(  CData<ConfigInfo> *p )
{
    if( p )
    {
        m_pstConfigInfo = p;
    }
    
    if(m_pInstance == NULL )
    {
        m_pInstance = new CConfig();
    }
    
    return m_pInstance;
}

CConfig::CConfig()
{
    
}


int CConfig::Get( int iKey , int iDefault )
{
    if (m_pstConfigInfo)
    {
        m_pConfig = m_pstConfigInfo->GetNode(iKey);
        if(m_pConfig)
        {
            return Common::strto<int>( m_pConfig->strValue );
        }
    }
    
    return iDefault;
} ;


string CConfig::Get( int iKey , string strDefault )
{
    if (m_pstConfigInfo)
    {
        m_pConfig = m_pstConfigInfo->GetNode(iKey);
        if(m_pConfig)
        {
            return m_pConfig->strValue ;
        }
    }
    return strDefault;
} ;
