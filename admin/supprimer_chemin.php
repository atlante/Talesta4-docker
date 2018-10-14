<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: supprimer_chemin.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:20 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_chemin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"id_lieu_1","id_lieu_2","type","difficulte","pass","distance"
	);

if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerChemin"])){
		$toto = array_keys($liste_type_objetSecret);
		$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    id_entite = ".$id_cible ." and type = ".$toto[0];
		$requete=$db->sql_query($SQL);	
		$requete2=true;
		if ($db->sql_numrows($requete)>0) {
			$row = $db->sql_fetchrow($requete);
			$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee=" . $row["id"];
			if ($requete2=$db->sql_query($SQL)) {
				$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
				$requete2=$db->sql_query($SQL);	
			}	
		}
		if ($requete2!==false) {
			$SQL = "DELETE FROM ".NOM_TABLE_CHEMINS." WHERE id_clef = '".$id_cible."'";
			if ($db->sql_query($SQL))
				$MJ->OutPut("Chemin correctement effac&eacute;",true);
		}
		$MJ->OutPut($db->erreur);	
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
	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer ce Chemin ?')\">";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."'>";
	$template_main .= "</form></div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel chemin voulez vous supprimer ?<br />";
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