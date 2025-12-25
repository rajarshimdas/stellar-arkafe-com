#!/bin/sh

# Global
sudo chown -R rd:rd .
sudo find . \( -type d -exec chmod 755 {} \; \) -o \( -type f -exec chmod 644 {} \; \)

# Writable folder
sudo chown -R rd:www-data ./w3filedb
sudo find ./w3filedb \( -type d -exec chmod 770 {} \; \) -o \( -type f -exec chmod 660 {} \; \)

sudo chown -R rd:www-data ./appengine/w3root/login/box
sudo find ./appengine/w3root/login/box \( -type d -exec chmod 775 {} \; \) -o \( -type f -exec chmod 664 {} \; \)

# Session folder will need to be owned by www-data
sudo chown -R www-data:rd ./w3filedb/session
sudo chown -R www-data:rd ./w3filedb/log

# Bash scripts
chmod +x ./*.sh
sudo chmod +x ./w3filedb/*.sh

