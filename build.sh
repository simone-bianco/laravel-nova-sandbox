#!/bin/bash

set -e
apt-get update && apt-get install -y zip unzip

if test -f "composer.json"; then
COMPOSER_AUTH=$(cat <<EOF
{
  "github-oauth": {
      "github.com": "${COMPOSER_AUTH_GITHUB_TOKEN}"
  },
  "bitbucket-oauth": {
    "bitbucket.org": {
        "consumer-key": "${COMPOSER_BITBUCKET_CONSUMER_KEY}",
        "consumer-secret": "${COMPOSER_BITBUCKET_CONSUMER_SECRET}"
    }
  },
  "http-basic": {
    "nova.laravel.com": {
      "username": "${COMPOSER_NOVA_KEY}",
      "password": "${COMPOSER_NOVA_SECRET}"
    },
    "packages-laravel.dnafactory.it": {
        "username": "${PACKAGES_DNAFACTORY_USERNAME}",
        "password": "${PACKAGES_DNAFACTORY_PASSWORD}"
    }
  }
}
EOF
)
mkdir -p /root/.composer && echo "${COMPOSER_AUTH}" > /root/.composer/auth.json

composer2 install --no-dev

fi

rm .gitignore *.sample -f

rm -Rf .git

# Is valid only for master
#TAG=`git describe --tags --exact-match $BITBUCKET_COMMIT`
TAG=${BITBUCKET_COMMIT}
tar --exclude=./.git --exclude=./z-doc --exclude=./z-data --exclude=./phpserver --exclude=./*.sample --exclude=bitbucket_pipelines.yml --exclude=build.sh -czpf /tmp/release-$TAG.tar.gz .
mkdir dist
mkdir dist/data
mv /tmp/release-$TAG.tar.gz dist/data

cat <<EOF >> dist/data/unzip.sh
#!/bin/bash
if [ -f "release-${BITBUCKET_COMMIT}.tar.gz" ]; then

if [ ! -d "${CODEDEPLOY_WEBROOT}" ]
then
  echo "La cartella ${CODEDEPLOY_WEBROOT} non esiste, creazione in corso..."
  mkdir -p "${CODEDEPLOY_WEBROOT}"
  echo "Cartella ${CODEDEPLOY_WEBROOT} creata con successo!"
else
  echo "La cartella ${CODEDEPLOY_WEBROOT} esiste già."
fi

echo "remove ondeck if exists..."
rm -rf ${CODEDEPLOY_WEBROOT}/ondeck
mkdir ${CODEDEPLOY_WEBROOT}/ondeck
echo "unzipping..."
tar -xzf release-${BITBUCKET_COMMIT}.tar.gz -C ${CODEDEPLOY_WEBROOT}/ondeck
echo "removing tar.gz file..."
rm release-${TAG}.tar.gz -f
fi
if [ -d ${CODEDEPLOY_WEBROOT}/ondeck ]; then
chown -R ${CODEDEPLOY_USER}:${CODEDEPLOY_GROUP} ${CODEDEPLOY_WEBROOT}/ondeck
else
echo "${CODEDEPLOY_WEBROOT}/ondeck is not a diectory. Please, re-run full deploy pipeline."
exit 1
fi
EOF

BASE_DIR=${CODEDEPLOY_WEBROOT:-$PWD}
DEPLOY_DIR="${CODEDEPLOY_DIR:-app}"
DEPLOY_USER=${CODEDEPLOY_USER:-www-data}:${CODEDEPLOY_GROUP:-www-data}

cat <<EOF >> dist/data/env.configuration
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG=${APP_DEBUG}
APP_URL="${APP_URL}"

NOVA_LICENSE_KEY=${NOVA_LICENSE_KEY}

LOG_CHANNEL=daily
LOG_LEVEL=debug
LOG_DEPTH=2

DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT=${DB_PORT}
DB_DATABASE="${DB_NAME}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

MAIL_MAILER=smtp
MAIL_HOST="pro.eu.turbo-smtp.com"
MAIL_PORT=465
MAIL_USERNAME=info@dnafactory.it
MAIL_PASSWORD=DNAsmtp1234
MAIL_ENCRYPTION=SSL
MAIL_FROM_ADDRESS=laravelbi@dnafactory.it
MAIL_FROM_NAME="LaravelBI"

NOVA_LOGS_PER_PAGE=6
NOVA_LOGS_REGEX_FOR_FILES="/^laravel/"

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

cat <<EOF >> dist/data/run.sh
#!/bin/bash
set -eu

chown -R $DEPLOY_USER ondeck
find ondeck/ -type f -exec chmod 644 {} \;                        # 644 permission for files
find ondeck/ -type d -exec chmod 755 {} \;                        # 755 permission for directory

echo "run deploy operations..."
# Deploy new application version

# --- moving env file ---
mv ./env.configuration ondeck/.env

# --- creating symlink for storage ---
[ ! -e storage ] && cp -r ondeck/storage ./

if [ -d storage ]; then
    rm -rf ondeck/storage
    ln -s "$CODEDEPLOY_WEBROOT/storage" ondeck/storage
else
    echo "$CODEDEPLOY_WEBROOT/storage is not a directory. Please, re-run full deploy pipeline."
    exit 1
fi

# --- deploy Laravel MS ---

cd ondeck
chmod +x artisan

echo "Checking refresh database"
if [ "${REFRESH_DATABASE}" = "1" ]; then
    echo "Refreshing Database..."
    echo "php8.1 artisan migrate:fresh --seed"
    php8.1 artisan migrate:fresh --seed
    echo "Database Refreshed"
else
    echo "Updating Database..."
    echo "php8.1 artisan migrate"
    php8.1 artisan migrate
    echo "Database Updated"
fi

#if [ -x "\$(which composer)" ]; then
#    ## Ottimizza l'autoloader di composer
#    composer dump-autoload -o
#fi

# --------------------

# Effettua lo switch della versione
cd ${BASE_DIR}
rm -Rf previous
if [ ! -d ${DEPLOY_DIR} ]; then
    mkdir ${DEPLOY_DIR}
    chown -R ${DEPLOY_USER} ${DEPLOY_DIR}
fi
mv -f ${DEPLOY_DIR} previous && mv -f ondeck ${DEPLOY_DIR}

cd ${DEPLOY_DIR}

#######################################################################
# Clean opcache
#######################################################################

if [ -z "\$(which opcache-flush)" ]; then
  echo "command opcache-flush not found"
else
  opcache-flush
  echo "opcache flushed"
fi

echo "Pulisco cache di laravel: php8.1 artisan cache:clear"
php8.1 artisan cache:clear

echo "Pulisco cache configurazione di laravel: php8.1 artisan config:clear"
php8.1 artisan config:clear

echo "Creo link simbolico per storage in laravel: php8.1 artisan storage:link"
php8.1 artisan storage:link

EOF

cat <<EOF >> dist/vars
SSH_USER=${SSH_USER}
SSH_HOST=${SSH_HOST}
CODEDEPLOY_WEBROOT=${CODEDEPLOY_WEBROOT}
EOF
