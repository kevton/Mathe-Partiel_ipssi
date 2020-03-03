<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue = 'listAnnonce';
$erreur = false;
$users = array();

try
{
    /* Si on reçoit bien un id anonce à supprimer */
    if(array_key_exists('id',$_GET))
    {
        
            $dbh = connexion();
                /** Puis on supprime l'annonce dans la bdd */
                $sql  ='DELETE FROM annonces WHERE id = :id';
                $sth = $dbh->prepare($sql);
                $sth->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                if($sth->execute())
                     addFlashBag('Annonce supprimé !');
                else
                     addFlashBag('Une erreur a empêché de supprimer l\'Annonce !');
            
        
        
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

header('Location:listAnnonce.php');