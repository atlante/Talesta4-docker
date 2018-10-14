<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_magie.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/02/28 22:58:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","prix_base","description","place","image","permanent",
		"charges","caracteristique","competence","provoqueetat","anonyme","id_etattempspecifique","typecible" ,
		"sortdistant","composantes","coutpa","coutpi","coutpo","coutpv"
	);

if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerMagie"])){
		if($id_cible != 1){
			$SQL = "DELETE FROM ".NOM_TABLE_MAGIE." WHERE id_magie = '".$id_cible."'";
			$db->sql_query($SQL);
			$SQL = "DELETE FROM ".NOM_TABLE_PERSOMAGIE." WHERE id_magie = '".$id_cible."'";
			$db->sql_query($SQL);
			$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Magasin Magique"]."' AND pointeur = '".$id_cible."'";
			$db->sql_query($SQL);
			$MJ->OutPut("Magie ".span(ConvertAsHTML($nom),"sort")." correctement effac&eacute;",true);
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGIE." WHERE id_magie = ".$id_cible;
	$result=$db->sql_query($SQL);	
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
		}
	include('forms/magie.form.'.$phpExtJeu);

	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer le sort ".$row["nom"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";

	$tmp = explode("|",$provoqueetat);
	$i=0;
	$provoqueetatValue="";
	while ($tmp[$i]) {	
		$temp = explode(";",$tmp[$i]);
		$SQL = "SELECT nom FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = ".$temp[0];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$provoqueetatValue.="<option value='".$tmp[$i]."'>".$row["nom"]
		. ";" . $temp[1] . '%;' . $temp[2] ." h"
		."</option>";	
		$i++;
	}
	$template_main .= '<input type="hidden" name="chaine" value="'.$provoqueetat.'" />';
	$template_main .= "</form>"; 
	$composantesValue="";
	$tmp=explode(";",$composantes);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$composantesValue.="<option value=\"".$tmp[$i]."\">".$row["nom"]."</option>";	
		$i++;		
	}

	include('forms/objet2.form.'.$phpExtJeu);
	include('forms/objetComposantesSort.form.'.$phpExtJeu);	
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel sort voulez vous effacer ?<br />";
	$SQL = "Select T1.id_magie as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),case when T1.anonyme = 1 then ' -(anonyme)' else '' end),'  --> '),T1.nom),'   - (Cha:'),T1.charges),', Degs '),T1.degats_min),'-'),T1.degats_max),', place '),T1.place),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_MAGIE." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>