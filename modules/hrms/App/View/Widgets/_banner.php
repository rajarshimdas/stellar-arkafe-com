<style>
    table#rd-banner {
        width: 100%;
        max-width: var(--rd-max-width);
        margin: auto;
        color: white;
    }

    table#rd-banner tr {
        height: 35px;
    }

    a.bnLink {
        text-decoration: none;
        color: black;
    }

    a.bnLink:hover {
        color: gray;
    }
</style>
<table id="rd-banner">
    <tr>
        <td style="width:50px;text-align:center;">
            <img src="<?= BASE_URL ?>da/arkafe-a.png" alt="Arkafe" width="28px">
        </td>
        <td style="width: 350px;"><?= "$companyNameFull | $year" ?></td>
        <td>
            <div style="text-align:right"><?= $displayname ?></div>
        </td>
        <td valign="middle" style="width:80px;text-align:center;">
            <div>
                <a class="button-18" href="<?= BASE_URL . 'studio/home.cgi' ?>">Home</a>
            </div>
        </td>
        <td style="width:45px;">
            <a class="bnLink" href="<?= BASE_URL ?>studio/logout.cgi">
                <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/power-off-w.png" style="width:20px;height:20px;" valign="middle">
            </a>
        </td>
    </tr>
</table>