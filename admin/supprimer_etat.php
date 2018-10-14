<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_etat.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.10 $
$Date: 2010/02/28 22:58:10 $

*/
 
require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $suppr_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}

if($etape==2){
	if($MJ->aDroit($liste_flags_mj["SupprimerEtat"])){
		if($id_cible != 1){
			$SQL = "DELETE FROM ".NOM_TABLE_ETATTEMPNOM." WHERE id_etattemp = '".$id_cible."'";
			if ($db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
				$SQL = "DELETE FROM ".NOM_TABLE_ETATTEMP." WHERE id_etattemp = '".$id_cible."'";
				if ($db->sql_query($SQL,"",END_TRANSACTION_JEU))
					$MJ->OutPut("Etat ".span(ConvertAsHTML($nom_etat),"etattemp")." correctement effac",true);
			}
			$MJ->OutPut($db->erreur);
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMP."  T1, ".NOM_TABLE_ETATTEMPNOM." T2 WHERE T1.id_etattemp = T2.id_etattemp AND T1.id_etattemp = ".$id_cible;
	$result = $db->sql_query($SQL);
	$SQL = "SELECT * FROM ".NOM_TABLE_ETATTEMPNOM." T2 WHERE T2.id_etattemp = ".$id_cible;
	$result2 = $db->sql_query($SQL);
	$row2 = $db->sql_fetchrow($result2);
	$objets= $row2['objetsfournis'];
	$sorts= $row2['sortsfournis'];
	$id_lieudepart=$row2['id_lieudepart'];
	$template_main .= "nom de l'etat : <input type='text' readonly='readonly' name='nom_etat' value='".$row2["nom"]."' size='25' /><br />";
	$template_main .= "rpo : <input type='text' name='rpo' readonly='readonly' value='".$row2["rpo"]."' size='4' /> rpa : <input type='text' name='rpa' readonly='readonly' value='".$row2["rpa"]."' size='4' /> rpv : <input type='text' name='rpv' readonly='readonly' value='".$row2["rpv"]."' size='4' />rpi : <input type='text' name='rpi' readonly='readonly' value='".$row2["rpi"]."' size='4' /><br />";
	$SQL ="Select T1.id_typeetattemp as idselect, T1.nomtype as labselect from ".NOM_TABLE_TYPEETAT." T1 ORDER BY T1.nomtype";
	$var = faitSelect("id_typeetattemp",$SQL,"disabled='disabled'",$row2["id_typeetattemp"]);
	if ($var[0]>0) {		
		$template_main .= "type d'tat ";
		$template_main .= $var[1];	
		$template_main .= "<br />";
	}
	else $template_main .= "Aucun type d'tat temporaire.<br />";	
	$template_main .= "Visible par les tiers :".faitOuiNon("Visible","disabled='disabled'",$row2["visible"])."<br />";
	$template_main .= "Utilisable  l'inscription (PJ) :".faitOuiNon("utilisableinscription","disabled='disabled'",$row2["utilisableinscription"])."<br />";
	$template_main .= "Lieu de dpart du PJ avec cet tat";
	$SQL_lieu = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var= faitSelect("id_lieudepart",$SQL_lieu,"disabled='disabled'",$id_lieudepart,array(),array("&nbsp;"));
	$template_main .= $var[1];

	while($row = $db->sql_fetchrow($result)){
			$comp[$row["id_comp"]]=$row["bonus"];
	}
	include('forms/status.form.'.$phpExtJeu);
	
	
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
	include('forms/objetFourniParEtat.form.'.$phpExtJeu);

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
        include('forms/sortFourniParEtat.form.'.$phpExtJeu);
	$template_main .= "<br /><input type='submit' value='effacer' onclick=\"return confirm('Etes vous sur de vouloir effacer l\'tat ".$row2["nom"]." ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "</form></div>";


}
if($etape===0){
	$template_main .= "<center><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quelle etat voulez vous supprimer ?<br />";
	$SQL = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." t2 where T1.id_typeetattemp=t2.id_typeetattemp ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></center>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>