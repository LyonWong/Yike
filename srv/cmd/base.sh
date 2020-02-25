PATH_ROOT=`cd $(dirname $0)/..;pwd`
PATH_CORE="$PATH_ROOT/Core"

source $PATH_ROOT/Core/config/basic/env.sh
if [ -f "$PATH_CORE/config/local/env.sh" ]; then
	source $PATH_CORE/config/local/env.sh
fi
