#ifndef _INCLUDED_CRYPT_H_
#define _INCLUDED_CRYPT_H_


unsigned long udc_crc32(
                    unsigned long crc,
                    const unsigned char* buf,
                    int len
                    );

#endif
