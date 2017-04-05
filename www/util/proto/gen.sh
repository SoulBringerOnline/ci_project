#!/bin/sh

CUR_DIR=$( cd ` dirname $0 ` && pwd )
pre_file="pre_file"

COLORSTEP="\033[31m"
COLORDESC="\033[32;1m"
COLOREND="\033[0m"

echo "------------------------------------"

G_STEP=1
G_TIME=`date +%s`

GIT_IOS_TRUNK="/Users/zhaoys/WorkStation/git/gsk/ios"
GIT_SRV_TRUNK="/Users/zhaoys/WorkStation/git/gsk/gsk-logic-server"
GIT_ANDROID_TRUNK="/Users/zhaoys/WorkStation/git/gsk/android"
GIT_API_TRUNK="/Users/zhaoys/WorkStation/git/gsk/api"
WWW_TRUNK="/Users/zhaoys/WorkStation/git/gsk/www"
PROTO_TRUNK="/Users/zhaoys/WorkStation/git/gsk/proto"

CUR_USER="zhaoys"

function pull()
{
    cd $1
    echo $1
    git pull
    cd ${CUR_DIR}
}

svn up
pull ${GIT_SRV_TRUNK} 
pull ${GIT_API_TRUNK} 
pull ${GIT_ANDROID_TRUNK} 
pull ${GIT_IOS_TRUNK} 

function debug()
{
    TMP_TIME=`date +%s`
    d=`expr ${TMP_TIME} - ${G_TIME}`
    echo -e  "${COLORSTEP}${G_STEP}、${COLOREND}  ${d} ${COLORDESC}$1${COLOREND} "
    ((G_STEP=${G_STEP}+1))
}

#############################################
function do_gen_proto()
{    
    pb_pre="pb_"$1"_"
    pb_def_file=$2
    pb_file_proto=$3

    if [ -f $pb_def_file ] ; then
        debug "【生成】${pb_file_proto}数据生成"
        awk -v pb_pre=$pb_pre 'BEGIN{IGNORECASE=1;print "// gsk_"pb_pre"data;";}
        {
            if( substr($1,0,2) == "//" )
                {
                    print $0
                }
            else if( $1 == "#__MESSAGE_BEGIN__" )
                {   
                    i = 0
                    print "message " pb_pre "" $2
                    print "{"
                }
            else if( $1 ==  "#__MESSAGE_FILED__") 
                {
                    i++
                    pre="optional"

                    if( $2 == "int" )
                        {
                            print "    " pre " int32 " $3 " = " i "; //"$4
                        }			
                    else if( $2 == "uint" )
                        {
                            print "    " pre " uint32 " $3 " = " i "; //"$4
                        }	
                    else if( $2 == "bool" )
                        {
                            print "    " pre " bool " $3 " = " i "; //"$4
                        }		
                    else if( $2 == "double" )
                        {
                            print "    " pre " double " $3 " = " i "; //"$4
                        }
                    else if( $2 == "long" )
                        {
                            print "    " pre " uint64 " $3 " = " i "; //"$4
                        }
                    else if( $2 == "string" )
                        {
                            print "    " pre " string " $3 " = " i "; //"$4
                        }
                    else if( $2== "bytes" )
                        {
                            print "    " pre " bytes " $3 " = " i "; //"$4
                        }
                    else if( $2 == "array" )
                        {
	            if($3 == "int")
                {
                    print "    repeated int32  " $4  " = " i "; //"$5
                }
	            else if( $3 == "uint" )
                {
                    print "    repeated uint32 " $4 " = " i "; //"$5
                }
                else if( $3 == "bool" )
                {
                    print "    repeated bool " $4 " = " i "; //"$5
                }
	            else if( $3 == "double" )
                {
                    print "    repeated double " $4 " = " i "; //"$5
                }
	            else if( $3 == "long" )
                {
                    print "    repeated uint64 " $4 " = " i "; //"$5
                }
	            else if( $3 == "string" )
                {
                    print "    repeated string " $4 " = " i "; //"$5
                }
                else if( $3 == "bytes" )
                {
                    print "    repeated bytes " $4 " = " i "; //"$5
                }
	            else
                {
                    print "    repeated " $3 "  " $4  " = " i "; //"$5
                }	
	        }
            else
            {
                    print "    " pre " " $2 "  " $3 " = " i "; //"$4 
            }
        }
        else if( $1 == "#__MESSAGE_END__" )
        {	
            print "}"
            print ""
        }
    }' $pb_def_file >> $pb_file_proto
    fi
}

