#!/usr/bin/env python
# -*- coding: utf-8; tab-width: 4; -*-
# Author: zhaoys  <yongsheng.zhao@lavaclan.com>
# 13-9-28
# Copyright 2013 LavaClan Inc.
import sys
import os

import datetime
import random
import math

import redis

from pymongo import *
from bson import ObjectId

import struct
import socket

from ConfigParser import SafeConfigParser
sys.path.append("proto")
import pb_gsk_pb2
from GSKDB import *

# ---------------------------------------------------------
# INIT
# ---------------------------------------------------------

parser = SafeConfigParser()
parser.read('GSKCfg.ini')

reload(sys)
sys.setdefaultencoding('utf-8')

mongo_gsk = MongoClient('192.168.165.240',27017)
mongo_gsk_user = mongo_gsk['gsk_test']['user']
mongo_gsk_log = mongo_gsk['gsk']['log']
mongo_gsk_news = mongo_gsk['gsk_spider']['news']

redis_gsk = redis.StrictRedis(host='192.168.165.241', port=6380, db=0)
mysql_conn=Connection(host = "192.168.164.199" , database="admin" , user="root" ,  password="wx123456" ) 

for i in mongo_gsk['gsk_ol']['groupMsg'].find({'f_group_type' : 6}):
	print i['f_msg_book']
	break;


# i=1
# for item in mongo_gsk['gsk_ol']['hotspot'].find():
# 	mongo_gsk['gsk_ol']['hotspot'].update_one({'_id':item['_id']}, {"$set" : {'f_hotspot_id':str(i)} }   , upsert=False)
# 	i = i + 1


# for item in mongo_gsk['gsk_spider']['news'].find():
# 	if item.get('diggTime'):
# 		mongo_gsk['gsk_spider']['news'].update_one({'_id':item['_id']}, {"$unset" : {'diggTime':1} }   , upsert=False)
# 	if item.get('editTime'):
# 		mongo_gsk['gsk_spider']['news'].update_one({'_id':item['_id']}, {"$unset" : {'editTime':1} }   , upsert=False)


# 	if item.get('diggTime'):
# 		print item['diggTime']
# 	else:
# 		mongo_gsk['gsk_spider']['news_ol'].update_one({'_id':item['_id']}, {"$set" : { 'diggTime' : '2015-08-18 10:31' }}   , upsert=False)

# # mongo_gsk_log.ensure_index( [('f_time', -1)])
# TEST
# ---------------------------------------------------------
# print mongo_gsk_user.find({'f_phone' : '13401031602'}).count()
# sql = "SELECT * FROM technology_knowledge ";
# for item in mysql_conn.query(sql):
# 	info = []
# 	for i in item['info'].splitlines():
# 		info.append(i.strip())
# 	query = '''UPDATE technology_knowledge SET info='%s' WHERE id=%s;''' % ( '\n'.join(info).replace('%','%%').replace('\'','\\\'')  , item['id']  );
# 	try:
# 		mysql_conn.execute( query );
# 		mysql_conn.commit()
# 	except Exception, e:
# 		print e , item['id']


# sql = "SELECT * FROM user";
# for item in mysql_conn.query(sql):
# 	if len(item['username']) == 11 and item['username'][0] == '1' and len(item['nickname']) > 0: 
# 		user_info = {}
# 		user_info['f_uin'] = int(item['username'][3:])
# 		user_info['f_province'] = "北京市"
# 		user_info['f_city'] = "朝阳区"
# 		user_info['f_phone'] = item['username']
# 		user_info['f_name'] = item['nickname']
# 		user_info['f_company_type'] = item['company_type']
# 		user_info['f_years_of_working'] = item['working_age']
# 		user_info['f_job_type'] = item['post']
# 		user_info['f_job_title'] = item['work_title']
# 		user_info['f_setting'] = int(3)
# 		# user_info['f_friend_list'] = []
# 		# user_info['f_friend_validation_list'] = []
# 		# user_info['f_friend_recommend_list'] = []
# 		# user_info['f_im_group'] = []
# 		user_info['f_name_pinyin'] = ''
# 		user_info['f_name_pinyin_j'] = ''
# 		# if mongo_gsk_user.find({'f_phone' : item['username']}).count() == 0:
# 		# if user_info['f_uin'] == 1031602:
# 		mongo_gsk_user.update_one({'f_uin':user_info['f_uin']}, {"$set" : user_info}   , upsert=True)


#规范图片
#版本更新
#


# for k in redis_gsk.keys('PUSH_*'):
# 	print k
# for k in redis_gsk.hvals('PUSH_U#1000011'):
# 	print k 
# ---------------------------------------------------------
# INDEX
# ---------------------------------------------------------
# mongo_gsk_user.ensure_index( [('f_uin', pymongo.ASCENDING) ])
# ---------------------------------------------------------
# CLEAR
# ---------------------------------------------------------
# def clear_data():
# 	mongo_gsk_user.update({}, { '$set' : { 'f_fight_round':0, 'f_fight_status':0, 'f_occupied_id':'' } } , upsert=False, multi=True)
# 	mongo_gsk_fight.drop()
# 	mongo_gsk_squad.drop()
# 	mongo_gsk_march.drop()
# 	mongo_gsk_user.drop()
# 	for i in redis_gsk.keys( "FIGHT*" ):
# 		redis_gsk.delete(i)
# clear_data()

