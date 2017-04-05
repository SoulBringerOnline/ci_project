local ps_key = KEYS[1]
local user_id  = KEYS[2]
local x = tonumber( KEYS[3] )
local y = tonumber( KEYS[4] )
local user_info = KEYS[5]

local key_info = string.format("WORLD#%s#%d#%d", ps_key, x, y)
local user_key = string.format("USER_INFO#%s",user_id)
redis.pcall( 'SET' , user_key , key_info )
local result = 0
if #ARGV then
	local oldX = ARGV[1]
	local oldY = ARGV[2]
	local resource_info = ARGV[3]
	local item = redis.pcall( 'GET', string.format("WORLD#%s#%d#%d", ps_key, x, y) )
	if not item then
		redis.pcall( 'SET', string.format("WORLD#%s#%d#%d", ps_key, x, y), user_info )
		redis.pcall( 'DEL', string.format("WORLD#%s#%d#%d", ps_key, oldX, oldY) )
		redis.pcall( 'SET', string.format("WORLD#%s#%d#%d", ps_key, oldX, oldY), resource_info ) 
		redis.log(redis.LOG_WARNING, "del", oldX, oldY )
		redis.log(redis.LOG_WARNING, "set", x, y )
		result = 0
	else
		result = 1
	end
else
	redis.pcall( 'SET', string.format("WORLD#%s#%d#%d", ps_key, x, y), user_info )
end
return result
