for HOST in 10.128.63.246 10.128.63.247 ; do
rsync --exclude "log" --exclude "rsync.sh"  -artz  -e "ssh -l root " . root@${HOST}:/data/www/util
rsync --exclude "log" --exclude "rsync.sh"  -artz  -e "ssh -l root " ./tools/config.js root@${HOST}:/data/www/download/js/config.js
done
