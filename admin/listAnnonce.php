<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='listAnnonce';
$title = 'Liste des annonces';
$erreur = false;
$users = array(); //On initialise le tableau des users.. si pas de users pas de users ;)

try
{
    //on récupère le flashbag
    $flashbag = getFlashBag();

    //On récupère toutes les annonces dans la base données
	//Fonction connectBdd() dans le fichier core/utilities.php
	$dbh = connexion();

	$sql  ='SELECT * FROM annonces';
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$annonces = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e)
{
	$erreur = 'Erreur base de données : '.$e->getMessage();
}

include('tpl/layout.phtml');
