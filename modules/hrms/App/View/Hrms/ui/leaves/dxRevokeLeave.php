<dialog id="dxRevokeLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="2">Revoke Leave</td>
                <td style="text-align: right;">
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxRevokeLeave')">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Applied On</td>
                <td id="dxAppliedOn"></td>
                <td></td>
            </tr>
            <tr>
                <td>Reason</td>
                <td id="dxReason"></td>
                <td></td>
            </tr>
            <tr>
                <td style="width:100px">Revoke</td>
                <td>
                    <select name="dxRevokeRx" id="dxRevokeRx">

                        <?php
                        $revokeRx = [
                            '-- Select a Reason --',
                            'Changed my plans',
                            'Leave not availed',
                            'Applied by mistake',
                        ];

                        for ($i = 0; $i < count($revokeRx); $i++) {
                            echo '<option value="' . $i . '">' . $revokeRx[$i] . '</option>';
                        }

                        ?>
                    </select>
                </td>
                <td style="width: 30px;"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button class="button-18" style="width: 80px;" onclick="revokeLeave()">Revoke</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxRevokeLeave')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>
