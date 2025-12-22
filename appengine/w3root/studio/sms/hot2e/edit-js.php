<script>
    /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 10-Jul-2012				                |
| Updated On: 26-Dec-2012  				                |
+-------------------------------------------------------+
*/

    var dp_cal;

    $(document).ready(function() {

        var co_listX = $('#co_listX').val();
        $('#totalItemCount').html(co_listX);

    });

    /*
    +-------------------------------------------------------+
    | Function: getFilteredList     			            |
    +-------------------------------------------------------+
    */
    function getFilteredList() {

        // console.log('function: getFilteredList.\n');

        var filterDC = $('#filterDC').val();
        var filterBK = $('#filterBK').val();
        var co_listX = $('#co_listX').val();
        var display = 'table-row';
        var thisBlockId = '-X-';
        var thisDisciplineCode = '-X-';

        // console.log('PackageNo: ' + blockno + ' Disciplinecode: ' + disciplinecode + ' co_listX: ' + co_listX);

        for (var i = 0; i < co_listX; i++) {

            // Check visibilityFlag       
            display = 'table-row';

            // This rowId contains the itemId value
            var itemId = $('#rowId-' + i).val();

            // Get the blockId & Disciplinecode of this row
            thisBlockId = $('#bk-' + itemId).val();
            thisDisciplineCode = $('#dc-' + itemId).val();

            // Filter Disciplinecode
            if (filterDC !== 'AllDiscipline') {
                if (thisDisciplineCode != filterDC) {
                    display = 'none';
                }
            }

            // Filter Blocks
            if (filterBK !== 'AllPackages') {
                if (thisBlockId != filterBK) {
                    display = 'none';
                }
            }

            // Deleted items - do not re-display on filter change
            if ($('#active-' + itemId).val() < 1) {
                display = 'none';
            }

            // Turn ON/OFF the table row
            $('#tr-' + i).css("display", display);

        }

    }

    /*
    +-------------------------------------------------------+
    | Function: itemDelete					                |
    +-------------------------------------------------------+
    */
    function itemDelete(rowNo, itemId) {

        // console.log ('itemDelete: ' + itemId);
        var itemcode = $('#ic-' + itemId).val();

        // Get User Confirmation
        var conf = confirm("Confirm Delete: " + itemcode);
        // console.log ('user confirmation: ' + conf);

        /* Confirm: TRUE */
        if (conf == true) {

            // AJAX
            var actionProg = 't2Edit/itemDelete.cgi';
            var dataString = 'a=' + actionProg + '&itemId=' + itemId;

            $.ajax({
                type: "GET",
                url: "gearbox.cgi",
                data: dataString,
                cache: false,
                success: function(rx) {

                    rx = rx.trim();
                    /**/
                    if (rx != "T") {

                        // Error Flag raised.
                        alert('Error: ' + rx);

                    } else {

                        // Sucess.
                        $("#tr-" + rowNo).css("display", "none");
                        $("#active-" + itemId).val("0");
                        // Update the totals count
                        var co_listX = $('#co_listX').val() - 1;
                        $('#co_listX').val(co_listX);
                        $('#totalItemCount').html(co_listX);
                        /**/
                    }

                }
            });

        }


    }

    /*
    +-------------------------------------------------------+
    | Function:buttonCancel                                 |
    +-------------------------------------------------------+
    */
    function buttonCancel() {
        $('#editDialogueBox').css("visibility", "hidden");
        // Reset background for all rows
        var co_listX = $('#co_listX').val();
        for (var i = 0; i < co_listX; i++) {
            $('#tr-' + i).css("background", "white");
        }
        // Clear the itemCode
        $('#itemCode').html('&nbsp;');
        $('#itemCode').css('background', 'white');
        // clear background
        $('#editItemProperties').css('background', 'white');
    }

    /*
    +-------------------------------------------------------+
    | Function: stageComboBox                               |
    +-------------------------------------------------------+
    */
    function stageComboBox(rowNo, stageNo, stageNm, stageList) {

        // Clean the current task options
        var comboBox = document.getElementById("stageComboBox");
        while (comboBox.options.length > 0) {
            comboBox.remove(0);
        }

        // Display the first/default option
        var newOption;
        newOption = document.createElement("option");
        newOption.value = stageNo;
        // newOption.text = stageNo + '. ' + stageNm;
        newOption.text = stageNm;
        // Attempt to add this option
        try {
            comboBox.add(newOption); // IE
        } catch (e) {
            comboBox.appendChild(newOption); // Firefox
        }

        // Remaining Options
        // rx(stageList);
        let stg = [];
        var co_stageX = stageList.length;

        for (var i = 0; i < co_stageX; i++) {

            stg = stageList[i];
            // rx(stg[1]);

            /* */
            if (((stg[0] + " - " + stg[1]) != stageNm) && stg[0]) {

                newOption = document.createElement("option");
                newOption.value = stg[2];
                newOption.text = stg[0] + " - " + stg[1];

                // Attempt to add this option
                try {
                    comboBox.add(newOption); // IE
                } catch (e) {
                    comboBox.appendChild(newOption); // Firefox
                }

            }

        }

        // Highlight Row
        activeRow4Editing(rowNo);

    }

    /*
    +-------------------------------------------------------+
    | Function: activeRow4Editing                           |
    +-------------------------------------------------------+
    */
    function activeRow4Editing(rowNo) {

        var co_listX = $('#co_listX').val();
        var activeRowId = '#tr-' + rowNo;
        // console.log('rowNo: ' + rowNo + ' co_listX: ' + co_listX + ' activeRowId: ' + activeRowId);

        // Reset background for all rows
        for (var i = 0; i < co_listX; i++) {
            $('#tr-' + i).css("background", "white");
        }

        // Highlight this row
        $('#tr-' + rowNo).css("background", "cyan");

    }


    /*
    +-------------------------------------------------------+
    | Function: itemEdit					                |
    +-------------------------------------------------------+
    */
    function itemEdit(rowNo, itemId) {

        // Variables - Meta    
        var itemcode = $('#ic-' + itemId).val();
        var title = $('#tx-' + itemId).val();
        var tdt = $('#tdt-' + itemId).val();
        var sNo = $('#sNo-' + itemId).val();
        var sNm = $('#sNm-' + itemId).val();

        console.log('itemEdit :: rowNo: ' + rowNo + ' itemId: ' + itemId + ' itemEdit: ' + itemId + ' sheetno: ' + itemcode + ' title: ' + title);

        // Load Values on the Dailogue Box
        $('#rowNo').val(rowNo);
        $('#itemId').val(itemId);
        $('#itemCodeNumber').html(itemcode);
        $('#itemTitle').val(title);
        $('#itemTdt').val(tdt);
        $('#oldTargetDt').val(tdt);

        // Combo Box options
        stageComboBox(rowNo, sNo, sNm, stageList);

        // Itemcode display
        $('#itemCode').html(itemcode);
        $('#itemCode').css('background', 'RGB(200,200,200)');

        // Background for edit dialogue box
        $('#editItemProperties').css('background', 'cyan');

        // Ready. Make the dialogue box visible
        $('#editDialogueBox').css("visibility", "visible");

    }
    /*
    +-------------------------------------------------------+
    | Function: buttonUpdate				                |
    +-------------------------------------------------------+
    */
    function buttonUpdate() {

        // Variables - Dialogue Box
        var rowNo = $('#rowNo').val();
        var itemId = $('#itemId').val();
        var newTitle = $('#itemTitle').val();
        var newStageNo = $('#stageComboBox').val();
        var newTdt = $('#itemTdt').val();
        var oldTdt = $('#oldTargetDt').val();

        console.log('buttonUpdate :: itemId: ' + itemId + ' stageNo: ' + newStageNo + ' newTdt: ' + newTdt + ' newTitle: ' + newTitle + ' oldTargetDt: ' + oldTdt);
        //return true;

        /* AJAX */
        var actionProg = 't2Edit/itemUpdate.cgi';
        /* This is the wrong way. & character is not handled properly
         var dataString = 'a=' + actionProg + '&itemId=' + itemId + '&newTitle=' + newTitle + '&newStageNo=' + newStageNo + '&newTdt=' + newTdt + '&oldTdt=' + oldTdt;
         */

        $.ajax({
            type: "GET",
            url: "gearbox.cgi",
            /* data: dataString,*/
            data: {
                a: actionProg,
                itemId: itemId,
                newTitle: newTitle,
                newStageNo: newStageNo,
                newTdt: newTdt,
                oldTdt: oldTdt
            },
            cache: false,
            success: function(rx) {

                console.log("rx: " + rx[1]);
                
                /* Server response */
                if (rx[0] == "F") {
                    alert('Error: ' + rx[1]);
                } else {

                    // Returns new target date - rx
                    //R var itemStageName = stageList[newStageNo][0] + ' - ' + stageList[newStageNo][1];
                    var sname = stageNoX[newStageNo][0];
                    //R console.log('Success: newTitle: ' + newTitle + ' StageNo: ' + newStageNo + ' itemStageName: ' + itemStageName);
                    
                    // Update the Row Cache
                    $('#tx-' + itemId).val(newTitle);
                    $('#tdt-' + itemId).val(rx[1]);
                    $('#sNo-' + itemId).val(newStageNo);
                    //R $('#sNm-' + itemId).val(itemStageName);
                    // Hide Dialogue Box
                    $('#editDialogueBox').css("visibility", "hidden");
                    // Reset itemCode
                    $('#itemCode').html('&nbsp;');
                    $('#itemCode').css('background', 'white');
                    // Background for edit dialogue box
                    $('#editItemProperties').css('background', 'white');
                    // Update Row
                    $('#cellTitle-' + rowNo).html(newTitle);
                    $('#cellStageNo-' + rowNo).html(sname);
                    $('#cellTargetdt-' + rowNo).html(rx[2]);
                    $('#tr-' + rowNo).css("background", "Yellow");

                }
                
            }
        });

    }
</script>