<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue = 'listUser';
$erreur = false;
$users = array();

try
{
    /* Si on reçoit bien un id utilisateur à supprimer */
    if(array_key_exists('id',$_GET))
    {
        /* Attention on ne peut pas supprimer l'utilisateur en cours ;) */
        if($_GET['id'] != $_SESSION['user']['id'])
        {
            $dbh = connexion();
                /** Puis on supprime l'utilisateur dans la bdd */
                $sql  ='DELETE FROM user WHERE id = :id';
                $sth = $dbh->prepare($sql);
                $sth->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                if($sth->execute())
                     addFlashBag('Utilisateur supprimé !');
                else
                     addFlashBag('Une erreur a empêché de supprimer l\'utilisateur !');
            
        }
        else
        {
            addFlashBag('Il est interdit de se supprimer !');
        }
    }
    else
    {
        addFlashBag('Vous vous êtes perdu !');
    }
}
catch(PDOException $e)
{
    addFlashBag('Une erreur de connexion a eu lieu :'.$e->getMessage());
}

header('Location:listUser.php');