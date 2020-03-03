<?php

include('config/config.php');
include('lib/app.lib.php');

$vue='home';
$title = 'Mon super Blog !!!!!';
try
{
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