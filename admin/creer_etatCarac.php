<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_etatCarac.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.6 $
$Date: 2010/02/28 22:58:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_etats;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$bonusMin=-5;
$bonusMax=5;


if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert à créer un jeu de données pour les tests du moteur. <br />";
	$template_main .= "Il va créer des Etats temporaires donnant un bonus pour chaque compétence du jeu.<br />";
	$template_main .= "Le bonus va de \$bonusMin (qui est à $bonusMin) à \$bonusMax (qui est à $bonusMax).<br />";
	$template_main .= "\$bonusMin, \$bonusMax peuvent être modifiés pour être adaptés à votre jeu .<br />";
		
	$template_main .= "<input type='submit' value='Création' /><input type='hidden' name='etape' value='5' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}



else if ($etape==5) {
        if($MJ->aDroit($liste_flags_mj["CreerEtat"])){	
	$SQL ="Select id_typeetattemp  from ".NOM_TABLE_TYPEETAT."  where nomtype='Divers'";
	$result = $db->sql_query($SQL);
	if($db->sql_numrows($result)== 0){
	   $template_main .= "Aucun type d'etat nommé Divers";	
	}	
	else {
		
		$rpa=0;
		$rpo=0;
		$rpv=0;
		$rpi=0;
		$Visible=0;
		$utilisableinscription=0;
		$row = $db->sql_fetchrow($result);
		$id_typeetattemp=$row["id_typeetattemp"];
        		$id_lieudepart="";
        		$chaineObjets="";
        		$chaineSorts="";
		for($bonus_malus=-5;$bonus_malus<=5;$bonus_malus++){
			if ($bonus_malus<>0) {
				foreach($liste_comp_full as $nomCompetence => $numeroCompetence) {
					$nom_etat=$nomCompetence ." Niveau ".$bonus_malus;
					$comp[$numeroCompetence] = $bonus_malus;
					include "./creer_etat.".$phpExtJeu;		
					$template_main .= "<br />";
				}
			}
		}
	}
        }
        else $template_main .= GetMessage("droitsinsuffisants");
}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>