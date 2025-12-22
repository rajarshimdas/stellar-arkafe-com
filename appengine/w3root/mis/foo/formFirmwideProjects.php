 <form action="<?= $base_url ?>studio/index.cgi" method="POST">
     <input type="hidden" name="a" value="reports-timesheet-firmwideProjects">
     <table width="100%" cellpadding="2" border="0">
         <tr>
             <td style="height:45px;text-align:right;">
                Project timesheet summary for month of:
            </td>
             <td width="120px">
                 <input type="month" name="mo" value="<?= date("Y-m") ?>" min="2024-04" max="<?= date("Y-m") ?>">
             </td>
             <td width="50px" align="center">
                 <input type="submit" name="go" value="Get">
             </td>
         </tr>
     </table>
 </form>