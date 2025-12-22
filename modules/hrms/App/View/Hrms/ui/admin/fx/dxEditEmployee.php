<?php
// rx($route);
// echo "hrmsActiveUser: $hrmsActiveUser";
$u = $users[(int)$hrmsActiveUser]; // Set in Session.php
// rx($u);
// rx($_SESSION);
?>
<style>
    .ui-dialog-box {
        background-color: whitesmoke;
        border: 20px solid whitesmoke;
        border-radius: 5px;
        width: 500px;
        margin: auto;
    }

    .ui-dialog-box tr td:first-child {
        color: gray;
        width: 150px;
        text-align: right;
    }

    .ui-dialog-box caption {
        padding: 35px 0px 10px 15px;
        text-align: left;
        color: gray;
        font-family: 'Roboto Bold';
    }
</style>

<div class="rd-banner-sticky">
    <?= $u['displayname'] ?>
</div>
<div style="margin: auto; text-align: center;">

    <!-- Login -->
    <table class="ui-dialog-box">
        <caption>
            Login
        </caption>
        <tr>
            <td>Login name</td>
            <td><input type="text" value="<?= $u['loginname'] ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="" class="button-18">Reset Password</a></td>
        </tr>
    </table>

    <!-- Personal Details -->
    <table class="ui-dialog-box">
        <caption>
            Personal Details
        </caption>
        <tr>
            <td>Display name</td>
            <td><input type="text" value="<?= $u['displayname'] ?>" /></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Middle Name:</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Date of Birth:</td>
            <td><input type="date" /></td>
        </tr>
        <tr>
            <td>Gender:</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Blood Group:</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Employment Details -->
    <table class="ui-dialog-box">
        <caption>
            Employment Details
        </caption>
        <tr>
            <td>Type</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Department</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Designation</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Reports To</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Date of Joining</td>
            <td><input type="date" /></td>
        </tr>
        <tr>
            <td>Date of Exit</td>
            <td><input type="date" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Emergency -->
    <table class="ui-dialog-box">
        <caption>
            Emergency Contact
        </caption>
        <tr>
            <td>Name</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Contact -->
    <table class="ui-dialog-box">
        <caption>
            Contact
        </caption>
        <tr>
            <td>Mobile Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Personal Email</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Current Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Permanent Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Accounts Detail -->
    <table class="ui-dialog-box">
        <caption>
            Accounts Detail
        </caption>
        <tr>
            <td>Bank Name</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Account Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>IFSC</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>PAN</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>PF Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>ESI Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>UAN Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Current Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Permanent Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

</div>