# for item in mongo_gsk_user.find({}):
#     for i in  item:
#     	print i, item[i]

# mongo_gsk_mail.update({'f_type':2}, { '$set' : { 'f_read_flag':1, 'f_title_type':0 } } , upsert=False, multi=True)
# mongo_gsk_user.update({'f_uin':1201}, { '$set' : { 'f_nengliang':0} } , upsert=False)
# for i in mongo_gsk_user.find( { 'f_lianmeng_title' : { '$exists' : 0 } } ):
# 	print i['f_uin']
# ---------------------------------------------------------
# TEST
# ---------------------------------------------------------
# mongo_gsk_user.update( { "f_uin": 111111} ,  {"$set" : { "f_oil_num" : 1 }})
# user_info = mongo_gsk_user.find_one( { "f_uin": 111111}  )
# print user_info['f_oil_num']

# mongo_gsk_lianmeng.update({},{'$set':{"f_zhanli":0}})
# mongo_gsk_lianmeng.remove( {'_id':ObjectId('429496730255870010000000')} )
# mongo_gsk_lianmeng.insert( {'_id':ObjectId('429496730255870010000000') , "f_uin": 10000, "f_plt": 51, "f_srv": 1, "f_level": 1, "f_exp": 0, "f_time": 1414397497, "f_name": "新手联盟"} )
# lianmeng = mongo_gsk_lianmeng.find( {'f_lianmeng_type':1} )[0]
# mongo_gsk_lianmeng.drop()
# for i in range(1, 100):
# 	print i
# 	del lianmeng['_id']
# 	lianmeng['f_srv'] = i
# 	lianmeng['f_lianmeng_type'] = 1
# 	mongo_gsk_lianmeng.insert(lianmeng)
# mongo_gsk_lianmeng.update({'f_lianmeng_type':1}, {'$set':{'f_member' : 0 , 'f_zhanli' : 0}}, multi = True)
# mongo_gsk_lianmeng.update({'f_lianmeng_type':1}, {'$set':{'f_lianmeng_type' : int(1) }}, multi = True)
# lianmeng = mongo_gsk_lianmeng.find({'f_lianmeng_type':1})
# for i in lianmeng :
# 	print i['f_lianmeng_type'],i['f_name']
# print lianmeng
# for i in mongo_gsk_user.find( { 'f_x': { '$gte': 0, '$lte': 10 }, 'f_y': { '$gte': 0, '$lte': 10 } } ):
    # print i
# mongo_gsk_chat.update( { } , { '$set' : { 'f_time' : 0 } }, upsert=False, multi=True )
# mongo_gsk_user.update( { 'f_x': pos_x, 'f_y': pos_y} , { '$set' : { 'f_fight_status' : 2 } } )
# for info in mongo_gsk_user.find( { 'f_x': pos_x, 'f_y': pos_y} ):
#     for i in info :
#     	print "%s : %s" % ( i, info[i])
# print '---'
# for info in mongo_gsk_squad.find( { 'f_x': pos_x, 'f_y': pos_y } ):
# 	# print info['f_x'] , info['f_y']
#     for i in info :
#     	print "%s : %s" % ( i, info[i])
# for k in range( 1, 100 ):	
# 	pb_mongo_t_fight_round = gsk_pb2.pb_mongo_t_fight_round()
# 	pb_mongo_t_fight_round.ParseFromString(redis_gsk.get( "FIGHT#1#%d#%d#%d" % (pos_x, pos_y, k) )  )
# 	# print 'round %d %d %d %d' % ( k , pb_mongo_t_fight_round.f_attacker_num , pb_mongo_t_fight_round.f_defender_num , pb_mongo_t_fight_round.f_fight_status)
# 	# if  pb_mongo_t_fight_round.f_fight_status == 2 :
# 	# 	print pb_mongo_t_fight_round.f_attacker.f_name
# 	# print 
#  	for i in pb_mongo_t_fight_round.f_fight_init.f_defender:
#  		if i.f_cur_ship_num > 10:
# 			print "	id:%s %d" % (i.f_individual_uuid , i.f_cur_ship_num)
# 	for i in pb_mongo_t_fight_round.f_fight_init.f_attacker:
# 		if i.f_cur_ship_num > 10:
# 			print "	id:%s %d" % (i.f_individual_uuid , i.f_cur_ship_num)
# 	for i in pb_mongo_t_fight_round.f_fight_q.f_defender:
# 		print "	id:%s " % (i.f_individual_uuid )
# 	print pb_mongo_t_fight_round.f_attacker.f_individual_id ,  pb_mongo_t_fight_round.f_attacker.f_individual_pos ,  pb_mongo_t_fight_round.f_attacker.f_cur_ship_num

