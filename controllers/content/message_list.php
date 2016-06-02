<?php
$messageManager = new MessageManager($db);
$messages = $messageManager->readByTopic($topic);


for($i = 0; $i < count($messages); $i++)
{
    $message = $messages[$i];
    /*UPDATE MESSAGE VIEWER*/
    $newMsg = $messageManager->updateViewers($message, $currentUser);
    $message->getAuthor();
    require('views/content/message_item.phtml');
}