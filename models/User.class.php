<?php

class User
{

	// Properties
	private $id;
	private $email;
	private $login;
	private $password;
	private $status;
	private $avatar;
	private $date_registered;
	private $date_last_connect;
	private $date_ban;
	private $messages;


	// Getters
	public function getId()
	{
		return $this -> id;
	}
	public function getEmail()
	{
		return $this -> email;
	}
	public function getLogin()
	{
		return $this -> login;
	}
	public function getHash()
	{
		return $this -> password;
	}
	public function getStatus()
	{
		return $this -> status;
	}
	public function getAvatar()
	{
		return $this -> avatar;
	}
	public function getDateRegistered()
	{
		return $this -> date_registered;
	}
	public function getDateLastConnect()
	{
		return $this -> date_last_connect;
	}
	public function getDateBan()
	{
		return $this -> date_ban;
	}
	public function getIdMessage()
	{
		return $this -> messages;
	}


	// Setters
	public function setEmail($email)
	{
		if (strlen($email) > 5 && strlen($email) < 63)
		{
			if (preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,5}$#", $email))
			{
				$this ->email = $email;
				return true;
			}
			else
			{
				return 'Email format invalid';
			}
		}
		else
		{
			return 'Email format invalid';
		}
	}
	public function setLogin($login)
	{
		if (strlen($login) > 3 && strlen($login) < 32)
		{
			if (preg_match("#[a-zA-Z0-9]+[ -_']*$#", $login))
			{
				$this ->login = $login;
				return true;
			}
			else
			{
				return 'Username not valid';
			}
		}
		else
		{
			return "Login must be between 4 and 31 characters";
		}
	}
	public function setPassword($password, $passwordRepeat)
	{
		if (strlen($password) > 7 && strlen($password) < 32)
		{
			if ($password == $passwordRepeat)
			{
				$this ->password = password_hash($password, PASSWORD_DEFAULT);
				return true;
			}
			else
			{
				return "Passwords don't match";
			}
		}
		else
		{
			return 'Password must be between 8 and 31 characters';
		}
	}
	public function setStatus($status)
	{
		if ($status == 0)
		{
			$this -> status = 0;
			return true;
		}
		else if ($status == 1)
		{
			$this -> status = 1;
			return true;
		}
		else if ($status == 2)
		{
			$this -> status = 2;
			return true;
		}
		else
		{
			return 'Invalid status';
		}
	}
	public function setAvatar($avatar)
	{
		if ( $avatar_proprietes = @getimagesize($avatar) )
		{
			if ($avatar_proprietes[0] > 200 || $avatar_proprietes[1] > 200)
			{
				return "Invalid avatar dimensions (max 200x200 px)";
			}
			else if (@filesize($avatar) > 1e6)
			{
				return "Invalid avatar size (max 25 kB)";
			}
			else
			{
				$this -> avatar = $avatar;
				return true;
			}
		}
		else
		{
			return "Invalid filetype";
		}
	}
	public function setDateLastConnect($date_last_connect)
	{
		if (ctype_digit(strtotime($date_last_connect)))
		{
			$this -> date_last_connect = $date_last_connect;
			return true;
		}
		else
		{
			return 'Format needs to be a timestamp';
		}
	}
	public function setDateBan($date_ban)
	{
		if (ctype_digit(strtotime($date_ban)))
		{
			$this -> date_ban = strtotime($date_ban);
			return true;
		}
		else
		{
			return 'Format needs to be a timestamp';
		}
	}
	public function verifPassword($password)
	{
		if ($retour = password_verify($password, $this->password))
		{
			return $retour;
		}
		else
		{
			return 'Incorrect password';
		}
	}

	/**
	 * @param $message
	 * @return bool and fill $this->messages
	 * @return string error
	 */
	public function setMessages(Message $message)
	{
		if(is_object($message))
		{
			$this->messages[] = $message;
			return true;
		}
		else
		{
			return "Param need to be a array";
		}
	}
}
