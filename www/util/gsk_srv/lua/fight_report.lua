local fight_save_key = KEYS[1]
local fight_round = {}

local round_id = 1
while true do
	local key = fight_save_key .. '#' .. round_id
	local round_info = redis.pcall( 'GET' ,  key)
	if round_info and type(round_info) == "string" then
		table.insert( fight_round , round_info )
	else
		break
	end

	round_id = round_id + 1

	redis.log(redis.LOG_WARNING, key)
end

return fight_round