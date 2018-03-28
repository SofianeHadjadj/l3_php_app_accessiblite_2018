<?php
session_start(); // On démarre la session 

if ($_SERVER['HTTP_REFERER'] == "http://perso-cvtic.unilim.fr/DW02_2018/Capture2/view/accessibility/ajouter.php") {
	header('refresh:2;url=../view/accessibility/ajouter.php');
}
else {
	header("location:".$_SERVER['HTTP_REFERER']);
}

include('../model/connexion_BDD.php');
include('../model/ModeleFichiers.php');

//$_SESSION['message'] = "Le fichier a bien &eacute;t&eacute; enregistr&eacute;";

//Conservation du mode confort de lecture si celui ci existe
if ($_POST['choixFunc'] != "") {
    $_SESSION['confort'] = $_POST['choixFunc'];
}

else {
   $_SESSION['confort'] = $_SESSION['confort']; 
}

if ($_POST['choixPol'] != "") {
    $_SESSION['police'] = $_POST['choixPol'];
}

else {
   $_SESSION['police'] = $_SESSION['police']; 
}
?>

	<!-- Suite conservation confort de lecture-->

        <?php include('../view/accessibility/confortLecture.php');
			if(isset($_FILES['mon_fichier']))
			$date_up=date('Y-m-d');
			$ext = '';
			{ 
     
				//1. strrchr renvoie l'extension avec le point (« . »).
				//2. substr(chaine,1) ignore le premier caractère de chaine.
				//3. strtolower met l'extension en minuscules.
				$extension_upload = strtolower(  substr(  strrchr($_FILES['mon_fichier']['name'], '.')  ,1)  );
				$dossier = 'upload/';
				$nom = $_POST['titre'];
				$fichier = "{$nom}.{$extension_upload}";
				if(move_uploaded_file($_FILES['mon_fichier']['tmp_name'], $dossier . $fichier)){
					if ($_SERVER['HTTP_REFERER'] == "http://perso-cvtic.unilim.fr/DW02_2018/Capture2/view/accessibility/ajouter.php") {
					?>
						<html lang="fr">
						    <head>
						        <meta charset="utf-8" />
						        <title>Reception</title>
						        <link href="style.css" rel="stylesheet">
						        <link rel="stylesheet" media="screen" href="../view/accessibility/confort_lecture/style.css" />
						    </head>
						    <body  onload="<?php echo $_SESSION['confort']; if ($_SESSION['confort'] != "") {echo '(); ';}?><?php echo $_SESSION['police']; if ($_SESSION['police'] != "") {echo '(); ';}?>">
						    	<br><br><p>Upload effectu&eacute; avec succ&eacute;s !</p><br><br>
						    </body>
						</html>
					<?php
					}
					else {
						$_SESSION['message'] = "Le fichier a bien &eacute;t&eacute; upload&eacute; sur le serveur";
					}
					$ext = $extension_upload;
					$_SESSION['titre']=$nom;
					$_SESSION['description']=$_POST['description'];
					$_SESSION['type']=$_POST['type'];
					$_SESSION['categorie']=$_POST['categorie'];
					$_SESSION['extension']=$ext;
					$_SESSION['id_user']=$_SESSION['id'];
					$_SESSION['date_upload']=$date_up;
					  
					$valider=new ModeleValidationMedia();
					$validation=$valider->valid_upload();

					$to = "projetcapture@gmail.com";
					$titre = $_SESSION['titre'].".".$ext;
					$headers = "Fichier : $titre -- Page de validation : http://perso-cvtic.unilim.fr/DW02_2018/Capture/validation_fichiers.php";
					$body = "Un nouveau fichier vient d'être uploader, merci de le valider ou de le refuser \n\n"; 
					$send = mail($to, $body, $headers);
					exit();
				}
				else { //Sinon (la fonction renvoie FALSE).
					if ($_SERVER['HTTP_REFERER'] == "http://perso-cvtic.unilim.fr/DW02_2018/Capture2/view/accessibility/ajouter.php") {
					?>
						<html lang="fr">
						    <head>
						        <meta charset="utf-8" />
						        <title>Reception</title>
						        <link href="style.css" rel="stylesheet">
						        <link rel="stylesheet" media="screen" href="../view/accessibility/confort_lecture/style.css" />
						    </head>
						    <body  onload="<?php echo $_SESSION['confort']; if ($_SESSION['confort'] != "") {echo '(); ';}?><?php echo $_SESSION['police']; if ($_SESSION['police'] != "") {echo '(); ';}?>">
						    	<p>Echec de l\'upload !<p><br><br>
						    </body>
						</html>
					<?php
					}
					else {
						$_SESSION['message'] = "<span style='color:red;font-weight:bold'>!! Echec de l'upload !!</span>";
					}
				}
			}

		?>
