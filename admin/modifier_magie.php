<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_magie.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/02/28 22:58:08 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","prix_base","description","place","image","permanent",
		"charges","caracteristique","competence","provoqueetat","anonyme","id_etattempspecifique","typecible",
		"sortdistant","composantes","coutpa","coutpi","coutpo","coutpv"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierMagie"])){
		if($charges == 0){$charges = -1;}
		// on trie $chaine2 par ID croissant pour combinet_objets
		$tmp2 = explode(";",$chaine2);
		if($tmp2[0]) {
			logdate("nb tmp2".count($tmp2));
			sort($tmp2);
			logdate("nb tmp2".count($tmp2));
			array_shift($tmp2);//supprime le null du debut
			logdate("nb tmp2".count($tmp2));
			$chaine2 = implode(";",$tmp2).";";

		}
		$composantes=$chaine2;		

		$SQL = "UPDATE ".NOM_TABLE_MAGIE." SET ";
		$provoqueetat = $chaine;	
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
				//$SQL.=$liste_champs[$i]."= '".$tmp."'";
				if ($$liste_champs[$i]<>"")
					$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
				else 	$SQL.=$liste_champs[$i]."= null";
				if($i != ($nbchamps -1) ){$SQL .= ",";}
		}
		$SQL .= " WHERE id_magie = ".$id_cible;
		if($db->sql_query($SQL)) {
			$MJ->OutPut("Sort ".span(ConvertAsHTML($nom),"sort")." correctement modifi&eacute;",true);
			$etape=0;
		}	
		else {
			$template_main .= $db->erreur;	
			$etape="1bis";
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}

if($etape=="1"){
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGIE." WHERE id_magie = ".$id_cible;
	$result=$db->sql_query($SQL);	
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

		$composantesValue="";
		$tmp=explode(";",$composantes);
		$i=0;
		while ($tmp[$i]) {	
			$tmp_compo = explode("|",$tmp[$i]);
			$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp_compo[0];
			$result=$db->sql_query($SQL);	
			$row = $db->sql_fetchrow($result);
			$composantesValue.="<option value=\"".$tmp_compo[0]."\">".$row["nom"];
			if ($tmp_compo[1]==1) {
				$composantesValue.=" ".getMessage("composanteConservee");
			}	
			else $composantesValue.=" ".getMessage("composanteDetruiteAuLancement");
			$composantesValue.="</option>";	
			$i++;		
		}
	$etape="1bis";
}
if($etape=="1bis"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	include('forms/magie.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='provoqueetatValue' value='".ConvertAsHTML($provoqueetatValue)."' />";
	$template_main .= "<input type='hidden' name='chaine' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='provoqueetat' value='".$provoqueetat."' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='submit' value='Dupliquer ce sort' onclick=\"document.forms[0].etape.value='0bis';document.forms[0].action='creer_magie.$phpExtJeu';document.forms[0].submit();\" />";
	$template_main .= '<input type="hidden" name="composantesValue" value="'.ConvertAsHTML($composantesValue).'" />';
	$template_main .= '<input type="hidden" name="chaine2" value="'.$composantes.'" />';
	$template_main .= '<input type="hidden" name="composantes" value="'.$composantes.'" />';

	$template_main .= "</form>"; 

	include('forms/objet2.form.'.$phpExtJeu);

	include('forms/objetComposantesSort.form.'.$phpExtJeu);

	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel sort voulez vous modifier ?<br />";
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