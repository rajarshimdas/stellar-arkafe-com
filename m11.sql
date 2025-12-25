use s1db;

UPDATE `users` SET `fullname` = 'NA' WHERE (`id` = '150');
UPDATE `users` SET `domain_id` = '0' WHERE (`id` = '157');
UPDATE `users_a` SET `reports_to_user_id` = '150' WHERE (`user_id` = '153');

update users_a 
set 
    dob = '1900-01-01',
    dt_of_joining = if(dt_of_joining != '1977-11-23', dt_of_joining, '1900-01-01'),
    dt_of_confirmation = '1900-01-01';
