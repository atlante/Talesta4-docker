<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_desc_lieu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/02/28 22:58:07 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_desc_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","trigramme","accessible_telp","id_forum"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierDescLieu"])){
		if (($f = fopen("../lieux/descriptions/desc_".$id_cible.".txt","w+b"))!==false) {
			if (fwrite($f,stripslashes($description))===false) {
				$template_main .= "Probleme à l'écriture de '../lieux/descriptions/desc_".$id_cible.".txt'";
			}
			else  {
			if (fclose ($f)===false)
				$template_main .= "Probleme à la fermeture de '../lieux/descriptions/desc_".$id_cible.".txt'";
			else $MJ->OutPut("description de ".span($nom,"lieu")." correctement modifi&eacute;e");
			}	
		}
		else die ("impossible d'ouvrir le fichier ../lieux/descriptions/desc_".$id_cible.".txt en ecriture");	
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_LIEU." WHERE id_lieu = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);

	$template_main .= "Modifier la description de ".span($row["nom"],"lieu")."<br />";

	$template_main .= "<textarea name='description' rows='20' cols='50'>";
		if(file_exists("../lieux/descriptions/desc_".$row["id_lieu"].".txt")){
			$content_array = file("../lieux/descriptions/desc_".$row["id_lieu"].".txt");
			$content = implode("", $content_array);
			$template_main .= $content;
		}
	$template_main .="</textarea>";
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='nom' value='".$row["nom"]."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quelle description de Lieu voulez vous modifier ?<br />";
	$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>