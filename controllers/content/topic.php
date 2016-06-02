<?php
$topicManager = new TopicManager($db);
$topic = $topicManager->readByID(intval($_GET['id']));
require('views/content/topic.phtml');