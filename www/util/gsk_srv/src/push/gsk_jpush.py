import sys
import os
import time
import zmq
import jpush as jpush

reload(sys)
sys.setdefaultencoding('utf8')

gsk_jpush = jpush.JPush("cab0d0d61fc1aa7ab6aca608", "9ba8093e82f2b668c3f866f3")

zmq_ctx = zmq.Context()
zmq_sock = zmq_ctx.socket(zmq.SUB)
zmq_sock.connect("tcp://0.0.0.0:18006")
zmq_sock.setsockopt(zmq.SUBSCRIBE, "")

while True:
    user_id = zmq_sock.recv()
    more = zmq_sock.getsockopt(zmq.RCVMORE)
    if more:
        msg = zmq_sock.recv()
        try:
            push = gsk_jpush.create_push()
            push.platform = jpush.all_
            push.options = {"time_to_live":3600, "apns_production":True }

            push.audience = jpush.audience( jpush.alias( user_id ) )
            push.notification = jpush.notification(alert=msg)
            try:
                push.send()
            except Exception,e:
                print '[JPUSH ERROR]' , e
            print("[JPUSH] uid:%s msg:%s " % ( user_id, msg ) )

        except Exception,e:
            print '[RECV MSGBODY ERROR]' , e