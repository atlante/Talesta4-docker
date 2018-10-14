<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_etat.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.23 $
$Date: 2010/05/15 08:55:11 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape=="1"|| $etape==5){
	if($MJ->aDroit($liste_flags_mj["CreerEtat"])){
	        if (!isset($id_lieudepart) || $id_lieudepart=="")
	                $id_lieu="null";
	        else $id_lieu=$id_lieudepart;        
	        
                if (isset($chaineObjets)) {
		$tmp2 = explode(";",$chaineObjets);
		if($tmp2[0]) {
			sort($tmp2);
			array_shift($tmp2);//supprime le null du debut
			$chaineObjets = implode(";",$tmp2).";";
		}
		$objets=$chaineObjets;		
	        }
	        else   $objets="";
	        
	        if (isset($chaineSorts)) {     
		$tmp2 = explode(";",$chaineSorts);
		if($tmp2[0]) {
			sort($tmp2);
			array_shift($tmp2);//supprime le null du debut
			$chaineSorts = implode(";",$tmp2).";";
		}
		$sorts=$chaineSorts;		
        	}
        	else   $sorts="";		
	        
		$SQL = "INSERT INTO ".NOM_TABLE_ETATTEMPNOM." (nom,rpa,rpo,rpv,rpi,visible,id_typeetattemp,utilisableinscription, id_lieudepart, objetsfournis, sortsfournis) VALUES ('".ConvertAsHTML($nom_etat)."','".$rpa."','".$rpo."','".$rpv."','".$rpi."','".$Visible."','".$id_typeetattemp."','".$utilisableinscription."',$id_lieu, '$objets', '$sorts')";		
		if ($result = $db->sql_query($SQL)) {
			$result_id= $db->sql_nextid();
			if(isset($comp)){
				$toto = array_keys($comp);
				$tata = array_values($comp);
				$debutSQL = "INSERT INTO ".NOM_TABLE_ETATTEMP." (id_etattemp,id_comp,bonus) VALUES ";
				$nbComp = count($comp);
				for($i=0;$i< $nbComp && ($result!==false);$i++){
					if($tata[$i] != 0){
						$SQL = $debutSQL . "('".$result_id."','".$toto[$i]."','".$tata[$i]."')";
						$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
					}
				}
			}
			if ($result!==false) {
				$MJ->OutPut("Etat ".span(ConvertAsHTML($nom_etat),"etattemp")." correctement cree",true);
				if($etape=="1")
					$etape=0;
				unset($comp);
			}	
			else {
				$MJ->OutPut($db->erreur);
				if($etape=="1")
					$etape="0bis";
			}	
		}
		else {
			$MJ->OutPut($db->erreur);
			if($etape=="1")
				$etape="0bis";
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}

if($etape=="0"){
	$nom_etat="";
	$rpa="0";
	$rpo="0";
	$rpi="0";
	$etape="0bis";
	$Visible="";
	$id_typeetattemp="";
	$utilisableinscription="1";
	$id_lieudepart="";
	$objets="";
	$objetsValue="";
	$sorts="";
	$sortsValue="";
}

if($etape=="0bis"){
        
	$objetsValue="";
	$tmp=explode(";",$objets);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_OBJET." WHERE id_objet = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$rowObj = $db->sql_fetchrow($result);
		$objetsValue.="<option value=\"".$tmp[$i]."\">".$rowObj["nom"]."</option>";	
		$i++;		
	}	

	$sortsValue="";
	$tmp=explode(";",$sorts);
	$i=0;
	while ($tmp[$i]) {	
		$SQL = "SELECT nom FROM ".NOM_TABLE_MAGIE." WHERE id_magie = ".$tmp[$i];
		$result=$db->sql_query($SQL);	
		$rowObj = $db->sql_fetchrow($result);
		$sortsValue.="<option value=\"".$tmp[$i]."\">".$rowObj["nom"]."</option>";	
		$i++;		
	}        
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "nom de l'etat : <input type='text' name='nom_etat' value='".ConvertAsHTML($nom_etat)."' size='25' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' value='".$rpo."' size='4' /> rpa : <input type='text' name='rpa' value='$rpa' size='4' /> rpv : <input type='text' name='rpv' value='0' size='4' /> rpi : <input type='text' name='rpi' value='$rpi' size='4' /> <br />";

	$SQL ="Select T1.id_typeetattemp as idselect, T1.nomtype as labselect from ".NOM_TABLE_TYPEETAT." T1 ORDER BY T1.nomtype";
	$var = faitSelect("id_typeetattemp",$SQL,"",$id_typeetattemp);
	if ($var[0]>0) {		
		$template_main .= "type d'tat ";
		$template_main .= $var[1];	
		$template_main .= "<br />";
	}
	else $template_main .= "Aucun type d'tat temporaire.<br />";	
	$template_main .= "Visible par les tiers :".faitOuiNon("Visible","",$Visible)."<br />";
	
	$template_main .= "Utilisable  l'inscription (PJ) :".faitOuiNon("utilisableinscription","",$utilisableinscription)."<br />";
	$template_main .= "Lieu de dpart du PJ avec cet tat";
	$SQL_lieu = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var= faitSelect("id_lieudepart",$SQL_lieu,"",$id_lieudepart,array(),array("&nbsp;"));
	$template_main .= $var[1];
	
	$template_main .= "<input type='hidden' name='objets' value='".$objets."' />";
	$template_main .= "<input type='hidden' name='sorts' value='".$sorts."' />";
	$template_main .= "<input type='hidden' name='chaineObjets' value='".$objets."' />";
	$template_main .= "<input type='hidden' name='chaineSorts' value='".$sorts."' />";
	include('forms/status.form.'.$phpExtJeu);
	
	

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	include('forms/objetFourniParEtat.form.'.$phpExtJeu);
        include('forms/sortFourniParEtat.form.'.$phpExtJeu);	
	$template_main .= "</div>";
}


if($etape<>5) {
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}	
?>