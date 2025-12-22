<script type="text/javascript">
$(function() {
    $('#mCal2').datepicker( {
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

<?php
// Current Month
$monthCurrent   = date("n");
$yearCurrent    = date("Y");

// Zero day of current month is the last day of Previous month
$monthZeroDay       = mktime(0, 0, 0, $monthCurrent, 0, $yearCurrent);
$monthPrevious      = date("Y-m", $monthZeroDay);
$monthPreviousCal   = date("M Y", $monthZeroDay);
// echo 'Previous Month: '.$monthPrevious;

?>

<table width="100%" cellspacing="4px" cellpadding="4px">
    <form action="concert-mis.cgi" method="GET">
        <input type="hidden" name="a" value="attendenceReport">
        <tr>
            <td>
                <table width="100%" cellspacing="" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Biometric Attendence Records:</td>
                        <td width="120px">

                            <input id="mCal2" 
                                   name="mCal2"
                                   type="text"
                                   value="<?php echo $monthPreviousCal; ?>"
                                   style="width:100%"
                                   >
                            
                        </td>
                        
                        <td width="50px" align="left">
                            <input type="submit" name="go" value="Get">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>