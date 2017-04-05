local ps_key = KEYS[1]
local user_key = KEYS[2]
local opt = tonumber(KEYS[3])

local pvp_key = 'PVP_RANK#'..ps_key

redis.log(redis.LOG_WARNING, "opt", opt)

local function getUserInfo( id )
	local user_info = redis.pcall('GET', "USER_INFO#"..id)
	return user_info
end

-- get user rank
if opt == 1 then
	local user_rank = redis.pcall( 'ZRANK', pvp_key, user_key)

	redis.log(redis.LOG_WARNING, "user_rank 1", user_rank)
	if not user_rank then
		local total = redis.pcall('ZCARD', pvp_key)
		redis.log(redis.LOG_WARNING, "total", total)
		redis.pcall('ZADD', pvp_key, total, user_key)
		user_rank = redis.pcall( 'ZRANK', pvp_key, user_key)
	end

	redis.log(redis.LOG_WARNING, "user_rank 2", user_rank)
	return tonumber(user_rank)

elseif opt == 2 then
	local result = {}
	for i,v in ipairs(ARGV) do
		local range = tonumber(v-1)
		local item = redis.pcall('ZRANGE' , pvp_key , range, range, 'WITHSCORES')
		if item ~= nil and #item == 2 then
	        local user_id = item[1]
	        local rank = tostring( item[2] )
	        if string.len( user_id ) and rank then
	        	local user_info = getUserInfo(user_id)
	        	if user_info then
	        		table.insert(result, rank)
	        		table.insert(result, user_info)
	        	end
	        end
	    end
	end
	return result

elseif opt == 3 then
	local first = tonumber(KEYS[4])
	local last 	= tonumber(KEYS[5])
	local result = {}
	local item = redis.pcall('ZRANGE' , pvp_key , first, last, 'WITHSCORES')
	if item ~= nil and #item >= 2 then
		local index = 1
		for i=1, #item/2 do
			local user_id = item[index]
			index = index + 1
        	local rank = tostring( item[index] )
        	index = index + 1

        	if string.len( user_id ) and rank then
	        	local user_info = getUserInfo(user_id)
	        	if user_info then
	        		table.insert(result, rank)
	        		table.insert(result, user_info)
	        		redis.log(redis.LOG_WARNING, "rank", rank, user_id)
	        	end
	        end
		end
    end
	return result
elseif opt == 4 then
	local zset_key = 'BAK_PVP_RANK#' ..  KEYS[1]
	local user_rank = redis.pcall( 'ZREVRANK' , zset_key , user_key  ) 
	local result = 999998
	if user_rank then
		result = tonumber(user_rank)
	end
	return result
elseif opt == 5 then
	local enemy_key = KEYS[4]
	local fr = tonumber(KEYS[5])
	local score_1 =  tonumber( redis.pcall( 'ZRANK' , pvp_key  , user_key  ) )
	local score_2 =  tonumber( redis.pcall( 'ZRANK' , pvp_key  , enemy_key  ) )

	redis.log(redis.LOG_WARNING, "opt fdasjifdji", fr, score_1, score_2)

	if fr == 1 then
		if score_1 > score_2 then
			redis.pcall( 'ZADD' , pvp_key  , score_2,  user_key  ) 
	    	redis.pcall( 'ZADD' , pvp_key  , score_1,  enemy_key  )
	    end
		local rank_1 =  tonumber( redis.pcall( 'ZRANK' , pvp_key  , user_key  ) )
		local rank_2 =  tonumber( redis.pcall( 'ZRANK' , pvp_key  , enemy_key  ) )
		return {rank_1, rank_2}
	else
		return {score_1, score_2}
	end
end