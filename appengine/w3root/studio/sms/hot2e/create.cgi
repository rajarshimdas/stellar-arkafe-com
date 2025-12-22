<?php  /* Studio Management System Module
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 30-Jun-2012				                |
+-------------------------------------------------------+
*/
include 'hot2e/create-form.cgi';
?>
<style>
    button {
        height: 32px;
    }
</style>

<table>
    <tr>
        <td><img src="/da/icons/32/lightbulb.png" alt="Tip"></td>
        <td>
            <button onclick="ncDialogShow()">Naming Convention</button>
        </td>
        <td>
            <button onclick="icDialogShow()">Floor Numbers</button>
        </td>
    </tr>
</table>

<dialog id='nc'>
    <table style="width: 950px;">
        <tr>
            <td>Naming Convention</td>
            <td style="width: 30px;">
                <button onclick="ncDialogClose()">X</button>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="/da/itemcode.png" alt="Itemcode" style="width:100%;">
            </td>
        </tr>
    </table>
</dialog>

<dialog id='ic'>
    <table style="width: 550px;">
        <tr>
            <td>Naming Convention</td>
            <td style="width: 30px;">
                <button onclick="icDialogClose()">X</button>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="/da/itemcode-fn.jpg" alt="Floor Numbers" style="width: 100%;">
            </td>
        </tr>
    </table>
</dialog>

<script>
    function ncDialogClose() {
        document.getElementById("nc").close()
    }

    function ncDialogShow() {
        document.getElementById("nc").showModal()
    }

    function icDialogClose() {
        document.getElementById("ic").close()
    }

    function icDialogShow() {
        document.getElementById("ic").showModal()
    }
</script>