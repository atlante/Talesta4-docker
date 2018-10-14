<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: attaquer.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:00 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $attaquer;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";

	$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,1);
	$var= faitSelect("id_cible",$SQL,"",-1,array($PERSO->ID));
	if ($var[0]>0) {
		// arme equipee ou poing
		$SQL =$PERSO->listeObjets(array('ArmeMelee','ArmeJet'), null, 1,0,0, 0);
		$recherche = $db->sql_query($SQL);
		$nbarmes = $db->sql_numrows($recherche);
		if($nbarmes>1)
			$var2= faitSelect("id_arme",$SQL,"",null,array(),array(htmlentities($libelleConfigArmes)));
		else 
		if($nbarmes==1) $var2= faitSelect("id_arme",$SQL,"");	
		else 
		if($nbarmes==0)	{
			// la derniere arme equipee a ete detruite il faut reequiper le poing (cadeau d'Uriel)
			$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 1 WHERE id_objet = 1 AND id_perso = ".$PERSO->ID;
			$db->sql_query($SQL);
		 	$var2= faitSelect("id_arme",$SQL,"");	
		}	
		if ($var2[0]>0) {
			$template_main .= "Qui voulez vous attaquer ?<br />";
			$template_main .= $var[1];		
			$template_main .= "<br /><br />Avec quelle(s) arme(s) ?<br />";
			if (defined("ENGAGEMENT") && ENGAGEMENT==1)
				$template_main .= GetMessage("RappelEngagement")."<br />";
			$template_main .= $var2[1];
			$template_main .= "<br /><br />".BOUTON_ENVOYER;
		}
		else $template_main .= "<br />Vous n'avez aucune arme quipe. <br />";		
	}
	else $template_main .= "Il n'y a personne ici. <br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

	if($etape=="1"){
		$etape=0;
		$ADVERSAIRE = new Joueur($id_cible,true,true,true,true,true,true);	
		if ($id_arme <> $libelleConfigArmes) {			
			$degats = attaquer ( $ADVERSAIRE,$id_arme,$PERSO,false,true,false);	
			if ($degats>=0)
				traceAction("Attaquer", $PERSO, "", $ADVERSAIRE, $degats);
		}	
		else {
			$nbObjets=count($PERSO->Objets);
			for($i=0;$i<$nbObjets;$i++){				
				if ($PERSO->Objets[$i]->equipe && ($PERSO->Objets[$i]->type=='ArmeJet' || $PERSO->Objets[$i]->type=='ArmeMelee')) {
					// on passe bien une string et non la valeur du malus dans le dernier param
					// Cela permet de contrebalancer le malus avec autre chose 
					//et de le faire 1 seule fois dans la fonction_attaquer au lieu d'en mettre partout
					// ne pas permettre de riposte  la premire attaque
					if ( $i==0)									
						$degats =attaquer ( $ADVERSAIRE,$PERSO->Objets[$i]->id_clef,$PERSO,false,false,false, true,"MALUS_ATTAQUE1_ATTAQUESENCHAINEES");	
					else 	
						$degats =$degats+attaquer ( $ADVERSAIRE,$PERSO->Objets[$i]->id_clef,$PERSO,false,true,false, false,"MALUS_ATTAQUE2_ATTAQUESENCHAINEES");	
				}	
				if ($degats>=0)
					traceAction("Attaquer", $PERSO, "", $ADVERSAIRE, $degats, " via un enchainement d'attaques");
			}
			
		}				
		$template_main .= "<br /><p>&nbsp;</p>";
	}

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>