/* ======================================================================
 * gsk project
 *
 * ----------------------------------------------------------------------
 * Author 	: 	yongshengzhao@vip.qq.com
 * Date   	: 	2014-08-15
 * 
 * 
 * ======================================================================*/


#ifndef __YS_MACRO_H__
#define __YS_MACRO_H__


#define ADD_CHAR_RET(ppCur, piLen, pDst) ({ if (AddChar((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-200);} })
#define GET_CHAR_RET(ppCur, piLen, pDst) ({ if (GetChar((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-100); } })

#define ADD_WORD_RET(ppCur, piLen, pDst) ({ if (AddWord((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-201); } })
#define GET_WORD_RET(ppCur, piLen, pDst) ({ if (GetWord((char**)(ppCur), (piLen), (pDst)) < 0) {LOG_BUG_RET(-101); } })

#define ADD_DWORD_RET(ppCur, piLen, pDst) ({ if (AddDWord((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-202); } })
#define GET_DWORD_RET(ppCur, piLen, pDst) ({ if (GetDWord((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-102); } })

#define ADD_DDWORD_RET(ppCur, piLen, pDst) ({ if (AddDDWord((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-203); } })
#define GET_DDWORD_RET(ppCur, piLen, pDst) ({ if (GetDDWord((char**)(ppCur), (piLen), (pDst)) < 0) { LOG_BUG_RET(-103); } })

#endif
