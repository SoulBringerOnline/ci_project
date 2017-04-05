local uin = KEYS[1]
local clt = KEYS[2]
local result = {}

local world_list_key = string.format("PUSH_UC#%s", ps_key)
if world_list_type == 0 then
	world_list_key = string.format("WORLD_LIST_NEW#%s", ps_key)
end
local item = redis.pcall( 'LPOP', world_list_key )
redis.log(redis.LOG_WARNING, "pos", item)
if item then
	local first, last = item.find(item, "#")
	-- redis.log(redis.LOG_WARNING, "pos", item)
	x = tonumber(string.sub(item, 1, first-1) )
	y = tonumber(string.sub(item, first+1, string.len(item) ) )
	-- redis.log(redis.LOG_WARNING, "pos", item, x, y )
	-- local key = string.format("WORLD#%s#%d#%d", ps_key, x, y)
	-- local node = redis.pcall( 'GET', key )
	-- redis.log(redis.LOG_WARNING, "get", node, key)
	-- if node then
	-- 	x = 0 
	-- 	y = 0
	-- end
	-- redis.log(redis.LOG_WARNING, "after", x, y )
else
	return result
end
table.insert(result, x)
table.insert(result, y)
redis.log(redis.LOG_WARNING, "result", x, y )
return result
