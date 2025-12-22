<?php /*
  +-------------------------------------------------------+
  | Rajarshi Das					|
  +-------------------------------------------------------+
  | Created On:                                           |
  | Updated On: 05-Jun-2013                               |
  +-------------------------------------------------------+
  |	Display info for testing			|
  +-------------------------------------------------------+
  | $DisplaySessionInfo settings in config.php            |
  +-------------------------------------------------------+
 */
?>
<style>    
    .headerRowCell1 { background: cyan; }
    .headerRowCell1, .headerRowCell2, .dataRowCell1, .dataRowCell2 { 
        padding-left: 10px; 
    }
</style>

<!-- Session -->
<div align="center">
    <br>
    <table class="tabulation" style="width: 60%;">

        <tr class="headerRow">
            <td class="headerRowCell1" colspan="3" align="center">Session</td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1" width="30%">Session ID</td>
            <td class="dataRowCell2" width="30%">$sessionid</td>
            <td class="dataRowCell2"><?php echo $sessionid; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Login Time Out</td>
            <td class="dataRowCell2">$LoginTimeOut</td>
            <td class="dataRowCell2"><?php echo $LoginTimeOut . " seconds"; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Navigation</td>
            <td class="dataRowCell2">$_GET['a']</td>
            <td class="dataRowCell2"><?php echo $_GET["a"]; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Active Tab</td>
            <td class="dataRowCell2">$activetab</td>
            <td class="dataRowCell2"><?php echo $activetab; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Active Menu</td>
            <td class="dataRowCell2">$activetabmenu</td>
            <td class="dataRowCell2"><?php echo $activemenu; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Active Body</td>
            <td class="dataRowCell2">$activetabmenucontent</td>
            <td class="dataRowCell2"><?php echo $activemenucontent; ?></td>
        </tr>
    </table>
    <br>   

    <!-- Project -->
    <table class="tabulation" style="width: 60%;">
        <tr class="headerRow">
            <td class="headerRowCell1" colspan="3" align="center">Project</td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1" width="30%">Project Name</td>
            <td class="dataRowCell2" width="30%">$projectname</td>
            <td class="dataRowCell2"><?php echo $projectname; ?></td>
        </tr>        
        <tr class="dataRow">
            <td class="dataRowCell1">Project ID</td>
            <td class="dataRowCell2">$projectid [$pid]</td>
            <td class="dataRowCell2"><?php echo $projectid; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Job Code</td>
            <td class="dataRowCell2">$jobcode</td>
            <td class="dataRowCell2"><?php echo $jobcode; ?></td>
        </tr>
    </table>
    <br>

    <table class="tabulation" style="width: 60%;">
        <tr class="headerRow">
            <td class="headerRowCell1" colspan="3" align="center">Project Domain</td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1" width="30%">Domain ID</td>
            <td class="dataRowCell2" width="30%">$domainid</td>
            <td class="dataRowCell2"><?php echo $domainid; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Domain Name</td>
            <td class="dataRowCell2">$domainname</td>
            <td class="dataRowCell2"><?php echo $domainname; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Corporate Name</td>
            <td class="dataRowCell2">$corporatename</td>
            <td class="dataRowCell2"><?php echo $corporatename; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Corporate Address</td>
            <td class="dataRowCell2">$corporateaddress</td>
            <td class="dataRowCell2"><?php echo $corporateaddress; ?></td>
        </tr>
    </table>
    <br>

    <table class="tabulation" style="width: 60%;">

        <tr class="headerRow">
            <td class="headerRowCell1" colspan="3" align="center">User Info</td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1" width="30%">Fullname</td>
            <td class="dataRowCell2" width="30%">$fullname</td>
            <td class="dataRowCell2"><?php echo $fullname; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Loginname</td>
            <td class="dataRowCell2">$loginname</td>
            <td class="dataRowCell2"><?php echo $loginname; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">UserID</td>
            <td class="dataRowCell2">$userid [$uid]</td>
            <td class="dataRowCell2"><?php echo $userid; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">HR Group</td>
            <td class="dataRowCell2">$hrgroup [$hrgroup_id]</td>
            <td class="dataRowCell2"><?php echo $hrgroup . ' ['.$hrgroup_id.']'; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Role ID</td>
            <td class="dataRowCell2">$roleid</td>
            <td class="dataRowCell2"><?php echo $roleid; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Department ID</td>
            <td class="dataRowCell2">$dept_id</td>
            <td class="dataRowCell2"><?php echo $dept_id; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Department Name</td>
            <td class="dataRowCell2">$dept_name</td>
            <td class="dataRowCell2"><?php echo $dept_name; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Branch ID</td>
            <td class="dataRowCell2">$branch_id</td>
            <td class="dataRowCell2"><?php echo $branch_id; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Branch Name</td>
            <td class="dataRowCell2">$branch_name</td>
            <td class="dataRowCell2"><?php echo $branch_name; ?></td>
        </tr>
        <!-- Added on 06-Jun-2013 -->
        <tr class="dataRow">
            <td class="dataRowCell1">email</td>
            <td class="dataRowCell2">$emailid</td>
            <td class="dataRowCell2"><?php echo $emailid; ?></td>
        </tr>
    </table>   
    <br>

    <table class="tabulation" style="width: 60%;">

        <tr class="headerRow">
            <td class="headerRowCell1" colspan="3" align="center">System</td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1" width="30%">Server Operating System</td>
            <td class="dataRowCell2" width="30%">$systemOS</td>
            <td class="dataRowCell2"><?php echo $systemOS; ?></td>
        </tr>
        <tr class="dataRow">
            <td class="dataRowCell1">Email</td>
            <td class="dataRowCell2">$adminEmailId</td>
            <td class="dataRowCell2"><?php echo $adminEmailId; ?></td>
        </tr>
    </table>
</div>


