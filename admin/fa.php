<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: fa.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/01/31 12:26:17 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fa_admin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


/*
if(! isset($del))
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>
		<input type='hidden' name='del' value='1' />
		<input type='submit' value='Reseter' /></form></div>";*/


	if(isset($del)){
		$MJ->ArchiveFA(FALSE);
	}
	else {
	$fagz = $MJ->GetCheminFA();			
	if(file_exists($fagz)){		
		$temp=TAILLE_MAX_FA*1024;
		$template_main .= "<p align='right'>Taille du FA: ".filesize ($fagz)." octets sur ".$temp." autorisés</p>";
	}
		$contenu = $MJ->LireFA();
		$template_main .= ($contenu);
	}	
	


$template_main .= "<br /><p>&nbsp;</p>";

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>