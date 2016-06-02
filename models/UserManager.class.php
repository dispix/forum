<?php

class UserManager
{

	// Properties
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Functions


	// Create user
	public function create($email, $login, $password, $passwordRepeat)
	{
		$user 		= new User();
		$errors 	= array();
		$errors[]	= $user -> setEmail($email);
		$errors[] 	= $user -> setLogin($login);
		$errors[] 	= $user -> setPassword($password, $passwordRepeat);
		$errors 	= array_filter($errors, function($value)
		{
			return $value !== true;
		});
		if (count($errors) == 0)
		{
			$email 		= mysqli_real_escape_string($this -> db, $user -> getEmail());
			$login 		= mysqli_real_escape_string($this -> db, $user -> getLogin());
			$password 	= $user -> getHash();
			$query		= '	INSERT INTO user (email, login, password)
							VALUES ("'.$email.'","'.$login.'","'.$password.'")';
			$res		= mysqli_query($this -> db, $query);

			if ($res)
			{
				$id = mysqli_insert_id($this -> db);

				if ($id)
				{
					return $this -> readById($id);
				}
				else
				{
					return '01 : Database error';
				}
			}
			else
			{
				return 'User already exist';
			}
		}
		else
		{
			return $errors;
		}
	}


	// Read all users
	public function read($n = 0)
	{
		$n 		= intval($n);

		if ($n > 0)
		{
			$query = '	SELECT *
						FROM user
						ORDER BY `date_registered` ASC
						LIMIT '.$n;
		}
		else
		{
			$query = '	SELECT *
						FROM user
						ORDER BY `date_registered` ASC';
		}

		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$users = array();

			while ($user = mysqli_fetch_object($res, 'User'))
			{
				$users[] = $user;
			}
			return $users;
		}
		else
		{
			return 'Database error';
		}
	}


	// Read user by id
	public function readById($id)
	{
		$query 	= "SELECT * FROM user WHERE id = '".$id."'";
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			if ($user = mysqli_fetch_object($res, 'User'))
			{
				return $user;
			}
			else
			{
				return 'User not found';
			}
		}
		else
		{
			return 'Error 02 : Internal server error';
		}
	}


	// Read user by email
	public function readByEmail($email)
	{
		$email 	= mysqli_escape_string($this -> db, $email);
		$query 	= 'SELECT * FROM user WHERE email = "'.$email.'"';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			if ($user = mysqli_fetch_object($res, 'User'))
			{
				return $user;
			}
			else
			{
				return 'User not found';
			}
		}
		else
		{
			return '06 : Database error';
		}
	}


	// Read user by login
	public function readByLogin($login)
	{
		$login 	= mysqli_escape_string($this -> db, $login);
		$query 	= 'SELECT * FROM user WHERE login = "'.$login.'"';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			if ($user = mysqli_fetch_object($res, 'User'))
			{
				return $user;
			}
			else
			{
				return 'User not found';
			}
		}
		else
		{
			return '03 : Database error';
		}
	}


	// Read user by status
	public function readByStatus($status)
	{
		$status = intval($status);
		$query 	= '	SELECT *
					FROM user
					WHERE status = "'.$status.'"
					ORDER BY login DESC';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$users = array();
			while ($user = mysqli_fetch_object($res, 'User'))
			{
				$users[] = $user;
			}
			return $users;
		}
		else
		{
			return '08 : Database error';
		}
	}


	// Read user by Date registered
	public function readByDateRegistered($min, $max)
	{
		$min 	= intval($min);
		$max 	= intval($max);
		$query 	= '	SELECT *
					FROM user
					WHERE date_registered >= '.$min.' AND date_registered <= '.$max.'
					ORDER BY login DESC';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$users = array();
			while ($user = mysqli_fetch_object($res, 'User'))
			{
				$users[] = $user;
			}
			return $users;
		}
		else
		{
			return '07 : Database error';
		}
	}


	// Read user by date last connect
	public function readByDateLastConnect($min, $max)
	{
		$min 	= intval($min);
		$max 	= intval($max);
		$query 	= '	SELECT *
					FROM user
					WHERE date_last_connect > '.$min.' AND date_last_connect < '.$max.'
					ORDER BY login DESC';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$users = array();
			while ($user = mysqli_fetch_object($res, 'User'))
			{
				$users[] = $user;
			}
			return $users;
		}
		else
		{
			return '07 : Database error';
		}
	}

	/**
	 * @param User $user
	 * @return array
	 */
	public function checkNewMessage( User $user)
	{
		$id_user 	= $user->getId();

		$query = "	SELECT 		id_message
					FROM 		link_user_message
					WHERE 		id_message NOT IN
						(		SELECT 	id_message
								FROM link_user_message
								WHERE id_user = '".$id_user."'
						)
					GROUP BY 	id_message
					";
		$data = mysqli_query($this->db, $query);
		if($data)
		{
			$newMessages = array();

			while($result = mysqli_fetch_assoc($data)){
				$messageManager = new MessageManager($this->db);
				$newMessages[] = $messageManager->ReadById($result['id_message']);

			}
			return $newMessages;
		}
		else
		{
			return "DB connect errors";
		}
	}


	// Update user
	public function update(User $user)
	{
		$id 		= intval($user -> getId());
		$email 		= mysqli_escape_string($this -> db, $user -> getEmail());
		$login 		= mysqli_escape_string($this -> db, $user -> getLogin());
		$hash 		= $user -> getHash();
		$status 	= intval($user -> getStatus());
		$avatar 	= mysqli_escape_string($this -> db, $user -> getAvatar());
		$dateBan 	= date('Y-m-d H:i:s', $user -> getDateBan());
		$query 		= "	UPDATE user
									SET 	email = '".$email."',
												login = '".$login."',
												`password` = '".$hash."',
												`status` = ".$status.",
												avatar = '".$avatar."',
												date_ban = '".$dateBan."'
									WHERE id = ".$id;
		$res 		= mysqli_query($this -> db, $query);

		if ($res)
		{
			return $this -> readById($id);
		}
		else
		{
			return '04 : Database error';
		}
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public function updateDateLastConnect(User $user)
	{
		$id  =  $user->getId();
		$query = "	UPDATE user
					SET date_last_connect = NOW()
					WHERE id = '".$id."'";
		$data = mysqli_query($this->db, $query);

		if($data)
		{
			return true;
		}
		else
		{
			return "Update date last connect failed !";
		}
	}

	// Delete user
	public function delete(User $user)
	{
		$id 	= intval($user -> getId());
		$query 	= 'DELETE FROM user WHERE id = '.$id;
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			return true;
		}
		else
		{
			return '05 : Database error';
		}
	}
}
