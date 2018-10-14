<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: modifier_mj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.23 $
$Date: 2010/05/15 08:52:12 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $mod_mj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$liste_champs=array(
		"nom","flags","pass","titre","email","wantmail","wantmusic","dispo_pour_ppa"
	);

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["ModifierMJ"])||($MJ->ID== $id_cible)){
		$erreur="";
		if( (!isset($email)) || (!verif_email($email))){
			$erreur .= "Adresse Mail incorrect <br />";
		}
		
		if (defined("IN_FORUM")&& IN_FORUM==1 && (in_array (strtoupper($nom), $forum->nomsReservesForum))) 
			$erreur .= "nom déjà utilisé pour le forum <br />";

		if ($erreur=="") {
			$SQL = "UPDATE ".NOM_TABLE_MJ." SET ";
			$fl = "";
			//$flags n'est pas sette si les champs sont disabled
			if (isset($flags)) {
				for($i=0;$i<count($flags);$i++){
					$fl .= $flags[$i];
				}
				$fl .= '000000000000000000000';
				$flags = $fl;
			}	
			$nbchamps = count($liste_champs);
			for($i=0;$i<$nbchamps;$i++){
				if ((isset($flags) && $liste_champs[$i]=="flags") || $liste_champs[$i]<>"flags") {
					$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."'";
					if($i != ($nbchamps -1) ){$SQL .= ",";}
				}
			}
			$SQL .= " WHERE id_mj = ".$id_cible;
			if ($result=$db->sql_query($SQL)) 
				if(defined("IN_FORUM")&& IN_FORUM==1) {
					$result=$forum->MAJuser($nom, $email,$imageforum,$ancienne_image,$ancien_nom,$pass);			
				}
			if ($result!==false) {
				$MJ->OutPut("MJ ".span(ConvertAsHTML($nom),"mj")." correctement modifi&eacute;",true);
				$etape=0;
			}	
			else {
				$template_main .= $db->erreur;	
				$etape=1;
			}	
			
		}
		else {
			$MJ->OutPut($erreur,true);		
			$etape=1;			
		}		
	}
	else {
		$MJ->OutPut("Vous n'avez pas le droit de modifier ce MJ",true);		
		$etape=0;
	}		

}
if($etape=="1"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )  
		$SQL = $forum->requeteMJ($id_cible);
	else $SQL = "SELECT * FROM ".NOM_TABLE_MJ." WHERE id_mj =".$id_cible;
	$result = $db->sql_query($SQL);

	if ($row = $db->sql_fetchrow($result)) {
      	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )   {
      		$imageforum = $row[$forum->champimage];
      		$imagetype = $row[$forum->champtypeimage];
      		$template_main .= "<input type='hidden' name='ancienne_image' value='".$imageforum ."' />";
      	}	
      	else {
      		$imageforum = "";
      		$imagetype = "";
      		$template_main .= "<input type='hidden' name='ancienne_image' value='".$imageforum ."' />";
      		
      	}	
      	$nbchamps = count($liste_champs);
      	for($i=0;$i<$nbchamps;$i++){
      			$$liste_champs[$i] = $row[$liste_champs[$i]];
      	}
      	include('forms/mj.form.'.$phpExtJeu);
      	$template_main .= "<br />".BOUTON_ENVOYER;
      	$template_main .= "<input type='hidden' name='ancien_nom' value='".$row["nom"] ."' />";
      	$template_main .= "<input type='hidden' name='etape' value='2' />";
      	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
  }
  else 
    $template_main .= "Données non trouvées. Synchronisez le forum avec le jeu";
	$template_main .= "</form></div>";
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Quel MJ voulez vous modifier ?<br />";
	$SQL = "Select T1.id_mj as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1 ";
	if( ! $MJ->aDroit($liste_flags_mj["ModifierMJ"]))
		$SQL .= " WHERE T1.id_mj = $MJ->ID ";		
	else
	if ($MJ->ID<>1)
		$SQL .= " WHERE T1.id_mj > 1 ";	
	$SQL .= "ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>