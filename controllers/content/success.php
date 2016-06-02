<?php 
if (isset($_SESSION['success']) && $_SESSION['success'] != "")
{
	require('views/content/success.phtml');
}
?>