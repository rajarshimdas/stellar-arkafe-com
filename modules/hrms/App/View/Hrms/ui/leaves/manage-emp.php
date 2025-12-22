<?php
// die(rx($route));
// rx($route);
$thisUId = empty($route->parts[5]) ? 0 : $route->parts[5];
$leave_type_id = 2;
?>
<style>
    table.rd-table-card {
        border-collapse: collapse;
        /* background-color: rgb(24 118 120); */
        width: 100%;
        margin: auto;
    }

    table.rd-table-card thead tr {
        background-color: var(--rd-form-bg);
    }

    table.rd-table-card tr td {
        border: 1px solid gray;
        text-align: center;
        /* color: white; */
        height: 30px;
    }
</style>
<?php
if ($thisUId < 1) {
    require_once __DIR__ . '/manage-app.php';
} else {
    require_once W3APP . '/View/Widgets/wxLeaveCardMo.php';
}
?>