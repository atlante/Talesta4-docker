<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: supprimer_spec.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/01/31 12:26:20 $

*/

require_once("../include/extension.inc");if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_spec;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerSpec"])){
		if($id_cible != 1){
			$SQL = "DELETE FROM ".NOM_TABLE_SPECNOM." WHERE id_spec = '".$id_cible."'";
			if ($db->sql_query($SQL)) {
				$SQL = "DELETE FROM ".NOM_TABLE_SPEC." WHERE id_spec = '".$id_cible."'";
				if ($db->sql_query($SQL)) {
					$SQL = "DELETE FROM ".NOM_TABLE_PERSOSPEC." WHERE id_spec = '".$id_cible."'";
					if ($db->sql_query($SQL)) 
						$MJ->OutPut("Sp&eacute;cialisation ".span(ConvertAsHTML($nom_spec),"specialite")." correctement effac&eacute;e",true);
				}		
			}
		}		
		$template_main .= $db->erreur;
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_SPEC." T1, ".NOM_TABLE_SPECNOM." T2 WHERE T1.id_spec = T2.id_spec AND T1.id_spec = ".$id_cible;
	$SQL2 = "SELECT * FROM ".NOM_TABLE_SPECNOM." T2 WHERE T2.id_spec = ".$id_cible;
	$result = $db->sql_query($SQL);
	$result2 = $db->sql_query($SQL2);
	$row2 = $db->sql_fetchrow($result2);	
	
	$template_main .= "nom de la sp&eacute;cialisation : <input type='text' readonly='readonly' name='nom_spec' value='".$row2["nom"]."' size='15' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' readonly='readonly' value='".$row2["rpo"]."' size='4' /> rpa : <input type='text' readonly='readonly' name='rpa' value='".$row2["rpa"]."' size='4' /> rpv : <input type='text' readonly='readonly' name='rpv' value='".$row2["rpv"]."' size='4' />rpi : <input type='text' readonly='readonly' name='rpi' value='".$row2["rpi"]."' size='4' /><br />";
	$template_main .= "Visible par les tiers :".faitOuiNon("Visible","disabled='disabled'",$row2["visible"])."<br />";
	//for($i=0;$i<$db->sql_numrows($result);$i++){
	while(		$row = $db->sql_fetchrow($result)) {
			$comp[$row["id_comp"]]=$row["bonus"];
	}
	
	include('forms/status.form.'.$phpExtJeu);

	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer la sp&eacute;cialisation ".$row2["nom"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quelle sp&eacute;cialisation voulez vous supprimer ?<br />";
	$SQL = "Select T1.id_spec as idselect, T1.nom as labselect from ".NOM_TABLE_SPECNOM." T1 WHERE T1.id_spec > 1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>