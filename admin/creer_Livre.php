<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creer_Livre.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.7 $
$Date: 2010/02/28 22:58:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_livres;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$bonusMin=1;
$bonusMax=5;
$coeffPrix = 30;
$coeffDegats = 5;

if(!(isset($etape))){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Ce script sert à créer un jeu de données pour les tests du moteur. <br />";
	$template_main .= "Il va créer des Livres (objets de type Divers, sous-type Livre) permettant au lecteur d'obtenir un bonus pour chaque compétence du jeu.<br />";
	$template_main .= "Le bonus (etat temporaire non limité en durée de niveau du bonus) va de \$bonusMin (qui est à $bonusMin) à \$bonusMax (qui est à $bonusMax), le prix de ces manuels est de niveau donné * \$coeffPrix (actuellement à $coeffPrix), la difficulé d'apprentissage est de niveau accordé * \$coeffDegats (actuellement à $coeffDegats).<br />";
	$template_main .= "\$bonusMin, \$bonusMax, \$coeffPrix et \$coeffDegats peuvent être modifiés pour être adaptés à votre jeu .<br />";
	$template_main .= " <b>Le script creer_etatCarac doit avoir été utilisé avant celui-ci. </b><br />";
	$template_main .= "<input type='submit' value='Création' /><input type='hidden' name='etape' value='1' />";
	$template_main .= "</form>";
	
	$template_main .= "</div>";	
}



else if ($etape=="1") {
         if($MJ->aDroit($liste_flags_mj["CreerObjet"])){
        	$SQL ="Select id_typeetattemp  from ".NOM_TABLE_TYPEETAT."  where nomtype='Divers'";
        	$result = $db->sql_query($SQL);
        	if($db->sql_numrows($result)== 0){
        	   $template_main .= "Aucun type d'etat nommé Divers";	
        	}	
        	else {
        		$chaine2="";
        		$munitions = -1;
        		$durabilite = -1;
        		$degats_min=1;
        		$poids=1;
        		$permanent=0;
        		$competencespe="";
        		$anonyme="";
        		$image="";
        		$id_etattempspecifique="";
        		$caracteristique="Intelligence";
        		$provoqueetatValue="";
        		$composantesValue="";
        		$row = $db->sql_fetchrow($result);
        		$id_typeetattemp=$row["id_typeetattemp"];
        		for($bonus_malus=$bonusMin;$bonus_malus<=$bonusMax;$bonus_malus++){
        			$degats_max=$bonus_malus*$coeffDegats;
        			$prix_base=$bonus_malus*$coeffPrix;
        			foreach($liste_comp_full as $nomCompetence => $numeroCompetence) {
        			        $sqlEtat = "select id_etattemp from ".NOM_TABLE_ETATTEMPNOM." where nom='".$nomCompetence ." Niveau ".$bonus_malus. "'";
                                        $result = $db->sql_query($sqlEtat);
                                	if($db->sql_numrows($result)== 0){
                                	   $template_main .= "Aucun etat nommé '".$nomCompetence ." Niveau ".$bonus_malus. "'";
                                	}	
                                	else {        			        
                				$type="Divers;Livre";
                				$row = $db->sql_fetchrow($result);
                				$chaine=$row['id_etattemp'].";100;-1|";
                				$competence = $nomCompetence;
                				$description="Manuel de l'apprenti de ".$nomCompetence." niveau ".$bonus_malus;
                				$nom="Manuel de l'apprenti de ".$nomCompetence ." niveau ".$bonus_malus;
                				include "./creer_objet.".$phpExtJeu;		
        				}
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