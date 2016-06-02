<?php
class Section extends SubsectionManager
{
	

	// Properties
	private $id;
	private $id_author;
	private $author;
	private $name;
	private $description;
	private $image;
	private $date_created;
	private $status_read;
	private $status_write;
	private $db;


	// Constructor
	public function __construct($db)
	{
		parent::__construct($db);
		$this -> db = $db;
	}

	// Getters
	public function getId()
	{
		return $this -> id;
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
	public function getIdAuthor()
	{
		return $this -> id_author;
	}
	public function getName()
	{
		return $this -> name;
	}
	public function getDescription()
	{
		return $this -> description;
	}
	public function getImage()
	{
		return $this -> image;
	}
	public function getDateCreated()
	{
		return $this -> date_created;
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
		$this -> id_author = $author -> getId();
		$this -> author = $author;
		return true;
	}
	public function setName($name)
	{
		if (strlen($name) > 1 && strlen($name) < 32)
		{
			$this -> name = $name;
			return true;
		}
		else
		{
			return "Invalid name";
		}
	}
	public function setDescription($description)
	{
		if (strlen($description) > 1 && strlen($description) < 512)
		{
			$this -> description = $description;
			return true;
		}
		else
		{
			return "Invalid description";
		}
	}
	public function setImage($image)
	{
		if ( $image_proprietes = @getimagesize($image) )
		{
			if ($image_proprietes[0] > 200 || $image_proprietes[1] > 200)
			{
				return "Invalid image dimensions (max 200x200 px)";
			}
			else if (@filesize($image) > 1e6)
			{
				return "Invalid image size (max 25 kB)";
			}
			else
			{
				$this -> image = $image;
				return true;
			}
		}
		else
		{
			return "Invalid filetype";
		}
	}
	public function setStatusRead($status_read)
	{
		if (!is_nan($status_read))
		{
			$this -> status_read = $status_read;
			return true;
		}
		else
		{
			return "Invalid status";
		}
	}
	public function setStatusWrite($status_write)
	{
		if (!is_nan($status_write))
		{
			$this -> status_write = $status_write;
			return true;
		}
		else
		{
			return "Invalid status";
		}
	}
}