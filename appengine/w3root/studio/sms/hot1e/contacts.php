<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   2007                                    |
| Updated On:   29-Jul-08                               |
|               21-Dec-2024 Travelling to Chandigarh by |
|                           Paschim Express             |
+-------------------------------------------------------+
| Class :: Contact Management				            |
+-------------------------------------------------------+
*/
class contact
{

    public $ProjID;
    public $roleid;

    function __construct($projectid, $roleid = 100)
    {
        $this->roleid = $roleid;
        $this->CheckUserRole();
        if (!$projectid) {
            $mc = "System failure to identify the projectid";
            $this->go2('t1xcontacts', $mc);
        }
        $this->ProjID = $projectid;
    }

    function CheckUserRole()
    {
        // global $roleid;
        if ($this->roleid > 60) {
            $mc = "You do not have Contacts Creation/Editing rights for this Project..s";
            $this->go2('t1xcontacts', $mc);
        }
    }

    function go2($a, $mc)
    {
        /* Redirect page to Location */
        header("location:project.cgi?a=$a&mc=$mc");
        die;
    }
    /*
	function AddNewContact($name,$company,$role,$phoneno,$email){
		
		if ($name === ""){
			$mc="Name field was empty. Please try again.";
			$this->go2('t1xcontacts', $mc);
		}
		if ($company === "-- Select Company --"){
			$mc="Please select company from the drop down list and try again";
			$this->go2('t1xcontacts', $mc);
		}
		if ($role === "-- Select Role --"){
			$mc="Please select role from the drop down list and try again";
			$this->go2('t1xcontacts', $mc);
		}
		
		include('foo/arnav/dblogin.cgi');
		
		// check if the name already exists for this specific project
		$nameexists = 0;
		$sql = "select contact from transname where project_id = $this->ProjID and contact = '$name' and active = 1";
		if ($result = $mysqli->query($sql)) {    		
    		while ($row = $result->fetch_row()) {        		
        		$nameexists = 1;
    		}    		
    		$result->close();
		} else "Error: $mysqli->error";
		if ($nameexists > 0) {
			$mysqli->close();
			$mc= "The contact name $name already exists" ;
			$this->go2('t1xcontacts', $mc);	
		}		
		
		// Get roleid
		$sql = "select id from roles where roles = '$role'"; 
		echo "<br>sql1: $sql";
		if ($result = $mysqli->query($sql)) {    		
    		while ($row = $result->fetch_row()) {
        		$roleid = $row[0];
    		}    		
    		$result->close();
		} else "Error: $mysqli->error";	
		
		// Get transadd_id
		$sql = "select id from transadd where project_id = $this->ProjID and company='$company'";
		echo "<br>sql2: $sql";
		if ($result = $mysqli->query($sql)) {    		
    		while ($row = $result->fetch_row()) {
        		$transaddid = $row[0];
    		}    		
    		$result->close();
		} else "Error: $mysqli->error";		
		
		$sql = "insert into transname values (NULL,'$name',$roleid,$this->ProjID,$transaddid,'$phoneno','$email',0,'nopassword',1)";
		echo "<br>sql3: $sql";
		if (!$mysqli->query($sql)) {
			echo "Error: $mysqli->error";
			$mysqli->close();
			return false;
		}
				
		$mysqli->close();		
		return true;
	}
    */
}

class company extends contact
{

    function __construct($projectid)
    {

        $this->CheckUserRole();
        if (!$projectid) {
            $mc = "System failure to identify the projectid";
            $this->go2('t1xcontacts', $mc);
        }
        $this->ProjID = $projectid;
    }

