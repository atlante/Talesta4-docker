<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_lieuapprentissage.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:18 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_lieu_app;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierArmurerie"])){
			if(isset($chaine_vente)){
				$liste = explode(";",$chaine_vente);
				for($i=0;$i<count($liste)-1;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Lieu d'apprentissage"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste_comp_full[$liste[$i]];
					$result = $db->sql_query($SQL);
										if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur) VALUES ('".$id_cible."','".$liste_types_magasins["Lieu d'apprentissage"]."','".$liste_comp_full[$liste[$i]]."')";
						$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($del_)){
				$toto = array_keys($del_);
				$tata = array_values($del_);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Lieu d'apprentissage"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;$i<count($del_);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$liste_comp_full[$toto[$i]]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$db->sql_query($SQL);
				
			}
			$MJ->OutPut("Lieu d'apprentissage correctement modif&eacute;",true);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible = $id_cible.$sep.$libelle;
	$etape=1;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 

	global $liste_comp_full;
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1 WHERE T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Lieu d'apprentissage"];
	$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		$template_main .= "Modifier le lieu d'apprentissage de ".span($libelle,"lieu")."<br />";
		while($row = $db->sql_fetchrow($result)) {
				$ListeObj[$compteur]= array_search($row["pointeur"], $liste_comp_full); 
				$compteur++;
		}
		include('forms/competence.form.'.$phpExtJeu);
	} else {
		$template_main .= span($libelle,"lieu") ." n'est pas encore un lieu d'apprentissage. Ajoutez lui des comp&eacute;tences.<br />";
	}
	$template_main .= '<input type="hidden" name="chaine_vente" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les comp&eacute;tences &eacute;ventuellement s&eacute;lectionn&eacute;es ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='libelle' value='".$libelle."' />";
	$template_main .= "</form>";
	include('forms/competence2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "De quel Lieu voulez vous &eacute;ditez la salle d'entranement ?<br />";
	$SQL = "Select concat(concat(T1.id_lieu,'$sep'),T1.nom) as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.id_lieu > 1 ORDER BY T1.trigramme, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>