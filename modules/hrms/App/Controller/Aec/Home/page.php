<?php /* home page router | 20-Jan-25 */

# $bdUserType is set in BD/Controller/Config.php
$homepage = $bdUserType[$bdUserTypeId][1];
echo 'utid: '. $bdUserTypeId .' | Home: ' . $homepage;

# Redirect to homepage
header("Location:" . BASE_URL . 'user-modules/' . $homepage);
