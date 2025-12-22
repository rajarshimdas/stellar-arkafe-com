/* Create Studio Management System Users */

# smsdb

/* Create a super-user for vasmsdb */
GRANT all 
on vasmsdb.* 
to `mycutedaemon`@`localhost` 
identified by "is+23+naughty+03+Arnav+2004" 
with grant option;

/* Create a select only user for vasmsdb */
GRANT select 
on vasmsdb.* 
to `angels`@`localhost` 
identified by "are+all+vidhyakunj+girls+of+92+batch" 
with grant option;


# usersdb 
grant select 
on `va_usersdb`.* 
to `angels2k8`@`localhost` 
identified by "d4fSA4fsf48fuis" 
with grant option;

grant all 
on `va_usersdb`.* 
to `anu2k8`@`localhost` 
identified by "j39GJew83id93jf" 
with grant option;


flush privileges;