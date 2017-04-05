local fight_key = KEYS[1]
local fight_round = {}
for _, v in ipairs(ARGV) do
	local round_info = redis.pcall( 'GET' , fight_key .. '#' .. v )
	if round_info and type(round_info) == "string" then
		table.insert( fight_round , round_info )
	else
		break
	end
end
return fight_round
