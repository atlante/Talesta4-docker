<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: fa.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/01/31 12:26:24 $

*/

require_once("../include/extension.inc");	
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fa_perso;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

	/*
	if(! isset($del))
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' methode='post'>
			<input type='hidden' name='del' value='1' />
			<input type='submit' value='Reseter' /></form></div>";
	*/
	
	if(isset($del)){
		$PERSO->ArchiveFA(FALSE);
	}
	else {
		$contenu = $PERSO->LireFA();
		$fagz = $PERSO->GetCheminFA();			
		if(file_exists($fagz)){		
			$temp=TAILLE_MAX_FA*1024;
			$template_main .= "<p align='right'>Taille du FA: ".filesize ($fagz)." octets sur ".$temp." autoriss</p>";
		}
		$template_main .= ($contenu);
	}	
	
	$template_main .= "<br /><p>&nbsp;</p>";
	
	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>