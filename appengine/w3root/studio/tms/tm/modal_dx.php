<style>
    #formTS tr td:first-child {
        text-align: right;
        vertical-align: middle;
    }

    #formTS tr td {
        border: 0px solid red;
    }

    #formTS select,
    #formTS input,
    #formTS textarea {
        width: 100%;
        box-sizing: border-box;
    }

    .ibox {
        border: 1px solid gray;
        border-radius: 3px;
        background-color: white;
        width: calc(100% - 4px);
        padding: 2px;
        line-height: 22px;
    }
</style>

<table id="formTS" width="100%" style="border:0px;font-size:95%;background:#cdcddd;border-spacing:4px">

    <!-- Date & Time -->
    <tr>
        <td style="width:140px;">Date & Time:</td>
        <td style="width:150px;">
            <div class="ibox" id="dxDate"></div>
        </td>

        <td style="width:150px;">
            <div class="ibox" id="dxHour"></div>
        </td>

        <td style="width:150px;">
            <div class="ibox" id="dxMin"></div>
        </td>
    </tr>

    <!-- Project -->
    <tr>
        <td>Project:</td>
        <td colspan="3">
            <div class="ibox" id="dxProject"></div>
        </td>

    </tr>

    <!-- Project Stage -->
    <tr>
        <td>Milestone:</td>
        <td colspan="3">
            <div class="ibox" id="dxMilestone"></div>
        </td>
    </tr>


    <tr>
        <td style="vertical-align:top">Work Description:</td>
        <td colspan="3">
            <textarea style="height:60px;" id="dxWork" readonly></textarea>
        </td>
    </tr>

    <!-- New Field : Percent Completed -->
    <tr>
        <td>Details:</td>
        <td colspan="2">
            <div class="ibox" id="dxWorkFrom"></div>
        </td>
        <td colspan="1">
            <div class="ibox" id="dxPercent"></div>
        </td>
    </tr>

</table>