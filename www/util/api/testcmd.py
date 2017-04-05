#!/usr/bin/env python
# -*- coding: utf-8; tab-width: 4; -*-
# Author: zhaoys  <yongshengzhao@vip.qq.com>
# 13-10-3

import sys, os , json, urllib2
import logging
from GSKTest import *

sys.path.append("cmd")
from CmdUser import *

sys.path.append("proto")
import pb_gsk_pb2
import pb_gsk_req_pb2
gid = '5592984a6a69504c7280c0da'  #最大的群

# gsk_clt = Client( 'TEST' )
#gsk_clt.request( CmdBeatheart() )
for i in range(1000,1100):
	gsk_clt = Client( 'TEST' )
	gsk_clt.request( CmdUesrInfo() )
#for i in range(1000,1100):
	#gsk_clt.request( CmdSendMsg(gid,str(i)) )
