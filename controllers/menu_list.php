<?php 

if (isset($_GET['page']))
{
	$page = $_GET['page'];
	if ($page == 'home')
	{
		$breadcrumb = 'Home';
		$link 		= '?page=home';
		$attr 		= 'class="active"';
		require('views/menu_list.phtml');
	}
	else if ($page == 'section')
	{
		$id = $_GET['id'];
		$sectionManager = new SectionManager($db);
		$section = $sectionManager -> readById($id);

		if (is_object($section))
		{
			$breadcrumb = 'Home';
			$link 		= '?page=home';
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $section -> getName();
			$link 		= '';
			$attr 		= 'class="active"';
			require('views/menu_list.phtml');
		}
		else
		{
			header('Location: ?page=home');
			exit;
		}
	}
	else if ($page == 'subsection')
	{
		$id = $_GET['id'];
		$subsectionManager = new SubsectionManager($db);
		$subsection = $subsectionManager -> readById($id);


		if (is_object($subsection))
		{
			$breadcrumb = 'Home';
			$link 		= '?page=home';
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $subsection -> getSection() -> getName();
			$link 		= '?page=section&id='.$subsection -> getSection() -> getId();
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $subsection -> getName();
			$link 		= '';
			$attr 		= 'class="active"';
			require('views/menu_list.phtml');
			}
		else
		{
			header('Location: ?page=home');
			exit;
		}
	}
	else if ($page == 'topic')
	{
			$id = $_GET['id'];
			$topicManager = new TopicManager($db);
			$topic = $topicManager -> readById($id);

		if (is_object($topic))
		{
			$breadcrumb = 'Home';
			$link 		= '?page=home';
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $topic -> getSubsection() -> getSection() -> getName();
			$link 		= '?page=section&id='.$topic -> getSubsection() -> getSection() -> getId();
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $topic -> getSubsection() -> getName();
			$link 		= '?page=subsection&id='.$topic -> getSubsection() -> getId();
			$attr 		= '';
			require('views/menu_list.phtml');

			$breadcrumb = $topic -> getName();
			$link 		= '';
			$attr 		= 'class="active"';
			require('views/menu_list.phtml');
		}
		else
		{
			header('Location: ?page=home');
			exit;
		}
	}
}