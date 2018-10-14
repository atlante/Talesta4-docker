<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_mag_magique.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2010/02/28 22:58:08 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_mag_sort;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","trigramme","accessible_telp","id_forum"
	);

if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierMagasinMagique"])){
			$result =true;
			if(isset($chaine_vente)){
				$liste = explode(";",$chaine_vente);
				for($i=0;$i<count($liste)-1;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Magasin Magique"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur) VALUES ('".$id_cible."','".$liste_types_magasins["Magasin Magique"]."','".$liste[$i]."')";
						$result =$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($chaine_recharge) && $result ){
				$liste = explode(";",$chaine_recharge);
				for($i=0;$i<count($liste)-1;$i++){
					$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Magasin Magique-Recharge"]."' AND id_lieu = '".$id_cible."' AND pointeur = ".$liste[$i];
					$result = $db->sql_query($SQL);
					if($db->sql_numrows($result) == 0){
						$SQL = "INSERT INTO ".NOM_TABLE_MAGASIN." (id_lieu,type,pointeur) VALUES ('".$id_cible."','".$liste_types_magasins["Magasin Magique-Recharge"]."','".$liste[$i]."')";
						$result =$db->sql_query($SQL);
					}
					
				}
			}
			if(isset($del_vente) && $result ){
				$toto = array_keys($del_vente);
				$tata = array_values($del_vente);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Magasin Magique"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;$i<count($del_vente);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$result =$db->sql_query($SQL);
				
			}
			if(isset($del_recharge)&&$result){
				$toto = array_keys($del_recharge);
				$tata = array_values($del_recharge);
				$SQL = "DELETE FROM ".NOM_TABLE_MAGASIN." WHERE type = '".$liste_types_magasins["Magasin Magique-Recharge"]."' AND id_lieu = '".$id_cible."' AND (";
				for($i=0;$i<count($del_recharge);$i++){
					if($tata[$i] == "on"){
						$SQL.= " pointeur = '".$toto[$i]."' OR";
					}
				}
				$SQL = substr($SQL,0,strlen($SQL)-2).")";
				$result =$db->sql_query($SQL);
				
			}
			if ($result)
				$MJ->OutPut("Magasin Magique correctement modif&eacute;",true);
			else
				$template_main .= $db->erreur;	
				
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
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_MAGIE." T2 WHERE T1.pointeur = T2.id_magie AND T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Magasin Magique"];
	$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
		$ListeSort = null;
		$compteur=0;
		$template_main .= "Modifier le Magasin Magique de ".span($libelle,"lieu")."<br />";
		while($row = $db->sql_fetchrow($result)){				
				$ListeSort[$compteur]=new Magie($row["pointeur"]);
				$compteur++;
		}
		$type_tab = 'vente';
		include('forms/mag_magique.form.'.$phpExtJeu);
	} else {
		$template_main .= span($libelle,"lieu")." n'est pas encore un magasin magique de vente. Ajoutez lui des sorts a vendre.<br />";
	}
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_MAGIE." T2 WHERE T1.pointeur = T2.id_magie AND T1.id_lieu = ".$id_cible." AND T1.type = ".$liste_types_magasins["Magasin Magique-Recharge"];
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result) > 0){
		$ListeSort = null;
		$compteur=0;
		while($row = $db->sql_fetchrow($result)){
				$ListeSort[$compteur]=new Magie($row["pointeur"]);
				$compteur++;
		}
		$type_tab = 'recharge';
		include('forms/mag_magique.form.'.$phpExtJeu);
	} else {
		$template_main .= "Ce Lieu n'est pas encore un magasin magique de recharge. Ajoutez lui des sorts a recharger.<br />";
	}
	$template_main .= '<input type="hidden" name="chaine_vente" value="" />';
	$template_main .= '<input type="hidden" name="chaine_recharge" value="" />';
	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer les sorts eventuellement selectionnes ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='libelle' value='".$libelle."' />";
	$template_main .= "</form>";
	include('forms/mag_magique2.form.'.$phpExtJeu);
	$template_main .= "</div>";
}
if($etape===0){
	$SQL = "Select concat(concat(T1.id_lieu,'$sep'),T1.nom) as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 WHERE T1.id_lieu > 1 ORDER BY T1.trigramme, T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	if ($var[0]>0) {
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "De quel Lieu voulez vous editez le magasin magique ?<br />";
	
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
	}
	else $template_main .= "Aucun Lieu";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>