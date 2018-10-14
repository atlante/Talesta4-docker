<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: supprimer_question.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.6 $
$Date: 2006/01/31 12:26:20 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_qcm;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"question","reponse1","reponse2","reponse3","reponse4","bonne"
		);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerQuestion"])){
		$SQL = "DELETE FROM ".NOM_TABLE_QCM." WHERE id_question ='".$id_cible."'";
		if($db->sql_query($SQL))
			$MJ->Output("question ".span(ConvertAsHTML($question),"pj")." correctement effac&eacute;e",true);
		else $template_main .= $db->erreur;	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_QCM." WHERE id_question = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = ConvertAsHTML($row[$liste_champs[$i]]);
	} 
	include('forms/question.form.'.$phpExtJeu);
	$template_main .= "<br><input type='submit' value='Effacer' onclick=\"return confirm('Etes-vous sur de vouloir effacer la question ".$row["question"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form>";
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quelle question voulez-vous supprimer ?<br /><br />";
	$SQL = "Select T1.id_question as idselect, T1.question as labselect from ".NOM_TABLE_QCM." T1 ORDER BY T1.question ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br /><br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";

}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>