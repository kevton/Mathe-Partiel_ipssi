<?php

session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='home';
$title = 'Bienvenue - ' . $_SESSION['user']['name'] ;

include('tpl/layout.phtml');