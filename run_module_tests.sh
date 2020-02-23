#!/usr/bin/env bash

VERSION=1.0.0

## PLEASE CUSTOMIZE YOURSELF
default_phpunit_active=1

# To active xdebug change variable `off_run_xdebug=` to `run_xdebug=`. Remove `off_`
off_run_xdebug='php -dxdebug.idekey=debugCLI
                -dxdebug.remote_host=host.docker.internal
                -dxdebug.remote_enable=On
                -dxdebug.remote_autostart=On'

# Script can be controlled by environment variables (Nice for Docker)
export PHPUNIT_ACTIVE=${PHPUNIT_ACTIVE:-$default_phpunit_active}

function phpunit {
    if [[ PHPUNIT_ACTIVE -eq 1 ]]; then
        printf ${yellow}${bold}"[Start PHPUnit]"${reset}"\n"
        $PHPCLI ${vendor}/bin/phpunit ./ $*
    else
        printf ${cyan}"[Skip PHPUnits]"${reset}"\n"
    fi
}

#color
export bold="\e[1m"
export yellow="\e[33m"
export cyan="\e[96m"
export cerr="\e[31m"
export reset="\e[0m"

function usage() {
cat <<EOF
Usage: $0  command [argumente]
Bash script to starts any Test Frameworks.

command       description
-------------------------
all           Start all Tests Frameworks
phpunit       Start PHPUnit Frameworks

-h --help     Print this help text.
--version     Print Version: v$VERSION

EOF
exit 1
}

cd $(dirname $0);
export SCRIPT_DIR=$(pwd -P);
export PHPCLI=${run_xdebug-php}

vendor='../../../../vendor'; #v6.x-Vendor
if [[ ! -d $vendor ]]; then
    vendor='../../vendor'; #v4.x-Vendor
fi

function all() {
    phpunit;
}

case "$1" in
phpunit)
    shift;
    phpunit $*
    ;;
-h|--help)
    usage
    ;;
--version)
    echo "v$VERSION"
    ;;
*)
    all
    ;;
esac
