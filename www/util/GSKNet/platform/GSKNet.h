#ifndef __GSK_NET_WRAPPER_H__
#define __GSK_NET_WRAPPER_H__


#if defined(ANDROID)
#include "android/GSKNet_android.h"
#elif (defined(TARGET_OS_IPHONE) || defined(TARGET_IPHONE_SIMULATOR))
#include "iOS/GSKNet_ios.h"
#else
#error
#endif
#endif