#ifndef __GSK_NET_IOS_WRAPPER_H__
#define __GSK_NET_IOS_WRAPPER_H__

#import "PbGsk.pb.h"
#import "PbGskReq.pb.h"

#define GSK_NET_DEFAULT_TIMEOUT 3

typedef void (^GSKNetRequestCallback)(NSInteger code);
typedef void (^GSKPushCallback)();


@protocol GSKNetDelegate <NSObject>

- (void)initModelWithPb:(PbCltUser *)pPbCltUser isPush:(BOOL)isPush;

@end


@interface GSKNet : NSObject

+ (GSKNet *) sharedInstance;
+ (void) open:(NSString*)ip port:(NSInteger)port;
+ (void) initGSKNetWithModelDelegate:(id<GSKNetDelegate>)delegate ip:(NSString *)ip port:(int)port version:(int)version;
+ (void) setHeartBeatCallback:(int)iUin callback:(GSKNetRequestCallback)callback;
+ (void) stopHeartBeat;
+ (void) setChannel:(int)channel;
+ (void) closeGSKNet;
+ (int) getLastRspTime;

/**
 @param iUin: user id
 @param iCmd: cmd
 @param timeout: 超时
 @param pbReq: 请求pb
 @param modelDelegate: 如果传入model，网络层调用InitModelWithPb初始化model
 @param block: 回调
 */
+ (int) request:(int)iUin iCmd:(int)iCmd timeout:(int)iTimeOut pbReq:(PbReqReq *)pbReq block:(GSKNetRequestCallback)callback;

+ (int) requestWithDefaultTimeout:(int)iUin iCmd:(int)iCmd pbReq:(PbReqReq *)pbReq block:(GSKNetRequestCallback)callback;

@end

#endif