<table width="100%" style="background:#fff255" cellspacing="0px" border="0">
    <tr>
        <td style="background:RGB(150,150,150);color:white;font-weight:bold;font-size:110%">
            TimeDESK UID mapping
        </td>
    </tr>
</table>

<?php

// Get Users data
$query = "select id, fullname from users where active = 1 order by fullname";
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {
        $uX[] = array ("uid" => $row[0], "fname" => $row[1]);
    }
    $result->close();
}

// Get iouidmap data
for ($e = 0; $e < count($uX); $e++) {

    $timedesk_uid = 0;
    $query = "select timedesk_uid from iouidmap where user_id = ".$uX[$e]["uid"];

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $timedesk_uid = $row[0];
        }
        $result->close();
    }

    $userX[] = array ("uid" => $uX[$e]["uid"], "fname" => $uX[$e]["fname"], "timeUID" => $timedesk_uid);

}

?>

<table class="tabulation" style="width:100%">

    <tr class="headerRow" style="height:35px">
        <td class="headerRowCell1" style="padding-left: 15px;">Name</td>
        <td class="headerRowCell2" colspan="2">TimeDesk<br>UID</td>
    </tr>

    <?php

    for ($i = 0; $i < count($userX); $i++) {

        $inputId = 'T'.$userX[$i]["uid"];

        echo '
            <tr class="dataRow" style="height:35px">

                <td class="dataRowCell1" width="300px" style="padding-left: 15px;">'.$userX[$i]["fname"].'</td>
                <td class="dataRowCell2" width="80px" style="border-right:0px">
                    <input
                        type="text" id="'.$inputId.'" value="'.$userX[$i]["timeUID"].'" style="border:0px"
                        onkeyup="javascript:onValueChange('.$userX[$i]["uid"].')"
                        >
                </td>
                <td class="dataRowCell2" align="right" >
                    <img id="S'.$userX[$i]["uid"].'" src="/da/icons/save-red.png" alt="Save" style="cursor:pointer;visibility:hidden" title="Save"
                       onclick="javascript:updateUID('.$userX[$i]["uid"].');"
                    >&nbsp;<img id="D'.$userX[$i]["uid"].'" src="/da/icons/delete.png" alt="Delete" style="cursor:pointer" title="Delete"
                       onclick="javascript:deleteUID('.$userX[$i]["uid"].', '."'".$userX[$i]["fname"]."'".');"
                    >
                </td>

            </tr>';
    }
    ?>

</table>

<script type="text/javascript">

    function onValueChange (userId){
        // console.log("S" + userId);
        $("#S" + userId).css("visibility", "visible");
    }


    function updateUID (userId) {

        var timeUID = $("#T" + userId).val();
        // console.log('uid: ' + userId + ' timeUID: ' + timeUID);        

        // AJAX
        var actionProg = 'timedesk-uid';
        var dataString = 'a=' + actionProg + '&uid=' + userId + '&timeUid=' + timeUID;

        // Send request to server
        $.ajax({
            type: "GET",
            url: "engine.cgi",
            data: dataString,
            dataType: "text",
            cache: false,
            success: function(rx){
                console.log(rx);
                $("#S" + userId).css("visibility", "hidden");

            }
        });

    }

    function deleteUID (userId, fname) {
        console.log('deleteUID: ' + userId);

        // Get User Confirmation
        var conf = confirm("Confirm delete UID Mapping for " + fname);
        // Confirm: TRUE
        if(conf == true){

            console.log ("User clicked Ok.");
            
            // AJAX
            var actionProg = 'timedesk-del';
            var dataString = 'a=' + actionProg + '&uid=' + userId;
            
            // Send request to server
            $.ajax({
                type: "GET",
                url: "engine.cgi",
                data: dataString,
                dataType: "text",
                cache: false,
                success: function(rx){
                    console.log(rx);
                    //$("#tr" + rowNo).css("display", "none");
                    $("#T" + userId).val(rx);
                }
            });   

        } else {
            console.log ("User clicked No.");
        }
    }
    
</script>

