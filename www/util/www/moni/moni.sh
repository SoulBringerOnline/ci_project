## -------------------- util.sh ----------------------------
## Author   :   yongsheng.zhao@lavaclan.com
## Date     :   2013-04-17
## ---------------------------------------------------------
export WORKDIR=$( cd ` dirname $0 ` && pwd )
cd "$WORKDIR" || exit 1
ulimit -c unlimited

python moni.py


