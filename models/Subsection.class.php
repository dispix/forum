<?php 
class Subsection extends TopicManager
{


	// Properties
	private $id;
	private $id_section;
	private $section;
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
		$this->db = $db;
	}

	// Getters
	public function getId()
	{
		return $this -> id;
	}
	public function getIdSection()
	{
		return $this -> id_section;
	}
	public function getSection()
	{
		if (!$this -> section)
		{
			$id_section = intval($this -> id_section);
			$query 		= 'SELECT * FROM section WHERE id ='.$id_section;
			$res 		= mysqli_query($this -> db, $query);

			if ($res && ($section = mysqli_fetch_object($res, 'Section', array($this -> db))))
			{
				$this -> section = $section;
			}
		}
		return $this -> section;
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
			}
		}
		return $this -> author;
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
	public function setSection(Section $section)
	{
		$this -> section 	= $section;
		$this -> id_section = $section -> getId();
		return true;
	}
	public function setAuthor(User $author)
	{
		$this -> author 	= $author;
		$this -> id_author 	= $author -> getId();
		return true;
	}
	public function setName($name)
	{
		if (strlen($name) > 1)
		{
			$this -> name = $name;
			return true;
		}
		else
		{
			return "Nom trop court";
		}
	}
	public function setDescription($description)
	{
		if (strlen($description) > 1)
		{
			$this -> description = $description;
			return true;
		}
		else
		{
			return "Description trop courte";
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
	public function setStatusRead($status)
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
	public function setStatusWrite($status)
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