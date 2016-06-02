<?php

if (isset($_POST['subsection_name'], $_POST['subsection_banner'], $_POST['subsection_description']))
{
	$sectionManager 	= new SectionManager($db);
	$subsectionManager 	= new SubsectionManager($db);
	$subsection 		= $subsectionManager -> create($sectionManager -> readById($_GET['id']), $currentUser, $_POST['subsection_name'], $_POST['subsection_description'], $_POST['subsection_banner']);

	if (is_array($subsection))
	{
		$errors 				= array_merge($errors, $subsection);
		$subsection_name 		= $_POST['subsection_name'];
		$subsection_banner 		= $_POST['subsection_banner'];
		$subsection_description = $_POST['subsection_description'];
	}
	else
	{
		$_SESSION['success'] = "Sous-section créée avec succès :)";
		header('Location: ?page=section&id='.$subsection -> getIdSection());
		exit;
	}
}