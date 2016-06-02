<?php 

class Message
{


	// Properties
	private $id;
	private $id_author;
	private $author;
	private $id_topic;
	private $topic;
	private $content;
	private $date_created;
	private $viewers = array();
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this->db = $db;
	}


	// Getters
	public function getId()
	{
		return $this -> id;
	}
	public function getIdAuthor()
	{
		return $this -> id_author;
	}
	public function getAuthor()
	{
		if (!$this  ->  author)
		{
			$id_author 	= intval($this  ->  id_author);
			$query 		= 'SELECT * FROM user WHERE id ='.$id_author;
			$res 		= mysqli_query($this  ->  db, $query);

			if ($res && ($author = mysqli_fetch_object($res, 'User')))
			{
				$this  ->  author = $author;
			}
		}
		return $this  ->  author;
	}
	public function getIdTopic()
	{
		return $this -> id_topic;
	}
	public function getTopic()
	{
		if (!$this  ->  topic)
		{
			$id_topic 	= intval($this  ->  id_topic);
			$query 		= 'SELECT * FROM topic WHERE id ='.$id_topic;
			$res 		= mysqli_query($this  ->  db, $query);

			if ($res && ($topic = mysqli_fetch_object($res, 'Topic', array($this -> db))))
			{
				$this  ->  topic = $topic;
			}
		}
		return $this  ->  topic;
	}
	public function getContent()
	{
		return $this -> content;
	}
	public function getDateCreated()
	{
		return $this -> date_created;
	}
	public function getViewers()
	{
		return $this -> viewers;
	}


	// Setters
	public function setAuthor(User $author)
	{
		$this -> author 	= $author;
		$this -> id_author 	= $author -> getId();
		return true;
	}
	public function setIdAuthor($id)
	{
		$this -> id_author 	= $id;
		return true;
	}
	public function setTopic(Topic $topic)
	{
		$this -> topic 		= $topic;
		$this -> id_topic 	= $topic -> getId();
		return true;
	}
	public function setIdTopic($id)
	{
		$this -> id_topic 	= $id;
		return true;
	}
	public function setContent($content)
	{
		if (strlen($content) > 15 && strlen($content) < 2047)
		{
			$this -> content = $content;
			return true;
		}
		else
		{
			return 'Content must be between 16 and 2046 characters';
		}
	}
	public function setDateCreated($date_created)
	{
		if (!is_nan($date_created))
		{
			$this -> date_created = $date_created;
			return true;
		}
		else
		{
			return 'Format needs to be a timestamp';
		}
	}

	/**
	 * @param User $user
	 * @return bool ands fill viewers;
	 * @return string
	 */
	public function setViewers(User $user)
	{
		if(is_object($user))
		{
			$this->user[] = $user;
			return true;
		}
		else
		{
			return "Viwer need to be a object";
		}
	}
}