function gen_proto()
{
    cd fmt 
    sh fmt.sh
    cd ${CUR_DIR}

    ls def | while read d ; 
    do
        dir_name=${d}
        proto_name="pb_gsk_"${dir_name}".proto"
        if [[ $dir_name == "gsk" ]] ; then
            proto_name="pb_gsk.proto"
        fi
        cat head/gsk_proto.head > ${proto_name}

        ls def/${dir_name} | while read def_file ; 
        do
            prefix=`echo ${def_file} | sed 's/^def_//'`
            do_gen_proto "${prefix}" "def/${dir_name}/${def_file}" "${proto_name}"
        done

        cat tail/gsk_proto.tail >> ${proto_name}
        awk -f fmt.awk ${proto_name} > ${proto_name}.tmp && mv ${proto_name}.tmp ${proto_name}
        ProtoName=`echo ${proto_name} | sed 's/_\w/\U&/g' | sed 's/pb_/Pb/g' | sed 's/_//g'`
        cat ${proto_name} | sed "s/_t_/_/g"  | sed "s/ f_/ _/" | sed 's/\_\w/\U&/g' | sed 's/ pb_/ Pb/g' | sed 's/_//g' > ${ProtoName}
        cp ${proto_name} ${CUR_DIR}/proto/cpp/ 
        cp ${proto_name} ${CUR_DIR}/proto/py/
        cp ${ProtoName} ${CUR_DIR}/proto/java/ 
        cp ${ProtoName} ${CUR_DIR}/proto/oc/
        rm *.proto
    done
}

function do_protoc()
{
    cd ${CUR_DIR}/proto/cpp 
    ls *.proto  | while read p ;  do
        protoc -I=. --cpp_out=.  ${p}
    done

    cd ${CUR_DIR}/proto/java 
    ls *.proto  | while read p ;  do
        protoc -I=. --java_out=.  ${p}
    done

    cd ${CUR_DIR}/proto/oc 
    ls *.proto  | while read p ;  do
        protoc -I=. --objc_out=.  ${p}
    done
    sed -i  's/#import <ProtocolBuffers\/ProtocolBuffers.h>/#import "ProtocolBuffers.h"/g' *.h

    cd ${CUR_DIR}/proto/py 
    ls *.proto  | while read p ;  do
        protoc -I=. --python_out=.  ${p}
    done
    protoc -I=$CUR_DIR/proto/java --java_out=$CUR_DIR/proto/java  $CUR_DIR/proto/java/${p}

    cd ${CUR_DIR}
}


function sync_proto()
{
    u=`users`
    if [ $u == ${CUR_USER} ] ; then

        oc_proto="proto/oc/*"
        cp ${oc_proto} ${GIT_IOS_TRUNK}/GSK/GSK/Classes/Proto

        #cpp_proto="proto/cpp/*"
        # cp ${cpp_proto} ../log/src
        # cp ${cpp_proto} ../gsk_srv/src/common
        # py_proto="proto/py/*"
        # cp ${py_proto} ../api/proto

        cd proto/java/
        ls *.java | while read f;
        do
            echo "package com.gsk.protobuf;" > ${GIT_SRV_TRUNK}/src/main/java/com/gsk/protobuf/${f}
            cat ${f} >> ${GIT_SRV_TRUNK}/src/main/java/com/gsk/protobuf/${f}

            echo "package com.grandsoft.gsk.core.packet.base;" > ${GIT_ANDROID_TRUNK}/GSK/src/com/grandsoft/gsk/core/packet/base/${f}
            cat ${f} >> ${GIT_ANDROID_TRUNK}/GSK/src/com/grandsoft/gsk/core/packet/base/${f}
        done
        cd ${CUR_DIR}

    fi
}

