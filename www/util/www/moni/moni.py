#!/usr/bin/python
# -*- coding: utf-8 -*-
# Author: zhaoys  <yongsheng.zhao@lavaclan.com>
# 13-10-3
# Copyright 2013 LavaClan Inc.

import os
import sys
import redis
import time
import datetime
import json

reload(sys)
sys.setdefaultencoding('utf-8')

cur_day = (int)(( time.time() + 28800 ) / 86400)

# redis_laki_mq = redis.StrictRedis(host='192.168.165.241', port=6380, db=0)
redis_laki_mq = redis.StrictRedis(host='10.128.63.250', port=6380, db=0)

json_path = '/var/www/html/op/moni/data'
def gen_attr_json( attr_id ):
    key = 'moni_attr#%s#%s' % ( attr_id , cur_day )
    attr_info = []
    max_value = 0
    attr_values = redis_laki_mq.hgetall( key )
    for i in range( 0 , 1440 ):
        attr_info.append( [ ( cur_day * 86400 + i * 60  ) * 1000, int(attr_values.get( str(i) , 0 ))  ] )
        if int(attr_values.get( str(i) , 0 )) > max_value:
            max_value = int(attr_values.get( str(i) , 0 ))

    json_dir = '%s/%s' % ( json_path, cur_day )
    # json_dir = 'data/%s' % ( cur_day )
    if os.path.exists( json_dir ) == False:
        os.makedirs( json_dir )

    json_file = '%s/%s.json' % ( json_dir , attr_id )
    file_handle = open( json_file, 'w')
    file_handle.write( json.dumps( attr_info ) )
    file_handle.close( )

    dat_file = '%s/%s.dat' % ( json_dir , attr_id )
    file_handle = open( dat_file, 'w')
    file_handle.write( json.dumps( max_value ) )
    file_handle.close( )


def gen_srv_attr_json( srv, attr_id ):
    key = 'moni_srv_attr#%s#%s#%s' % ( attr_id , cur_day , srv)
    attr_info = []
    max_value = 0
    attr_values = redis_laki_mq.hgetall( key )
    for i in range( 0 , 1440 ):
        attr_info.append( [ ( cur_day * 86400 + i * 60  ) * 1000, int(attr_values.get( str(i) , 0 ))  ] )
        if int(attr_values.get( str(i) , 0 )) > max_value:
            max_value = int(attr_values.get( str(i) , 0 ))

    json_dir = '%s/%s/%s' % (json_path, srv, cur_day )
    # json_dir = 'data/%s' % ( cur_day )
    if os.path.exists( json_dir ) == False:
        os.makedirs( json_dir )

    json_file = '%s/%s.json' % ( json_dir , attr_id )
    file_handle = open( json_file, 'w')
    file_handle.write( json.dumps( attr_info ) )
    file_handle.close( )

    dat_file = '%s/%s.dat' % ( json_dir , attr_id )
    file_handle = open( dat_file, 'w')
    file_handle.write( json.dumps( max_value ) )
    file_handle.close( )

for key in redis_laki_mq.keys( 'moni_attr#*#%d' % cur_day ):
    print key
    attr_id = key.split( '#' )[1]
    gen_attr_json(attr_id)
for key in redis_laki_mq.keys( 'moni_srv_attr#*#%d#*' % cur_day ):
    print key
    attr_id = key.split( '#' )[1]
    srv = key.split( '#' )[3]
    gen_srv_attr_json(srv ,attr_id)
for key in redis_laki_mq.keys( 'moni_attr#*#%d' % (cur_day-2) ):
    print key
    redis_laki_mq.delete( key )
for key in redis_laki_mq.keys( 'moni_srv_attr#*#%d#*' % (cur_day-2) ):
    print key
    redis_laki_mq.delete( key )
