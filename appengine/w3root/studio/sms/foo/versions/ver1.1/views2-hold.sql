drop view if exists view_drawing_list;

create view view_drawing_list as
 
select 	t1.id,
		t1.project_id, 
		concat(t1.dwgidentity,'-',t1.disciplinecode,'-',t1.unit,t1.part) as sheetno,
		t1.currentrevno as revno, 
		t1.title, 
		
		t1.newstage as stage,		
		t1.r0issuedt as anewtargetdt,
		t1.stageclosed as aissuedflag,
		
		t1.r0targetdt,
		t1.newr0targetdt,
		t1.r0issuedflag,
		
		t1.lastissuedrevno,
		t1.lastissueddate,
		
		t1.priority as actionby,
		t1.remark,
		
		t2.targetdt,
		t2.newtargetdt

		
from dwglist as t1 inner join targetdts as t2

ON t1.id = t2.dwglist_id
 
where t1.active = 1;
 


select * from view_drawing_list where id = 615;