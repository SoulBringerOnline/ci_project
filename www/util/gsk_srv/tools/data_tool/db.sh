sql_file="gsk.sql"

dbhost="192.168.128.128"
dbport=3306
dbuser="root"
dbpswd="zhaoys"
dbname="gsk_srv"

CUR_DIR=$( cd ` dirname $0 ` && pwd )
pre_file="pre_file"

COLORSTEP="\033[31m"
COLORDESC="\033[32;1m"
COLOREND="\033[0m"

mysqldump -h${dbhost} -u${dbuser} -p${dbpswd} ${dbname} > sql/$sql_file
