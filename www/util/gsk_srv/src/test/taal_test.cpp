/* ======================================================================
 * taal project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongsheng.zhao@lavaclan.com
 * Date   	: 	2014-08-15
 *
 * Copyright (c) 2014年 http://www.lavaclan.com All rights reserved.
 * ======================================================================*/

#include "inc.h"
#include "def.h"
#include "util.h"
#include "taal_util.h"
/*
 *
int JPushMsg(GUin &stUin , string &strMsg)
{
    if( strMsg.size() && stUin.stUser.dwUin && stUin.stUser.wPlt && stUin.stUser.wSrv )
    {
        string strBody = "{ \"platform\": \"all\", \"audience\" : \"all\", \"notification\" : { \"alert\" : \"Hi, JPush!\", \"title\" : \"火山舰队\" } }";
        LavaHttpRequest stHttpReq;
        stHttpReq.setContentType("application/json");
        string a;
        stHttpReq.setPostRequest("https://s39.cnzz.com:8080/stat.php?id=533357&web_id=533357&show=pic2", a, true );
        
        cout << stHttpReq.encode() << endl;
        LavaHttpResponse stHttpRsp;
        int iRet = stHttpReq.doRequest(stHttpRsp, 3000);
        if( iRet < 0 )
        {
            cout << iRet << " [1]" << endl;
            return -101;
        }

        if(stHttpRsp.getContent().length() <= 0)
        {
            return -102;
        }

        string strTaskId = stHttpRsp.getContent();
        cout << strTaskId << endl;
        return 0;
    }
}

*/
int main()
{
    GUin stUin ; 
    stUin.stUser.dwUin = 666666;
    stUin.stUser.wPlt = 51; 
    stUin.stUser.wSrv =1 ;
    string strMsg = "测试测试！！！！asdflasjflksa";
        //   JPushMsg( stUin ,  strMsg );
        //
}
