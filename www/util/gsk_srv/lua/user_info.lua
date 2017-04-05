local ps_key = KEYS[1]
local info = KEYS[2]
local key = "USER_INFO#" .. ps_key
redis.pcall("SET", key, info)