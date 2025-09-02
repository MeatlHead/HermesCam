#!/bin/bash
set -e
PORT=3333
HOST="127.0.0.1"
PHOTO_DIR="photos"
mkdir -p "$PHOTO_DIR"
echo "[*] Starting PHP server at http://$HOST:$PORT ..."
php -S "$HOST:$PORT" > server_php.log 2>&1 &
PHP_PID=$!
sleep 1
if [ "$1" = "serveo" ]; then
  echo "[*] Starting Serveo tunnel ..."
  ssh -o StrictHostKeyChecking=no -R 80:localhost:$PORT serveo.net 2>&1 | tee serveo.log
  kill $PHP_PID
else
  echo "Access locally: http://127.0.0.1:$PORT/index.html"
  wait $PHP_PID
fi
