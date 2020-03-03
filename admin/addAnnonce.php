<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='addAnnonce';
$title = 'Ajouter une annonce';

//Initialisation des erreurs à false
$erreur = '';



//Tableau correspondant aux valeurs à récupérer dans le formulaire (hors fichiers)
$values = [
'titre'=>'',
'content'=>''
];

$tab_erreur =
[
'titre'=>'Le titre doit être rempli !',
'content'=>'Le contenu est vide !'
];

try
{
    $dbh = connexion();


    if(array_key_exists('titre',$_POST))
    {
    
        //On valide que tous les champs ne sont pas vides sinon on référence un erreur !
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
            {
                $values[$champ] = $_POST[$champ];
            }
               
            elseif(isset($tab_erreur[$champ])) 
            {
                $erreur.= '<br>'.$tab_erreur[$champ];
            }  
                
            else
            {
                $values[$champ] = NULL;
            }
                
        }


        /** SI pas d'erreurs on fini la préparation des données et on save ! */
        if($erreur =='')
        {
            
            
            //On déplace lA PHOTO 1 transmis pour l'annonce dans le répertoire uploads/annonces/ 
                if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == UPLOAD_ERR_OK) 
                {
                    $tmp_name = $_FILES["picture"]["tmp_name"];
                    $name = $_FILES["picture"]["name"];
                    
                    if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                        {
                            $values['photo1'] = $name;
                        }
                    else
                        {
                            $values['photo1'] = NULL;
                        }
                }
                else
                    {
                        $values['photo1'] = NULL;
                    }
        
                


            //On déplace lA PHOTO 2 transmis pour l'annonce dans le répertoire uploads/annonces/ 
            if (isset($_FILES["picture2"]) && $_FILES["picture2"]["error"] == UPLOAD_ERR_OK) 
            {
                $tmp_name = $_FILES["picture2"]["tmp_name"];
                $name = basename(time().'_'.$_FILES["picture2"]["name"]);
                if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                {
                    $values['photo2'] = $name;
                }
                    
                else
                {
                    $values['photo2'] = NULL;
                }
                    var_dump($values);
                }
            else
            {
                $values['photo2'] = NULL;
            }
                    

                


            //On déplace lA PHOTO 3 transmis pour l'annonce dans le répertoire uploads/annonces/ 
            if (isset($_FILES["picture3"]) && $_FILES["picture3"]["error"] == UPLOAD_ERR_OK) 
                {
                    $tmp_name = $_FILES["picture3"]["tmp_name"];
                    $name = basename(time().'_'.$_FILES["picture3"]["name"]);
                    if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                    {
                        $values['photo3'] = $name;
                    }
                    else
                    {
                        $values['photo3'] = NULL;
                    }
                }
                else
                    $values['photo3'] = NULL;

                    


                //On déplace lA PHOTO 4 transmis pour l'annonce dans le répertoire uploads/annonces/ 
                if (isset($_FILES["picture4"]) && $_FILES["picture4"]["error"] == UPLOAD_ERR_OK) 
                {
                    $tmp_name = $_FILES["picture4"]["tmp_name"];
                    $name = basename(time().'_'.$_FILES["picture4"]["name"]);
                    if(move_uploaded_file($tmp_name, REP_BLOG.REP_UPLOAD.'annonces/'.$name))
                    {
                        $values['photo4'] = $name;
                    }
                        
                    else
                    {
                        $values['photo4'] = NULL;
                    }
                }
                else
                    $values['photo4'] = NULL;

            /**2 : Prépare ma requête SQL */
            $sth = $dbh->prepare('INSERT INTO annonces VALUES (NULL, :titre, :content, :photo1, :photo2 , :photo3, :photo4)');
            
            
            /** 3 : executer la requête */
            $sth->execute($values);
            
            $articleId = $dbh->lastInsertId(); 


            /** FLASHBAG
             
             * Le flashBag (notion connue avec le framework symfony) est une variable session qui accueille des messages 
             * à afficher lors de la prochaine requête (souvent automatique avec une redirection). Lors de l'affichage de la prochaine vue le flashbag sera analysé
             * puis son contenu affiché et enfin il sera vidé ! 
             * */
            addFlashBag('Article ajouté avec succès !');

            header('Location:listAnnonce.php');
            exit();
        }
    }
    else
    {
        $sth = $dbh->prepare('SELECT * FROM annonces ');
        $sth->execute();
        $categories = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
catch(PDOException $e)
{
    $erreur.='Une erreur de connexion a eu lieu :'.$e->getMessage();
}


include('tpl/layout.phtml');

