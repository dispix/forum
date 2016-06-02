<?php 
$userManager = new UserManager($db);
$sectionManager = new SectionManager($db);
$subsectionManager = new SubsectionManager($db);
$topicManager = new TopicManager($db);
$messageManager = new MessageManager($db);

$users 			= $userManager -> read();
$sections 		= $sectionManager -> read();
$subsections 	= $subsectionManager -> read();
$topics 		= $topicManager -> read();
$messages 		= $messageManager -> read();


require('views/content/dashboard.phtml');