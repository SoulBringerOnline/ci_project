find . -type d | xargs chmod 755 
find . -type f | xargs chmod 644

chmod 777 log

rsync --exclude "sync.sh" -artz  -e "ssh -l root " . root@192.168.164.199:/var/www/html/util
