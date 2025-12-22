<dialog id="dxProfileSettings">
    <table class='dxTable' style="width:300px;">
        <tr>
            <td>
                Profile Settings
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxProfileSettings')">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <style>
                    table.dxTableForm {
                        text-align: right;
                        border-spacing: 4px;
                    }
                </style>
                <table class='dxTableForm' style="width:100%">
                    <tr>
                        <td colspan="2" style="text-align: left; border-bottom: 1px solid gray;">Emergency Contact</td>
                    </tr>
                    <tr>
                        <td style="width:70px;">Name</td>
                        <td>
                            <input type="text" name="cname" style="width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td>Phone No</td>
                        <td>
                            <input type="text" name="pno" style="width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left;">
                            <button onclick="setProfileSettings()">Save</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</dialog>