<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: motdepasseperdu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(isset($etape)){
	$erreur="";
	global $db;
	if(( (!isset($nom))|| $nom=="" ) && ( (!isset($email))|| $email=="" )) {
		$erreur = "Il me faut votre nom ou votre E-mail <br />";
		unset($etape);
	}	
	else if ( isset($nom) && $nom<>"" && isset($email) && $email<>"") {
		$erreur = "Il me faut 1 seule information (nom ou votre E-mail) <br />";
		unset($etape);
	}
	else {
		if( (!isset($nom))|| $nom=="" ) {
			if ($Admin==1)
			$SQL="select id_mj as id_perso, nom, email from ".NOM_TABLE_MJ." where email = '". $email."'";
			else 
			$SQL="select id_perso, nom, email from ".NOM_TABLE_PERSO." where email = '". $email."'";
		}
		else 
		if ($Admin==1)
			$SQL="select id_mj as id_perso, nom, email from ".NOM_TABLE_MJ." where nom = '". $nom."'";
		else 	$SQL="select id_perso, nom, email from ".NOM_TABLE_PERSO." where nom = '". $nom."'";
		$recherche = $db->sql_query($SQL);

		if(($db->sql_numrows($recherche)==0) ) {
			if( (!isset($nom))|| $nom=="" ) 
				$erreur = "Aucun personnage n'est enregistr&eacute; avec cet E-mail";
			else 
				$erreur = "Aucun personnage n'est enregistr&eacute; avec ce nom";
			unset($etape);	
		}	
		else {		
			$row = $db->sql_fetchrow($recherche);
			    list($usec, $sec) = explode(' ', microtime());
			    mt_srand((float) $sec + ((float) $usec * 100000));
			    $nouveaupass="";
			    for ($i=1; $i<=8;$i++) {
			    	$var= 96;
				while (($var<=96 && $var>=91) || ($var<=64 && $var>=58))
				     $var=mt_rand(48, 122);
				$nouveaupass.=chr($var);     				
			    }	
			if ($Admin==1)    
				$SQL="update ".NOM_TABLE_MJ." set pass = '". $nouveaupass."' where id_mj = ". $row["id_perso"];
			else
				$SQL="update ".NOM_TABLE_PERSO." set pass = '". md5($nouveaupass)."' where id_perso = ". $row["id_perso"];
			$query=$db->sql_query($SQL);	
			if ($query) {
				//logDate("mot de passe genere" .$nouveaupass);
				if(defined("IN_FORUM")&& IN_FORUM==1)  {
					$query=$forum->MAJuser($row["nom"], "","","",$row["nom"], $nouveaupass);
				}		
				if ($query) {		
					EnvoyerMail("",$row["email"],"[".NOM_JEU." - Mot de passe]","Vous avez demandé votre mot de passe. Celui-ci a été initialisé à ". $nouveaupass .".\n Vous êtes invité à le modifier une fois connecté ");
					$template_main .= "Votre mot de passe vient de vous être envoy&eacute;.";
					unset($erreur);	
				}
			}
			else $template_main .= "Impossible de modifier votre mot de passe (raison: $db->erreur;).";
		}	
	}	
}

if(!isset($etape)){
	if(!isset($nom)){$nom='';}
	if(!isset($email)){$email='';}

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Vous avez oubli&eacute; votre mot de passe.<br /> Entrer votre nom de Personnage OU votre email. <br />";
	if(isset($erreur)){$template_main .= "<span class='c0'>".$erreur."</span><br />";}
	$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td>nom du Personnage</td><td><input type='text' name='nom' size='35' maxlength='25' value='".$nom."' /></td></tr>";
	$template_main .= "<tr><td>email</td><td><input type='text' name='email' size='35' maxlength='80' value='".$email."' /></td></tr>";
	$template_main .= "<tr><td colspan='2'><input type='submit' value='Envoyer moi mon mot de passe' /></td></tr>";
	$template_main .= "</table>";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='Admin' value='".$Admin ."' />";
	$template_main .= "</form></div>";
}


if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>