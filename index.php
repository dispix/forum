<?php

// Start session
session_start();

// Initialize database

/**
 * INITIALISE DATABASE HERE
 */
require('db.php');
$db = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db === false)
	die(mysqli_connect_error());


// Errors
$errors = array();


// Objects autoloader
spl_autoload_register(function($class)
{
	require('models/'.$class.'.class.php');
});


// Load current user and check ban status
if (isset($_SESSION['id']))
{
	$userManager = new UserManager($db);
	$currentUser = $userManager -> readById($_SESSION['id']);

	if (strtotime($currentUser -> getDateBan()) > time())
	{
		session_destroy();
		$_SESSION = array();
	}
}



// Pages
$access_public 	= array('login', 'register');
$access_user 	= array('logout', 'home', 'section', 'subsection', 'topic', 'user');
$access_admin	= array('dashboard', 'dashboard_users', 'dashboard_sections', 'dashboard_subsections', 'dashboard_topics', 'dashboard_messages');
$access_ids		= array('section', 'subsection', 'topic', 'user');


// Handlers
$handlers_public 	= array('login' => 'user', 'register' => 'user');
$handlers_user 		= array('home' => 'section', 'section' => 'subsection', 'subsection' => 'topic', 'topic' => 'message', 'user' => 'user');
$handlers_admin		= array('dashboard_users' => 'user');


if (isset($_GET['page']))
{


	// Logout page
	if ($_GET['page'] === 'logout')
	{
		session_destroy();
		$_SESSION = array();
		header('Location: ?page=login');
		exit;
	}


	// Public pages
	if (in_array($_GET['page'], $access_public) && !isset($_SESSION['id']))
	{
		$page = $_GET['page'];

		if (isset($handlers_public[$_GET['page']]) && !empty($_POST))
		{
			require('controllers/handler/handler_'.$handlers_public[$_GET['page']].'.php');
		}
	}


	// Members pages
	else if (in_array($_GET['page'], $access_user) && isset($_SESSION['id']))
	{
		if (in_array($_GET['page'], $access_ids))
		{
			if (isset($_GET['id']))
			{
				$page = $_GET['page'];
			}
			else
			{
				header('Location: ?page=home');
				exit;
			}
		}
		else
		{
			$page = $_GET['page'];
		}
		if (isset($handlers_user[$_GET['page']]) && !empty($_POST))
		{
			require('controllers/handler/handler_'.$handlers_user[$_GET['page']].'.php');
		}
	}

	// Admin pages
	else if (in_array($_GET['page'], $access_admin) && isset($_SESSION['id']) && ($currentUser -> getStatus()) > 0)
	{
		$page = $_GET['page'];

		if (isset($handlers_admin[$_GET['page']]) && !empty($_POST))
		{
			require('controllers/handler/handler_'.$handlers_admin[$_GET['page']].'.php');
		}
	}


	// Default pages
	else
	{
		if (isset($_SESSION['id']))
		{
			header('Location: ?page=home');
			exit;
		}
		else
		{
			header('Location: ?page=login');
			exit;
		}
	}
}
else
{
	if (isset($_SESSION['id']))
	{
		$page = 'home';
	}
	else
	{
		$page = 'login';
	}
}


require('controllers/skel.php');

/* TURN OFF SESSION SUCCESS*/
$_SESSION['success'] = "";