function gen_cmd()
{
    u=`users`
    if [ $u == ${CUR_USER} ] ; then

    JAVA_FILE=${GIT_SRV_TRUNK}/src/main/java/com/gsk/manager/CommandManager.java
    OC_FILE=${GIT_IOS_TRUNK}/GSK/GSK/Classes/NewModel/GSKCmd.h
    ANDROID_FILE=${GIT_ANDROID_TRUNK}/GSK/src/com/grandsoft/gsk/common/GSKCmd.java
    PY_FILE=${GIT_API_TRUNK}/GSKTestCmd.py
    JAVA_CMDMAP_FILE=${GIT_SRV_TRUNK}/src/main/java/com/gsk/manager/CommandDescManager.java
    PHP_FILE=${WWW_TRUNK}/cmd.php
    CPP_FILE=${PROTO_TRUNK}/proto/cpp/GSKCmd.h

    #ANDROID
    sed 's/package com.gsk.manager;/package com.grandsoft.gsk.common;/g' ${JAVA_FILE} | sed 's/CommandManager/GSKCmd/g' > ${ANDROID_FILE}
    
    #PY
    echo "#!/usr/bin/env python" > ${PY_FILE}
    echo "# -*- coding: utf-8; tab-width: 4; -*-" >> ${PY_FILE}
    echo "CMD = {" >> ${PY_FILE}

    cat ${JAVA_FILE} | sed 's/=/ /g' | sed 's/;/  /g' | awk '{
        if( substr($5,0,4) == "CMD_" )
        {
            printf( "    \"%s\" ",$5 )
            space_len = 40 - length( $5 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " : %s , #%s \n",$6, $7 )
        }
    }' >> ${PY_FILE}

    echo "" >> ${PY_FILE}
    echo "};" >> ${PY_FILE}

    #PHP
    echo "<?php" > ${PHP_FILE}
    echo "\$CMD = array();" >> ${PHP_FILE}

    cat ${JAVA_FILE} | sed 's/=/ /g' | sed 's/;/  /g' | awk '{
        if( substr($5,0,4) == "CMD_" )
        {
            printf( "    $CMD[%d] = ",$6 )
            space_len = 40 - length( $6 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            len = length($7)
            printf( " \"%s\" ;\n", substr($7,3,len-3) )
        }
    }' >> ${PHP_FILE}

    echo "" >> ${PHP_FILE}
    echo "?>" >> ${PHP_FILE}

    #CPP
    echo "#ifndef __GSK_CMD_H__" > ${CPP_FILE}
    echo "#define __GSK_CMD_H__" >> ${CPP_FILE}
    echo "" >> ${CPP_FILE}
    cat ${JAVA_FILE} | sed 's/=/ /g' | sed 's/;/  /g' | awk '{
        if( substr($1,0,3) == "//-" )
        {
            print ""
            print $1
        }
        else if( substr($5,0,4) == "CMD_" )
        {
            printf( "",$7 )
            printf( "#define    %s ",$5 )
            space_len = 40 - length( $5 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " %s    %s\n",$6, $7)
        }
    }' >> ${CPP_FILE}

    echo "" >> ${CPP_FILE}
    echo "#endif" >> ${CPP_FILE}
    cd ${CUR_DIR}



    #OC
    echo "#ifndef GSK_CMD_H" > ${OC_FILE}
    echo "#define GSK_CMD_H" >> ${OC_FILE}
    echo "#import \"GSKNet.h\"" >> ${OC_FILE}
    echo "#import \"GSKDataModel.h\"" >> ${OC_FILE}
    echo "" >> ${OC_FILE}
    echo "typedef NS_ENUM(NSInteger, GSKNetCmd) {" >>  ${OC_FILE}
    cat ${JAVA_FILE} | sed 's/=/ /g' | sed 's/;/  /g' | awk '{
        if( substr($1,0,3) == "//-" )
        {
            print ""
            print $1
        }
        else if( substr($5,0,4) == "CMD_" )
        {
            printf( "%s ",$5 )
            space_len = 40 - length( $5 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " = %s , %s \n",$6, $7 )
        }
    }' >> ${OC_FILE}

    echo "" >> ${OC_FILE}
    echo "};" >> ${OC_FILE}
    echo "#endif" >> ${OC_FILE}
    cd ${CUR_DIR}
    fi
}


