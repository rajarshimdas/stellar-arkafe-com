create view view_users as 

select  user_profiles.id,
		user_profiles.loginname,
		user_profiles.passwd,		
		user_profiles.fname,
		user_profiles.lname,
		user_profiles.email,
		
		transadd.company as organization,
		
		user_profiles.designation,
		user_profiles.mobile_no as mobile,
		user_profiles.phone_no as phone,
		
		user_projects.project_id,
		user_projects.role_id,
		user_projects.user_preference as preferences,
		user_projects.accept_terms_and_conditions as ok,
		user_projects.active
		
from user_profiles, user_projects, transname, transadd

where 
	
	(user_profiles.id = user_projects.user_profiles_id) 

and (user_profiles.id = transname.user_profiles_id)

and (transname.transadd_id = transadd.id)

and	user_profiles.active = 1;