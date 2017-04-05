#ifndef __GSK_LOGGING_H__
#define __GSK_LOGGING_H__

#if defined(__APPLE__)




#include <CoreFoundation/CoreFoundation.h>
const int32_t BUFFER_SIZE = 512;

#define LOGI(format,...) do { \
char c[BUFFER_SIZE]; \
snprintf(c, BUFFER_SIZE, "[%s:%d]" format, __FUNCTION__, __LINE__, ##__VA_ARGS__); \
CFStringRef str = CFStringCreateWithCString(kCFAllocatorDefault, c, kCFStringEncodingMacRoman); \
CFShow(str); \
CFRelease(str); \
} while (0)

#elif defined(WIN32)
#define LOGI(format, ...)\
	printf( "[%s:%d]"##format##"\r\n",__FUNCTION__, __LINE__, __VA_ARGS__ );
#else

#include <android/log.h>
#define DEBUG_TAG "[GSKCNet]"
#define LOGI(...) \
((void)__android_log_print(ANDROID_LOG_DEBUG,DEBUG_TAG,__VA_ARGS__))
#endif // __ANDROID__



#endif