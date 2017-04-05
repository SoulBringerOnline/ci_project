#import "GSKCNet.h"
#import "GSKNet_ios.h"
#include <vector>
#import "Reachability.h"
#import "DebugDataModel.h"
@implementation GSKNet

static id<GSKNetDelegate> modelDelegate_;

static GSKPushCallback g_pushCallback;

static dispatch_queue_t g_processQueue;

static int g_iVersion;

+ (void) initGSKNetWithModelDelegate:(id<GSKNetDelegate>)delegate ip:(NSString *)ip port:(int)port version:(int)version
{
    g_iVersion = version;
    g_processQueue  = dispatch_queue_create("gsk_net_process_queue", nil);
    modelDelegate_ = delegate;
    
    GSKCNet::sharedInstance()->SetPushMsgHandler([=](__GSKNetArg stArg) {
        GSKLog(@"[PUSH MSG] len:%ld", stArg.strMsg.size());
        
        NSData *data = [NSData dataWithBytes:stArg.strMsg.data() length:stArg.strMsg.size()];
        PbCltUser *l_pb = nil;
        @try {
            l_pb = [PbCltUser parseFromData:data];
        }
        @catch (NSException *exception) {
            NSLog(@"解包失败");
        }
        @finally {
            if (!l_pb) {
                return ;
            }
            dispatch_async(g_processQueue, ^{
                if (modelDelegate_)
                    [modelDelegate_ initModelWithPb:l_pb isPush:YES];
            });
        }
    });
    
    GSKCNet::sharedInstance()->Open( [ip UTF8String]  , (int)port, g_iVersion );
}

+ (GSKNet *)sharedInstance {
    static GSKNet *s_pInstance = nil;
    static dispatch_once_t pred;
    dispatch_once(&pred, ^{
        s_pInstance = [[self alloc] init];
    });
    return s_pInstance;
}

+ (void) open:(NSString*)ip port:(NSInteger)port {
    GSKCNet::sharedInstance()->Open( [ip UTF8String]  , (int)port, g_iVersion);
}

+ (int)request:(int)iUin iCmd:(int)iCmd timeout:(int)iTimeOut pbReq:(PbReqReq *)pbReq block:(GSKNetRequestCallback)callback
{
    PbReqReportBuilder * report = [PbReqReport builder];
    NSString * phoneInfo    = [[UIDevice currentDevice] userModel];
    NSString * phoneVersion = [[UIDevice currentDevice] userSystemVersion];
    NSString * phoneCarrier = [[UIDevice currentDevice] carrierName];
    NSString * stateNet = @"";
    Reachability *r = [Reachability reachabilityWithHostName:@ "www.baidu.com"];
    
    
    switch  ([r currentReachabilityStatus])
    {
        case  NotReachable:
            // 没有网络连接
            stateNet = @"NotReach";
            break ;
        case  ReachableViaWWAN:
            stateNet = @"WWAN";
            // 使用3G网络
            break ;
        case  ReachableViaWiFi:
            stateNet = @"WiFi";
            // 使用WiFi网络
            break ;  
    }
    
    report.phoneInfo  = phoneInfo;
    report.os         = phoneVersion;
    
    if(phoneCarrier && ![phoneCarrier isEqualToString:@""]){
        report.sp         = phoneCarrier;
    }else {
        report.sp         = @"NoSimCard";
    }
    
    report.network    = stateNet;
    
    PbReqReport * pbReport = [report build];
    
    pbReq = [[[pbReq toBuilder] setReport:pbReport] build];
    
    std::string strBody((const char *)[pbReq.data bytes], [pbReq.data length]);
    [[DebugDataModel sharedInstance] insertTcpInfoList:[NSString stringWithFormat:@"[request] cmd:%d hex:%x",iCmd,iCmd]];
    [[DebugDataModel sharedInstance] insertTcpDetailList:[NSString stringWithFormat:@"request cmd:%d hex:%x\n%@",iCmd,iCmd,pbReq]];
    return GSKCNet::sharedInstance()->Request(iUin, iCmd, iTimeOut, strBody, [=](__GSKNetArg stArg) {
        GSKLog(@"request callback size:%ld code:%d", stArg.strMsg.size(), stArg.iCode);


        NSData *data = [NSData dataWithBytes:stArg.strMsg.data() length:stArg.strMsg.size()];
        PbCltUser *l_pb = nil;
        @try {
             l_pb = [PbCltUser parseFromData:data];
        }
        @catch (NSException *exception) {
            GSKLog(@"#####parseFromData error! uin:%d cmd:0x%x", iUin, iCmd);
        }
        [[DebugDataModel sharedInstance] insertTcpInfoList:[NSString stringWithFormat:@"[response] cmd:%d hex:%x status:%d",iCmd,iCmd,stArg.iCode]];
        [[DebugDataModel sharedInstance] insertTcpDetailList:[NSString stringWithFormat:@"responsecmd:%d hex:%x\n%@",iCmd,iCmd,l_pb]];
        NSLog(@"%@",[NSString stringWithFormat:@"responsecmd:%d hex:%x\n%@",iCmd,iCmd,l_pb]);
        // 已经在子线程中
        if (modelDelegate_)
        {
            dispatch_async(g_processQueue, ^{
                if (modelDelegate_)
                    [modelDelegate_ initModelWithPb:l_pb isPush:NO];
                if (callback) {
                    callback(stArg.iCode);
                }
            });
        }
    });
}

+ (int)requestWithDefaultTimeout:(int)iUin iCmd:(int)iCmd pbReq:(PbReqReq *)pbReq block:(GSKNetRequestCallback)callback
{


    return [self request:iUin iCmd:iCmd timeout:GSK_NET_DEFAULT_TIMEOUT pbReq:pbReq block:callback];
}

+ (void)setHeartBeatCallback:(int)iUin callback:(GSKNetRequestCallback)callback
{
    GSKCNet::sharedInstance()->SetHeartBeatHandler([=](__GSKNetArg stArg) {
        GSKLog(@"[HEARTBEAT] code:%d", stArg.iCode);
        if (callback)
        {
            dispatch_async(dispatch_get_main_queue(), ^{
                callback(stArg.iCode);
            });
        }

    });
    
    GSKCNet::sharedInstance()->StartHeartBeat(iUin);
}

+ (void)stopHeartBeat
{
    GSKCNet::sharedInstance()->StopHeartBeat();
}

+ (void)setChannel:(int)channel
{
    GSKCNet::sharedInstance()->SetChannel(channel);
}

+ (void)closeGSKNet
{
    GSKCNet::sharedInstance()->Close();
}

+ (int)getLastRspTime
{
    return GSKCNet::sharedInstance()->GetTime();
}

@end



