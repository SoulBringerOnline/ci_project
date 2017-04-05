#!/usr/bin/env python
# -*- coding: utf-8; tab-width: 4; -*-
# Author: zhaoys  <yongshengzhao@vip.qq.com>
# 13-10-3

import sys, os , json, urllib2
import logging
from pymongo import MongoClient
from celery import Celery , platforms

from GSKTest import *

sys.path.append("cmd")
from CmdUser import *

sys.path.append("proto")
import pb_gsk_pb2
import pb_gsk_req_pb2

from ConfigParser import SafeConfigParser

logging.basicConfig(level=logging.DEBUG,
    format='%(asctime)s %(filename)s[line:%(lineno)d] %(levelname)s %(message)s',
    datefmt='%a, %d %b %Y %H:%M:%S',
    filename='gsk.log',
    filemode='w')

parser = SafeConfigParser()
parser.read( "GSKCfg.ini" )

SERVER =  parser.get('GSK_OL', 'SERVER_ID')

API_HOST = parser.get('GSK_OL', 'API_HOST')
API_PORT = int( parser.get('GSK_OL', 'API_PORT') )

sys.path.append("util")
import protobuf_json
from GSKDB import Connection
from flask import Flask, Blueprint, abort, jsonify, request, session

app = Flask(__name__)
app.config.update(
    CELERY_BROKER_URL       =    "redis://192.168.165.241:6380" ,
    CELERY_RESULT_BACKEND   =    "redis://192.168.165.241:6380" ,
)

def make_celery(app):
    celery = Celery(app.import_name, broker=app.config['CELERY_BROKER_URL'])
    celery.conf.update(app.config)
    TaskBase = celery.Task
    class ContextTask(TaskBase):
        abstract = True
        def __call__(self, *args, **kwargs):
            with app.app_context():
                return TaskBase.__call__(self, *args, **kwargs)
    celery.Task = ContextTask
    return celery

platforms.C_FORCE_ROOT = True
celery = make_celery(app)



gsk_clt = Client( 'TEST' )
gsk_mongo = MongoClient(parser.get('GSK_TEST', 'MONGO_HOST'), int( parser.get('GSK_TEST', 'MONGO_PORT') ))

def pb_json(pb):
    return str( protobuf_json.pb2json( pb ))

def err_json(errno = -1, errmsg = ''):
    return '{errno:%d, errmsg:%s}' % (errno, errmsg)

	
@celery.task(name="TASKS.TASK_NOTIFY")
def task_prj_notify(taskId , opType):    
	# logging.debug("[NOTIFY] taskId:%s opType:%d" % (taskId , opType ))
    print("[NOTIFY] taskId:%s opType:%d" % (taskId , opType ))

	gsk_clt.request( CmdTaskNotify(taskId , opType) )
    return 0

@app.route("/api/prj_notify/<taskId>/<int:opType>/<int:delay>")
def prj_notify(taskId , opType, delay = 0):
    task_id = '0'
    res = task_prj_notify.apply_async((taskId ,opType),countdown=delay)
    if res.task_id:
        task_id = str(res.task_id)
    logging.debug("[NOTIFY] taskId:%s opType:%s delay:%d celery_task_id:%s" % (taskId , opType, delay , task_id ) )
    return task_id

#获取用户信息
@app.route("/api/get_user_data/<uin>")
def get_user_data( uin ):
    os.environ["GSK_UIN"] = uin
    try:
        return pb_json( gsk_clt.request( CmdUesrInfo() ) )
    except Exception,e:
        logging.debug("[ERROR] %d %s" % ( uin, e ) )
        return err_json

@app.route("/api/send_msg/<gid>/<msg>")
def send_msg( gid , msg ):
    os.environ["GSK_UIN"] = '13953360'
    try:
        return pb_json( gsk_clt.request( CmdSendMsg(gid , msg) ) )
    except Exception,e:
        logging.debug("[ERROR] %s %s %s" % ( gid, msg, e ) )
        return err_json

    
@app.route("/api/uinfo/<id>")
def uinfo( id ):
    user_info = None
    for item in gsk_mongo['gsk']['user'].find({'f_phone' : id}) :
        if item['f_phone'] == id:
            user_info = item

    if user_info is not None :
        os.environ["GSK_UIN"] = str(user_info['f_uin'])
    else:
        os.environ["GSK_UIN"] = id
    try:
        return pb_json( gsk_clt.request( CmdUesrInfo() ) )
    except Exception,e:
        logging.debug("[ERROR] %d %s" % ( id, e ) )
        return err_json
    


@app.route("/api/t/<task_id>")
def show_result(task_id):
    if task_prj_notify.AsyncResult(task_id).ready():
        retval = task_prj_notify.AsyncResult(task_id).get(timeout=1.0)
        return repr(retval)
    return '0'

if __name__ == "__main__":
    app.run(host=API_HOST, port=API_PORT , debug=True)
