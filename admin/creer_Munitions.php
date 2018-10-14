<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_Munitions.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.3 $
$Date: 2010/02/28 22:58:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_munitions;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$bonusMin=1;
$bonusMax=5;
$coeffPrix = 30;
$coeffDegats = 5;
$munitions = 10;
$durabilite = 50;
$poids=3;
if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert  crer un jeu de donnes pour les tests du moteur. <br />";
	$template_main .= "Il va crer des Munitions (objets de type Munition) (Ex: carquois de fleches pour arc long...) permettant au possesseur de recharger son arme.<br />";
	$template_main .= "Chaque recharge possde \$munitions (actuellement  $munitions). <br />";
	$template_main .= "Chaque ensemble (carquois, chargeur ....) a un \$poids (actuellement  $poids). <br />";
	$template_main .= "\$bonusMin, \$bonusMax, \$coeffPrix et \$coeffDegats peuvent tre modifis pour tre adapts  votre jeu .<br />";
	$template_main .= "<input type='submit' value='Cration' /><input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}



else if ($etape=="1") {
        if($MJ->aDroit($liste_flags_mj["CreerObjet"])){
		$chaine2="";
		$degats_min=$bonusMin;
		$degats_max=$bonusMax;		
		$permanent=0;
		$competencespe="";
		$competence = "Dexterite";
		$anonyme="";
		$image="";
		$id_etattempspecifique="";		
		$provoqueetatValue="";
		$composantesValue="";
		$id_typeetattemp="";
		//$liste_ChocsAbsorbes = array_merge($liste_magie, $liste_competencesArmes);		
		//foreach($liste_ChocsAbsorbes as $nomCompetence => $numeroCompetence) {
			$prix_base=$coeffPrix;
			foreach($liste_type_objs as $type => $valueType) {
				$temp = explode(";",$type);
				if ($temp[0]=="Munition") {
					$chaine="";
					$description= $munitions." Munitions pour ". $temp[1];
					$nom = $munitions. " Munitions pour ". $temp[1];
					$caracteristique=$temp[1];
					include "./creer_objet.".$phpExtJeu;		
					$template_main .= "<br />";
				}
			}
			
		//}
	}
	else $template_main .= GetMessage("droitsinsuffisants");	
}

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>