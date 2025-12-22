<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   31-Dec-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$dxUserId   = (int)$_POST["dxUserId"];
$year       = (int)$_POST["finStartYear"];
$rs         = empty($_POST['dxUserMHCostRs']) ? 0 : $_POST['dxUserMHCostRs'];
$ps         = empty($_POST['dxUserMHCostPs']) ? 0 : $_POST['dxUserMHCostPs'];

$rs = (int)$rs;
$ps = (int)$ps;
/*
$rs = floor($rs);
$ps = floor($ps);
*/
/*
rdReturnJsonHttpResponse(
    'f', ['F', $_POST['dxUserMHCostRs']]
);
*/

if (empty($dxUserId) || empty($year)) {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "setUserManhourCost failed."]
    );
}
// Integers
if (!is_int($dxUserId)  || !is_int($year) || !is_int($rs) || !is_int($ps)) {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "setUserManhourCost failed."]
    );
}

// paise is 0 to 99
if ($ps < 0 || $ps > 99) {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Enter 0 to 99 paise"]
    );
}

// Cost-in-paise
$costperhour = ($rs * 100) + $ps;

/*
manhourcost;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| id          | mediumint(9) | NO   | PRI | NULL    | auto_increment |
| finyear     | year(4)      | NO   | MUL | NULL    |                |
| user_id     | mediumint(9) | NO   |     | NULL    |                |
| costperhour | mediumint(9) | NO   |     | 0       |                |
+-------------+--------------+------+-----+---------+----------------+
*/

// Check existing record in db
$flag = 0;
$query = "select 1 as flag, id from manhourcost where finyear = '$year' and user_id = '$dxUserId'";

$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
    $flag = $row['flag'];
    $id = $row['id'];
}

// Insert or Update query
if ($flag < 1) {
    $query = "insert into manhourcost 
                (finyear, user_id, costperhour)
            values
                ('$year','$dxUserId','$costperhour')";
} else {
    $query = "update manhourcost 
            set 
                costperhour = '$costperhour'
            where
                id = '$id'";
}

$mysqli = cn2();
if ($mysqli->query($query)) {
    rdReturnJsonHttpResponse(
        '200',
        ['T', ($costperhour / 100)]
    );
} else {
    rdReturnJsonHttpResponse(
        '200',
        ['F', "Failed to save data. $query"]
    );
}
