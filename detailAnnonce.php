<?php

include('config/config.php');
include('lib/app.lib.php');

$vue='detailAnnonce';
$title = "Détails de l'annonce";

$id = $_GET['id'];


try
{
	//Fonction connectBdd() dans le fichier core/utilities.php
	$dbh = connexion();

	$sql  = "SELECT * FROM annonces WHERE id = $id";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$annonces = $sth->fetch(PDO::FETCH_ASSOC);
	
}
catch (PDOException $e)
{
	$erreur = 'Erreur base de données : '.$e->getMessage();
}

include('tpl/layout.phtml');