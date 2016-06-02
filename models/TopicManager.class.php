<?php

class TopicManager
{


	// Properties
	private $db;


	// Constructor
	public function __construct($db)
	{
		$this -> db = $db;
	}


	// Functions


	// Create topic
	public function create(Subsection $subsection, User $author, $name, $message)
	{

		$topic 		= new Topic($this->db);
		$errors[] 	= $topic -> setSubsection($subsection);
		$errors[] 	= $topic -> setAuthor($author);
		$errors[] 	= $topic -> setName($name);
		$errors 	= array_filter($errors, function ($val) {
						return $val !== true;
					});
		if (count($errors) == 0)
		{
			$idSubsection 	= intval($topic -> getIdSubsection());
			$idAuthor 		= intval($topic -> getIdAuthor());
			$name 			= mysqli_escape_string($this -> db, $topic -> getName());

			$query 			= "	INSERT INTO topic(id_subsection, id_author, name)
								VALUES('" . $idSubsection . "', '" . $idAuthor . "', '" . $name . "')";
			$data 			= mysqli_query($this -> db, $query);

			if ($data)
			{
				$id = mysqli_insert_id($this -> db);

				if ($id)
				{
					$topic 		= $this  -> readByID($id);
					$message	= $topic -> create($author, $topic, $message);
					if(is_array($message))
					{
						$this->delete($topic);
						$errors = $message;
						return $errors;
					}
					elseif (is_string($message))
					{
						$this->delete($topic);
						$errors[] = $message;
						return $errors;
					}
					else
					{
						return $this -> readById($id);
					}

				}
			else
				{
					return "Internal server error";
				}
			}
			else
			{
				return "DB connect error";
			}
		}
		else
		{
			return $errors;
		}
	}


	// Read all topics
	public function read($n = 0)
	{
		$n 		= intval($n);
		
		if ($n > 0)
		{
			$query = '	SELECT * 
						FROM topic
						ORDER BY `date_created` ASC 
						LIMIT '.$n;
		}
		else
		{
			$query = '	SELECT * 
						FROM topic 
						ORDER BY `date_created` ASC';
		}
		
		$res 	= mysqli_query($this -> db, $query);

		if ($res)
		{
			$topics = array();

			while ($topic = mysqli_fetch_object($res, 'Topic', array($this->db)))
			{
				$topics[] = $topic;
			}
			return $topics;
		}
		else
		{
			return 'Database error';
		}
	}


	// Read topic by id
	public function readByID($id)
	{
		$id 	= intval($id);
		$query 	= "SELECT * FROM topic WHERE id='" . $id . "'";
		$data 	= mysqli_query($this -> db, $query);
		
		if ($data)
		{
			$result = mysqli_fetch_object($data, "Topic", array($this->db));
		
			if ($result)
			{
				return $result;
			}
			else
			{
				return "Topic not found";
			}
		}
	}


	// Read topic by name
	public function readByName($name)
	{
		if (is_string($name))
		{
			$name 	= mysqli_escape_string($this -> db, $name);
			$query 	= "SELECT * FROM topic WHERE name = '" . $name . "'";
			$data 	= mysqli_query($this -> db, $query);
			
			if ($data)
			{
				$result = mysqli_fetch_object($data, "Topic", array($this->db));

				if ($result)
				{
					return $result;
				}
				else
				{
					return "Object errors";
				}
			}
			else
			{
				return "Db connect error";
			}
		}
	}


	// Read topic by author
	public function readByAuthor(User $author)
	{
		$idAuthor 	= intval($author -> getId());
		$query 		= " SELECT * 
						FROM topic 
						WHERE id_author = ". $idAuthor ."
						ORDER BY date_created DESC";
		$data 		= mysqli_query($this -> db, $query);
		
		if ($data)
		{
			$topics = array();
			while ($topic = mysqli_fetch_object($data, 'Topic', array($this -> db)))
			{
				$topics[] = $topic;
			}
			return $topics;
		}
		else
		{
			return "Topic not found";
		}
	}

	// Read topic by date created
	public function readyByDateCreated($dateMin, $dateMax)
	{
		if (ctype_digit($dateMin))
		{
			if (ctype_digit($dateMax))
			{
				$dateMin 	= intval($dateMin);
				$dateMax 	= intval($dateMax);
				$query 		= "	SELECT * FROM topic 
								WHERE date_created >= '" . $dateMin . "' 
								AND date_created <= '" . $dateMax . "'";
				$data 		= mysqli_query($this -> db, $query);
			
				if ($data)
				{
					$listTopic 	= array();
					
					while ($result = mysqli_fetch_object($data, "Topic", array($this->db)))
					{
						$listTopic[] = $result;
					}
					return $listTopic;
				}
				else
				{
					return "Database error";
				}
			}
			else
			{
				return "Date max is not valid";
			}
		}
		else
		{
			return "Date min is not valid";
		}
	}


	// Read topic by date updated
	public function readyByDateUpdate($dateMin, $dateMax)
	{
		if (ctype_digit($dateMin))
		{
			if (ctype_digit($dateMax))
			{
				$dateMin 	= intval($dateMin);
				$dateMax 	= intval($dateMax);
				$query 		= "	SELECT * FROM topic 
								WHERE date_updated >= '" . $dateMin . "' 
								AND date_updated <= '" . $dateMax . "'";
				$data 		= mysqli_query($this -> db, $query);
				
				if ($data)
				{
					$listTopic = array();
				
					while ($result = mysqli_fetch_object($data, "Topic", array($this->db)))
					{
						$listTopic[] = $result;
					}
					return $listTopic;
				}
				else
				{
					return "Database error";
				}
			}
			else
			{
				return "Date max is not valid";
			}
		}
		else
		{
			return "Date min is not valid";
		}
	}

	/**
	 * @param Subsection $subsection
	 * @return array  all topic with id_subsection = $subsection
	 */
	public function readBySubSection($idSubsection)
	{
		$idSubsection = intval($idSubsection);
		$query = "	SELECT * FROM topic
					WHERE id_subsection = '".$idSubsection."'
					ORDER BY date_created DESC";
		$data = mysqli_query($this->db, $query);
		if ($data)
		{
			$listTopic = array();
			while($result = mysqli_fetch_object($data, "Topic",array($this->db)))
			{
				$listTopic[] = $result;
			}
			return $listTopic;
		}
		else
		{
			return "Db error";
		}

	}


	// Update function
	public function update(Topic $topic)
	{
		$id 			= intval($topic -> getId());
		$id_subsection 	= intval($topic -> getIdSubsection());
		$id_author 		= intval($topic -> getIdAuthor());
		$name 			= mysqli_escape_string($this -> db, $topic -> getName());
		$status_read	= intval($topic -> getStatusRead());
		$status_write	= intval($topic -> getStatusWrite());

		$query 			= "	UPDATE topic 
							SET id_subsection = '" . $id_subsection . "', id_author = '" . $id_author . "' , name = '" . $name . "', status_read='".$status_read."', status_write='".$status_write."'
							WHERE id = '".$id;
		$data 			= mysqli_query($this -> db, $query);
	
		if ($data)
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
			return "DB connect error";
		}
	}


	// Delete function
	public function delete(Topic $topic)
	{
		$id 	= intval($topic -> getId());
		$query 	= "DELETE FROM topic WHERE id = '".$id."'";
		$data 	= mysqli_query($this -> db, $query);
		if ($data)
		{
			return true;
		}
		else
		{
			return "Db connect error";
		}
	}
}