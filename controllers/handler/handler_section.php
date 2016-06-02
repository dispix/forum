<?php

if (isset($_POST['section_name'], $_POST['section_banner'], $_POST['section_description']))
{
	$sectionManager = new SectionManager($db);
	$section = $sectionManager -> create($currentUser, $_POST['section_name'], $_POST['section_description'], $_POST['section_banner']);

	if (is_array($section))
	{
		$errors 				= array_merge($errors, $section);
		$section_name 			= $_POST['section_name'];
		$section_banner 		= $_POST['section_banner'];
		$section_description 	= $_POST['section_description'];
	}
	else
	{
		$_SESSION['success'] = "Section créée avec succès :)";
		header('Location: ?page=home');
		exit;
	}
}