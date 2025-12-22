<?php /* Transmittals :: Search */	$option = $_GET['selectoption']; ?>
<table style="text-align:left;width:100%;" border="0"  cellpadding="2" cellspacing="0" bgcolor="#E8E9FF">
    <tr style="height:40px;">    
		
		<form name="selectoption" action="project.cgi" method="get">		
		<td align="center" valign="top" width="30%">    
      		<input name="a" type="hidden" value="t3xsearch">      			
				Search by:
        		<select name="selectoption" style="width:120px">
        			<?php         			
					if ($option) 
						/* Display if option not selected */
						echo "<option>$option"; else 
						/* Display if no option available */
						echo "<option>-- Select --";
					          			
          			if ($option !== 'Contents') 
					  echo '<option>Contents';         
          			
          			if ($option !== 'Date Range') 
					  echo '<option>Date Range';						  
          			?>
        		</select>
        	<input type="submit" name="submit" value="Go">		
 		</td>
 		</form>
    
 	  
    <?php /* -- Select Option -- */
      	if ($option === "-- Select --" || !$option) { ?>
      	 <td align="center" width="70%" style="vertical-align: top;">
			Please select the method for serching transmittals
		 </td>
	<?php /* close if function */} ?> 	
	
        
    <?php /* Contents */
      if ($option === "Contents") {?>  
      <form name="sort" action="project.cgi" method="get">
        <input name="a" type="hidden" value="t3xsearch-contents">
          <td align="center" width="65%" style="vertical-align: top;">
          	Search Transmittals Contents<br>           
          	<table border="0" cellspacing="0" width="100%"> 
          		<tr>
          			<td>To:</td><td>Item:&nbsp;<span class="infotext">(SheetNo-RevNo)</span></td><td>Description:</td><td>&nbsp;</td>
          		</tr>        
          		<tr>          			
          			<td width="28%">
						<select name="to" style="width:100%">
							<option>-- Select --
								<?php
									include('foo/arnav/dblogin.cgi');
									$sql="select contact from transname where project_id=$projectid and active = 1";
									if ($result = $mysqli->query($sql)) {    						
    									while ($row = $result->fetch_row()) {
        									echo "<option>$row[0]";
    									}    
    									$result->close();
									} else echo "Error: $mysqli->error";
									$mysqli->close();								
								?>									
					</td>         		
                    <td width="28%"><input type="text" name="item" style="width:100%"></td>
                    <td width="28%"><input type="text" name="desc" style="width:100%"></td>
                    <td width="16%"><input type="submit" name="submit" value="Search" style="width:100%"></td>
          		</tr>
          	</table>
          </td>
      </form>
    <?php /* close if function */} ?>
 
   
    <?php /* Date Range */
      if ($option === "Date Range") {?>
      	<link rel="stylesheet" type="text/css" href="foo/calendar/styles.css" />
		<script type="text/javascript" src="foo/calendar/classes.js"></script>
		<script type="text/javascript">
			var a1; var a2; var a3; var a4;
			window.onload = function () {
				a1 = new Epoch('epoch_popup','popup',document.getElementById('pop1'));
				a2 = new Epoch('epoch_popup','popup',document.getElementById('pop2'));						
			};
		</script> 
      <form name="sort" action="project.cgi" method="get">
      	<input name="a" type="hidden" value="t3xsearch-daterange">      	
          <td align="center" width="65%" style="vertical-align: top;">		 
          	Transmittal Date Range<br>          
          	<table border="0">         
          		<tr>
          			<td>From:<input id="pop1" type="text" name="date1"></td>
                    <td>To:<input id="pop2" type="text" name="date2"></td>
                    <td><input type="submit" name="submit" value="Search"></td>
          		</tr>
          	</table>
          </td>
      </form>
    <?php /* close if function */} ?>     
   
 </tr>
</table>
