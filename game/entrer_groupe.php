<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: entrer_groupe.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.19 $
$Date: 2006/01/31 12:26:24 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $entrer_groupe;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(defined("GROUPE_PJS") && GROUPE_PJS==1) {
	if(!isset($etape)){$etape=0;}
	if($etape=="1"){
		if(isset($id_groupe)){
			$pos = strpos($id_groupe, $sep);
			$libelle=substr($id_groupe, $pos+strlen($sep)); 
			$id_groupe=substr($id_groupe, 0,$pos); 
			if ($PERSO->EntrerGroupe($id_groupe))
				$PERSO->OutPut("Vous faites d&eacute;sormais partie du groupe ".span(ConvertAsHTML($libelle),"etattemp"),true);
		}
		unset($id_groupe);
		$template_main .= "<br /><p>&nbsp;</p>";
		
	}
	if($etape===0){
		if ($PERSO->Groupe!="") {
			$template_main .= Getmessage("entrer_groupeKO");
			$template_main .= "<br /><p>&nbsp;</p>";
		}
		else {	
			$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
			$SQL ="Select distinct concat(concat(g.ID_groupe,'$sep'),g.nom) as idselect, g.nom as labselect from ".NOM_TABLE_GROUPE." g, ".NOM_TABLE_REGISTRE. " p where g.ID_groupe=p.ID_groupe and p.id_lieu=". $PERSO->Lieu->ID."  ORDER BY g.nom";
			$var=faitSelect("id_groupe",$SQL,"");		
			if ($var[0]>0) {
				$template_main .= "Dans quel groupe voulez vous entrer ?<br />".$var[1];
				$template_main .= "<br />". GetMessage("warningGroupe");
				$template_main .= "<br />".BOUTON_ENVOYER;
			} else $template_main .= "Il n'y a aucun groupe ici. <br />";
			$template_main .= "<input type='hidden' name='etape' value='1' />";
			$template_main .= "</form></div>";
		}
	}
}
else {
	$template_main .= GetMessage("gestionGroupeInterdit")."<br /><br />";
}	
	

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>