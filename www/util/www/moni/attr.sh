## -------------------- util.sh ----------------------------
## Author   :   yongsheng.zhao@lavaclan.com
## Date     :   2013-04-17
## ---------------------------------------------------------
export WORKDIR=$( cd ` dirname $0 ` && pwd )
cd "$WORKDIR" || exit 1
ulimit -c unlimited

ATTR_PHP_FILE="attr.php.bak"
ATTR_PHP_FILE_OL="attr.php"

echo "<?php" > ${ATTR_PHP_FILE}
echo "\$attr = array();" >> ${ATTR_PHP_FILE}
mysql -uroot -pwx123456 -h192.168.164.199 gsk_srv -N -e "SET NAMES utf8;SELECT * FROM t_attr;" | while read attr_id  attr_value ; do 
    echo "\$attr[${attr_id}] = '${attr_value}' ;"   >> ${ATTR_PHP_FILE}
done
echo "?>"  >> ${ATTR_PHP_FILE}
mv ${ATTR_PHP_FILE} ${ATTR_PHP_FILE_OL}


sh moni.sh
