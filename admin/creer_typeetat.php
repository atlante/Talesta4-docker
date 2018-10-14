<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_typeetat.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.6 $
$Date: 2006/01/31 12:26:17 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_type_etat;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}

$liste_champs=array(
		"nomtype","critereinscription","modifiableparpj"
	);

if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["CreerEtat"])){
		if ($critereinscription1) {
			if ($choixObligatoire) {
				$critereinscription = 2;
			}
			else $critereinscription = 1;
		}
		else $critereinscription = 0;
		$SQL = "INSERT INTO ".NOM_TABLE_TYPEETAT." (";
		$SQL2="";
		$SQL3="";
		$nbchamps = count($liste_champs);
		for($i=0;$i<$nbchamps;$i++){
			if ($$liste_champs[$i]<>"") {
				if ($SQL2<>"")  {
					$SQL2.=",";
					$SQL3.=",";	
				}
				$SQL2.=$liste_champs[$i];
				$SQL3.="'".ConvertAsHTML($$liste_champs[$i])."'";	
			}	
		}
		$SQL=$SQL . $SQL2 .") VALUES (" . $SQL3.")";
		if ($result = $db->sql_query($SQL))
			$MJ->OutPut("type d'Etat ".span(ConvertAsHTML($nomtype),"etattemp")." correctement cree",true);
		else 	$MJ->OutPut($db->erreur);
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	$etape=0;
}
if($etape===0){
	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = '';
	}
	$critereinscription1	='';
	$choixObligatoire ='';
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
	$SQL = "SELECT * FROM ".NOM_TABLE_TYPEETAT;
	$result2 = $db->sql_query($SQL);
	$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td align='center' colspan='3'>Liste des types d'états temporaires</td></tr>";
	$template_main .= "<tr><td align='center'>Nom du type</td><td>Critère d'inscription</td><td>Modifiable par le pj durant le jeu</td></tr>";
	while($row = $db->sql_fetchrow($result2)) {
		$template_main .= "<tr><td>".span($row["nomtype"],"etattemp")."</td>";
		switch($row["critereinscription"]) {
			case 0:
				$temp="Non";
				break;
			case 1:
				$temp="Oui (Choix facultatif)";
				break;
			case 2:
				$temp="Oui (Choix obligatoire)";
				break;
		}
		$template_main .= "<td>".$temp."</td>";
		if ($row['modifiableparpj']>0) 			
			$template_main .= "<td>Oui</td>";
		else $template_main .= "<td>Non</td>";	
		$template_main .= "</tr>";
	}
	$template_main .= "</table>";	
	include('forms/typeEtatTemp.form.'.$phpExtJeu);

	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>