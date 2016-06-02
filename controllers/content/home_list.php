<?php

/*\ CHECK NEW MESSAGE \*/
$sectionManager = new SectionManager($db);
$sections 		= $sectionManager -> read();

if (count($sections) == 0)
{
	$sections = "Nothing to show";
}
else
{
	for ($i = 0, $c = count($sections); $i < $c; $i++)
	{
		$section = $sections[$i];
		require('views/content/home_list.phtml');
	}
}