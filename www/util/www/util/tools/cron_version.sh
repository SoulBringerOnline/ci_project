#!/bin/sh
export WORKDIR=$(cd `dirname $0` && pwd)

source /etc/profile
cd ${WORKDIR}

MYSQL_HOST="192.168.164.199"
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASW=wx123456
MYSQL_DB=gsk_srv

VERSION_CONFIG_NEW="cfg_version.php.new"
VERSION_CONFIG="cfg_version.php"
VERSION_CONFIG_JS="config.js"

echo '<?php' > ${VERSION_CONFIG_NEW}
echo '$channel_version = array();'  >> ${VERSION_CONFIG_NEW}
#[PHPCONFIG]-------------------------------------------------------------------------------
#IOS
echo "" >> ${VERSION_CONFIG_NEW}
echo '$channel_version[2] = array();' >> ${VERSION_CONFIG_NEW}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_ios_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "\$channel_version[2][${CHANNEL}] = ${VERSION};"  >> ${VERSION_CONFIG_NEW}
done


#-------------------------------------------------------------------------------
#ANDROID
echo "" >> ${VERSION_CONFIG_NEW}
echo '$channel_version[3] = array();' >> ${VERSION_CONFIG_NEW}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_android_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "\$channel_version[3][${CHANNEL}] = ${VERSION};"  >> ${VERSION_CONFIG_NEW}
done

#-------------------------------------------------------------------------------
#PC
echo "" >> ${VERSION_CONFIG_NEW}
echo '$channel_version[4] = array();' >> ${VERSION_CONFIG_NEW}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_pc_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "\$channel_version[4][${CHANNEL}] = ${VERSION};"  >> ${VERSION_CONFIG_NEW}
done



#[JSCONFIG]-------------------------------------------------------------------------------
echo "" > ${VERSION_CONFIG_JS}
echo 'iosVersion = new Array();' >> ${VERSION_CONFIG_JS}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_ios_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "iosVersion['${CHANNEL}'] = ${VERSION}"  >> ${VERSION_CONFIG_JS}
done

echo "" >> ${VERSION_CONFIG_JS}
echo 'androidVersion = new Array();' >> ${VERSION_CONFIG_JS}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_android_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "androidVersion['${CHANNEL}'] = ${VERSION}"  >> ${VERSION_CONFIG_JS}
done

echo "" >> ${VERSION_CONFIG_JS}
echo 'pcVersion = new Array();' >> ${VERSION_CONFIG_JS}
mysql -h${MYSQL_HOST} -P${MYSQL_PORT} -u${MYSQL_USER} -p${MYSQL_PASW} ${MYSQL_DB} -Ne "SELECT f_id, f_pc_version  FROM t_channel" |
while read CHANNEL VERSION ;  do
echo "pcVersion['${CHANNEL}'] = ${VERSION}"  >> ${VERSION_CONFIG_JS}
done



MD5_NEW=`md5sum ${VERSION_CONFIG_NEW} | awk '{print $1}'`
MD5_OLD=`md5sum ${VERSION_CONFIG} | awk '{print $1}'`
if [ ! ${MD5_NEW} == ${MD5_OLD} ] ; then
	cp ${VERSION_CONFIG_NEW} ${VERSION_CONFIG}
	cp ${VERSION_CONFIG} ../config/
	echo "[VERSION PUBLISH] @ "`date`
	cd ../
	sh rsync.sh
fi

