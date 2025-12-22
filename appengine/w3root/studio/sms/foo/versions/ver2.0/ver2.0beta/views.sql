# Views are not dumped in the backups, use this file to recreate the views


# Update the view_drawing_list view...
use `vasmsdb`;
drop table view_drawing_list;

create view view_drawing_list as
 
select 	dwglist.id,
		dwglist.project_id,
		
		dwglist.dwgidentity,
		dwglist.disciplinecode,		 
		concat(dwglist.dwgidentity,'-',dwglist.disciplinecode,'-',dwglist.unit,dwglist.part) as sheetno,
		dwglist.currentrevno as revno, 
		dwglist.title, 
		
		dwglist.newstage as stage,		
		dwglist.r0issuedt as anewtargetdt,
		dwglist.stageclosed as aissuedflag,
		
		/* Tracking commited Target dates */
		dwglist.dtime as commitdt,
		
		dwglist.r0targetdt,
		dwglist.newr0targetdt,
		dwglist.r0issuedflag,
		
		dwglist.lastissuedrevno,
		dwglist.lastissueddate,
		
		dwglist.priority as actionby,
		dwglist.remark

		
from dwglist
 
where active = 1;


# view_users
use `va_usersdb`;
drop table view_users;

create view view_users as
 
select 	t1.id,
		t1.loginname,
		t1.passwd,		
		t1.fname,
		t1.lname,
		t1.email,
		t1.organization,
		t1.designation,
		t1.mobile_no as mobile,
		t1.phone_no as phone,
		
		t2.project_id,
		t2.role_id,
		t2.user_preference as preferences,
		t2.accept_terms_and_conditions as ok,
		t2.active
		
from user_profiles as t1 inner join user_projects as t2

ON t1.id = t2.user_profiles_id
 
where t1.active = 1;