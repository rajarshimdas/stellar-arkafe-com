<dialog id="dxProfile">
    <table class='dxTable' style="width:400px;">
        <tr>
            <td>
                Avatar
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxProfile')">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <!-- https://www.php.net/manual/en/features.file-upload.post-method.php -->
                <form enctype="multipart/form-data" action="index.cgi" method="POST">
                    <input type="hidden" name="a" value="user-avatar-profilePic">

                    <table id="dxAvatar">
                        <tr>
                            <td style="text-align:center;">
                                <img src="<?= $avatar ?>" width="80px">
                            </td>
                            <td colspan="2">
                                <div style="font-weight:bold;"><?= $fullname ?></div>
                                <div><?= $hrgroup ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:120px;text-align: center;">Set Avatar</td>
                            <td>
                                <!-- MAX_FILE_SIZE must precede the file input field -->
                                <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
                                <!-- Name of input element determines name in $_FILES array -->
                                <input id="avatarFile" name="avatarFile" type="file">
                            </td>
                            <td style="width:40px; text-align: center;">
                                <input type="image" src="<?= $base_url ?>da/fa5/save.png" height="32px">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:center;color:gray;font-size:0.9em">
                                Please upload a 400 X 400 pixel photo in jpg or png
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
</dialog>