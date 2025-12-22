<?php

function getLeaveDates(
    array $empLeaveRec,
    string $monthStartDate,
    string $monthEndDate,
    array $holidays
): array {
    // die(rx($empLeaveRec));
    // rx($empLeaveRec);

    $ptoDates = [];

    // Cache for chain lookup
    $fullDayLeaves = [];
    $endFullDayLeaves = [];

    // $co = count($empLeaveRec);
    // echo '['.$co.']<br>';

    foreach ($empLeaveRec as $r) {

        $startDt    = $r['from_dt'];
        $endDt      = $r['end_dt'];
        $statuscode = $r['statuscode'];
        $statusId   = $r["status_id"];

        // Filter Leaves for this Month
        if (
            $endDt >= $monthStartDate
            && $startDt <= $monthEndDate
            && $statusId == '10'
        ) {

            $sx     = $r['from_dt_units'];
            $ex     = $r['end_dt_units'];
            $nod    = (float)$r['nod_units'];
            $attrId = $r['leave_attr_id'];
            $typeId = $r['leave_type_id'];


            if ($typeId == 9) {
                // Short Leave
                // echo 'shortleave<br>';
                $ptoDates['shortleave'][$sx][] = $startDt;
            } elseif ($startDt == $endDt) {
                // Single Day Leave
                if ($sx != 'F') {
                    $ptoDates['halfday'][$attrId][] = $startDt;
                } else {
                    $ptoDates['fullday'][$attrId][] = $startDt;
                    $fullDayLeaves[] = $startDt;
                }
            } else {
                // Multi Day Leave

                // echo "<div>$startDt : $sx | $endDt : $ex | nod: $nod | statuscode: $statuscode [$statusId]</div>";
                $days = getAllDatesInRange($startDt, $endDt);
                // var_dump($days);

                // Remove start and end dates
                $daysInBetween = [];
                $daysInBetween = array_slice($days, 1, -1);


                ## If start day is a half day Second(and more) day is a holiday?
                # 20-Dec-2025
                if ($sx != 'F') {
                    $daysInBetween = trimStartDtHolidays($daysInBetween, $holidays);
                } else {
                    $fullDayLeaves[] = $startDt;
                }


                ## If End day is a half day Second(and more) day is a holiday?
                # 20-Dec-2025
                if ($ex != 'F') {
                    $daysInBetween = trimEndDtHolidays($daysInBetween, $holidays);
                } else {
                    $fullDayLeaves[] = $endDt;
                }


                ## StartDt half day?
                if (isDateInRange($startDt, $monthStartDate, $monthEndDate)) {
                    if ($sx != 'F')
                        $ptoDates['halfday'][$attrId][] = $startDt;
                    else
                        $ptoDates['fullday'][$attrId][] = $startDt;
                }


                ## In between dates
                foreach ($daysInBetween as $d) {
                    if (isDateInRange($d, $monthStartDate, $monthEndDate)) {
                        $ptoDates['fullday'][$attrId][] = $d;
                        $fullDayLeaves[] = $d;
                    }
                }


                ## EndDt half day?
                if (isDateInRange($endDt, $monthStartDate, $monthEndDate)) {
                    if ($ex != 'F') {
                        $ptoDates['halfday'][$attrId][] = $endDt;
                    } else {
                        $ptoDates['fullday'][$attrId][] = $endDt;
                    }
                }
            }

            // All End dates for chainLeaveLookup
            if ($ex == 'F' && isDateInRange($endDt, $monthStartDate,$monthEndDate)) {
                $endFullDayLeaves[] = [
                    'date' => $endDt,
                    'attrId' => $attrId,
                ];
            }
        }
    }

    return empty($ptoDates) ? [] : chainLeaveLookup($ptoDates, $fullDayLeaves, $endFullDayLeaves, $holidays);
}


## chainLeaveLookup
#
function chainLeaveLookup(
    $ptoDates,
    $fullDayLeaves,
    $endFullDayLeaves,
    $holidays
) {

    // die(rx($holidays));
    // die(rx($ptoDates));
    // die(rx($endFullDayLeaves));

    $rx = $ptoDates;

    foreach ($endFullDayLeaves as $e) {
        $edt = $e['date'];
        $dt = [];
        $nextdt = getNextISODate($edt);
        // die(rd($nextdt));
        while (in_array($nextdt, $holidays)) {
            $dt[] = $nextdt;
            $nextdt = getNextISODate($nextdt);
        }
        // die(rx($dt));
        // rd($nextdt);
        if (in_array($nextdt, $fullDayLeaves)) {
            foreach ($dt as $d) {
                # code...
                $rx['fullday'][$e['attrId']][] = $d;
            }
        }
    }

    return $rx;
}


# discard dates outside this month
function isDateInRange($dt, $monthStartDate, $monthEndDate)
{
    return ($dt >= $monthStartDate && $dt <= $monthEndDate) ? true : false;
}

# date display formatting
function dateFx($dt)
{
    // return date('d-M-y D', strtotime($dt));
    // return date('d D', strtotime($dt));
    return date('d', strtotime($dt));
}


function trimEndDtHolidays($leaves, $holidays)
{
    $rx = [];
    $co = empty($leaves) ? 0 : count($leaves);

    // Backward lookup
    $flag = 0;
    $co = empty($leaves) ? 0 : (count($leaves) - 1);

    for ($n = $co; $n >= 0; $n--) {
        // rd($lx[$n]);

        if (!in_array($leaves[$n], $holidays) || $flag > 0) {
            $rx[] = $leaves[$n];
            $flag++;
        }
    }

    return empty($rx) ? [] : array_reverse($rx);
}


function trimStartDtHolidays($leaves, $holidays)
{
    $rx = [];
    $co = empty($leaves) ? 0 : count($leaves);

    // Forward lookup
    $flag = 0;
    for ($i = 0; $i < $co; $i++) {
        if (!in_array($leaves[$i], $holidays) || $flag > 0) {
            $rx[] = $leaves[$i];
            $flag++;
        }
    }

    return empty($rx) ? [] : $rx;
}
