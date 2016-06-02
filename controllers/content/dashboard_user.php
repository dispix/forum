<?php 

$userManager = new UserManager($db);
$users = $userManager -> read();

for ($i=0, $c = count($users); $i < $c; $i++)
{ 
	$user = $users[$i];
	require('views/content/dashboard_user.phtml');
}