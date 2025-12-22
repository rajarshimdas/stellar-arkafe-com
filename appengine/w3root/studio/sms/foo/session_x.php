<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 11-Feb-2008								|
| Updated On: 											|
+-------------------------------------------------------+
| Session Preference - All Functions					|
+-------------------------------------------------------+
|	1. Parse the session information tags & values		|
|	2. Update the session tag - value					|
+-------------------------------------------------------+
*/

class session_x {
	
	
	// Properties
	var $sessionid;
	var $userpreference;
		
	
	// Constructor
	
	/* 
	+-----------------------------------------------------------+
	| Consturctor												|
	+-----------------------------------------------------------+
	|	Setup the following properties							|
	+-----------------------------------------------------------+
	|	1. sessionid											|
	|	2. userpreference										|
	|	3. mysqli												|
	+-----------------------------------------------------------+
	*/
	function __construct($sessionid,$mysqli){
		
		// register the session id
		$this->sessionid = $sessionid;
		
		// Get the session's user preference	
		$query = "select userpreference from sessi0ns where sessionid = '$this->sessionid'";	
		
		if ($result = $mysqli->query($query)){
   
			while ($row = $result->fetch_row()) {
			    $this->userpreference = $row[0];
			}
			
			$result->close();
			
		}
								
	}
	
	
	// Methods
	
	/* 
	+-----------------------------------------------------------+
	| Method: GetTagValue										|
	+-----------------------------------------------------------+
	|	Parse the session information to read tag value			|
	+-----------------------------------------------------------+
	|	1. On sucess will return the tag value.					|
	|	2. If no value for specified tag, will return false.	|
	+-----------------------------------------------------------+
	*/
	function GetTagValue($tag){	
		
		// No userpreference
		if ($this->userpreference === "-"){
			return false;
		}
		
		// Get the preference array [tag:value]
		$a = explode(";",$this->userpreference);		
		
		// search for the specified tag in the preference array		
		foreach ($a as $v) {
			
	        $tx 	= explode(":",$v);
		    $tagX 	= $tx[0];
		    $valueX = $tx[1];    
		    //echo "<br>$tagX:$valueX";
		    
		    // Check if this is the tag we want?
		    if ($tagX === $tag) return $valueX;   
		    
		}
		
		// if the above search missed out the tag, the info is not avaliable
		return false;	
		
	}
	
	/* 
	+-----------------------------------------------------------+
	| Method: UpdateTagValue									|
	+-----------------------------------------------------------+
	|	Update the specified tag value							|
	+-----------------------------------------------------------+
	|	1. If tag value already exists, then update its value	|
	|	2. If no tag value then set the new tag value.			|
	+-----------------------------------------------------------+
	*/
	function UpdateTagValue($tag,$value,$mysqli){
		
		// No userpreference
		if ($this->userpreference === "-"){
			
			$query = "update sessi0ns set userpreference = '$tag:$value' where sessionid = '$this->sessionid'";
			if (!$mysqli->query($query)) return false; else return true;   
		    			
		}
		
		// Setup some default variables
		$string = "-"; 
		$tag_updated = 0;
		
		// Check/Get the existing tag value
		$a = explode(";",$this->userpreference);		
		
		foreach ($a as $v){
			
	        $tx 	= explode(":",$v);
		    $tagX 	= $tx[0];
		    $valueX = $tx[1];    
		    		    
		    // Generate the updated string
		    if ($tag !== $tagX){
		    	
		    	// Keep collecting exiting preference tags and values
		    	if ($string === "-"){
		    		$string = "$tagX:$valueX";
		    	} else {
		    		$string = "$string;$tagX:$valueX"; 
		    	}
		    			    		
		    } else {
		    	
		    	// Update the tag with new value 		    	
		    	if ($string === "-"){
		    		$string = "$tagX:$value";
		    	} else {
		    		$string = "$string;$tagX:$value"; 
		    	}
		    		    	
		    	// set updated flag to true
		    	$tag_updated = 1;
		    	
		    }    
		    
		}
		
		if ($tag_updated < 1) {
			$string = "$string;$tag:$value";
		}
		
		$query = "update sessi0ns set userpreference = '$string' where sessionid = '$this->sessionid'";		
		if (!$mysqli->query($query)) return false; else return true;	
		
	}
	
}

?>