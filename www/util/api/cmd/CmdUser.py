#!/usr/bin/env python
# -*- coding: utf-8; tab-width: 4; -*-
# Author: zhaoys  <yongshengzhao@vip.qq.com>
# 13-9-28
# 获取用户数据

import struct
import sys
import os

sys.path.append("..")
from GSKTest import *
from GSKTestCmd import *

sys.path.append("../proto")
import pb_gsk_req_pb2
import pb_gsk_pb2

class CmdBeatheart(Command):
    def __init__(self):
        Command.__init__(self, 0xFFFF   )
    def unpack_body(self, body):
        print body


class CmdNewUser(Command):
    def __init__(self):
        Command.__init__(self, CMD['CMD_USER_CREATE'])
        self.pb_req_t_req.f_i_args.append( 0xFFFF )
    def unpack_body(self, body):
        print body

class CmdUesrInfo( Command ):
	def __init__(self):
        Command.__init__(self, CMD['CMD_USER_INFO'])
        self.pb_req_t_req.f_i_args.append( 0xFFFF )

    def handle(self):
        print self.pb_clt_t_user.f_info.f_uin

class CmdUpdateUesrInfo( Command ):
    def __init__(self):
        Command.__init__(self, CMD['CMD_USER_UPDATE_INFO'])
        self.pb_req_t_req.f_s_args.append( "f_name" )
        self.pb_req_t_req.f_s_args.append( "AAAAA" )

    def handle(self):
        print self.pb_clt_t_user.f_info.f_uin


class CmdGroupInfo( Command ):
    def __init__(self):
        Command.__init__(self, 0x0205)
        self.pb_req_t_req.f_s_args.append( '5552e7b2bc2ec123b489aa57' )

    def handle(self):
        print self.pb_clt_t_user

class CmdTaskNotify( Command ):
    def __init__(self , taskId , opType ):
        Command.__init__(self, CMD['CMD_TASK_TIMER_CALL'])
		self.pb_req_t_req.f_s_args.append(taskId)
		self.pb_req_t_req.f_s_args.append(str(opType))

    def handle(self):
        print self.pb_clt_t_user
		
class CmdUploadUserAddress( Command ):
    def __init__(self):
        Command.__init__(self, CMD['CMD_USER_UPLOAD_PHONE_ADDRESS'])
        self.pb_req_t_req.f_s_args.append( u"唐昕" )
        self.pb_req_t_req.f_s_args.append( "13401001001" )
        self.pb_req_t_req.f_s_args.append( u"赵永生" )
        self.pb_req_t_req.f_s_args.append( "13401001002" )
        self.pb_req_t_req.f_s_args.append( u"赵永生" )
        self.pb_req_t_req.f_s_args.append( "13401001003" )
    def handle(self):
        print self.pb_clt_t_user.f_info.f_uin

class CmdSendMsg( Command ):
    def __init__(self , gid,  msg):
        import uuid
        uu = str(uuid.uuid1())
        Command.__init__(self, 0x0201)
        self.pb_req_t_req.f_i_args.append(1)
        self.pb_req_t_req.f_i_args.append(2)
        self.pb_req_t_req.f_s_args.append(gid)
        self.pb_req_t_req.f_s_args.append(msg)
        self.pb_req_t_req.f_s_args.append(uu)

    def handle(self):
        print self.pb_clt_t_user

class CmdGrpAddUser( Command ):
    def __init__(self):
        # gid = '5582bcba6a6950103025d342'
        # gid = '5582bdb16a6950103025d369'
        # gid = '558379596a69501bb15e9e6a'  #意见反馈
        Command.__init__(self, 0x0207)
        # self.pb_req_t_req.f_s_args.append('1031602')
        # self.pb_req_t_req.f_s_args.append(gid)
        # users = ["15810117562","13801070519","13718058621","15901193359","13701011165","13987197653","18911366585","18600055071","13701054061","13901116646","13810001336","13807106181","18911385048","13914494888","13999868860","18640082786","15050535388","13140086661","13553897924","18910036720","13911383638","18610049603","13910715261","15002909006","13161365609","13488880082","18610764911","13681261892","13911728177","13910145926","13810752036","13391288456","13331162003","13691009241","13811976417","15210181811","13810621042","18587194077","13522318608","18629338222","13759518326","13554200787","13641271348","13701206568","13983179339","18901263837","18518790996","18666010660","13601513351","18611683329","18600055072","13910938750","13916048593","18610696329","13501395680"]
        # users = ["15810117562","18600055071","13810001336","13553897924","13488880082","13759518326","13701206568","18518790996","18666010660","13601513351","18600055072","13916048593"]
        # users = [ '13811349261', '18610210990', "15810117562","13801070519","13718058621","15901193359","13701011165","13987197653","18911366585","18600055071","13701054061","13901116646","13810001336","13807106181","18911385048","13914494888","13999868860","18640082786","15050535388","13140086661","13553897924","18910036720","13911383638","18610049603","13910715261","15002909006","13161365609","13488880082","18610764911","13681261892","13911728177","13910145926","13810752036","13391288456","13331162003","13691009241","13811976417","15210181811","13810621042","18587194077","13522318608","18629338222","13759518326","13554200787","13641271348","13701206568","13983179339","18901263837","18518790996","18666010660","13601513351","18611683329","18600055072","13910938750","13916048593","18610696329","13501395680"]
        # users = ['13810416344']
        # for u in users:
        #     self.pb_req_t_req.f_s_args.append(str(int(u[3:])))

    def handle(self):
        print self.pb_clt_t_user

def main():
    cli = Client("TEST")
    # cmd = CmdUesrInfo()
    # cmd = CmdGrpAddUser()
    # cmd = CmdNewUser()
    # cmd = CmdGroupInfo()
    # cmd = CmdUpdateUesrInfo()
    # cmd = CmdUploadUserAddress()
    # ret = cli.request(cmd)
    # print ( ret )

if __name__ == "__main__":
    main()
