<?php
	if (isset($_SESSION['id']))
	{
		if (isset($_GET['id']))
		{
			$id = intval($_GET['id']);
			$userManager = new UserManager($db);
			$retour = $userManager->readById($id);
			if (is_object($retour))
			{
				$user = $retour;
				$topicManager = new TopicManager($db);
				$messageManager = new MessageManager($db);
				$sectionManager = new SectionManager($db);
				$subsectionManager = new SubsectionManager($db);
				if ($_SESSION['id'] == $id)
				{
					if (!isset($avatar))
					{
						$avatar = $user->getAvatar();
					}
					if (!isset($email))
					{
						$email = $user->getEmail();
					}
					require('views/content/profile.phtml');
				}
				else
				{
					require('views/content/user.phtml');
				}
			}
			else
			{
				//$errors[] = $retour;
				header ('Location: ?page=home');
				exit;
			}
		}
		else
		{
			header ('Location: ?page=home');
			exit;
		}
	}
	else
	{
		$_SESSION['success'] = "Délai de connexion expiré. Veuillez vous reconnecter";
		header ('Location: ?page=login');
		exit;
	}
?>