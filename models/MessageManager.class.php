<?php 

class MessageManager
{

	// Properties
	private $db;

	
	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Functions


	// Create function
	public function create(User $user, Topic $topic, $content)
	{
		$message 	= new Message($this -> db);
		$errors 	= array();
		$errors[] 	= $message -> setAuthor($user);
		$errors[]	= $message -> setTopic($topic);
		$errors[]	= $message -> setIdAuthor($user -> getId());
		$errors[]	= $message -> setIdTopic($topic -> getId());
		$errors[] 	= $message -> setContent($content);
		$errors 	= array_filter($errors, function($value)
		{
			return $value !== true;
		});
		if (count($errors) == 0)
		{
			$idAuthor 	= intval($message -> getIdAuthor());
			$idTopic 	= intval($message -> getIdTopic());
			$content 	= mysqli_escape_string($this -> db, $message -> getContent());
			$query 		= '	INSERT INTO message (id_author, id_topic, content)
							VALUES ("'.$idAuthor.'","'.$idTopic.'","'.$content.'")';
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
					return 'Internal server error';
				}
			}
			else
			{
				return mysqli_error($this -> db);
			}
		}
		else
		{
			return $errors;
		}
	}


	// Read functions
	public function read($n = 0)
	{
		$n 		= intval($n);
		
		if ($n > 0)
		{
			$query = '	SELECT * 
						FROM message 
						ORDER BY `date_created` ASC 
						LIMIT '.$n;
		}
		else
		{
			$query = '	SELECT * 
						FROM message 
						ORDER BY `date_created` ASC';
		}
		
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$messages = array();
			
			while ($message = mysqli_fetch_object($res, 'Message', array($this -> db)))
			{
				$messages[] = $message;
			}
			if (count($messages) > 0)
			{
				return $messages;
			}
			else
			{
				return 'No message to show';
			}
		}
		else
		{
			return 'Error 01 : Database error';
		}
	}
	public function readById($id)
	{
		$query 	= 'SELECT * FROM message WHERE id = '.$id;
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			if ($message = mysqli_fetch_object($res, 'Message', array($this -> db)))
			{
				return $message;
			}
			else
			{
				return 'Message not found';
			}
		}
		else
		{
			return 'Error 02 : Database error';
		}
	}
	public function readByAuthor(User $author)
	{
		$idAuthor 	= $author -> getId();
		$query 		= '	SELECT * 
						FROM message 
						WHERE id_author = '.$idAuthor.'
						ORDER BY date_created DESC';
		$res 		= mysqli_query($this -> db, $query);

		if ($res)
		{
			$messages = array();
			while ($message = mysqli_fetch_object($res, 'Message', array($this -> db)))
			{
				$messages[] = $message;
			}
			return $messages;
		}
		else
		{
			return 'Error 03 : Database error';
		}
	}
	public function readByTopic(Topic $topic)
	{
		$idTopic 	= $topic -> getId();
		$query 		= '	SELECT * 
						FROM message 
						WHERE id_topic = '.$idTopic.'
						ORDER BY date_created ASC';
		$res 		= mysqli_query($this -> db, $query);

		if ($res)
		{
			$messages = array();
			while ($message = mysqli_fetch_object($res, 'Message', array($this -> db)))
			{
				$messages[] = $message;
			}
			return $messages;
		}
		else
		{
			return 'Error 08 : Database error';
		}
	}
	public function readByDate($min, $max)
	{
		$query 	= '	SELECT * 
					FROM message 
					WHERE date_created >= '.$min.' AND date_created <='.$max.'
					ORDER BY date_created ASC';
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$messages = array();
			while ($message = mysqli_fetch_object($res, 'Message', array($this -> db)))
			{
				$messages[] = $message;
			}
			return $messages;
		}
		else
		{
			return 'Error 04 : Database error';
		}
	}


	// Update function
	public function update(Message $message)
	{
		$id 		= $message -> getId();
		$idAuthor 	= $message -> getIdAuthor();
		$idTopic 	= $message -> getIdTopic();
		$content 	= $message -> getContent();
		$query 		= '	UPDATE message
						SET id_author = "'.$id_author.'", id_topic = "'.$idTopic.'", content = "'.$content.'"
						WHERE id = '.$id;
		$res 		= mysqli_query($this -> db, $query);

		if ($res)
		{
			return $this -> readById($id);
		}
		else
		{
			return 'Error 05 : Database error';
		}
	}

	public function updateViewers(Message $message, User $user)
	{

		$idMessage	= $message->getId();
		$idUser		= $user->getId();

		$query 		= "	INSERT INTO link_user_message(id_user, id_message)
						VALUES ('".$idUser."', '".$idMessage."')
						ON DUPLICATE KEY UPDATE id_user = id_user";
		$data 		= mysqli_query($this->db, $query);
		if($data)
		{
			return true;
		}
		else
		{
			return "Update viewers failed";
		}
	}




	// Delete function
	public function delete(Message $message)
	{
		$id 	= $message -> getId();
		$query 	= 'DELETE FROM message WHERE id = '.$id;
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			return true;
		}
		else
		{
			return 'Error 07 : Database error';
		}
	}
}