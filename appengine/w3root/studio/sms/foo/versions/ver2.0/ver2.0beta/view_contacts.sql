create view view_contacts as 

select  t1.id,
		t1.contact,
				
		t3.roles,

		t2.company,
		
		t1.phoneno,
		t1.email,
		t1.project_id	

from transname as t1, transadd as t2, roles as t3

where 
	
	(t1.transadd_id = t2.id)
	
and (t1.role_id = t3.id)

and t1.active = 1;

