<?php 
class SubsectionManager
{


	// Properties
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Functions


	// Create subsection
	public function create(Section $section, User $author, $name, $description, $image)
	{
		$subsection = new Subsection($this -> db);
		$valide 	= $subsection -> setName($name);
		$errors 		= array();
		$errors[] 		= $subsection -> setSection($section);
		$errors[] 		= $subsection -> setAuthor($author);
		$errors[] 		= $subsection -> setName($name);
		$errors[] 		= $subsection -> setDescription($description);
		$errors[] 		= $subsection -> setImage($image);

		$errors 	= array_filter($errors, function($val)
			{
				return $val !== true;
			});

		if (count($errors) == 0)
		{
			$idSection 		= intval($subsection -> getIdSection());
			$idAuthor 		= intval($subsection -> getIdAuthor());
			$name 			= mysqli_escape_string($this -> db, $subsection -> getName());
			$description 	= mysqli_escape_string($this -> db, $subsection -> getDescription());
			$image 			= mysqli_escape_string($this -> db, $subsection -> getImage());
			$query 			= "	INSERT INTO subsection (id_section, id_author, name, description, image) 
								VALUES ('".$idSection."', '".$idAuthor."', '".$name."', '".$description."', '".$image."')";
			$res 			= mysqli_query($this->db, $query);
			
			if ($res)
			{
				$id = mysqli_insert_id($this -> db);
			
				if ($id)
				{
					return $this -> readById($id);
				}
				else
				{
					return "Internal server error";
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


	// Read all subsections
	public function read($n = 0)
	{
		$n 		= intval($n);
		
		if ($n > 0)
		{
			$query = '	SELECT * 
						FROM subsection 
						ORDER BY `name` ASC 
						LIMIT '.$n;
		}
		else
		{
			$query = '	SELECT * 
						FROM subsection 
						ORDER BY `name` ASC';
		}
		
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$subsections = array();

			while ($subsection = mysqli_fetch_object($res, 'Subsection', array($this->db)))
			{
				$subsections[] = $subsection;
			}
			return $subsections;
		}
		else
		{
			return 'Database error';
		}
	}


	// Read subsection by id
	public function readById($id)
	{
		$id 	= intval($id);
		$query 	= "SELECT * FROM subsection WHERE id='".$id."'";
		$res 	= mysqli_query($this -> db, $query);
		if ($res)
		{
			$subsection = mysqli_fetch_object($res, "Subsection", array($this->db));

			if ($subsection)
			{
				return $subsection;
			}
			else
			{
				return "Subsection not found";
			}
		}
		else
		{
			return "Internal Server Error";
		}
	}


	// Read subsections by name
	public function readByName($name)
	{
		if (strlen(trim($name)) > 0)
		{
			$name 	= mysqli_real_escape_string($this -> db, $name);
			$query 	= "SELECT * FROM subsection WHERE name LIKE '%".$name."%'";
			$res 	= mysqli_query($this -> db, $query);
			
			if ($res)
			{
				$subsections = array();

				while ($subsection = mysqli_fetch_object($res, "Subsection", array($this->db)))
				{
					$subsections[] = $subsection;
				}
				return $subsections;
			}
			else
			{
				return "Internal Server Error";
			}
		}
		else
		{
			return "Recherche vide";
		}
	}


	// Read subsections by author
	public function readByAuthor(User $author)
	{
		$id 	= intval($author -> getId());
		$query 	= "SELECT * FROM subsection WHERE id_author='".$id."'";
		$res 	= mysqli_query($this -> db, $query);
		
		if ($res)
		{
			$subsections = array();
			
			while ($subsection = mysqli_fetch_object($res, "Subsection", array($this->db)))
			{
				$subsections[] = $subsection;
			}
			return $subsections;
		}
		else
		{
			return "Internal Server Error";
		}
	}


	// Read subsections by section
	public function readBySection(Section $section)
	{
		$id 	= intval($section -> getId());
		$query 	= "SELECT * FROM subsection WHERE id_section='".$id."'";
		$res 	= mysqli_query($this -> db, $query);
		
		if ($res)
		{
			$subsections = array();
			
			while ($subsection = mysqli_fetch_object($res, "Subsection", array($this->db)))
			{
				$subsections[] = $subsection;
			}
			return $subsections;
		}
		else
		{
			return "Internal Server Error";
		}
	}

	// Read subsections by date created
	public function readByDateCreated($min, $max)
	{
		$min 	= intval($min);
		$max 	= intval($max);
		$query 	= 'SELECT * FROM subsection WHERE date_created > '.$min.' AND date_created < '.$max;
		$res 	= mysqli_query($this -> db, $query);
		
		if ($res)
		{
			$subsections = array();
			
			while ($subsection = mysqli_fetch_object($res, "Subsection", array($this->db)))
			{
				$subsections[] = $subsection;
			}
			return $subsections;
		}
		else
		{
			return "Internal Server Error";
		}
	}


	// Update subsection
	public function update(Subsection $subsection)
	{
		$name 			= mysqli_real_escape_string($this -> db, $subsection -> getName());
		$description 	= mysqli_real_escape_string($this -> db, $subsection -> getDescription());
		$image 			= mysqli_real_escape_string($this -> db, $subsection -> getImage());
		$section 		= intval($subsection -> getIdSection());
		$author 		= intval($subsection -> getIdAuthor());
		$status_read 	= intval($subsection -> getStatusRead());
		$status_write 	= intval($subsection -> getStatusWrite());

		$query 			= "	UPDATE subsection
							SET name='".$name."', description='".$description."', image='".$image."', id_section='".$section."', id_author='".$author."', status_read='".$status_read."', status_write='".$status_write."'
							WHERE id='".$id."'";
		$res 			= mysqli_query($this -> db, $query);
		
		if ($res)
		{
			return $this -> readById($id);
		}
		else
		{
			return "Internal Server Error";
		}
	}


	// Delete subsection
	public function delete(Subsection $subsection)
	{
		$id 	= intval($subsection -> getId());
		$query 	= "DELETE FROM subsection WHERE id='".$id."'";
		$res 	= mysqli_query($this -> db, $query);
		
		if ($res)
		{
			return true;
		}
		else
		{
			return "Internal Server Error";
		}
	}
}