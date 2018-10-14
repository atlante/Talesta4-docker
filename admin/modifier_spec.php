<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_spec.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/04/17 21:24:51 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_spec;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierSpec"])){
		if($id_cible != 1){
			$SQL = "UPDATE ".NOM_TABLE_SPECNOM." SET nom = '".ConvertAsHTML($nom_spec)."', rpa= '".$rpa."', rpo = '".$rpo."', rpv = '".$rpv."', rpi = '".$rpi."',Visible = '".$Visible."' WHERE id_spec = '".$id_cible."'";
			if ($result = $db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
				$SQL = "DELETE FROM ".NOM_TABLE_SPEC." WHERE id_spec = '".$id_cible."'";
				if ($result = $db->sql_query($SQL)) {
					if(isset($comp)){
						$toto = array_keys($comp);
						$tata = array_values($comp);
						$debutSQL = "INSERT INTO ".NOM_TABLE_SPEC." (id_spec,id_comp,bonus) VALUES ";
						for($i=0;$i<count($comp) && $result!==false;$i++){
							if($tata[$i] != 0){
								$SQL = $debutSQL . "('".$id_cible."','".$toto[$i]."','".$tata[$i]."')";
								$result=$db->sql_query($SQL);
							}
						}
					}
					if ($result)
						$MJ->OutPut("Sp&eacute;cialisation ".span(ConvertAsHTML($nom_spec),"specialite")." correctement modif&eacute;e",true);
				}	
			}
			$MJ->OutPut($db->erreur);
		}
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
	$template_main .= "nom de la sp&eacute;cialisation : <input type='text' name='nom_spec' value='".$row2["nom"]."' size='15' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' value='".$row2["rpo"]."' size='4' /> rpa : <input type='text' name='rpa' value='".$row2["rpa"]."' size='4' /> rpv : <input type='text' name='rpv' value='".$row2["rpv"]."' size='4' />rpi : <input type='text' name='rpi' value='".$row2["rpi"]."' size='4' /><br />";
	$template_main .= "Visible par les tiers :".faitOuiNon("Visible","",$row2["visible"])."<br />";
	while($row = $db->sql_fetchrow($result)) {
			$comp[$row["id_comp"]]=$row["bonus"];
	}
	include('forms/status.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quelle sp&eacute;cialisation voulez vous modifier ?<br />";
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