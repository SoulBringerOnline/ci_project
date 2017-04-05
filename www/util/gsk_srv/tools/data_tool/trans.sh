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

max_version=`mysql -h${dbhost} -u${dbuser} -p${dbpswd} ${dbname} -N -e "SELECT MAX(f_id) FROM t_version;"`
echo "数据版本号:$max_version"
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
#将db文件做预处理
#############################################
function pre_process()
{
	debug "【生成】DB数据文件$sql_file"
	mysqldump -h${dbhost} -u${dbuser} -p${dbpswd} ${dbname} > $sql_file
	sed -i 's/^[ ]*//g' $sql_file 
	sed -i 's/)/ /g' $sql_file 
	sed -i 's/(/ /g' $sql_file 
	sed -i 's/`/ /g' $sql_file 
	sed -i 's/=/ /g' $sql_file 

	debug "【生成】预处理文件pre_file"
    awk 'BEGIN{IGNORECASE=1}{
	if( $1 == "create" )
	{
		num=0
		d=$3
	}
	else if( $2 == "int" || $2 == "tinyint" || $2 == "bigint" ||  $2 == "double" || $2 == "float"  ||  $2 == "varchar" || $2 == "text"  )
	{
		num++;
	}
	else if( $1 == "engine")
	{
		print "#fm", d, d , num
	}
    }'  $sql_file | sed 's/\_\w/\U&/' | sed 's/_//' > tmp
    
    sed_field tmp

    awk 'BEGIN{IGNORECASE=1}{
	if( $1 == "#fm")
	{
		table_info[$3]=$2
		table_fnum[$3]=$4
	}
	else if( $1 == "create" )
    {
		print "#TABLE_BEGIN____"
		table_name = $3
		print "#TYPE_TABLE____ " table_name " " table_info[table_name] " " table_fnum[table_name]
		idx = 0
    }
	else if( $1 == "engine")
    {
		print "#TABLE_END____ " table_name
    }

	else if( $2 == "int" || $2 == "tinyint" || $2 == "bigint")
    {
		idx++;

		if( $1 == "f_id" )
		{
			print ("#TYPE_FIELD____ " table_name " " table_info[table_name] " " idx " "$1 " uint64_t id " )
		}
		else
		{
		split($1, field_name, "_")
		len = length(field_name)
		c_field_name = "i"
		for(i = 2 ; i <= len ; i++)
		{
			c_field_name = c_field_name""toupper(substr(field_name[i],1,1))""substr(field_name[i],2)
		}
		if( $2 == "bigint" )
		{
			print ("#TYPE_FIELD____ " table_name " " table_info[table_name] " " idx " "$1 " int64_t " c_field_name )

		}
		else
		{
			print ("#TYPE_FIELD____ " table_name " " table_info[table_name] " " idx " "$1 " int " c_field_name )
		}
		}
        }
        else if( $2 == "double" || $2 == "float" )
        {				
		idx++;
		split($1, field_name, "_")
		len = length(field_name)
		c_field_name = "f"
		for(i = 2 ; i <= len ; i++)
		{
			c_field_name = c_field_name""toupper(substr(field_name[i],1,1))""substr(field_name[i],2)
		}
		print ("#TYPE_FIELD____ " table_name " " table_info[table_name] " " idx " " $1 " float " c_field_name )
    }
    else if( $2 == "text" || $2 == "varchar" )
	{		
		idx++
		split($1, field_name, "_")
		len = length(field_name)
		c_field_name = "str"
		for(i = 2 ; i <= len ; i++)
		{
			c_field_name = c_field_name""toupper(substr(field_name[i],1,1))""substr(field_name[i],2)
		}
		print ("#TYPE_FIELD____ " table_name " " table_info[table_name] " " idx " " $1 " string " c_field_name )
	}
	
	}' tmp $sql_file > $pre_file
	rm tmp $sql_file
}

#############################################
#替换字段（需要人工干预一下！！！）
#############################################
function sed_field()
{
	debug "【生成】人工干预数据生成步骤"
	#SEDINFO
    sed -i 's\tConfig\ConfigInfo\g' $1
    sed -i 's\tVersion\VersionInfo\g' $1
}
#######################################################################################################################################

function gen_cmd_map()
{
	debug "【生成】cmd_info"
 	cat head/cmd_info.head > cmd_info.h
	grep "#define" ../../src/common/cmd_def.h | sed 's/\/\// /' | awk '(NF == 4){print "		mapInfo.insert( pair< int , string >( "$2", \""$4"\" ) );" }'  >> cmd_info.h
	cat tail/cmd_info.tail >> cmd_info.h
}

