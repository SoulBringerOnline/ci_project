FILE_NAME="define.lua"

echo "-- ---------------------------------------------------------------------------------------"  > ${FILE_NAME}
echo "-- [ These defines are generated from game_srv/src/common/data_def_ex.h ]"    >> ${FILE_NAME}
grep "="    data_def_ex.h   | grep -v "==" | sed 's/,//' | awk -f x.awk         >> ${FILE_NAME}

echo "-- ---------------------------------------------------------------------------------------"  >> ${FILE_NAME}
echo "-- [ These defines are generated from game_srv/src/common/cmd_def.h ]" >> ${FILE_NAME}
grep "CMD"  cmd_def.h       | grep -v "__YS_CMD_DEFINE_H__" | awk '{print $2 , "=" , $3 , " -- " , $4} ' | sed 's/\// /g' | awk -f x.awk  >> ${FILE_NAME}

cp ${FILE_NAME} ../../../client/Taal/scripts/common
