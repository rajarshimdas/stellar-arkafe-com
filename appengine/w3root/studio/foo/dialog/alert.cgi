<dialog id="dxAlertBox">
    <table class='dxTable' style="width:400px;">
        <tr>
            <td id="dxAlertBoxH" style="font-weight: bold; color: red;">
                <!-- Alert -->
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxAlertBox')">
            </td>
        </tr>
        <tr>
            <td id="dxAlertBoxM">
                <!-- Message goes here -->
            </td>
        </tr>
    </table>
</dialog>
<script>
    function dxShow(dxId) {
        // console.log(dxId + ' show')
        document.getElementById(dxId).showModal()
        document.getElementById("avatarFile").blur()
    }

    function dxClose(dxId) {
        // console.log(dxId + ' close')
        document.getElementById(dxId).close()
    }

    function showAlert(header, message) {

        const dxAlertBox = document.getElementById("dxAlertBox")

        document.getElementById("dxAlertBoxH").innerHTML = header
        document.getElementById("dxAlertBoxM").innerHTML = message

        dxAlertBox.showModal()
    }
</script>