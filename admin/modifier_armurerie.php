<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_armurerie.php,v $
*/

/**
Bri�ve Description � mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/02/28 22:58:06 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_arm;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierArmurerie"])){
			$result=true;
			if(isset($chaine_vente)){
				$liste2 = explode(";",$chaine_vente);
				for($i=0;($i<count($liste2)-1)&&($result!==false);$i++){
					$liste = explode("|",$liste2[$i]);
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste[0];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur,stockmax,remisestock,quantite, derniereremise) VALUES ('".$id_cible."','".$liste_types_magasins["Armurerie"]."','".$liste[0]."','".$liste[1]."','".$liste[2]."','".$liste[1]."','".time()."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($chaine_repare)&&($result!==false)){
				$liste = explode(";",$chaine_repare);
				for($i=0;($i<count($liste)-1)&&($result!==false);$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie-Repare"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur) VALUES ('".$id_cible."','".$liste_types_magasins["Armurerie-Repare"]."','".$liste[$i]."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($chaine_recharge)&&($result!==false)){
				$liste = explode(";",$chaine_recharge);
				for($i=0;($i<count($liste)-1)&&($result!==false);$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie-Recharge"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur) VALUES ('".$id_cible."','".$liste_types_magasins["Armurerie-Recharge"]."','".$liste[$i]."')";
						$result=$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($del_vente)&&($result!==false)){
				$toto = array_keys($del_vente);
				$tata = array_values($del_vente);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;($i<count($del_vente))&&($result!==false);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$result=$db->sql_query($SQL);
				
			}
			if(isset($del_repare)&&($result!==false)){
				$toto = array_keys($del_repare);
				$tata = array_values($del_repare);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie-Repare"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;($i<count($del_repare))&&($result!==false);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$result=$db->sql_query($SQL);
				
			}
			if(isset($del_recharge)&&($result!==false)){
				$toto = array_keys($del_recharge);
				$tata = array_values($del_recharge);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Armurerie-Recharge"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;($i<count($del_recharge))&&($result!==false);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$result=$db->sql_query($SQL);
				
			}
			if ($result!==false)
				$MJ->OutPut("Armurerie correctement modif&eacute;e",true);
			else 	$template_main .= $db->erreur;
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$id_cible = $id_cible.$sep.$libelle;
	$etape=1;
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$libelle=ConvertAsHTML(substr($id_cible, $pos+strlen($sep))); 
	$id_cible=substr($id_cible, 0,$pos); 
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Armurerie"];
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		$template_main .= "Modifier l'Armurerie de ".span($libelle,"lieu")."<br />";
		while ($row = $db->sql_fetchrow($result)) {				
				$ListeObj[$compteur]=new ObjetMagasin($row["pointeur"],$id_cible,$row["stockmax"],$row["quantite"],$row["remisestock"],$row["derniereremise"]);
				$compteur++;
		}
		$type_tab = 'vente';
		include('forms/armurerie.form.'.$phpExtJeu);
	} else {
		$template_main .= span($libelle,"lieu")." n'est pas encore une armurerie de vente. Ajoutez lui des items &agrave; vendre.<br />";
	}
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Armurerie-Repare"];
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		while ($row = $db->sql_fetchrow($result)) {
				$ListeObj[$compteur]=new ObjetMagasin($row["pointeur"],$id_cible);
				$compteur++;
		}
		$type_tab = 'repare';
		include('forms/armurerie.form.'.$phpExtJeu);
	} else {
		$template_main .= span($libelle,"lieu")." n'est pas encore une armurerie de r&eacute;paration. Ajoutez lui des items &agrave; r&eacute;parer.<br />";
	}
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Armurerie-Recharge"];
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) > 0){
		$ListeObj = null;
		$compteur=0;
		while ($row = $db->sql_fetchrow($result)) {
				$ListeObj[$compteur]=new ObjetMagasin($row["pointeur"],$id_cible);
				$compteur++;
		}
		$type_tab = 'recharge';
		include('forms/armurerie.form.'.$phpExtJeu);
	} else {
		$template_main .= span($libelle,"lieu")." n'est pas encore une armurerie de recharge. Ajoutez lui des items &agrave; recharger.<br />";
	}
	$template_main .= '<input type="hidden" name="chaine_vente" value="" />';
	$template_main .= '<input type="hidden" name="chaine_repare" value="" />';
	$template_main .= '<input type="hidden" name="chaine_recharge" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les armes eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='libelle' value='".$libelle."' />";
	$template_main .= "</form>";
	include('forms/armurerie2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "De quel Lieu voulez vous editez l'armurerie ?<br />";
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