#!/usr/bin/python
# -*- coding: utf-8 -*-
# Author: zhaoys  <yongsheng.zhao@lavaclan.com>
# 13-10-3
# Copyright 2013 LavaClan Inc.

import os
import sys
import redis
import time



REDIS_HOST = "192.168.165.241"
REDIS_PORT = 6380

cur_day = (int)(( time.time() + 28800 ) / 86400)
cur_hour = (int)(( time.time() + 28800 ) / 3600)
cur_min = (int)(( time.time() + 28800 ) / 60)

redis_mq = redis.StrictRedis(host=REDIS_HOST, port=REDIS_PORT, db=0)

# from pymongo import *
# from bson import ObjectId
# mongo_gsk = MongoClient('192.168.165.240',27017)
# mongo_gsk_user = mongo_gsk['gsk_ol']['user']

# V_USERS
# V_CHANNEL_USERS#{CHANNEL}
# V_OL#{UIN}#{CHANNEL}

# V_CHANNEL_DNU#{CHANNEL}#{DAY}
# V_CHANNEL_HNU#{CHANNEL}#{HOUR}
# V_DNU#{DAY}
# V_HNU#{HOUR}

# V_CHANNEL_DAU#{CHANNEL}#{DAY}
# V_CHANNEL_HAU#{CHANNEL}#{HOUR}
# V_DAU#{DAY}
# V_HAU#{HOUR}

ALL_CHANNEL = []
for item in redis_mq.keys( 'V_CHANNEL_DAU#*#%d' %  ( cur_day ) ):
    item_info = item.split('#')
    plt = int(item_info[1] )
    if plt > 0 :
        redis_mq.sadd( 'V_CHANNELS' , plt )
for plt in redis_mq.smembers( 'V_CHANNELS' ):
    ALL_CHANNEL.append( int(plt) )
print( ALL_CHANNEL )


# def init_users():
#     for u in mongo_gsk_user.find():
        # redis_mq.sadd( 'V_USERS' , int(u['f_uin']) )
# init_users()

def stat_total():
    items = redis_mq.keys( 'V_CHANNEL_USERS#*' )
    for item in items:
        _ , channel = item.split('#')
        num = redis_mq.scard( item )
        redis_mq.set( 'STAT_CHANNEL_USERS#%s' % (channel) , num )

    num = redis_mq.scard( 'V_USERS' )
    redis_mq.set( 'STAT_CHANNEL_USERS#0' , num )

def stat_ol():
    items = redis_mq.keys( 'V_CHANNEL_OL_STAT#*' )
    for item in items:
        _ , channel, minute = item.split('#')
        channel = int(channel)
        minute = int(minute)
        if channel == 0 :
            continue
        new_num =  redis_mq.scard( item )
        hset_key = minute % 1440
        stat_key = 'STAT_OL#%d#%d' % ( channel ,  cur_day )
        old_num = 0
        tmp_num = redis_mq.hget( stat_key , hset_key )
        if tmp_num is not None :
            old_num = int( tmp_num )
        if new_num > old_num:
            redis_mq.hset( stat_key , hset_key , new_num)

    items = redis_mq.keys( 'V_OL_STAT#*' )
    for item in items:
        _ ,  minute = item.split('#')
        minute = int(minute)
        new_num =  redis_mq.scard( item )
        hset_key = minute % 1440
        stat_key = 'STAT_OL#0#%d' % ( cur_day )
        old_num = 0
        tmp_num = redis_mq.hget( stat_key , hset_key )
        if tmp_num is not None :
            old_num = int( tmp_num )
        if new_num > old_num:
            redis_mq.hset( stat_key , hset_key , new_num)


# for k in redis_mq.keys( 'STAT_*' ):
#     print k
#     print  k , redis_mq.delete(k)

def vesuvio_stat( data_key, stat_key ):
    new_num = redis_mq.scard( data_key )
    tmp_num = redis_mq.get( stat_key )
    old_num = 0
    if tmp_num is not None:
        old_num = int(tmp_num)
    if new_num > old_num:
        redis_mq.set( stat_key , new_num )
    
def vesuvio_stat_user( plt , i):
    #CHANNEL
    data_key = "V_CHANNEL_DNU#%d#%d" % (plt, cur_day - i )
    stat_key = "STAT_CHANNEL_DNU#%d#%d" % (plt, cur_day - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_CHANNEL_DAU#%d#%d" % (plt, cur_day - i )
    stat_key = "STAT_CHANNEL_DAU#%d#%d" % (plt, cur_day - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_CHANNEL_HNU#%d#%d" % (plt, cur_hour - i )
    stat_key = "STAT_CHANNEL_HNU#%d#%d" % (plt, cur_hour - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_CHANNEL_HAU#%d#%d" % (plt, cur_hour - i )
    stat_key = "STAT_CHANNEL_HAU#%d#%d" % (plt, cur_hour - i )
    vesuvio_stat( data_key , stat_key )

    #GLOBAL
    data_key = "V_DNU#%d" % ( cur_day - i )
    stat_key = "STAT_CHANNEL_DNU#0#%d" % ( cur_day - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_DAU#%d" % ( cur_day - i )
    stat_key = "STAT_CHANNEL_DAU#0#%d" % ( cur_day - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_HNU#%d" % ( cur_hour - i )
    stat_key = "STAT_CHANNEL_HNU#0#%d" % ( cur_hour - i )
    vesuvio_stat( data_key , stat_key )

    data_key = "V_HAU#%d" % ( cur_hour - i )
    stat_key = "STAT_CHANNEL_HAU#0#%d" % ( cur_hour - i )
    vesuvio_stat( data_key , stat_key )

def vesuvio_retention(i):
    new = "V_DNU#%d" % ( cur_day - i - 1)
    new3 = "V_DNU#%d" % ( cur_day - i - 3)
    new7 = "V_DNU#%d" % ( cur_day - i - 7)

    act = "V_DAU#%d" % ( cur_day - i)

    new_num =  (int)(redis_mq.scard( new ))
    if new_num:
        inter = len( redis_mq.sinter( new , act ) )
        ur = "%d#%d" % ( inter, new_num )
        redis_mq.hset( 'STAT_UR', (cur_day - i - 1 ) , ur  )

    new_num3 = (int)(redis_mq.scard( new3 ))
    if new_num3:
        inter = len( redis_mq.sinter( new3 , act ) )
        ur3 = "%d#%d" % ( inter, new_num3 )
        redis_mq.hset( 'STAT_UR3' , (cur_day - i - 3 ) , ur3  )

    new_num7 = (int)(redis_mq.scard( new7 ))
    if new_num7:
        inter = len( redis_mq.sinter( new7 , act ) )
        ur7 = "%d#%d" % ( inter, new_num7 )
        redis_mq.hset( 'STAT_UR7', (cur_day - i - 7 ) , ur7  )



stat_total()
stat_ol()
for plt in ALL_CHANNEL:
    for i in range(0,2):
        vesuvio_stat_user( plt , i )
for i in range(0,2):
    vesuvio_retention(i)


