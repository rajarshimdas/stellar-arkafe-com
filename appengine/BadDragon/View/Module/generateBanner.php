<?php
/*
+-----------------------------------------------+
| Rajarshi Das                                  |
+-----------------------------------------------+
| Created On: 16-May-2024                       |
| Updated On:                                   |
+-----------------------------------------------+
*/
if (!isset($moduleName)) {
    die("Server Error: moduleName missing.");
}

?>
<style>
    a.bnLink {
        text-decoration: none;
        color: RGB(220,220,220);
    }

    a.bnLink:hover {
        color: white;
    }

    #banner tr td {
        border: 0px solid white;
        padding: 0px 5px;
    }

    .fa5button:hover {
        color: white;
        opacity: 1;
    }
</style>

<table id="banner" style="width: 100%; text-align: left;" cellpadding="0" cellspacing="0">
    <tbody>
        <tr style="height: 45px;">
            <td style="width: 50px; color: gray;">
                <img src="<?= BASE_URL ?>da/arkafe-a.png" alt="Arkafe" width="28px">
            </td>
            <td style="width: 250px; color: white;">
                <?= $companyname ?>
            </td>
            <td>
                <div style="color:white;text-align:right"><?= $fullname ?></div>
            </td>
            <td valign="middle" style="width:80px;text-align:right;">
                <div style="color:whitesmoke;">
                    <?php
                    echo '<a class="button-18" href="' . $base_url . 'studio/home.cgi">Home</a>';
                    if (isset($returnURL)) {
                        echo '&nbsp;|&nbsp;<a class="button-18" href="' . $returnURL[1] . '">' . $returnURL[0] . '</a>';
                    }

                    ?>
                </div>
            </td>
            <td style="width:35px;">
                <?= '<a class="bnLink" href="' . $base_url . 'studio/logout.cgi"><img class="fa5button" src="' . BASE_URL . 'da/fa5/power-off-w.png" style="width:15px;height:15px;" valign="middle"></a>' ?>
            </td>
        </tr>
    </tbody>
</table>