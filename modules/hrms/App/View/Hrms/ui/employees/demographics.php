<?php
die();

$hrGp   = bdgetHRGroups($mysqli);
$users  = bdGetUsers($mysqli);
// var_dump($users);

foreach ($users as $u) {
    // Present & Past

    // HR Group
    if ($u['active'] > 0) {
        $hrgroup[$u['hrgroup']] = empty($hrgroup[$u['hrgroup']]) ? 1 : $hrgroup[$u['hrgroup']] + 1;
        $team[$u['userhrgroup_id']][] = $u['displayname'];
    }
}
// rx($hrgroup);
// rx($team);
?>
<style>
    .rd-table tr td {
        vertical-align: text-top;
        height: fit-content;
    }
</style>
<table class="rd-table" style="width: 550px;">
    <thead>
        <tr>
            <td>No</td>
            <td>HR Group</td>
            <td>Count</td>
            <td>Members</td>
        </tr>
    </thead>
    <?php
    $no = 1;
    foreach ($hrGp as $g):
        if ($g['active'] < 1) continue;

        $hrId = $g['id'];

        $hrGpMembers = empty($team[$hrId]) ? [] : $team[$hrId];
        $hrGpMembersCo = count($hrGpMembers);

        $srno = 1;
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td style="text-align: left;"><?= $g['name'] ?></td>
            <td><?= $hrGpMembersCo ?></td>
            <td style="text-align: left;">
                <?php
                foreach ($hrGpMembers as $e):
                    // var_dump($e);
                ?>
                    <div><?= $srno++ . ". " . $e ?></div>
                <?php
                endforeach;
                ?>
            </td>
        </tr>


    <?php endforeach; ?>
</table>