<?php

/*\ CHECK NEW MESSAGE \*/
if(isset($_SESSION['id']))
{
    $userManager = new UserManager($db);
    $newMessages = $userManager->checkNewMessage($currentUser);
    $newMessagesArray = array();
    for($i = 0; $i<count($newMessages); $i++)
    {
        $sectionName = $newMessages[$i]->getTopic()->getSubsection()->getSection()->getName();
        if(!isset($newMessagesArray[$sectionName]))
        {
            $newMessagesArray[$sectionName] = 0;
        }

        $newMessagesArray[$sectionName] ++;
    }
}
//END NEW MESSAGE

require('views/content/home.phtml');