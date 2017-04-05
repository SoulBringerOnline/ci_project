#!/usr/bin/env python
# -*- coding: utf-8; tab-width: 4; -*-
# Author: zhaoys  <yongshengzhao@vip.qq.com>
# 13-9-28
# 

import sys
import struct
import socket
import inspect
import os
import random
import logging
import subprocess
from os import getenv
from GSKTestEnv import *
sys.path.append("proto")
import pb_gsk_req_pb2
import pb_gsk_pb2

reload(sys)
sys.setdefaultencoding('utf8')

STX = 0x2
ETX = 0x3

GSK_HOST = {
    '106':'127.0.0.1',
    # 'TEST':'127.0.0.1',
    # 'TEST':'203.195.182.51',
    # 'TEST':'192.168.78.65', 
    # 'TEST':'192.168.165.239', 
    # 'TEST':'192.168.164.199', 
    'TEST':'103.227.79.82', 
    #'TEST':'192.168.164.200', 
    'TEST_OL':'192.168.164.200', 

}
GSK_PORT = 18100
# GSK_PORT = 9999

def get_logger():
    logger = logging.getLogger('GSKTest')
    logger.setLevel(logging.DEBUG)
    formatter = logging.Formatter(
        '%(asctime)s %(name)s [%(levelname)s] <%(filename)s:%(lineno)d> %(funcName)s: %(message)s')
    ch = logging.StreamHandler()
    ch.setLevel(logging.DEBUG)
    ch.setFormatter(formatter)
    logger.addHandler(ch)
    return logger


logger = get_logger()

class Errors:
    errorMessages = {-1: "Timeout",
                    0x11: "Login Session Error",
    }

    @staticmethod
    def str_error(errno):
        try:
            msg = Errors.errorMessages[errno]
        except:
            msg = ""
        return "[ERROR] errno(%d) errmsg(%s)" % (errno, msg)


def pack_header(cmd, uin, clt):
    # fmt = "!HIHHHIIBiIBIHIBIIIII63x"
    fmt = "!HIHIHI42x"
    seq = random.randint(7, 81208878)
    channel = 1
    return struct.pack(fmt, cmd, uin, clt, seq , channel , 81208878)


def pack_package(head, body):
    fmt = "!BI%ds%dsB" % ( len(head), len(body) )
    return struct.pack(fmt, STX, struct.calcsize(fmt), head, body, ETX)


def get_package_body(data):
    stx, = struct.unpack("!B", data[0])
    etx, = struct.unpack("!B", data[-1])

    assert stx == STX and etx == ETX, "Wrong stx[%d] etx[%d]" % (stx, etx)
    pkg = data[1:-1]
    fmt = "!IHIHIHIB41x"
    pkg_len = struct.calcsize(fmt)
    ret = struct.unpack(fmt, pkg[:pkg_len])
    assert ret[0] == len(data), "HeaderLen[%d] != RecvDataLen[%d]" % (ret[0], len(data)  )
    return pkg[pkg_len:]


# def local_ip_addr(ifname="eth1"):
#     s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
#     return socket.inet_ntoa(fcntl.ioctl(s.fileno(),
#                                         0x8915, # SIOCGIFADDR
#                                         struct.pack('256s', ifname[:15]))[20:24])

def recv_package( cli ):
    data = ''
    pkg_len = 0
    while True:
        try:
            buf = cli.recv(4096) #接收数据
            data += buf

            if not len(buf):
                break

            if len(data) >= 65:
                    fmt = "!BIHIHIHIB41x"
                    header = struct.unpack(fmt, data[:struct.calcsize(fmt)])
                    assert header[0] == STX, "Wrong stx[%d] " % (header[0])
                    assert header[8] == 0, Errors.str_error(header[8])
                    pkg_len = header[1]
                    print header
            print len(data) , pkg_len
            if pkg_len > 0 and len(data) >= pkg_len:
                print "'\x1B[;35m[%d:%d]\x1B[0m' " % (len(data), pkg_len)
                break;

        except socket.error, e:
            print 'Error receiving data:%s' % e
            return None
    return data


