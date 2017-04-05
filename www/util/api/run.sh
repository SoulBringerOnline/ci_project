ps -fe | grep celery | grep -v grep | awk '{print $2}' | xargs kill -9
ps -fe | grep GSKAPI.py | grep -v grep | awk '{print $2}' | xargs kill -9
nohup celery -A GSKAPI.celery worker --loglevel=info >> gsk.log 2>&1 &
nohup python GSKAPI.py >> gsk.log 2>&1 &