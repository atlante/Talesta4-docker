<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: supprimer_quete.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/02/28 22:58:11 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $supprimer_Quete;
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);

if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champsQuete=array(
		"nom_quete","type_quete","detail_type_quete","duree_quete","public","proposepar",
		"proposepartype","cyclique","texteproposition","textereussite","texteechec",
		"refuspossible","abandonpossible","validationquete","proposant_anonyme","id_lieu","id_etattempspecifique"
	);

$liste_champsRecompenseQuete=array(
		"type_recompense","recompense"
	);


if(!isset($etape)){$etape=0;}


if($etape=="2"){
	if($MJ->aDroit($liste_flags_mj["SupprimerQuete"])){

			$SQL = "DELETE FROM ".NOM_TABLE_PERSO_QUETE." WHERE id_quete = '".$id_cible."'";
			if ($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)!==false) {
				$SQL = "DELETE FROM ".NOM_TABLE_RECOMPENSE_QUETE." WHERE id_quete = '".$id_cible."'";
				if ($db->sql_query($SQL)!==false) {
					$SQL = "DELETE FROM ".NOM_TABLE_QUETE." WHERE id_quete = '".$id_cible."'";
        			        $valeurs=array();
        			        $valeurs[1]=ConvertAsHTML($nom_quete);			        
					if ($db->sql_query($SQL)!==false) 
                				$MJ->OutPut(GetMessage("queteSupprime",$valeurs),true);
				}	
			}	
	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape="0";
}


if($etape=="1"){
/*	$nbchamps = count($liste_champsQuete);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champsQuete[$i] = '';
	}
	$nbchamps =count($liste_champsRecompenseQuete);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champsRecompenseQuete[$i] = '';
	}*/

	$detail_type_quetePO="";
	$detail_type_queteOBJ="";
	$detail_type_queteLieu="";
	$detail_type_queteSort="";
	$detail_type_quetePJ="";
	$recompenseEtat="";
	$recompenseSort	="";
	$recompenseMontant="";
	$recompenseComp="";
	$recompenseOBJ="";
	$id_proposeMJ="";
	$id_proposePJ="";
	$recompenses="";
	$punitions="";
	$punitionSort	="";
	$punitionMontant="";
	$punitionOBJ="";
	$punitionComp="";
	$punitionEtat="";	
}

if($etape=="1" || $etape=="1bis"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT * from ".NOM_TABLE_QUETE ." where id_quete = ".$id_cible;
	$result = $db->sql_query($SQL);
	$row = $db->sql_fetchrow($result);
	$nbchamps = count($liste_champsQuete);
	for($i=0;$i<$nbchamps;$i++){
			$$liste_champsQuete[$i] = $row[$liste_champsQuete[$i]];
	}	

	switch ($proposepartype) {
		case 1:
		$id_proposeMJ = $proposepar;
		break;
		case 2:
		$id_proposePJ = $proposepar;
		break;
	}	

	switch ($type_quete) {
		case 1:  //lieu
		case 7:  
			$detail_type_queteLieu=$detail_type_quete;
			break;	
		case 2:  //pj
		case 4:
		case 5:
			$detail_type_quetePJ=$detail_type_quete;	
			break;
		case 3:  //objet
			$detail_type_queteOBJ=$detail_type_quete;		
			break;
	}

	include('forms/quete.form.'.$phpExtJeu);

	$template_main .= "<input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir effacer la quete selectionnee ?')\" />";
	
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= '<input type="hidden" name="chaineRecompenses2" value="" /><br />';
	$template_main .= '<input type="hidden" name="chainePunitions2" value="" /><br />';		
//	include('forms/recompenseQuete.form.'.$phpExtJeu);
	$template_main .= "</form>";
	$template_main .= "</div>";
}

if($etape=="0"){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL ="Select T1.id_quete as idselect, nom_quete as labselect from ".NOM_TABLE_QUETE." T1 ORDER BY T1.nom_quete ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	if ($var[0]>0) {
		$template_main .= GetMessage("queteAsupprimer")."<br />";
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else 	$template_main .= GetMessage("PasDeQuete")."<br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}


if($etape>=-1 && $etape <=1){
	if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>