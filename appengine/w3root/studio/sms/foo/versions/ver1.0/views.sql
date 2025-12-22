drop view if exists view_drawing_list;

create view view_drawing_list as
 
select 	dwglist.id,
		dwglist.project_id, 
		concat(dwglist.dwgidentity,'-',dwglist.disciplinecode,'-',dwglist.unit,dwglist.part) as sheetno,
		dwglist.currentrevno as revno, 
		dwglist.title, 
		
		dwglist.newstage as stage,		
		dwglist.r0issuedt as anewtargetdt,
		dwglist.stageclosed as aissuedflag,
		
		dwglist.r0targetdt,
		dwglist.newr0targetdt,
		dwglist.r0issuedflag,
		
		dwglist.lastissuedrevno,
		dwglist.lastissueddate,
		
		dwglist.priority as actionby,
		dwglist.remark

		
from dwglist
 
where active = 1;

select * from view_drawing_list where id = 615;