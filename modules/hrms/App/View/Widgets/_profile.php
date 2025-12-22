<?php
// Avatar
$avatar = $user['avatar'];
if ($avatar == '-') {
    $avatar = BASE_URL . 'da/fa5/user-circle-avatar.png';
} else {
    $avatar = BASE_URL . 'login/box/avatar/' . $avatar;
}

// Color code Active & Deleted Users
$bgColor = ($user['active'] < 1) ? 'style="background-color:gray;"' : 'background-color: var(--rd-nav-light);';

?>
<style>
    table#rd-profile-box {
        width: 100%;
        color: white;
        text-align: left;
    }

    table#rd-profile-box tr td {
        /* border: 1px solid white; */
        height: 120px;
    }

    table#rd-profile-info {
        width: 100%;
        border-spacing: 0px;
    }

    table#rd-profile-info tr td {
        font-size: 0.8em;
        font-family: 'Roboto Bold';
        height: 25px;
        /* border: 1px solid white; */
        /* border-bottom: 1px solid var(--rd-nav-light); */
        padding: 0px 15px;
    }

    /* 
    table#rd-profile-info tr:first-child td {
        border-top: 1px solid var(--rd-nav-light);
    } 
    */

    table#rd-profile-info tr td:first-child {
        text-align: right;
        color: lightblue;
        font-family: "Segoe UI", "Roboto", "Arial";
    }
</style>

<div class="rd-banner-sticky" <?= $bgColor ?>>

    <table id="rd-profile-box">
        <tr>
            <td style="text-align:center;width: 150px;">
                <img id="avatar" src="<?= $avatar ?>" width="75px" height="75px">
            </td>
            <td style="width: 750px;">
                <div style="font-size:1.5em;font-family:'Roboto Bold';">
                    <?= $user['displayname'] ?>
                </div>
                <div>
                    <?= $user['hrgroup'] ?>
                </div>
            </td>
            <td>
                <table id='rd-profile-info'>
                    <tr>
                        <td>Reports To</td>
                        <td><?= $user['reports_to'] ?></td>
                    </tr>
                    <tr>
                        <td style="width: 160px;">Email</td>
                        <td><?= $user['emailid'] ?></td>
                    </tr>
                    <tr>
                        <td>Mobile No</td>
                        <td>9876543210</td>
                    </tr>
                    <tr>
                        <td>Emergency Contact No</td>
                        <td>9876654310</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</div>