#############################################
#generate data_def.h
#############################################
function gen_data_def()
{
	debug "【生成】data_def"
	cat head/data_def.head > data_def.h
	
	echo "" >> data_def.h
	echo "" >> data_def.h

	# awk '{
	# 	if( $1 == "#TYPE_TABLE____")
	# 	{	
			
	# 		print "#define " $5 , $6 ;
	# 	}
	# 	else if( $1 == "#TYPE_FIELD____" )
	# 	{
	# 		print "#define " $8 , $9 ;
	# 	}
	# }' $pre_file >> data_def.h
	
	echo "" >> data_def.h
	echo "" >> data_def.h


	awk 'BEGIN{IGNORECASE=1}{
		if($1 == "#TYPE_FIELD____")
		{
			print "    " $6 " " $7 " ;"
		}
		else if( $1 == "#TYPE_TABLE____" )
		{
			print "struct " $3 " {"
		}
		else if( $1 == "#TABLE_END____" )
		{
			print "} ;"
		}
	}'  $pre_file  >> data_def.h

	cat tail/data_def.tail >> data_def.h	
}

#############################################
#generate init_data.cpp
# black
#############################################
function gen_init_data()
{
	debug "【生成】data_init"
	cat head/data_init.head > data_init.h

	#go
	echo "//INIT_DATA" >> data_init.h
	awk '{
		{
			if( $1 == "#TYPE_TABLE____" )
			{
				field_num = $4+1
				table_name = $2
				print "void Init"$3"(CData<"$3"> *pstData)"
				print "{"			
				print "    pstData->clear();"
				print "    Mysql::MysqlData data;"
				print "    "$3 " stItem ;"

				info = "        //"field_num"个字段"
			}
			else if($1 == "#TYPE_FIELD____")
			{	
				if($6 == "string")
				{
					info = info"\n        stItem." $7 " = data[i][\"" $5 "\"];"
				}           
				else
				{
					info = info"\n        stItem." $7 " = Common::strto<"$6">(data[i][\"" $5 "\"]) ;"
				}
			}
			else if($1 == "#TABLE_END____")
			{
				if( srv_table[table_name] )
				{
					split( srv_table[table_name] , a , ":" )
					for( m = 1 ; m < length(a)+1 ; m++ )
					{
						tbl = a[m]
						split( tbl , tbl_info , "#" )
						srv_id=tbl_info[1]
						tbl_name = table_name"_"tbl_info[2]

						print ""
						print "    //"tbl_name
						print "    data = mysql.queryRecord(\" SELECT * FROM " tbl_name "; \");"
						print "    for(size_t i = 0; i < data.size(); i++)"
						print "    {"
						print info
						print "        pstData->SetNode(stItem.id, stItem, "srv_id");"
						print "    }"
					}
				}

				print ""
				print "    //"table_name
				print "    data = mysql.queryRecord(\" SELECT * FROM " table_name "; \");"
				print "    for(size_t i = 0; i < data.size(); i++)"
				print "    {"
				print info
				print "        pstData->SetNode(stItem.id, stItem);"
				print "    }"
				print "}"
			}
			else if( $1 == "#SRV_TABLE____" )
			{
				if( srv_table[$3] )
				{
					srv_table[$3] = $2"#"$4":"srv_table[$3];
				}
				else
				{
					srv_table[$3] = $2"#"$4;
				}
			}
		}
	}' $pre_file >> data_init.h
	cat tail/data_init.tail >> data_init.h
}	

function logic_init()
{
	debug "【生成】logic_init"
	cat head/logic_init.head > logic_init.h

	#--
	echo >> logic_init.h
	echo >> logic_init.h
	awk '{
			if( $1 == "#TYPE_TABLE____" )
			{
				print "CData<"$3"> *g_pst"$3" = new CData<"$3">();"
			}
	}' $pre_file >> logic_init.h
	#--
	echo >> logic_init.h

	#--
	echo >> logic_init.h
	echo "void LoadDataInfo( )" >> logic_init.h
	echo "{" >> logic_init.h
	echo "    LOG_PRINT(\".\");" >> logic_init.h
	awk '{
			if( $1 == "#TYPE_TABLE____" )
			{
				print "    Init"$3"( g_pst"$3" );"
				print "    LOG_PRINT(\"Init"$3" data size : %lu\",g_pst"$3"->size());"
			}
	}' $pre_file >> logic_init.h
    echo "}" >> logic_init.h
	echo >> logic_init.h

	cat tail/logic_init.tail >> logic_init.h	

}

function mv_data()
{
    mv data_def.h ../../src/common/
    mv data_init.h ../../src/common/
    mv cmd_info.h ../../src/common/
    mv logic_init.h ../../src/logic/gsk_logic_init.h
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


pre_process
gen_data_def
gen_init_data
gen_cmd_map
logic_init
mv_data
