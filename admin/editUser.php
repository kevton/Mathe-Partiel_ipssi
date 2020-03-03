<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='addUser';
$title = 'Modifier un utilisateur';

//Initialisation des erreurs à false
$erreur = '';

//Tableau correspondant aux valeurs à récupérer dans le formulaire (hors fichiers)
$values = [
'id'=>'',
'nom'=>'',
'prenom'=>'',
'email'=>''];

//'password'=>'', pas de mot de passe car pas obligé de le modifier quand on édit un utilisateur

$tab_erreur =
[
'nom'=>'Le nom doit être rempli !',
'prenom'=>'Le prénom doit être rempli !',
'email'=>'L\'email doit être rempli !',
];
//'password'=>'Le mot de passe ne peut être vide'

try
{

    /** Edition d'un utilisateur on reçoit l'id à éditer
     */
    if(array_key_exists('id',$_GET))
    {
        //On charge les données user de la base
        $dbh = connexion();
        $sth = $dbh->prepare('SELECT * FROM user WHERE id = :id');
        $sth->execute(array('id'=>$_GET['id']));
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        if($user)
        {
            $values['id'] = $user['id'];
            $values['nom'] = $user['prenom'];
            $values['prenom'] = $user['nom'];
            $values['email'] = $user['email'];
           
            
        }
    }
    elseif(array_key_exists('id',$_POST))
    {
        //Le formulaire est posté !
        //On valide que tous les champs ne sont pas vides et fournis !
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
                $erreur.= '<br>'.$tab_erreur[$champ];
            else
                $values[$champ] = NULL;
        }
        var_dump($values);

        
        //On valide l'égalité des 2 mots de passe !
        if($_POST['password'] != $_POST['passwordConf'])
            $erreur.= '<br> Erreur confirmation mot de passe';

        //On valide le champ email spécifique
        if(!filter_var($values['email'],FILTER_VALIDATE_EMAIL))
            $erreur.= '<br> Email erroné !';

        /** SI pas d'erreurs on fini la préparation des données et on save ! */
        if($erreur =='')
        {
            if($_POST['password'] != '')
                $values['password']     = password_hash($_POST['password'],PASSWORD_DEFAULT);
            
            
           

            /**1 : connexion au serveur de bdd */
            $dbh = connexion();
            /**2 : Prépare ma requête SQL */
            $sql = 'UPDATE user SET email=:email, prenom=:prenom, nom=:nom';

            
            //Si on a pas de mot de passe on ne le met pas à jour
            if(isset($values['password']))
                $sql.= ',mdp=:password';
            
            $sql.=' WHERE id=:id';

            var_dump($values);
            var_dump($sql);

            $sth = $dbh->prepare($sql);
            /** 3 : executer la requête */
            if($sth->execute($values))
                addFlashBag('Utilisateur modifié avec succès !');

            header('Location:listUser.php');
            exit();
        }

    }
}
catch(PDOException $e)
{
    $erreur.='Une erreur de connexion a eu lieu :'.$e->getMessage();
}


include('tpl/layout.phtml');