function gen_enum()
{
    u=`users`
    if [ $u == ${CUR_USER} ] ; then

    JAVA_FILE=${GIT_SRV_TRUNK}/src/main/java/com/gsk/manager/EnumManager.java
    OC_FILE=${GIT_IOS_TRUNK}/GSK/GSK/Classes/NewModel/GSKEnum.h
    ANDROID_FILE=${GIT_ANDROID_TRUNK}/GSK/src/com/grandsoft/gsk/common/GSKEnum.java
    CPP_FILE=${PROTO_TRUNK}/proto/cpp/GSKEnum.h

    #ANDROID
    echo "package com.grandsoft.gsk.common;" > ${ANDROID_FILE}
    echo "" >> ${ANDROID_FILE}
    echo "public class GSKEnum {" >>  ${ANDROID_FILE}
    cat ${JAVA_FILE} | sed 's/(/ /g' | sed 's/)/ /g' | sed 's/;/  /g' | sed 's/,/  /g' | sed 's/\/\/ /\/\//g' |  awk '{

        if( $3 == "enum" )
        {
            print "// -----------------------"$4
        }
        else if( substr($1,0,2) == "//" )
        {
            printf( "\n%s\n",$1 )
        }
        else if( substr($1,0,2) == "E_" )
        {
            printf( "    public final static int %s ",$1 )
            space_len = 60 - length( $1 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            if( substr($3,0,2) == "//"  )
            printf( " = %s ; %s",$2, $3 )
            else
            printf( " = %s ; ",$2 )
            printf( "\n" )

        }
    }' >> ${ANDROID_FILE}

    echo "" >> ${ANDROID_FILE}
    echo "};" >> ${ANDROID_FILE}



    #CPP
    echo "#ifndef __GSK_ENUM_H__" > ${CPP_FILE}
    echo "#define __GSK_ENUM_H__" >> ${CPP_FILE}
    echo "" >> ${CPP_FILE}
    cat ${JAVA_FILE} | sed 's/(/ /g' | sed 's/)/ /g' | sed 's/;/  /g' | sed 's/,/  /g' | sed 's/\/\/ /\/\//g'| awk '{
        if( $3 == "enum" )
        {
            print "// -----------------------"$4
        }
        else if( substr($1,0,2) == "//" )
        {
            printf( "\n%s\n",$1 )
        }
        else if( substr($1,0,2) == "E_" )
        {
            printf( "#define    %s ",$1 )
            space_len = 40 - length( $1 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            if( substr($3,0,2) == "//"  )
            printf( "  %s  %s",$2, $3 )
            else
            printf( "  %s  \n",$2 )
            printf( "\n" )
        }
    }' >> ${CPP_FILE}

    echo "" >> ${CPP_FILE}
    echo "#endif" >> ${CPP_FILE}
    cd ${CUR_DIR}

    #OC
    echo "#ifndef GSK_ENUM_H" > ${OC_FILE}
    echo "#define GSK_ENUM_H" >> ${OC_FILE}
    echo "" >> ${OC_FILE}
    echo "typedef NS_ENUM(NSInteger, GSKEnum) {" >>  ${OC_FILE}
    cat ${JAVA_FILE} | sed 's/(/ /g' | sed 's/)/ /g' | sed 's/;/  /g' | sed 's/,/  /g' | sed 's/\/\/ /\/\//g'| awk '{

        if( $3 == "enum" )
        {
            print "// -----------------------"$4
        }
        else if( substr($1,0,2) == "//" )
        {
            printf( "\n%s\n",$1 )
        }
        else if( substr($1,0,2) == "E_" )
        {
            printf( "%s ",$1 )
            space_len = 40 - length( $1 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            if( substr($3,0,2) == "//"  )
            printf( " = %s , %s",$2, $3 )
            else
            printf( " = %s , \n",$2 )
            printf( "\n" )
        }
    }' >> ${OC_FILE}

    echo "" >> ${OC_FILE}
    echo "};" >> ${OC_FILE}
    echo "#endif" >> ${OC_FILE}
    cd ${CUR_DIR}
    fi
}






function gen_errno()
{
    u=`users`
    if [ $u == ${CUR_USER} ] ; then

    JAVA_FILE=${GIT_SRV_TRUNK}/src/main/java/com/gsk/manager/ErrorCodeManager.java
    OC_FILE=${GIT_IOS_TRUNK}/GSK/GSK/Classes/NewModel/GSKErrno.h
    ANDROID_FILE=${GIT_ANDROID_TRUNK}/GSK/src/com/grandsoft/gsk/common/GSKErrno.java

    #ANDROID
    sed 's/package com.gsk.manager;/package com.grandsoft.gsk.common;/g' ${JAVA_FILE} | sed 's/ErrorCodeManager/GSKErrno/g' > ${ANDROID_FILE}
    
    #OC
    echo "#ifndef GSK_ERRNO_H" > ${OC_FILE}
    echo "#define GSK_ERRNO_H" >> ${OC_FILE}
    echo "" >> ${OC_FILE}
    echo "typedef NS_ENUM(NSInteger, GSKErrno) {" >>  ${OC_FILE}
    cat ${JAVA_FILE} | sed 's/=/ /g' | sed 's/;/  /g' | awk '{
        if( substr($1,0,3) == "//-" )
        {
            print ""
            print $1
        }
        else if( substr($5,0,6) == "ERROR_" )
        {
            printf( "%s ",$5 )
            space_len = 40 - length( $5 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " = %s , %s \n",$6, $7 )
        }
    }' >> ${OC_FILE}

    echo "" >> ${OC_FILE}
    echo "};" >> ${OC_FILE}
    echo "#endif" >> ${OC_FILE}
    cd ${CUR_DIR}
    fi
}



################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################
################################################################################################################

gen_proto
do_protoc
sync_proto
gen_cmd
gen_enum
gen_errno





