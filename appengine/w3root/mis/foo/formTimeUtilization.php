 <form action="<?= $base_url ?>studio/index.cgi" method="POST">
     <input type="hidden" name="a" value="reports-timesheet-tsTimeUtilization">
     <table width="100%" cellpadding="2" border="0">
         <tr>
             <td align="right" style="height:45px">Time Utilization Graph for month of:</td>
             <td width="120px">
                 <input type="month" name="month1" value="<?= date("Y-m") ?>" min="2024-02" max="<?= date("Y-m") ?>">
             </td>
             <td width="50px" align="center">
                 <input type="submit" name="go" value="Get">
             </td>
         </tr>
     </table>
 </form>