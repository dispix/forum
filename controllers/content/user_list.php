<?php
	$messages = $messageManager->readByAuthor($user);
	if (count($messages) == 0)
	{
		$errors[] = "Nothing to show";
	}
	else
	{
		$i = 0;
		while ($i < 3 && $i < count($messages))
		{
			$message = $messages[$i];
			require('views/content/user_list.phtml');
			$i++;
		}
	}
?>