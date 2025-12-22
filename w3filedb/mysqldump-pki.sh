#!/bin/bash
#
# MySQL Backup with GPG (PKI Encryption)
# --------------------------------------
# Usage: 
# Encrypt: ./mysql_backup.sh <DB_NAME> <RECIPIENT_EMAIL>
# Decrypt: gpg --decrypt mydatabase_20250830_120000.sql.gpg > mydatabase.sql
#

# GRANT SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER ON *.* TO 'rajarshi'@'localhost';

# === Config ===
USER="rajarshi"                 
PASSWORD="secret"             
BACKUP_DIR="/home/rd/backup/pki"
DATE=$(date +"%Y%m%d_%H%M%S")

# Input arguments
DB_NAME="$1"
RECIPIENT="$2"

if [ -z "$DB_NAME" ] || [ -z "$RECIPIENT" ]; then
    echo "Usage: $0 <DB_NAME> <RECIPIENT_EMAIL>"
    exit 1
fi

# Ensure backup directory exists
mkdir -p "$BACKUP_DIR"

# Filenames
SQL_FILE="$BACKUP_DIR/${DB_NAME}_$DATE.sql"
GPG_FILE="$SQL_FILE.gpg"

# === Dump database ===
echo "[*] Dumping database: $DB_NAME"
mysqldump -u"$USER" -p"$PASSWORD" "$DB_NAME" > "$SQL_FILE"
if [ $? -ne 0 ]; then
    echo "[!] mysqldump failed!"
    exit 1
fi

# === Encrypt with GPG ===
echo "[*] Encrypting backup for recipient: $RECIPIENT"
gpg --yes --encrypt --recipient "$RECIPIENT" -o "$GPG_FILE" "$SQL_FILE"

if [ $? -eq 0 ]; then
    echo "[*] Encrypted backup created: $GPG_FILE"
    rm -f "$SQL_FILE"   # Remove plain SQL file
else
    echo "[!] GPG encryption failed!"
    exit 1
fi

# === Optional: Cleanup old backups (>7 days) ===
find "$BACKUP_DIR" -type f -name "*.sql.gpg" -mtime +7 -exec rm {} \;

echo "[*] Backup completed successfully."
