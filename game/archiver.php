<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: archiver.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/01/31 12:26:23 $

*/

	require_once("../include/extension.inc");	
	if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
	if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
	if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $archiver;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
	if(!isset($etape)){

		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		if ($PERSO->Archive==1) {
			$template_main .= "Voulez vous d&eacute;sarchiver votre perso ? ";
		}
		else {
			$template_main .= "Voulez vous archiver votre perso ? ";
		}
		$template_main .="<select name='valider'><option value='0'>Non</option><option value='1'>Oui</option></select>";
		$template_main .= "<br />";
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
		$etape=0;
	} 

	if($etape=="1"){
		$etape=0;
		if ($valider==1) {
			if ($PERSO->Archive==1) {
				if ($PERSO->desarchiver())
					$template_main .= GetMessage("desarchiveOK");
			}
			else {
				if ($PERSO->Engagement)
					$template_main .= GetMessage("engagé");
				else	
				if ($PERSO->archiver())
					$template_main .= GetMessage("archiveOK");
			}
		}
		$template_main .= "<br /><p>&nbsp;</p>";
	
	}

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>