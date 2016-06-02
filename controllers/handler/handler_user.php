<?php
if (isset($_GET['page']))
{

	// Register function
	if ($_GET['page'] == 'register')
	{
		if (isset($_POST['register_email'], $_POST['register_login'], $_POST['register_password'], $_POST['register_passwordRepeat']))
		{
			$manager 	= new UserManager($db);
			$res 		= $manager -> create($_POST['register_email'], $_POST['register_login'], $_POST['register_password'], $_POST['register_passwordRepeat']);

			if (is_array($res))
			{
				$errors = $res;
				$email = $_POST['register_email'];
				$login = $_POST['register_login'];
			}
			else
			{
				$_SESSION['success'] = "Inscription réussie avec succès, vous pouvez dès à présent vous connecter :)";
				header('Location: ?page=login');
				exit;
			}
		}
	}

	// Login function
	if ($_GET['page'] == 'login')
	{
		if (isset($_POST['login_login'],$_POST['login_password']))
		{
			$userManager = new UserManager($db);
			$res = $userManager -> readByLogin($_POST['login_login']);
			if (is_string($res))
			{
				$errors[] = $res;
				$login = $_POST['login_login'];
			}
			else
			{
				$user = $res;
				$res = $user -> verifPassword($_POST['login_password']);
				if (is_string($res))
				{
					$errors[] = $res;
					$login = $_POST['login_login'];
				}
				else
				{
					if (strtotime($user -> getDateBan()) < time())
					{
						$_SESSION['id'] = $user -> getId();
						$_SESSION['success'] = "Login successfull";
						$userManager -> updateDateLastConnect($user);
						header('Location: ?page=home');
						exit;
					}
					else
					{
						$errors[] = 'You are banned until '.date('d-m-Y', strtotime($user -> getDateBan()));
					}
				}
			}
		}
	}


	// Administrate user
	if ($_GET['page'] == 'dashboard_users')
	{
		$userManager = new UserManager($db);

		// Update user
		if (isset($_POST['user_id'], $_POST['user_status'], $_POST['user_dateBan'])) {
			$user = $userManager -> readById($_POST['user_id']);

			if (is_object($user))
			{
				$user -> setStatus($_POST['user_status']);
				$user -> setDateBan($_POST['user_dateBan']);

				$res = $userManager -> update($user);

				if (is_object($res))
				{
					header('Location: ?page=dashboard_users&success=true');
					exit;
				}
				else
				{
					$errors[] = $res;
				}
			}
			else
			{
				$errors[] = $user;
			}
		}


		// Delete user
		if (isset($_POST['user_id'], $_POST['user_delete'])) {
			$user = $userManager -> readById($_POST['user_id']);

			if (is_object($user))
			{
				$res = $userManager -> delete($user);
				if ($res === true)
				{
					header('Location: ?page=dashboard_users&success=true');
					exit;
				}
				else
				{
					$errors[] = $res;
				}
			}
			else
			{
				$errors[] = $user;
			}
		}
	}


	// Update user
	if ($_GET['page'] == 'user')
	{
		if (isset($_SESSION['id']))
		{
			if(isset($_POST['avatar'], $_POST['email'], $_POST['password-old'], $_POST['password-new'], $_POST['password-new2']))
			{
				$errors = array();
				$userManager = new UserManager($db);
				$retour = $userManager -> readById($_SESSION['id']);
				if (is_string($retour))
				{
					$errors[] = $retour;
				}
				else
				{
					$user = $retour;

					$email = $_POST['email'];
					$errors[] = $user -> setEmail($email);


					if ($_POST['password-new'] != "")
					{
						$retour = $user -> verifPassword($_POST['password-old']);
						if(is_string($retour))
						{
							$errors[] = $retour;
						}
						else
						{
							$errors[] = $user -> setPassword($_POST['password-new'], $_POST['password-new2']);
						}
					}


					if ($_POST['avatar'] == "")
					{
						//Avatar par défaut
						$avatar = "http://orig05.deviantart.net/0355/f/2013/248/1/a/meepo_icon_by_imkb-d6l512x.png";
					}
					else
					{
						$avatar = $_POST['avatar'];
					}
					$errors[] = $user -> setAvatar($avatar);


					$errors = array_filter($errors, function($value)
					{
						return $value !== true;
					});
					if (count($errors) == 0)
					{
						$retour = $userManager -> update($user);
						if (is_string($retour))
						{
							$errors[] = $retour;
						}
						else
						{
							$_SESSION['success'] = "Modifications enregistrées";
							header("Location: ?page=user&id=".$_SESSION['id']);
							exit;
						}
					}
				}
			}
		}
		else
		{
			$_SESSION['success'] = "Délai de connexion expiré. Veuillez vous reconnecter";
			header ('Location: ?page=login');
			exit;
		}
	}
}
?>
