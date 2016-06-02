<?php
class Topic extends MessageManager
{


	// Properties
	private $id;
	private $id_subsection;
	private $subsection;
	private $id_author;
	private $author;
	private $name;
	private $date_created;
	private $date_updated;
	private $status_read;
	private $status_write;
	private $db;


	// Constructor
	public function __construct($db)
	{
		parent::__construct($db);
		$this->db = $db;
	}


	// Getters
	public function getId()
	{
		return $this -> id;
	}
	public function getIdSubsection()
	{
		return $this -> id_subsection;
	}
	public function getSubsection()
	{
		if (!$this -> subsection)
		{
			$id_subsection 	= intval($this -> id_subsection);
			$query 		= 'SELECT * FROM subsection WHERE id ='.$id_subsection;
			$res 		= mysqli_query($this -> db, $query);

			if ($res && ($subsection = mysqli_fetch_object($res, 'Subsection', array($this -> db))))
			{
				$this -> subsection = $subsection;
			}
		}
		return $this -> subsection;
	}
	public function getIdAuthor()
	{
		return $this -> id_author;
	}
	public function getAuthor()
	{
		if (!$this -> author)
		{
			$id_author 	= intval($this -> id_author);
			$query 		= 'SELECT * FROM user WHERE id ='.$id_author;
			$res 		= mysqli_query($this -> db, $query);

			if ($res && ($author = mysqli_fetch_object($res, 'User')))
			{
				$this -> author = $author;
			}else
			{
			}
		}
		return $this -> author;
	}
	public function getName()
	{
		return $this -> name;
	}
	public function getDateCreated()
	{
		return $this -> date_created;
	}
	public function getDateUpdated()
	{
		return $this -> date_updated;
	}
	public function getStatusRead()
	{
		return $this -> status_read;
	}
	public function getStatusWrite()
	{
		return $this -> status_write;
	}


	// Setters
	public function setAuthor(User $author)
	{
		$this -> id_author 	= $author -> getId();
		$this -> author 	= $author;
		return true;
	}
	public function setSubsection(Subsection $subsection)
	{
		$this -> id_subsection 	= $subsection -> getId();
		$this -> subsection 	= $subsection;
		return true;
	}
	public function setName($name)
	{
		if(is_string($name))
		{
			if(strlen(trim($name)) >= 2 && strlen(trim($name))<= 31)
			{
				$this -> name = trim($name);
				return true;
			}
			else
			{
				return 'Topic name is not valid';
			}
		}
		else
		{
			return 'Topic name needs to be a string';
		}
	}
	public function setDateCreated($date_created)
	{
		if(is_numeric($date_created))
		{
			$this -> date_created = $date_created;
		}
		else
		{
			return "Created date is not valid";
		}
	}
	public function setDateUpdated($dateUpdate)
	{
		if(is_numeric($dateUpdate))
		{
			$this -> date_updated = $dateUpdate;
		}
		else
		{
			return "Updated date is not valid";
		}
	}
	public function setStatusRead($status_read)
	{
		if(is_numeric($status_read))
		{
			$this -> status_read = $status_read;
		}
		else
		{
			return "Invalid status read format.";
		}
	}
	public function setStatusWrite($status_write)
	{
		if(is_numeric($status_write))
		{
			$this -> status_write = $status_write;
		}
		else
		{
			return "Status write is not valid";
		}
	}
}