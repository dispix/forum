<?php
$topicManager = new TopicManager($db);
if(isset($_GET['id'])){
    $topics = $topicManager->readBySubSection($_GET['id']);

    if(count($topics) == 0)
    {
        echo "<div class='alert alert-danger' role='alert'><p>;( No topic on this subsection</p></div>";
    }
    else
    {
        for($i = 0; $i <count($topics); $i++)
        {
            $topic = $topics[$i];
            require('views/content/topic_item.phtml');
        }
    }
}
