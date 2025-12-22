<?php /* Home Page
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 20-Jan-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
function showHeader($hostname,$displayLogoutLink,$displayHomeLink) {
    ?>
    
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="274px">
            <img src="/da/arkafe-a-dark.png" alt="Concert" style="height: 35px;">
        </td>
        <td style="text-align: center;">
            <a class="button" href="go2home.cgi">Home</a>
                <?php
                // Home button
                if ($displayHomeLink > 0) {
                    echo '&nbsp;<a href="concert.cgi" style="text-decoration:none;border:1px solid black;padding:2px;background:#aaccf7;color:black">Back</a>&nbsp;';
                }
                // Logout button
                if ($displayLogoutLink > 0) {
                    echo '&nbsp;<a href="logout.cgi" style="text-decoration:none;border:1px solid black;padding:2px;background:#aaccf7;color:black">Logout</a>&nbsp;';
                }
                ?>
        </td>
        <td width="274px" style="text-align: center;">
            <img src="/da/logo-company.jpg" alt="Company Logo">
        </td>
    </tr>
</table>

    <?php
}

?>

