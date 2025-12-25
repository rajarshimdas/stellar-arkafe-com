#!/bin/sh

dt=$(date +'%Y%m%d%H%M%s')
filename=s1db-${dt}.sql
BACKUP_DIR="/home/rd/backup/firefly"

# Ensure backup directory exists
mkdir -p "$BACKUP_DIR"

# Dump database
/usr/bin/mysqldump \
    --lock-tables \
    --single-transaction=TRUE \
    -u st2 -pstDec25 s1db > ${BACKUP_DIR}/${filename}

