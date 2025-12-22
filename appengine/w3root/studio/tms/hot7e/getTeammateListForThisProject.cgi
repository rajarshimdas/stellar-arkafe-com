<?php

function getTeammateListForThisProject ($ProjID,$mysqli) {
	/*
	roleinproject as t1
	+------------+-----------------------+------+-----+---------+-------+
	| Field      | Type                  | Null | Key | Default | Extra |
	+------------+-----------------------+------+-----+---------+-------+
	| project_id | mediumint(8) unsigned | NO   |     | NULL    |       |
	| loginname  | varchar(50)           | NO   |     |         |       |
	| roles_id   | tinyint(3) unsigned   | NO   |     | NULL    |       |
	| active     | tinyint(1)            | NO   |     | NULL    |       |
	+------------+-----------------------+------+-----+---------+-------+
	
	users as t2
	+--------------+----------------------+------+-----+---------+----------------+
	| Field        | Type                 | Null | Key | Default | Extra          |
	+--------------+----------------------+------+-----+---------+----------------+
	| id           | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
	| domain_id    | smallint(5) unsigned | NO   | MUL | NULL    |                |
	| loginname    | varchar(50)          | NO   |     | NULL    |                |
	| passwd       | varchar(50)          | NO   |     |         |                |
	| fullname     | varchar(50)          | NO   |     | NULL    |                |
	| emailid      | varchar(150)         | NO   |     | NULL    |                |
	| internaluser | tinyint(1)           | YES  |     | NULL    |                |
	| remark       | varchar(250)         | NO   |     | NULL    |                |
	| active       | tinyint(1)           | YES  |     | NULL    |                |
	+--------------+----------------------+------+-----+---------+----------------+
	*/
	
	$query = "select 
                        t1.loginname,
                        t2.fullname,
                        t1.roles_id,
                        t2.id,
                        t1.active
                    from
                        roleinproject as t1, users as t2
                    where
                        project_id = $ProjID and
                        t2.loginname = t1.loginname

                    order by t2.fullname";
	
	// echo "Q1: $query<br>";
	
	if ($result = $mysqli->query($query)) {
	    
	    while ($row = $result->fetch_row()) {
	    	
	        $teamX[] = array(
	        
	        			"loginname" 	=> $row[0],
	        			"fullname" 		=> $row[1],
	        			"roles_id" 		=> $row[2],
	        			"user_id"		=> $row[3],
	        			"user_active" 	=> $row[4]
	        			        			
	        			);
	        			
	    }
	        
	    $result->close();
	}
	
	return $teamX;
	
}
    
?>