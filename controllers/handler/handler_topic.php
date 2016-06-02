<?php
if(isset($_POST['action']))
{
    if($_POST['action'] == 'create')
    {
        if(isset($_POST['create_name'], $_POST['create_message']))
        {
            $created_name       =  $_POST['create_name'];
            $created_message    =  $_POST['create_message'];
            $subsectionManager  =  new SubsectionManager($db);
            $subsection         =  $subsectionManager -> readById($_GET['id']);
            $topicManager       =  new TopicManager($db);
            $res                =  $topicManager -> create($subsection, $currentUser, $_POST['create_name'], $_POST['create_message']);

            if(is_array($res))
            {
                $errors = $res;
                return $errors;
            }
            elseif(is_string($res))
            {
                $errors[] = $res;
                return $errors;
            }
            else
            {
                $_SESSION['success'] = "Topic créé avec succès :)";
                header('Location: ?page=topic&id='.intval($res->getId()));
                exit;
            }
        }
    }

}