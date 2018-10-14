<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: supprimer_typeetat.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2006/01/31 12:26:20 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_type_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nomtype","critereinscription","modifiableparpj"
	);
	
if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["CreerEtat"])){
		$SQL = "DELETE FROM ".NOM_TABLE_TYPEETAT." WHERE id_typeetattemp = '".$id_cible."'";
		if ($db->sql_query($SQL))
			$MJ->OutPut("Type d'Etat ". span(ConvertAsHTML($nomtype),"etattemp")." correctement supprim&eacute;",true);
		else 	$MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_TYPEETAT." WHERE id_typeetattemp = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	if ($critereinscription =="0") {
		$critereinscription1=0;
		$choixObligatoire=0;	
	}	
	else if ($critereinscription =="1") {
		$critereinscription1=1;
		$choixObligatoire=0;	
	}	
	else if ($critereinscription =="2") {
		$critereinscription1=1;
		$choixObligatoire=1;	
	}	
	include('forms/typeEtatTemp.form.'.$phpExtJeu);
	//$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer le type d'etat ".$row["nomtype"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='nomtype' value='$nomtype' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel type d'etat temp voulez vous supprimer ?<br />";
	$SQL = "Select id_typeetattemp  as idselect, nomtype as labselect from ".NOM_TABLE_TYPEETAT." order by nomtype";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>