<form action="sysadmin.cgi" method="POST">
    <input type="hidden" name="a" value="<?php echo $activemenu; ?>">
    <input type="hidden" name="x" value="module-tick">
    <table class="formTBL" style="background:#fff255">
        <tr>
            <td colspan="2">
                Module Access
            </td>
        </tr>
        <tr>
            <td width="30%">
                Module
            </td>
            <td>
                <select name="module">
                    <option value="0">-- Select Module --</option>
                    <?php
                    $query = "select `id`,`name` from `modules` where `active` = 1 order by `name`";
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_row()) {
                            echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                        }
                        $result->close();
                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                User:
            </td>
            <td>
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers("-- Select User --", 0, $mysqli);
                ?>
            </td>

        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="go" value="Authorize" style="width:150px"></td>
        </tr>
    </table>
</form>

<!-- Tabulate -->
<table class="tabulation" style="width:100%" border="1">

    <tr class="headerRow">
        <td class="headerRowCell2" style="width:250px">Module</td>
        <td class="headerRowCell2" colspan="2">Authorized User</td>
    </tr>

    <?php
    $query = "select
                t1.name,
                t2.fullname,
                t3.modules_id,
                t3.user_id
            from
                modules as t1,
                users as t2,
                moduletick as t3
            where
                t3.modules_id = t1.id and
                t3.user_id = t2.id
            order by
                t1.name,
                t2.fullname";

    $rowNo = 1;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            echo '<tr id="tr'.$rowNo.'" class="dataRow">
                    <td class="dataRowCell2">'.$row[0].'</td>
                    <td class="dataRowCell2" style="width:250px;border-right:0px">'.$row[1].'</td>
                    <td style="border-left:0px">
                        <img src="/da/icons/delete.png" alt="Delete" style="cursor:pointer" title="Delete"
                            onclick="javascript:itemDelete('.$rowNo++.','.$row[2].','.$row[3].',\''.$row[0].'\',\''.$row[1].'\');"
                        >
                    </td>
                </tr>
                ';

        }

        $result->close();
    }

    ?>

</table>

<script type="text/javascript">
    function itemDelete (rowNo, moduleId, userId, module, userNm) {
        // Get User Confirmation
        var conf = confirm("Delete: " + module + ' - ' + userNm);
        // Confirm: TRUE
        if(conf == true){
            // AJAX
            var actionProg = 'module-ux';
            var dataString = 'a=' + actionProg + '&mid=' + moduleId + '&uid=' + userId;
            // Send request to server
            $.ajax({
                type: "GET",
                url: "engine.cgi",
                data: dataString,
                dataType: "text",
                cache: false,
                success: function(rx){
                    console.log(rx);
                    $("#tr" + rowNo).css("display", "none");
                }
            });
        }
    }
</script>