class Command(object):
    """
    all cmd will derived from here
    recommend to derive Command to a personal MyCommand, then drive MyCommand to specified Commands.
    """

    def __init__(self, cmd, uin = -1 ):
        if uin < 0 :
            uin = getenv("GSK_UIN") or 0
    

        uin = int(uin)
      

        derived_frame = inspect.getouterframes(inspect.currentframe())[1][0]
        args_info = inspect.getargvalues(derived_frame)
        args_name = filter(lambda name: name != "self", args_info[0])
        args_values = args_info[3]
        self.head = pack_header(cmd, uin, 1)
        self.data = map(lambda name: args_values[name], args_name)
        self.pb_req_t_req = pb_gsk_req_pb2.pb_req_t_req()
        self.pb_req_t_req.f_cmd = cmd
        self.pb_clt_t_user = pb_gsk_pb2.pb_clt_t_user()

    def pack_body(self):
        pb = self.pb_req_t_req.SerializeToString()
        fmt = "!%ds" % ( len(pb) )
        return struct.pack(fmt, pb) 

    def unpack_body(self, body):
        try:
            self.pb_clt_t_user.Clear()
            self.pb_clt_t_user.ParseFromString(body)
            self.handle()
            return self.pb_clt_t_user
        except:
            return None        

    def handle(self):
        pass

    def pack(self):
        """
        called by Client()
        """
        body = self.pack_body()
        #assert body, "pack_body() should return a value"
        return pack_package(self.head, body)

    def unpack(self, data):
        """
        called by Client()
        """
        body = get_package_body(data)
        return self.unpack_body(body)

    def update(self, *data):
        """
        helper function
        """
        assert len(self.data) == len(data), "update() only accepts the same arguments as __init__()."
        self.data = data



class Client(object):
    def __init__(self, host_name, timeout=600):
        self.host = GSK_HOST.get(host_name, '106')
        self.port = GSK_PORT

        self.request_count = 0
        try:
            self.tcp = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            self.tcp.connect((self.host, self.port))
            self.tcp.settimeout(timeout)
        except socket.error, e:
            self.tcp = None
            print 'Error Connect socket:%s' % e
            sys.exit(1)


    def close( self ):
        self.tcp.close( )

    def request(self, cmd):
        assert isinstance(cmd, Command), "cmd should be an instance of Command"
        try:
            self.tcp.sendall(cmd.pack())
        except socket.error, e:
            print 'Error sending data:%s' % e
            sys.exit(1)

        data = recv_package( self.tcp )
        if data == None:
            print "Error recving data"
            sys.exit(1)
        if len(data) == 66:
            #print "PkgBody Length 0"
            pass

        return cmd.unpack(data)


def print_all_modules(dirpath):
    rets = []
    sys.path.append(dirpath)
    for f in os.listdir(dirpath):
        if f.startswith("Cmd0x") and f.endswith(".py"):
            root, ext = os.path.splitext(f)
            try:
                module = __import__(root)
                doc = module.__doc__
                if doc:
                    docs = doc.split("\n")
                    doc = docs[0] or docs[1] or docs[2] # ...
                else:
                    doc = "unknown"
                rets.append(root + "\t" + doc)
            except:
                pass
    sys.path.pop()
    print "\n".join(sorted(rets))


def main():
    dirpath = os.path.dirname(__file__)
    if len(sys.argv) > 1:
        module = sys.argv[1]
        sys.path.append(dirpath)
        try:
            m = __import__(module)
        except:
            try:
                m = __import__("Cmd" + module)
            except:
                try:
                    m = __import__("Cmd0x" + module)
                except:
                    m = None
        if m:
            help(m)
        else:
            logger.error("Specified command has NOT been implemented or you give the INCORRECT command.")
        sys.path.pop()
    else:
        print_all_modules(dirpath)


    if __name__ == "__main__":
        main()

