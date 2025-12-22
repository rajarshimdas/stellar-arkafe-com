
create user 'st1'@'localhost';
set password for 'st1'@'localhost' = password('minnie7j');

create user 'st2'@'localhost';
set password for 'st2'@'localhost' = password('m7jack');

grant select,lock tables,show view on s5db.* to 'st1'@'localhost';
grant all on s5db.* to 'st2'@'localhost';

flush privileges;

