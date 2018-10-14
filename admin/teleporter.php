<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: teleporter.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2010/02/28 22:58:11 $

*/
 
require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $teleport;
if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom", "nomlieu", "idlieu","trigramme","dissimule"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["DeplacerPJ"])){
		$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', id_lieu = ". $id_lieu.", dissimule = ".$dissimule;
		$SQL .= " WHERE id_perso = ".$id_cible;
		if ($requete2=$db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
			global $liste_type_objetSecret;
			$toto = array_keys($liste_type_objetSecret);
			$SQL="DELETE FROM ".NOM_TABLE_ENGAGEMENT." WHERE id_perso = ".$id_cible." OR id_adversaire = ".$id_cible;
   			$db->sql_query($SQL);
	
			if ($anciendissimule) {	
				$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    id_entite = ".$id_cible .
				" and type = ".$toto[2];
				$requete = $db->sql_query($SQL);
				if ($db->sql_numrows($requete)>0) {
					$row = $db->sql_fetchrow($requete);				
					$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee =" . $row["id"];
					if ($requete2=$db->sql_query($SQL)) {
						$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
						$requete2=$db->sql_query($SQL);	
					}	
				}
			}	
	
			if ($dissimule && $requete2!==false) {	
				$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$id_cible.",'".ConvertAsHTML($id_lieu)."',". $toto[2].", '".ConvertAsHTML($nom)."')";
				$requete2=$db->sql_query($SQL);
			}
			if ($requete2)
				$MJ->OutPut("Lieu de ".span(ConvertAsHTML($nom),"pj")." correctement modifi&eacute;",true);
		}		
		$MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT t1.nom as nom, t2.nom as nomlieu, t2.id_lieu as idlieu, t2.trigramme, t1.dissimule as dissimule FROM ".NOM_TABLE_REGISTRE. " t1, ".NOM_TABLE_LIEU." t2 WHERE t1.id_lieu=t2.id_lieu and id_perso = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champs[$i] = $row[$liste_champs[$i]];
	} 
	include('forms/teleporter.form.'.$phpExtJeu);

	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir modifier le lieu de ce PJ ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='nom' value='".$nom."' />";
	$template_main .= "<input type='hidden' name='anciendissimule' value='".$dissimule."' />";

	$template_main .= "</form>";
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Le Lieu de quel PJ voulez vous modifier ?<br />";
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where pnj<> 2 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>