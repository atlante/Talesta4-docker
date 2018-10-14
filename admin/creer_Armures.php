<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_Armures.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.3 $
$Date: 2010/02/28 22:58:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_armures;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$bonusMin=1;
$bonusMax=5;
$coeffPrix = 30;
$coeffDegats = 5;

if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert à créer un jeu de données pour les tests du moteur. <br />";
	$template_main .= "Il va créer des Armures (objets de type Armure) permettant au possesseur d'obtenir des bonus pour l'absorption de degats pour chaque compétence dans liste_magie ou liste_competencesArmes.<br />";
	$template_main .= "Le bonus  va de \$bonusMin (qui est à $bonusMin) à \$bonusMax (qui est à $bonusMax), le prix de ces armures est de \$coeffPrix (actuellement à $coeffPrix). <br />";
	$template_main .= "\$bonusMin, \$bonusMax, \$coeffPrix et \$coeffDegats peuvent être modifiés pour être adaptés à votre jeu .<br />";
	$template_main .= " Remarques : Comme je n'ai pas voulu entrer dans le detail des type d'armures, toute armure pèse le même poids, encaisse autant de dégats et suporte autant de coups, ce qui est bien entendu ridicule mais pour mes tests, suffisant. <br />";
	$template_main .= "<input type='submit' value='Création' /><input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}



else if ($etape=="1") {
        
        if($MJ->aDroit($liste_flags_mj["CreerObjet"])){
		$chaine2="";
		$munitions = -1;
		$durabilite = 50;
		$poids=5;
		$degats_min=$bonusMin;
		$degats_max=$bonusMax;		
		$permanent=0;
		$competencespe="";
		$anonyme="";
		$image="";
		$id_etattempspecifique="";
		$caracteristique="";
		$provoqueetatValue="";
		$composantesValue="";
		$id_typeetattemp="";
		$liste_ChocsAbsorbes = array_merge($liste_magie, $liste_competencesArmes);
		foreach($liste_ChocsAbsorbes as $nomCompetence => $numeroCompetence) {
			$prix_base=$coeffPrix;
			$competence = $nomCompetence;
			foreach($liste_type_objs as $type => $valueType) {
				$temp = explode(";",$type);
				if ($temp[0]=="Armure") {
					$chaine="";
					$description= $temp[1]." protégeant contre ". $nomCompetence;
					$nom = $temp[1]." protégeant contre ". $nomCompetence;
					include "./creer_objet.".$phpExtJeu;		
					$template_main .= "<br />";
				}
			}
			
		}
	}
	else $template_main .= GetMessage("droitsinsuffisants");	
}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>