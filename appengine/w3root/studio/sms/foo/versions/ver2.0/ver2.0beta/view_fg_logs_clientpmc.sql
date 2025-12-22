create view view_fg_logs_clientpmc as 

select  t1.session_id,
		t1.fg_files_id,
		t1.action,
		t1.details,
		t1.dtime,
		
		t2.loginname as uname,
		t2.project_id

from fg_activity_log as t1, sessions as t2

where (t1.session_id = t2.id) and t1.login_from = 'Client&PMC';