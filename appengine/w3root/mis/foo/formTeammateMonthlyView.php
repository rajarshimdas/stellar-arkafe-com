<script type="text/javascript">
$(function() {
    $('#mCal').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});
</script>
<style>
.ui-datepicker-calendar {
    display: none;
    }
</style>
<table width="1000px" cellspacing="4px" cellpadding="4px">
    <form action="teammateMonthlyView.cgi" method="GET">
        <tr>
            <td style="background: #e3f2d5; border: 1px solid black">
                <table width="100%" cellspacing="" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Teammate Timesheet (Monthly):</td>
                        <td width="250px">
                            <?php include 'foo/comboTeammates.php'; ?>
                        </td>                        
                        <td width="25px" align="right">
                            Month:
                        </td>
                        <td width="80px">
                            <input id="mCal" name="mCal" type="text" value="<?php echo date('M Y'); ?>" style="width:100%">
                        </td>
                        <td>
                            <input type="submit" name="go" value="Get" width="50px">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>
