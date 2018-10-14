<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_chemin.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/01/31 12:26:18 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_chemin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"id_lieu_1","id_lieu_2","type","difficulte","pass","distance"
	);


if(!isset($etape)){$etape=0;}
if($etape==2){
	$pos = strpos($id_lieu_2, $sep);
	$libelle=substr($id_lieu_2, $pos+strlen($sep)); 
	$id_lieu_2=substr($id_lieu_2, 0,$pos); 	
	if($MJ->aDroit($liste_flags_mj["ModifierChemin"])){
		$SQL = "UPDATE ".NOM_TABLE_CHEMINS." SET ";
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
				$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
				if($i != ($nbchamps -1) ){$SQL .= ",";}
		}
		$SQL .= " WHERE id_clef = ".$id_cible;
		if ($db->sql_query($SQL))
			$MJ->OutPut("Chemin correctement modifi&eacute;",true);
		else 	$MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.*, T3.nom as nomlieuarrivee FROM ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_2 = T3.id_lieu and T1.id_clef = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	$id_lieu_2 .= $sep . $row['nomlieuarrivee'];
	include('forms/chemin.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel chemin voulez vous modifier ?<br />";
	$SQL = "Select T1.id_clef as idselect, concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'-  '),T2.trigramme),'-'),T2.nom),'   --->   '),T3.trigramme),'-'),T3.nom) as labselect from ".NOM_TABLE_CHEMINS." T1, ".NOM_TABLE_LIEU." T2,".NOM_TABLE_LIEU." T3 WHERE T1.id_lieu_1 = T2.id_lieu AND T1.id_lieu_2 = T3.id_lieu ORDER BY T1.type, T2.trigramme, T2.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>