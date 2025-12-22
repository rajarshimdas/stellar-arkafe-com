<?php
$hrmsDeletedUsersToggle = $_POST['hrmsDeletedUsersToggle'];

$users = bdGetEmployeeDataById($mysqli);
$no = 1;

ob_start();

foreach ($users as $u):

    if ($hrmsDeletedUsersToggle != 'Show' && $u['active'] < 1) continue;
    $rowBGcolor = ($u['active'] < 1) ? 'RGB(225,225,225)' : 'white';

?>
    <tr style="background-color: <?= $rowBGcolor ?>;">
        <td><?= $no++ ?></td>
        <td><?= $u['displayname'] ?></td>
        <td><?= bd2hd($u['employee_code']) ?></td>
        <td><?= $u['hrgroup'] ?></td>
        <td><?= $u['reports_to'] ?></td>
        <td><?= bd2hd($u['mobile']) ?></td>
        <td><?= $u['doj'] ?></td>
        <td>
            <a href="#" class="button-18" onclick="sethrmsActiveUser('<?= $u['user_id'] ?>')">Edit</a>
            <!-- <a href="#" class="button-18" onclick="showDxDeleteConfirm('<?= $u['user_id'] ?>','<?= $u['displayname'] ?>')">Delete</a> -->
        </td>
    </tr>
<?php
endforeach;

$tbody = ob_get_clean();

bdReturnJSON(["T", $tbody]);
?>