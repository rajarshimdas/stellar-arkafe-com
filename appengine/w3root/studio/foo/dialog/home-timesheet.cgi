<dialog id="dxViewTS">
        <form method="POST" action="index.cgi" id="dxFormTS">
            <input type="hidden" name="a" value="reports-timesheet-getMyTimesheets">
            <table class="dxTable">
                <tr>
                    <td colspan="2" style="font-weight: bold;">View Timesheets</td>
                    <td style="text-align: right;">
                        <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" alt="Close" onclick="dxViewTSclose()">
                    </td>
                </tr>
                
                <tr>
                    <td style="width: 100px;">Date Range</td>
                    <td style="width: 100px;">
                        <?php
                        $today = date("Y-m-d");

                        $todayDay = date("d");
                        $todayMonth = date("m");
                        $todayYear = date("Y");

                        // 45 days ago     
                        $from_date = date("Y-m-d", mktime(0, 0, 0, $todayMonth, $todayDay - 45, $todayYear));

                        ?>
                        <input type="date" id="dxCal1" name="dxCal1" min="2023-12-01" max="<?= $today ?>" value="<?= $from_date ?>">
                    </td>
                    <td style="width: 100px;">
                        <input type="date" id="dxCal2" name="dxCal2" min="2023-12-01" max="<?= $today ?>" value="<?= $today ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <button onclick="dxGetTS()">Get Timesheets</button>
                    </td>
                </tr>
            </table>
        </form>
    </dialog>




    <dialog id="dxUserTS">
        <form method="POST" action="index.cgi" id="dxFormTS">
            <input type="hidden" name="a" value="reports-timesheet-getMyTimesheets">
            <table class="dxTable">
                <tr>
                    <td colspan="2" style="font-weight: bold;">View Team Member Timesheets</td>
                    <td style="text-align: right;">
                        <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" alt="Close" onclick="dxUserTSclose()">
                    </td>
                </tr>
                <tr>

                    <td>Name</td>
                    <td colspan="2">
                        <select name="dxUid" id="dxUid" style="box-sizing: border-box; width:100%">
                            <option value="0">-- Select --</option>
                            <?php
                            for ($i = 0; $i < count($team); $i++) {
                                if ($team[$i]["uid"] != $userid && $team[$i]["uid"] > 0) {
                                    echo '<option value="' . $team[$i]["uid"] . '">' . $team[$i]["fullname"] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px;">Date Range</td>
                    <td style="width: 100px;">
                        <?php
                        $today = date("Y-m-d");

                        $todayDay = date("d");
                        $todayMonth = date("m");
                        $todayYear = date("Y");

                        // 45 days ago     
                        $from_date = date("Y-m-d", mktime(0, 0, 0, $todayMonth, $todayDay - 45, $todayYear));

                        ?>
                        <input type="date" id="dxCal1" name="dxCal1" min="2023-12-01" max="<?= $today ?>" value="<?= $from_date ?>">
                    </td>
                    <td style="width: 100px;">
                        <input type="date" id="dxCal2" name="dxCal2" min="2023-12-01" max="<?= $today ?>" value="<?= $today ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <button onclick="dxGetTS()">Get Timesheets</button>
                    </td>
                </tr>
            </table>
        </form>
    </dialog>