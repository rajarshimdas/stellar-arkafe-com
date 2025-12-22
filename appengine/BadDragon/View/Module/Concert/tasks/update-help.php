<p>
    This example explains how to edit a task using the downloaded csv file.
</p>
<style>
    table#excel {
        width: 100%;
        margin: auto;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    table#excel tr {
        height: 35px;
    }

    table#excel tr td {
        border: 1px solid lightgray;
        padding: 0px 10px;
        text-align: center;
    }

    .alert {
        color: red;
    }

    #help tr {
        height: 30px;
        background-color: whitesmoke;
    }

    #help tr td {
        padding: 0px 6px;
    }

    #help tr.editable {
        background-color: yellow;
    }
</style>


<table id="excel">
    <tr style="background-color: grey; color: white; text-align:center;">
        <td style="width: 35px; padding: 0px;"></td>
        <td style="width: 40px;">A</td>
        <td style="width: 70px;">B</td>
        <td style="width: 70px;">C</td>
        <td style="width: 70px;">D</td>
        <td>E</td>
        <td style="width: 70px;">F</td>
        <td style="width: 70px;">G</td>
        <td style="width: 70px;">H</td>
        <td style="width: 70px;">I</td>
    </tr>

    <tr>
        <td style="background-color: grey; color: white; text-align:center;">1</td>
        <td>Flag</td>
        <td>TaskId</td>
        <td>Scope</td>
        <td>Milestone</td>
        <td style="text-align: left;">Work Description</td>
        <td>Completed%</td>
        <td>Target%</td>
        <td>AH</td>
        <td>AM</td>
    </tr>

    <tr style="background-color:white;">
        <td style="background-color: grey; color: white; text-align:center;">2</td>
        <td>@</td>
        <td>2540</td>
        <td>AD</td>
        <td>CC</td>
        <td style="text-align: left;">Wing K Door vendor dwg check</td>
        <td>45</td>
        <td>100</td>
        <td>1</td>
        <td>45</td>
    </tr>

</table>

<p>
    Fields are describe below. Fields shown in yellow background are editable.
</p>

<table id="help" style="width: 100%;">
    <tr>
        <td style="width: 150px;">
            Flag
        </td>
        <td style="width: 100px;">
            @
        </td>
        <td>
            Change the # symbol to @ symbol. This indicates your command to update the task defined in this row. Rows having # symbol are not updated.
        </td>
    </tr>
    <tr>
        <td>TaskId</td>
        <td>2540</td>
        <td>Do not edit this field.</td>
    </tr>
    <tr>
        <td>Scope</td>
        <td>AD</td>
        <td>Scope shortcode. Readonly.</td>
    </tr>
    <tr>
        <td>Milestone</td>
        <td>CC</td>
        <td>Milestone shortcode. Readonly.</td>
    </tr>
    <tr class="editable">
        <td>Work Description</td>
        <td>Wing K...</td>
        <td>The task to be performed.</td>
    </tr>
    <tr class="editable">
        <td>Completed%</td>
        <td>45</td>
        <td>Last month completed percentage.</td>
    </tr>
    <tr class="editable">
        <td>Target%</td>
        <td>100</td>
        <td>Current month work completion target percent.</td>
    </tr>
    <tr class="editable">
        <td>AH</td>
        <td>1</td>
        <td>Utilized Hours end of last month + Additional Allotted Hours to do the task.</td>
    </tr>
    <tr class="editable">
        <td>AM</td>
        <td>45</td>
        <td>Utilized Minutes end of last month + Additional Allotted Minutes to do the task.</td>
    </tr>
</table>

<p>
    * Double check before hitting the save button. Update cannot be reversed.
</p>