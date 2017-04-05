dbhost="192.168.1.107"
dbport=3306
dbuser="root"
dbpswd="lavaclan"
dbname="taal"
dbnewname="taal_tmp"

CUR_DIR=$( cd ` dirname $0 ` && pwd )
mysql -h${dbhost} -u${dbuser} -p${dbpswd} ${dbname} -N -e "SELECT f_type, f_name FROM t_building;" | sort | uniq | 
while read f_type f_name ; do
    echo "g_mapBuildingName[${f_type}]=\"${f_name}\";"
done
