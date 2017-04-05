#!/bin/sh

DEF_FILE=../../../proto/def/def_user

awk '{
	can_process = 0
	if( $1 == "#__MESSAGE_BEGIN__" )
	{
		handle = $2
		print ""
		print "//"$2
	}
	else if( $1 == "#__MESSAGE_END__" )
	{
		handle = ""
	}
	else if( $1 == "#__MESSAGE_FILED__" )
	{
		can_process = 1
	}

	pb_pre = ""
	if( handle == "t_info" )
	{
		pb_pre = "l_pb_user_t_user.mutable_f_info()"
		bo_item = "pDoc"
	}
	else
	{
		pb_pre = "l_pb_user_"handle
		bo_item = "&boItem"
	}
	if( can_process == 1 )
	{
		if( $2 == "int" || $2 == "uint" || $2 == "time")
		{
			print pb_pre"->set_"$3"(V_BsonIntField("bo_item",\""$3"\"));"
		}
		else if( $2 == "long" )
        {
			print pb_pre"->set_"$3"(V_BsonLongField("bo_item",\""$3"\"));"
        }
		else if( $2 == "string" )
		{
			print pb_pre"->set_"$3"(V_BsonStrField("bo_item",\""$3"\"));"
		}
		else
		{
			print $0
		}
	}
}' ${DEF_FILE}


awk '{
	can_process = 0
	if( $1 == "#__MESSAGE_BEGIN__" )
	{
		handle = $2
		print ""
		print "//"$2
	}
	else if( $1 == "#__MESSAGE_END__" )
	{
		handle = ""
	}
	else if( $1 == "#__MESSAGE_FILED__" )
	{
		can_process = 1
	}

	pb_pre = ""
	if( handle == "t_info" )
	{
		pb_pre = "f_info"
	}
	else
	{
		pb_pre = "l_pb_user_"handle
	}
	if( can_process == 1 )
	{
		if( $2 == "int" || $2 == "uint" || $2 == "time")
		{
			print "if( l_pb_user_t_user_bak."pb_pre"()."$3"() != l_pb_user_t_user."pb_pre"()."$3"() )"
	        print "{"
	        print "    bson_append_int32(bo_user, \""$3"\", -1, l_pb_user_t_user."pb_pre"()."$3"() ); "
	        print "}"
		}
		else if( $2 == "long" )
		{
			print "if( l_pb_user_t_user_bak."pb_pre"()."$3"() != l_pb_user_t_user."pb_pre"()."$3"() )"
	        print "{"
	        print "    bson_append_int64(bo_user, \""$3"\", -1, l_pb_user_t_user."pb_pre"()."$3"() ); "
	        print "}"
        }
		else if( $2 == "string" )
		{
			print "if( l_pb_user_t_user_bak."pb_pre"()."$3"() != l_pb_user_t_user."pb_pre"()."$3"() )"
	        print "{"
	        print "    bson_append_utf8(bo_user, \""$3"\", -1, l_pb_user_t_user."pb_pre"()."$3"().c_str() , -1 ); "
	        print "}"
		}
		else
		{
			print $0
		}
	}
}' ${DEF_FILE}


awk '{
	can_process = 0
	if( $1 == "#__MESSAGE_BEGIN__" )
	{
		handle = $2
		print ""
		print "//"$2
	}
	else if( $1 == "#__MESSAGE_END__" )
	{
		handle = ""
	}
	else if( $1 == "#__MESSAGE_FILED__" )
	{
		can_process = 1
	}

	pb_clt_pre = ""
	pb_usr_pre = ""

	if( handle == "t_info" )
	{
		pb_clt_pre = "l_pb_clt_t_user.mutable_f_info()"
		pb_usr_pre = "l_pb_user_t_user.f_info()"
		bo_item = "pDoc"
	}
	else
	{
		pb_clt_pre = "l_pb_clt_"handle
		pb_usr_pre = "l_pb_user_"handle
		bo_item = "&boItem"
	}
	if( can_process == 1 )
	{

		if( $2 == "int" || $2 == "uint" || $2 == "time" || $2 == "long" || $2 == "string")
		{
			print pb_clt_pre"->set_"$3"("pb_usr_pre"."$3"());"
		}
		else
		{
			print $0
		}
	}
}' ${DEF_FILE}