    function AddNewCompany($company2, $door, $street, $locality, $state, $city, $pincode, $website)
    {

        include('foo/arnav/dblogin.cgi');

        /* Data validation */
        if (!$company || !$door || !$city) {
            global $err;
            $err = "Incomplete data at input.";
            return false;
        }

        $companyexists = 0;

        $sql = "select id from transadd where project_id = $this->ProjID and company = '$company2' and active = 1";

        // echo "Q1: $sql<br>";

        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_row()) {
                $companyexists = 1;
            }
            $result->close();
        } else "Error: $mysqli->error";

        if ($companyexists > 0) {
            $mysqli->close();
            $mc = "The company $company already exists";
            $this->go2('t1xcontacts-addcomp', $mc);
        }

        /* Insert data into the transadd table */
        $sql = "insert into
                    transadd (project_id,company,dooradd,streetadd,locality,city,statecountry,pincode,website)
                values
                    ($this->ProjID,'$company2','$door','$street','$locality','$state','$city','$pincode','$website')";

        /*
        echo "Q2: $sql;";
        die();
        */

        if (!$mysqli->query($sql)) {
            //echo "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }

        $mysqli->close();
        return true;
    }

    function DeleteCompany($id)
    {

        include('foo/arnav/dblogin.cgi');

        /* Verify if any contact has this transadd_id */
        $sql =    "select contact from transname where transadd_id = $id and active = 1";
        //echo "<br>sql: $sql;";
        global $co, $err;
        $co = 0;


        if ($result = $mysqli->query($sql)) {
            $row_cnt = $result->num_rows;
            if ($row_cnt > 0)
                $err = "The following contacts refer to this address<br>";
            while ($row = $result->fetch_row()) {
                $co++;
                $err = $err . $co . " : $row[0]<br>";
            }
            $result->close();
        } else "Error: $mysqli->error";

        if ($co > 0) {
            $err = "$err Cannot delete the company information...";
            $mysqli->close();
            return false;
        }

        if ($co < 1) {
            $sql = "update transadd set active=0 where id=$id";
            if (!$mysqli->query($sql)) {
                $err = "Error: $mysqli->error";
                $mysqli->close();
                return false;
            }
        }

        return true;
        $mysqli->close();
    }


    function EditCompany($id, $company, $dooradd, $streetadd, $locality, $city, $statecountry, $pincode, $website)
    {

        /* Data validation */
        if (!$company) {

            global $err;
            $err = "Incomplete data at input.";
            return false;
        }

        // hack to save $company variable
        $c2 = $company;                     // store $company variable in a temporary variable
        include('foo/arnav/dblogin.cgi');   // changes the variable to the config.php $company array
        $company = $c2;                     // Restored $company to original value

        $sql = "update 
                transadd
            set
                company 	= '$company',
                dooradd 	= '$dooradd',
                streetadd 	= '$streetadd',
                locality 	= '$locality',
                city 		= '$city',
                statecountry 	= '$statecountry',
                pincode 	= '$pincode',
                website 	= '$website'
            where
                id = $id";

        /* echo "sql: $sql;"; */

        if (!$mysqli->query($sql)) {
            global $err;
            $err = "Error: $mysqli->error";
            $mysqli->close();
            return false;
        }

        $mysqli->close();
        return true;
    }
}


class companylist extends contact
{

    function __construct($projectid)
    {

        if ($projectid < 1 || $projectid === "") {
            $mc = "System failure to identify the projectid";
            $this->go2('t1xcontacts', $mc);
        }

        # die("Testing");

        $this->ProjID = $projectid;
        /*
        echo "Displaying Company Address List<br>
                <span style='color:#5A5A5A;font-size:13px;'>
                    (Select edit next to the company you wish to edit)
                </span>";
        */

        /* Table Header */
        echo "<table class='tabulation' border='0' cellspacing='0' style='width:100%;' cellpadding='3px'>
                <tr class='headerRow'>
                    <td class='headerRowCell1' style='width:5%;'>No</td>
                    <td class='headerRowCell2' style='width:30%;'>Name</td>
                    <td class='headerRowCell2'>Address</td>
                    <td class='headerRowCell2' style='width:10%;'>&nbsp;</td>
                </tr>";

        $sql = "select
                    id,
                    company,
                    dooradd,
                    streetadd,
                    locality,
                    city,
                    statecountry,
                    pincode,
                    website
                from
                    transadd 
                where
                    project_id=$this->ProjID and
                    active=1";

        include('foo/arnav/angels.cgi');

        if ($result = $mysqli->query($sql)) {
            $no = 1;
            while ($row = $result->fetch_row()) {

                /* Format Address */
                $address = "<span style='font-weight:bold;'>
                        $row[1]
                            </span><br>";

                if ($row[2]) $address = $address . $row[2] . ',';
                if ($row[3]) $address = "$address<br>$row[3],";
                if ($row[4]) $address = "$address<br>$row[4],";
                if ($row[5]) $address = "$address<br>$row[5],";
                if ($row[6]) $address = "$address<br>$row[6].";
                if ($row[7]) $address = "$address<br>Pincode: $row[7].";

                if ($row[8]) $address = "$address<br>$row[8]";


                /* Generate the rows */
                echo "<form action='project.cgi' method='get'>
                        <input type='hidden' name='a' value='t1xcontacts-edit-company-form'>
                        <input type='hidden' name='id' value='$row[0]'>
                        <input type='hidden' name='company' value='$row[1]'>
                        <input type='hidden' name='dooradd' value='$row[2]'>
                        <input type='hidden' name='streetadd' value='$row[3]'>
                        <input type='hidden' name='locality' value='$row[4]'>
                        <input type='hidden' name='city' value='$row[5]'>
                        <input type='hidden' name='statecountry' value='$row[6]'>
                        <input type='hidden' name='pincode' value='$row[7]'>
                        <input type='hidden' name='website' value='$row[8]'>
                    <tr class='dataRow'>
                        <td class='dataRowCell1' valign='top'>" . $no++ . "</td>
                        <td class='dataRowCell2' valign='top'>$row[1]</td>
                        <td class='dataRowCell2'>$address</td>
                        <td class='dataRowCell2' valign='top' align='center'>
                                <input type='submit' name='submit' value='Edit'>
                        </td>
            </tr>
            </form>";
            }
            $result->close();
        }


        /* Table close */
        echo "</table>";
    }
}
