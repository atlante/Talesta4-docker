<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: menu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.17 $
$Date: 2006/09/05 05:53:38 $

*/

require_once("../include/extension.inc");
//if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
	
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}//$template_main .= "Ici on decrit un peu le lieu ou tu te trouves";


if (isset($PERSO) ) {
	if($PERSO->Archive) {
		if  (NOM_SCRIPT<>("archiver.".$phpExtJeu))
			$template_main .= GetMessage("archive"). "<br /><p>&nbsp;</p>";
	}	
	else {
		if (((NOM_SCRIPT==("menu.".$phpExtJeu)) && (!isset($act))) ||((NOM_SCRIPT==("dep_lx.".$phpExtJeu)) && $etape=="1"))
			if ($PERSO->wantmusic && $PERSO->Lieu->cheminfichieraudio!="")
				if(substr($PERSO->Lieu->cheminfichieraudio,0,4)=="http" || file_exists("../lieux/sons/".$PERSO->Lieu->cheminfichieraudio)) 
					$template_main .="<embed src='../lieux/sons/". $PERSO->Lieu->cheminfichieraudio."' loop='1' hidden autostart='true' />";

                $temp=afficheImageLieu($PERSO->Lieu->ID);
		
		$template_main .= makeTableau(2, "center", "container", $temp,"","100%",0);
		$template_main .= "<br />&nbsp;<br />";
		//reduction de 25 a 5 sinon, ca n'affiche pas correctement quand il y a beaucoup de joueurs dans un meme lieu.
		$template_main .= makeTableau(5, "", "container", $PERSO->Lieu->listePJs($PERSO->ID),"nowrap","100%",0);
		
		if ($PERSO->ConnaitObjetsSecrets) {
			$template_main .= GetMessage("ObjetsAterre")."&nbsp;<a href='../game/recuperer_objet.$phpExtJeu'>Voir</a><br />";
		}	

		if ($PERSO->Lieu->possedeQuetesPubliquesDispos()) {
			$template_main .= GetMessage("QuetesDanslieu")."&nbsp;<a href='../game/voirQueteLieu.$phpExtJeu'>Voir</a><br />";
		}			
		
	}		
}

if(!defined("__MENU_JEU.PHP")){include('../game/menu_jeu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>