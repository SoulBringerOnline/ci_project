/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/
#ifndef __YS_UTIL_H__
#define __YS_UTIL_H__

#include "inc.h"

using namespace std;

int DiffDay( int iTime )
{
    return ( time(NULL) + SECS_OF_FIX ) / SECS_OF_DAY - ( iTime + SECS_OF_FIX ) / SECS_OF_DAY;
}

int DiffDayTwoTime( int iTime1, int iTime2 )
{
    return ( iTime1 + SECS_OF_FIX ) / SECS_OF_DAY - ( iTime2 + SECS_OF_FIX ) / SECS_OF_DAY;
}

int GetDay( int iTime )
{
    return ( iTime + SECS_OF_FIX ) / SECS_OF_DAY;
}

bool InTimePeriod(  int iBegin , int iEnd , int iCur = 0, bool bFix = false)
{
    if( iCur == 0 )
    {
        iCur = time(NULL);
    }
    if( bFix )
    {
        iCur += SECS_OF_FIX;
    }
    return (iCur >= iBegin && iCur <= iEnd);
}

string Load2Str(const char* szFile)
{
    ifstream ifs( szFile );
    string str = string(istreambuf_iterator<char>(ifs), istreambuf_iterator<char>());
    ifs.close();
    return str;
}


//jpush所用encode函数
unsigned char ToHex(unsigned char x)
{
    return  x > 9 ? x + 55 : x + 48;
}

unsigned char FromHex(unsigned char x)
{
    unsigned char y;
    if (x >= 'A' && x <= 'Z') y = x - 'A' + 10;
    else if (x >= 'a' && x <= 'z') y = x - 'a' + 10;
    else if (x >= '0' && x <= '9') y = x - '0';
    else assert(0);
    return y;
}

std::string UrlEncode(const std::string& str)
{
    std::string strTemp = "";
    size_t length = str.length();
    for (size_t i = 0; i < length; i++)
    {
        if (isalnum((unsigned char)str[i]) ||
            (str[i] == '-') ||
            (str[i] == '_') ||
            (str[i] == '.') ||
            (str[i] == '~'))
            strTemp += str[i];
        else if (str[i] == ' ')
            strTemp += "%20";
        // strTemp += "+";
        else
        {
            strTemp += '%';
            strTemp += ToHex((unsigned char)str[i] >> 4);
            strTemp += ToHex((unsigned char)str[i] % 16);
        }
    }
    return strTemp;
}

std::string UrlDecode(const std::string& str)
{
    std::string strTemp = "";
    size_t length = str.length();
    for (size_t i = 0; i < length; i++)
    {
        if (str[i] == '+') strTemp += ' ';
        else if (str[i] == '%')
        {
            assert(i + 2 < length);
            unsigned char high = FromHex((unsigned char)str[++i]);
            unsigned char low = FromHex((unsigned char)str[++i]);
            strTemp += high*16 + low;
        }
        else strTemp += str[i];
    }
    return strTemp;
}

#endif

