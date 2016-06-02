<?php

/*\ CHECK NEW MESSAGE \*/
if(isset($_SESSION['id']))
{
    $userManager = new UserManager($db);
    $newMessages = $userManager->checkNewMessage($userManager->readById($_SESSION['id']));
    $newMessagesArray = array();
    for($i = 0; $i<count($newMessages); $i++)
    {
        $sectionName = $newMessages[$i]->getTopic()->getName();
        if(!isset($newMessagesArray[$sectionName]))
        {
            $newMessagesArray[$sectionName] = 0;
        }
        $newMessagesArray[$sectionName] ++;
    }
}
//END NEW MESSAGE

require('views/content/subsection.phtml');