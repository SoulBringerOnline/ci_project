#!/bin/sh

CUR_DIR=$( cd ` dirname $0 ` && pwd )
pre_file="pre_file"

COLORSTEP="\033[31m"
COLORDESC="\033[32;1m"
COLOREND="\033[0m"

echo "------------------------------------"

G_STEP=1
G_TIME=`date +%s`

function debug()
{
	TMP_TIME=`date +%s`
    d=`expr ${TMP_TIME} - ${G_TIME}`
    echo -e  "${COLORSTEP}${G_STEP}、${COLOREND}  ${d} ${COLORDESC}$1${COLOREND} "
    ((G_STEP=${G_STEP}+1))
}

#############################################

function fmt()
{
    DEF_DIR="../def"
    ls ${DEF_DIR} | while read d ; 
    do
        DIR_NAME=${d}
        ls ${DEF_DIR}/${DIR_NAME} | while read DEF_FILE ; 
        do
          echo ${DEF_FILE}
          awk -f fmt.awk ${DEF_DIR}/${DIR_NAME}/${DEF_FILE} > ${DEF_FILE} 
          mv ${DEF_FILE} ${DEF_DIR}/${DIR_NAME}/${DEF_FILE}
        done
    done
}

fmt






