<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.19 $
$Date: 2006/01/31 12:26:17 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","type","sous_type","degats_min","degats_max","durabilite","prix_base","description","poids","image","permanent",
		"munitions","caracteristique","competence","provoqueetat","competencespe","anonyme","id_etattempspecifique","composantes"
	);

if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CreerObjet"])){
		$tmp = explode(";",$type);
		$type = $tmp[0];
		$sous_type = $tmp[1];
		if($munitions == 0){$munitions = -1;}
		if($durabilite == 0){$durabilite = -1;}
		if(($type != 'ArmeMelee') && ($type != 'ArmeJet')) {$anonyme = 0;}
		$provoqueetat = $chaine;
		// on trie $chaine2 par ID croissant pour combiner_objets
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
		$SQL = "INSERT INTO ".NOM_TABLE_OBJET." (";
		$SQL2="";
		$SQL3="";
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
			if ($$liste_champs[$i]<>"") {
				if ($SQL2<>"")  {
					$SQL2.=",";
					$SQL3.=",";	
				}
				$SQL2.=$liste_champs[$i];
				$SQL3.="'".ConvertAsHTML($$liste_champs[$i])."'";	
			}	
		}
		$SQL=$SQL . $SQL2 .") VALUES (" . $SQL3.")";
		if ($db->sql_query($SQL,"")) {
			$MJ->OutPut("Objet ".span(ConvertAsHTML($nom),"objet")." correctement cree",true);
			if (NOM_SCRIPT==("creer_objet.".$phpExtJeu))
				$etape=0;
		}	
		else {
			$template_main .= $db->erreur;	
			// reconcatene le type et sous type
			$type = $tmp[0].";".$tmp[1];
			$etape="0bis";
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	
}
if($etape=="0"){
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
	$provoqueetatValue="";
	$permanent=0;
	$etape="0bis";
	$composantesValue="";
	$degats_min=0;
	$durabilite=-1;
	$munitions=-1;
}

if($etape=="0bis"){

	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "<table class='detailscenter' width='60%'>";
	include('forms/objet.form.'.$phpExtJeu);
	$template_main .= "<tr><td align='center' colspan='2'>";
	$template_main .= '<input type="hidden" name="provoqueetatValue" value="'.ConvertAsHTML($provoqueetatValue).'" />';
	$template_main .= '<input type="hidden" name="chaine" value="'.$provoqueetat.'" />';
	$template_main .= '<input type="hidden" name="provoqueetat" value="'.$provoqueetat.'" />';
	$template_main .= '<input type="hidden" name="composantesValue" value="'.ConvertAsHTML($composantesValue).'" />';
	$template_main .= '<input type="hidden" name="chaine2" value="'.$composantes.'" />';
	$template_main .= '<input type="hidden" name="composantes" value="'.$composantes.'" />';

	$template_main .= "<input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les &eacute;tats eventuellement selectionnes ?')\" /><input type='hidden' name='etape' value='1' /></td></tr>";
	$template_main .= "</table>";
	$template_main .= "</form>";
	
	include('forms/objet2.form.'.$phpExtJeu);
	
	include('forms/objet3.form.'.$phpExtJeu);
	$template_main .= "</div>";	
}


if (NOM_SCRIPT==("creer_objet.".$phpExtJeu)) {
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>