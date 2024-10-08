
function show_help() {
    printf "

Usage:
$ ./command.sh COMMAND [COMMAND_ARGS...]

commands:
* up
* down
* tests
* stop
* restart
* artisan
* dev-front
* docker-logs
"
}
function command_docker() {
     docker-compose -f docker-compose.yml "$@"
}

function execute_container_command() {
    docker exec -it "$@"
}

case "$1" in
up)
    shift
    command_docker up "$@"
    ;;

down)
    shift
    command_docker down "$@"
    ;;

tests)
    shift
    command_docker run -it --rm toolbox php artisan test "$@"
    ;;
stop)
    shift
    command_docker stop "$@"
    ;;
restart)
    shift
    command_docker restart "$@"
    ;;
artisan)
    shift
    command_docker run -it --rm toolbox php artisan "$@"
    ;;
dev-front)
    shift
    npm install && npm run dev
    ;;

docker-logs)
    shift
    command_docker logs -f --tail=5
    ;;

*)
    show_help
esac
