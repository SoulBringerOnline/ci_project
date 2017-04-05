SRC_PATH="./"
DST_PATH="/data/gsk_srv/"

#HOST="203.195.182.51"
HOST="192.168.164.200"

if [ $# -ge 1 ]
then
    if [ $1 -eq "199" ]
    then
        HOST="192.168.164.199"
    elif [ $1 -eq "200" ]
    then
        HOST="192.168.164.200"
    fi
fi

TO_PATH="root@"${HOST}":"${DST_PATH}
echo "---------------------------------------------"
echo "[部署至] ${TO_PATH}"
echo "---------------------------------------------"
while true
do
    echo "[0] ALL"
    echo "[1] gsk_gate"
    echo "[2] gsk_logic"
    echo "[4] gsk_push_*"
    echo "[5] "
    echo "[6] gsk_log"
    echo "[7] gsk_mq"
    echo "[9] lua"
    echo "----------------------------"
    read  -p "PLEASE SELECT : " type
    case $type in
        1)
        f="gsk_gate"
        ;;
        2)
        f="gsk_logic"
        ;;
        3)
        f=""
        ;;
        4)
        f="gsk_push_*"
        ;;
        5)
        f=""
        ;;
        6)
        f="gsk_log"
        ;;
        7)
        f="gsk_mq"
        ;;
        8)
        f=""
        ;;
        6)
        f=""
        ;;
        9)
        f=""
        ;;
        0)
        f="gsk_gate gsk_logic gsk_push_gate gsk_push_sync"
        ;;
    esac

    echo "---------------------------------------------"
	echo $f
    echo "---------------------------------------------"

	for b in $f
	do
		echo "$b [FROM]:${SRC_PATH} [TO]:${TO_PATH}"
		scp ${SRC_PATH}/$b ${TO_PATH}/deploy/
	done

    type=-1
	echo "---------------------------------------------"
done
