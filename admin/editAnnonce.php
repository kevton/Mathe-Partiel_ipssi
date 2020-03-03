<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='addAnnonce';
$title = 'Editer une annonce';

//Initialisation des erreurs à false
$erreur = '';



//Tableau correspondant aux valeurs à récupérer dans le formulaire (hors fichiers)
$values = [
'titre'=>'',
'content'=>'',
];

$tab_erreur =
[
'titre'=>'Le titre doit être rempli !',
'content'=>'Le contenu est vide !'
];

try
{
    /** On se connecte ! */
    $dbh = connexion();

    if(array_key_exists('id',$_POST))
    {
        //On valide que tous les champs ne sont pas vides sinon on référence un erreur !
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
                $erreur.= '<br>'.$tab_erreur[$champ];
            else
                $values[$champ] = NULL;
        }

        /* On affecte les autre values qui peuvent être vide */
        $values['id'] = $_POST['id'];

        /** SI pas d'erreurs on fini la préparation des données et on save ! */
        if($erreur =='')
        {
            //On déplace le fichier transmis pour l'image d'entêt de l'article dans le répertoire upload/articles/ 
            if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) 
            {
              
                //On supprime l'ancienne image si elle existe 
                if(isset($_POST['oldpicture']))
                    unlink(REP_BLOG.REP_UPLOAD.'annonces/'.$_POST['oldpicture']);

                //Puis on upload la nouvelle image ;)
                $tmp_name = $_FILES["photo"]["tmp_name"];
                $name = basename(time().'_'.$_FILES["photo"]["name"]);
                if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                    $values['photo'] = $name;
                    
            }
            else{
                 addFlashBag('Aucune image ajoutée (non fournie ou trop grande) !');
            }

             //On déplace le fichier transmis pour l'image d'entêt de l'article dans le répertoire upload/articles/ 
             if (isset($_FILES["photo2"]) && $_FILES["photo2"]["error"] == UPLOAD_ERR_OK) 
             {
               
                 //On supprime l'ancienne image si elle existe 
                 if(isset($_POST['oldpicture']))
                     unlink(REP_BLOG.REP_UPLOAD.'annonces/'.$_POST['oldpicture']);
 
                 //Puis on upload la nouvelle image ;)
                 $tmp_name = $_FILES["photo2"]["tmp_name"];
                 $name = basename(time().'_'.$_FILES["photo2"]["name"]);
                 if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                     $values['photo2'] = $name;
                     
             }
             else{
                  addFlashBag('Aucune image ajoutée (non fournie ou trop grande) !');
             }

              //On déplace le fichier transmis pour l'image d'entêt de l'article dans le répertoire upload/articles/ 
            if (isset($_FILES["photo3"]) && $_FILES["photo3"]["error"] == UPLOAD_ERR_OK) 
            {
              
                //On supprime l'ancienne image si elle existe 
                if(isset($_POST['oldpicture']))
                    unlink(REP_BLOG.REP_UPLOAD.'annonces/'.$_POST['oldpicture']);

                //Puis on upload la nouvelle image ;)
                $tmp_name = $_FILES["photo3"]["tmp_name"];
                $name = basename(time().'_'.$_FILES["photo3"]["name"]);
                if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                    $values['photo3'] = $name;
                    
            }
            else{
                 addFlashBag('Aucune image ajoutée (non fournie ou trop grande) !');
            }

             //On déplace le fichier transmis pour l'image d'entêt de l'article dans le répertoire upload/articles/ 
             if (isset($_FILES["photo4"]) && $_FILES["photo4"]["error"] == UPLOAD_ERR_OK) 
             {
               
                 //On supprime l'ancienne image si elle existe 
                 if(isset($_POST['oldpicture']))
                     unlink(REP_BLOG.REP_UPLOAD.'annonces/'.$_POST['oldpicture']);
 
                 //Puis on upload la nouvelle image ;)
                 $tmp_name = $_FILES["photo4"]["tmp_name"];
                 $name = basename(time().'_'.$_FILES["photo4"]["name"]);
                 if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                     $values['photo4'] = $name;
                     
             }
             else{
                  addFlashBag('Aucune image ajoutée (non fournie ou trop grande) !');
             }

            
            /** Création de la requête d'update 
             * On créé une chaine qui représente notre requête.
             * L'image n'est mise à jour que si elle a été transmise, sinon on ne met pas dans l'update !!
            */
            $sql = 'UPDATE annonces SET titre = :titre, description = :content';
            if(isset($values['photo1']))
                $sql.= ', photo=:photo';
            $sql.= ' WHERE id = :id';

            $sql = 'UPDATE annonces SET titre = :titre, description = :content';
            if(isset($values['photo2']))
                $sql.= ', photo2=:photo2';
            $sql.= ' WHERE id = :id';

            $sql = 'UPDATE annonces SET titre = :titre, description = :content';
            if(isset($values['photo3']))
                $sql.= ', photo3=:photo3';
            $sql.= ' WHERE id = :id';

            $sql = 'UPDATE annonces SET titre = :titre, description = :content';
            if(isset($values['photo4']))
                $sql.= ', photo4=:photo4';
            $sql.= ' WHERE id = :id';

            /**2 : Prépare ma requête SQL */
            $sth = $dbh->prepare($sql);
          
            /** 3 : executer la requête */
            $sth->execute($values);

            /** FLASHBAG
             * On ajoute un flashbag pour informé de l'ajout d'un utilisateur sur la page listUser
             * Le flashBag (notion connue avec le framework symfony) est une variable session qui accueille des messages 
             * à afficher lors de la prochaine requête (souvent automatique avec une redirection). Lors de l'affichage de la prochaine vue le flashbag sera analysé
             * puis son contenu affiché et enfin il sera vidé ! 
             * */
            addFlashBag('Annonce modifié avec succès !');

            header('Location:listAnnonce.php');
            exit();
        }
    }
    elseif(array_key_exists('id',$_GET))
    {
        /** Si on est en mot GET - On récupère l'article dans la base 
         * pour récupérer ses données
         */
        
        $sth = $dbh->prepare('SELECT * FROM annonces WHERE id=:id');
        $sth->execute(['id'=>$_GET['id']]);
        $article = $sth->fetch(PDO::FETCH_ASSOC);

        /** On affecte ces données au tableau values pour l'insérer dans la vue ;) */
        $values = [
            'id' => $article['id'],
            'titre'=>  $article['titre'],
            'content'=>$article['description'],
            'picture1'=>$article['photo1'],
            'picture2'=>$article['photo2'],
            'picture3'=>$article['photo3'],
            'picture4'=>$article['photo4']
            
        ];
    }
    else
    {
       /** Si pas d'id en POST ou GET on retourne à la liste */
        addFlashBag('Erreur d\'accès à l\'édition !');
        header('Location:listAnnonce.php');
    }


}
catch(PDOException $e)
{
    $erreur.='Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('tpl/layout.phtml');

