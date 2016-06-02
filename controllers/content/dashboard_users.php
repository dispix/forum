<?php 
$userManager = new UserManager($db);

$users 			= $userManager -> read();

require('views/content/dashboard_users.phtml');