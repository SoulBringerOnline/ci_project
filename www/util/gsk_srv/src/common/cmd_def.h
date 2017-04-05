/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 *
 * Copyright (c) 2014年 http: //www.lavaclan.com All rights reserved.
 * ======================================================================*/


#ifndef __YS_CMD_DEFINE_H__
#define __YS_CMD_DEFINE_H__

//注册流程
#define 	CMD_REQ_INNER_CREATE_ACCOUNT                           0x0001 //创建帐户
#define 	CMD_REQ_INNER_LOGIN                                    0x0002 //登陆
#define 	CMD_REQ_THRIDPARTY_LOGIN                               0x0003 //第三方登陆
#define 	CMD_REQ_CHANG_PASSWD                                   0x0004 //修改密码
#define 	CMD_REQ_SERVER_LOGIN                                   0x0005 //内部登陆
#define     CMD_REQ_ACCOUNT_TO_UIN                                 0x0006 //账号转UIN

//用户相关
#define CMD_USER_CREATE                          0x0100 //创建用户
#define CMD_USER_INFO                            0x0101 //获取用户数据
#define CMD_USER_MODIFY                            0x0102 //修改用户数据

//杂项
#define  CMD_PUSH                               0xEEEE  //PUSH

#endif
