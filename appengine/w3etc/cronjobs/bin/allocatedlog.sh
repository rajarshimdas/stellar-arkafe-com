#!/bin/bash

# MySQL database credentials
source ./env.sh

# Stored procedure name and parameters
PROCEDURE_NAME="taskmanhouralloc_log"

# MySQL command to execute the stored procedure
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" -D "$DB_NAME" -e "CALL $PROCEDURE_NAME();"

# Check if the procedure executed successfully
if [ $? -eq 0 ]; then
    echo "Stored procedure executed successfully."
else
    echo "Error executing stored procedure."
fi
