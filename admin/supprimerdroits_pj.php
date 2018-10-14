<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: supprimerdroits_pj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/02/21 18:29:09 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $retrograder_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","pass","titre","email","wantmail","dispo_pour_ppa"
	);

if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerMJ"]) && $MJ->aDroit($liste_flags_mj["SupprimerDroitsMJauxPJs"])){
		$SQL = "update ".NOM_TABLE_REGISTRE ." set role_mj = null where id_perso = ".$id_ciblePJ;
		if ($result=$db->sql_query($SQL)) {
			if($id_cibleMJ != 1){
				$SQL = "DELETE FROM ".NOM_TABLE_MJ." WHERE ID_MJ = '".$id_cibleMJ."'";
				if ($result=$db->sql_query($SQL)) {
					if(defined("IN_FORUM")&& IN_FORUM==1) {
						$result=$forum->DeleteMembre($nom);
					}		
					if($result) {
						if(file_exists("../fas/mj_".$id_cibleMJ.".fa"))
							if (unlink ("../fas/mj_".$id_cibleMJ.".fa")===false)
								$template_main .= "Impossible d'effacer le fichier '../fas/mj_".$id_cibleMJ.".fa'";
						
						$MJ->OutPut("MJ ".span(ConvertAsHTML($nom),"mj")." correctement effac&eacute;",true);
					}
					else $MJ->OutPut($db->erreur);
				}
				else $MJ->OutPut($db->erreur);
			}	
		}
		else $MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$id_ciblePJ=substr($id_cible, $pos+strlen($sep)); 
	$id_cibleMJ=substr($id_cible, 0,$pos); 
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )  
		$SQL=$forum->requeteMJ($id_cibleMJ);
	else $SQL = "SELECT * FROM ".NOM_TABLE_MJ." WHERE ID_MJ =".$id_cibleMJ;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);	
	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )  {
		$imageforum = $row[$forum->champimage];
		$imagetype = $row[$forum->champtypeimage];
	}	

	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	include('forms/mj.form.'.$phpExtJeu);
	$template_main .= "<br /><input type='submit' value='enlever' />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cibleMJ' value='".$id_cibleMJ."'>";
	$template_main .= "<input type='hidden' name='id_ciblePJ' value='".$id_ciblePJ."'>";
	$template_main .= "</form></div>";
}
/*
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel MJ voulez vous supprimer ?<br />";
	$SQL = "Select T1.ID_MJ as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1 WHERE T1.ID_MJ > 1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}
*/

if($etape=="0"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel PJ voulez vous rétrograder ?<br />";
	$SQL = "Select concat(concat(role_mj,'$sep'),id_perso)  as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where role_mj is not null ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";	
}	

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>