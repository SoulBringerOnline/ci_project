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
redis_gsk = redis.StrictRedis(host='10.128.63.250', port=6380, db=0)
json_path = '/var/www/html/op/sqm/data'

SQM_STAT_TYPE_REQ        =                                   1
SQM_STAT_TYPE_RSP        =                                   2
SQM_STAT_TYPE_ERROR      =                                   3
SQM_STAT_TYPE_TIMEOUT    =                                   4

def pkg_size( val ):
    if val < 1024:
        return '%dB' % ( val )
    elif val < 1024 * 1024:
        return '%.1fKB' % ( val / 1024 )
    else:
        return '%.1fMB' % ( val / 1024 / 1024 )

def gen_sqm_json( cmd ):
    sqm_vals = redis_gsk.hgetall('sqm_val#%s#%d' % ( cmd , cur_day ))
    sqm_cnts = redis_gsk.hgetall('sqm_cnt#%s#%d' % ( cmd , cur_day ))
    sqm_pkg = redis_gsk.hgetall('sqm_pkg#%s#%d' % ( cmd , cur_day ))
    sqm_req = redis_gsk.hgetall('sqm_stat#%s#%d#1' % ( cmd , cur_day ))
    sqm_rsp = redis_gsk.hgetall('sqm_stat#%s#%d#2' % ( cmd , cur_day ))
    sqm_error = redis_gsk.hgetall('sqm_stat#%s#%d#3' % ( cmd , cur_day ))
    sqm_timeout = redis_gsk.hgetall('sqm_stat#%s#%d#4' % ( cmd , cur_day ))
    
    attr_elapsed = []
    attr_req = []
    attr_rsp = []
    attr_error = []
    attr_timeout = []
    attr_pkgsize = []

    total_elapsed = 0
    total_cnt = 0
    total_req = 0
    total_rsp = 0
    total_error = 0
    total_timeout = 0
    total_pkgsize = 0

    avg_elapsed = 0 
    max_elapsed = 0
    avg_pkgsize = 0 
    max_pkgsize = 0
    max_req = 0
    max_rsp = 0
    max_error = 0
    max_timeout = 0

    for i in range( 0 , 288 ):
        cnt = int(sqm_cnts.get( str(i) , 0 ))
        req = int(sqm_req.get( str(i) , 0 ))
        rsp = int(sqm_rsp.get( str(i) , 0 ))
        error = int(sqm_error.get( str(i) , 0 ))
        timeout = int(sqm_timeout.get( str(i) , 0 ))
        elapsed = 0 if cnt == 0 else int(sqm_vals.get( str(i) , 0 )) / cnt
        pkgsize = 0 if cnt == 0 else int(sqm_pkg.get( str(i) , 0 )) / cnt 

        if elapsed > max_elapsed:
            max_elapsed = elapsed
        if pkgsize > max_pkgsize:
            max_pkgsize = pkgsize
        if req > max_req:
            max_req = req
        if rsp > max_rsp:
            max_rsp = rsp
        if error > max_error:
            max_error = error
        if timeout > max_timeout:
            max_timeout = timeout            

        total_cnt += cnt 
        total_req += req
        total_rsp += rsp
        total_error += error
        total_timeout += timeout
        total_pkgsize += pkgsize * cnt
        total_elapsed += elapsed * cnt

        attr_elapsed.append( [ ( cur_day * 86400 + i * 300  ) * 1000,  elapsed ] )
        attr_req.append( [ ( cur_day * 86400 + i * 300  ) * 1000, req  ])
        attr_rsp.append( [ ( cur_day * 86400 + i * 300  ) * 1000,  rsp ])
        attr_error.append( [ ( cur_day * 86400 + i * 300  ) * 1000,  error ])
        attr_timeout.append( [ ( cur_day * 86400 + i * 300  ) * 1000,  timeout ])
        attr_pkgsize.append( [ ( cur_day * 86400 + i * 300  ) * 1000,  pkgsize ] )

    if total_elapsed > 0 and total_cnt > 0 :
        avg_elapsed = int( total_elapsed / total_cnt )
    if total_pkgsize > 0 and total_cnt > 0 :
        avg_pkgsize = int( total_pkgsize / total_cnt )

    json_dir = '%s/%s' % ( json_path, cur_day )
    # json_dir = 'data/%s' % ( cur_day )
    if os.path.exists( json_dir ) == False:
        os.makedirs( json_dir )

    def json_dump( file_name , values ):
        json_file = '%s/%s' % ( json_dir , file_name )
        file_handle = open( json_file, 'w')
        file_handle.write( json.dumps( values ) )
        file_handle.close( )


    json_dump( '%s_elapsed.json' % cmd , attr_elapsed )
    json_dump( '%s_req.json' % cmd , attr_req )
    json_dump( '%s_rsp.json' % cmd  , attr_rsp )
    json_dump( '%s_error.json' % cmd  , attr_error )
    json_dump( '%s_timeout.json' % cmd  , attr_timeout )
    json_dump( '%s_pkgsize.json' % cmd  , attr_pkgsize )

    if max_elapsed:
        json_dump( '%s_elapsed.dat' % cmd  , '耗时峰值 %d | 均值 %d' % (max_elapsed, avg_elapsed) )
    if max_req:
        json_dump( '%s_req.dat' % cmd  , '请求峰值 %d | 总 %d' % (max_req ,total_req ))
    if max_rsp:
        json_dump( '%s_rsp.dat' % cmd  , '响应峰值 %d | 总 %d' % (max_rsp ,total_rsp ))
    if max_error:
        json_dump( '%s_error.dat' % cmd  , '异常峰值 %d | 总 %d' % (max_error ,total_error ))
    if max_timeout:
        json_dump( '%s_timeout.dat' % cmd  , '超时峰值 %d | 总 %d' % (max_timeout ,total_timeout ))
    if max_pkgsize:
        json_dump( '%s_pkgsize.dat' % cmd  , '流量峰值 %d | 总 %d' % (max_pkgsize ,total_pkgsize ))
    if total_req:
        json_dump( '%s_desc.dat' % cmd  , '总请求(%d) = 总响应(%d) + 总超时(%d) | 异常 %d个(%s) | 超时 %d个(%s) | 流量 总(%s) 均(%s) 最大(%s)' % (total_req,total_rsp,total_timeout,total_error,format(total_error / total_req, '.2%'),total_timeout,format(total_timeout / total_req, '.2%'), pkg_size(total_pkgsize),pkg_size(avg_pkgsize),pkg_size(max_pkgsize) ))
    # dat_file = '%s/%s.dat' % ( json_dir , cmd )
    # file_handle = open( dat_file, 'w')
    # file_handle.write( json.dumps( max_elapsed ) )
    # file_handle.close( )
    
for key in redis_gsk.keys('sqm_val#*#%d' % ( cur_day )):
    _ , cmd , _ = key.split('#')
    gen_sqm_json(cmd)
    
