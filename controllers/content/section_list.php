<?php
$sectionManager = new SectionManager($db);
$section 		= $sectionManager -> readById($_GET['id']);

if (is_object($section))
{
	$subsectionManager 	= new SubsectionManager($db);
	$subsections 		= $subsectionManager -> readBySection($section);

	if (is_array($subsections) && count($subsections) > 0)
	{
		for ($i = 0, $c = count($subsections); $i < $c; $i++)
		{ 
			$subsection = $subsections[$i];
			require('views/content/section_list.phtml');
		}
	}
	else
	{		
		echo "<div class='alert alert-danger' role='alert'><p>;( Nothing to show</p></div>";
	}
}
else
{
	echo $section;
}