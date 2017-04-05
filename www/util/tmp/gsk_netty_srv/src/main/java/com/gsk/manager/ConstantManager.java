package com.gsk.manager;

/**
 * 常量管理器
 * 
 * @author yongshengzhao@vip.qq.com
 * 
 */
public class ConstantManager {
	// =============================Pkg===========================================
	public final static byte STX = 0x2;
	public final static byte ETX = 0x3;
	
	public final static int GSK_PKG_HEADER_SIZE = 64;
	public final static int GSK_PKG_MIN_SIZE = 66;

	// =============================用户信息===========================================
	public final static int USER_INFO_BASEINFO = 0x0001;
	public final static int USER_INFO_RELEATION = 0x0002;
	
	// =============================日志===========================================
	public final static String LOG_SEPARATE = " - ";// 日志分隔符
	public final static String LOG_SEPARATE_PARAMS = "|";// 日志参数分隔符

	public final static String SERVER_INFO = "server_info" + LOG_SEPARATE;// 服务器信息
	public final static String CLIENT_SERVER_INFO = "client_server_info" + LOG_SEPARATE;// 客户端服务器信息
	public final static String CLIENT_CONNECTED = "client_connected" + LOG_SEPARATE;// 客户端建立连接
	public final static String CLIENT_DISCONNECTED = "client_disconnected" + LOG_SEPARATE;// 客户端断开连接
	public final static String CLIENT_IDLE_TIMEOUT = "client_idle_timeout" + LOG_SEPARATE;// 客户端空闲超时
	public final static String CLIENT_ILLEGAL = "client_Illegal" + LOG_SEPARATE;// 客户端非法
	public final static String CLIENT_UNKNOWN_EVENT = "client_unknown_event" + LOG_SEPARATE;// 客户端不识别的事件
	public final static String CLIENT_ERROR = "client_error" + LOG_SEPARATE;// 客户端错误
	public final static String CLIENT_NO_HANDLER = "client_no_handler" + LOG_SEPARATE;// 没有对应的处理器

}
