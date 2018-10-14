<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_lieu.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/02/28 22:58:07 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_lieu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","trigramme","accessible_telp","id_forum","provoqueetat","difficultedesecacher","cheminfichieraudio" 
		//, "typemimefichieraudio"
		, "id_etattempspecifique","apparition_monstre","type_lieu_apparition"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierLieu"])){
	        if ($suppImage==1) {
			if(file_exists("../lieux/vues/view".$id_cible.".jpg"))
				if ((unlink ("../lieux/vues/view".$id_cible.".jpg"))===false)
					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".jpg'";
			if(file_exists("../lieux/vues/view".$id_cible.".gif"))
				if((unlink ("../lieux/vues/view".$id_cible.".gif"))===false)
					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".gif'";
			if(file_exists("../lieux/vues/view".$id_cible.".png"))
				if((unlink ("../lieux/vues/view".$id_cible.".png"))===false)
					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".png'";
	        }        
	        
	        
		if (isset($HTTP_POST_FILES['fichierImage']['tmp_name']) && isset($HTTP_POST_FILES['fichierImage']['name'])&& $HTTP_POST_FILES['fichierImage']['tmp_name']!="") {
				$erreur=verif_EstImage($HTTP_POST_FILES['fichierImage']['name']);
				$ext=strtolower(substr($HTTP_POST_FILES['fichierImage']['name'],strlen($HTTP_POST_FILES['fichierImage']['name'])-3));
				if ($erreur=="") {
                			if(file_exists("../lieux/vues/view".$id_cible.".jpg"))
                				if ((unlink ("../lieux/vues/view".$id_cible.".jpg"))===false)
                					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".jpg'";
                			if(file_exists("../lieux/vues/view".$id_cible.".gif"))
                				if((unlink ("../lieux/vues/view".$id_cible.".gif"))===false)
                					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".gif'";
                			if(file_exists("../lieux/vues/view".$id_cible.".png"))
                				if((unlink ("../lieux/vues/view".$id_cible.".png"))===false)
                					$template_main .= "Impossible d'effacer le fichier '../lieux/vues/view".$id_cible.".png'";
					uploadImage($HTTP_POST_FILES['fichierImage']['tmp_name'],"../lieux/vues/view".$id_cible.".".$ext);
				}	
				else 	$template_main .=$erreur;
		}		
	        
	        
		if(substr($cheminfichieraudio,0,4)<>"http" && (! file_exists("../lieux/sons/".ConvertAsHTML($cheminfichieraudio)))) {
			$template_main .= ("Fichier sonore ". ConvertAsHTML($cheminfichieraudio) . " non trouvé dans lieux/sons ");	
			$etape="1bis";			
		}	
		else {
			$SQL = "UPDATE ".NOM_TABLE_LIEU." SET ";
			$fl = "";
			for($i=0;$i<count($flags);$i++){
				$fl .= $flags[$i];
			}
			$fl .= '000000000000000000000';
			$flags = $fl;
			$provoqueetat = $chaine;
			$nbchamps = count($liste_champs);
			for($i=0;$i<$nbchamps;$i++){
					$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
					if($i != ($nbchamps -1) ){$SQL .= ",";}
			}
			$SQL .= " WHERE id_lieu = ".$id_cible;
			if ($db->sql_query($SQL)) {		
				$MJ->OutPut("Lieu ".span(ConvertAsHTML($nom),"objet")." correctement modifi&eacute;",true);
				$etape=0;
			}	
			else {
				$template_main .= ($db->erreur);	
				$etape="1bis";
			}	
		}
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if($etape=="1"){
	$SQL = "SELECT * FROM ".NOM_TABLE_LIEU." WHERE id_lieu = ".$id_cible;
	$result = $db->sql_query($SQL);

	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	$tmp = explode("|",$provoqueetat);
	$i=0;
	$provoqueetatValue="";
	while ($tmp[$i]) {	
		$temp = explode(";",$tmp[$i]);
		//supprime le moins eventuel
		if ($temp[0]{0}=="-") {
			$operateur="etatsupprime";
			$temp[0]=substr($temp[0],1);
		}
		else $operateur="etatajout";			
		$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$provoqueetatValue.="<option value=\"".$tmp[$i]."\" class=\"".$operateur."\">".$row["nom"]
		. ";" . $temp[1] . '%;' . $temp[2] ." h"
		."</option>";	
		$i++;		
	}
}
if($etape=="1"||$etape=="1bis"){
	$template_main .= "<div class ='centerSimple'><form id='formLieu' name='formLieu' enctype='multipart/form-data' action='".NOM_SCRIPT."' method='post'>";
	include('forms/lieu.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='provoqueetatValue' value='".ConvertAsHTML($provoqueetatValue)."' />";
	$template_main .= "<input type='hidden' name='chaine' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='provoqueetat' value='".$provoqueetat."' />";
	$template_main .= "<input type='submit' value='Dupliquer ce lieu' onclick=\"document.forms[0].etape.value='0.5';document.forms[0].action='creer_lieu.".$phpExtJeu."';document.forms[0].submit();\" />";
	$template_main .= "</form>"; 

	include('forms/objet2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}


if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel Lieu voulez vous modifier ?<br />";
	$SQL = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var= faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>"; 
	$template_main .= "</div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>