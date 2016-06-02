<?php
	class SectionManager
	{


		// Properties
		private $db;


		// Constructor
		public function __construct($db)
		{
			$this -> db = $db;
		}


		// Functions


		// Create section
		public function create(User $author, $name, $description, $image)
		{
			$section 	= new Section($this -> db);
			$errors[] 	= $section -> setAuthor($author);
			$errors[] 	= $section -> setName($name);
			$errors[] 	= $section -> setDescription($description);
			$errors[] 	= $section -> setImage($image);
			
			$errors 	= array_filter($errors, function($val)
			{
				return $val !== true;
			});

			if (count($errors) == 0)
			{
				$id_author 		= intval($section -> getIdAuthor());
				$name 			= mysqli_real_escape_string($this -> db, $section -> getName());
				$description 	= mysqli_real_escape_string($this -> db, $section -> getDescription());
				$image 			= mysqli_real_escape_string($this -> db, $section -> getImage());
		
				$query 			= "	INSERT INTO section (id_author,name,description,image) 
									VALUES('".$id_author."','".$name."','".$description."','".$image."')";
				$res 			= mysqli_query($this -> db, $query);

				if ($res)
				{
					$id = mysqli_insert_id($this -> db);

					if ($id)
					{
						return $this -> readById($id);
					}
					else
					{
						return "Erreur interne du serveur";
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


		// Read all sections
		public function read($n = 0)
		{
			$n 		= intval($n);
		
			if ($n > 0)
			{
				$query = '	SELECT * 
							FROM section 
							ORDER BY `name` ASC 
							LIMIT '.$n;
			}
			else
			{
				$query = '	SELECT * 
							FROM section 
							ORDER BY `name` ASC';
			}

			$res 		= mysqli_query($this -> db, $query);
			$sections 	= array();

			while ($section = mysqli_fetch_object($res, "Section", array($this -> db)))
			{
				$sections[] = $section;
			}
			return $sections;
		}


		// Read section by id
		public function readById($id)
		{
			$id 	= intval($id);
			$query 	= "SELECT * FROM section WHERE id='".$id."'";
			$res 	= mysqli_query($this -> db, $query);
			
			if ($res)
			{
				if ($section = mysqli_fetch_object($res, "Section", array($this -> db)))
				{
					return $section;
				}
				else
				{
					return "No match";
				}
			}
			else
			{
				return "internal server error";
			}
		}


		// Read section by name
		public function readByName($name)
		{
			if (strlen(trim($name)) > 0)
			{
				$name 	= mysqli_real_escape_string($this -> db, $name);
				$query 	= "SELECT * FROM section WHERE name='".$name."'";
				$res 	= mysqli_query($this -> db, $query);

				if ($res)
				{
					if ($section = mysqli_fetch_object($res, "Section", array($this -> db)))
					{
						return $section;
					}
					else
					{
						return "No match";
					}
				}
				else
				{
					return "Internal server error";
				}
			}
			else
			{
				return "Field empty";
			}
		}


		// Read section by author
		public function readByAuthor(User $author)
		{
			$id 	= $author -> getId();
			$query 	= "SELECT * FROM section WHERE id_author='".$id."'";
			$res 	= mysqli_query($this -> db, $query);

			if ($res)
			{
				$sections = array();

				while ($section = mysqli_fetch_object($res, "Section", array($this -> db)))
				{
					$sections[] = $section;
				}
				return $sections;
			}
			else
			{
				return "Internal server error";
			}
		}


		// Read section by date created
		public function readByDateCreated($min, $max)
		{
			$min 	= intval($min);
			$max 	= intval($max);
			$query 	= "SELECT * FROM section ORDER BY date_created DESC LIMIT ".$min.", ".$max;
			$res 	= mysqli_query($this -> db, $query);

			if ($res)
			{
				$sections = array();

				while ($section = mysqli_fetch_object($res, "Section", array($this -> db)))
				{
					$sections[] = $section;
				}
				return $sections;
			}
			else
			{
				return "Internal server";
			}
		}


		// Update section
		public function update(Section $section)
		{
			$id 			= intval($section -> getId());
			$name 			= mysqli_real_escape_string($this -> db, $section -> getName());
			$description 	= mysqli_real_escape_string($this -> db, $section -> getDescription());
			$image 			= mysqli_real_escape_string($this -> db, $section -> getImage());
			$status_read 	= intval($status_read);
			$status_write 	= intval($status_write);
			
			$query = "	UPDATE section 
						SET name='".$name."', description='".$description."', image='".$image."', status_read='".$status_read."', status_write='".$status_write."'
						WHERE id='".$id."'";
			
			$res = mysqli_query($this -> db, $query);

			if ($res)
			{
				return $this -> readById($id);
			}
			else
			{
				return "Internal server error";
			}
		}
		public function delete(Section $section)
		{
			$id 	= $section -> getId();
			$query 	= "DELETE FROM section WHERE id='".$id."'";
			$res 	= mysqli_query($this -> db, $query);

			if ($res)
			{
				return true;
			}
			else
			{
				return "Internal server error";
			}
		}
	}
?>