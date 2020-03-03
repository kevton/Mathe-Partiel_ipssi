<?php

session_start();

include('../config/config.php');
include('../lib/app.lib.php');



//$vue='login'; pas de vue, voir en bas de fichier
$title = 'Se connecter';

//Initialisation des erreurs à false
$erreur = '';

//Tableau correspondant aux valeurs à récupérer dans le formulaire.
$values = [
    'email'=>'',
    'password'=>''
];

$tab_erreur =
[
    'email'=>'Email vide, merci de préciser votre email',
    'password'=>'Password vide, merci de préciser votre mot de passe'
];

try
{

    if(array_key_exists('email',$_POST))
    {
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
                $erreur.= '<br>'.$tab_erreur[$champ];
            else
                $values[$champ] = '';
        }

        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
            $erreur.= '<br> Vous devez saisir un email valide !';

        if($erreur =='')
        {
            //Connexion
            $dbh = connexion();

            /**2 : Prépare ma requête SQL */
            $sth = $dbh->prepare('SELECT * FROM user WHERE email = :email');

            /** 3 : executer la requête - on utilise pas le tableau values car il contient email et password et pas que l'email */
            $sth->execute(array('email'=>$values['email']));

            $user = $sth->fetch(PDO::FETCH_ASSOC);
        
            /* Si l'utilisateur existe dans la base de données avec son email 
            et que le mot de passe match ! */
            if($user != false && password_verify($values['password'],$user['mdp']))
            {
                //On peut connecter l'utilisateur et garder quelques info en session
                $_SESSION['connect'] = true; 
                $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['prenom'].' '.$user['nom'], 'role'=>'ROLE_ADMIN'];

                //On redirige vers la page d'accueil de l'admin
                header('Location:index.php');
                exit();//exit after redirect !!
            }
            else
            {
                $erreur.='<br>Connexion impossible. Vérifiez vos identifiants !';
            }
        }

    }
}
catch(PDOException $e)
{
    $erreur.='<br>Une erreur de connexion a eu lieu :'.$e->getMessage();
}



/*Le layout du login est diférent du layout du reste de l'admin : la vue login inclu tout le HTML !! */
include('tpl/login.phtml');
