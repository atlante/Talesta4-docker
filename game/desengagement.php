<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: desengagement.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2010/01/24 17:44:01 $

*/
require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $desengagement;

if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}

if(defined("ENGAGEMENT") && ENGAGEMENT==1) {
	
	if(!isset($etape)|| $etape==0){
		if($PERSO->Engagement == 1) {
		
			$SQL = "Select T1.id_adversaire as idselect, T1.nom as labselect from ".NOM_TABLE_ENGAGEMENT." T1 WHERE T1.id_perso = '".$PERSO->ID."' and propdes=0";
			//$req = $requete=$db->sql_query($SQL);
			$var= faitSelect("id_cible",$SQL);
			if ($var[0]>0) {
				$template_main .= "<center><form action='".NOM_SCRIPT."' method='post'>";
				$template_main .= "<br /><br />Qui voulez vous desengager ?<br />";
				$template_main .= $var[1];
				$template_main .= "<br /><br />";
				$template_main .= "Comment ?<br />";
				$template_main .= "<br /><input type='radio' name='typeact' value='force' />Par la force<br />";
				$template_main .= "<br /><input type='radio' name='typeact' value='dexte' />Par la souplesse<br />";
				$template_main .= "<br /><input type='radio' name='typeact' value='ruse' />Par la ruse<br />";	
				$template_main .= "<br /><input type='radio' name='typeact' value='propdes' />A l'amiable<br />";

				$template_main .= "<br /><input type='radio' name='typeact' value='mort' />L'adversaire est mort ou assom<br />";
				$template_main .= "<br />".BOUTON_ENVOYER;
				$template_main .= "<input type='hidden' name='etape' value='1' />";
				$template_main .= "</form></center>";
			}
			else $template_main .= GetMessage("desengagementEnAttente");
			$etape=0;
		}
		else{ 
			$template_main .= "<center><br /><br />";
			$template_main .= GetMessage("pasengag");
			$template_main .= "</center>";
		}	
	} 

	if (isset($etape) && $etape=="1"){
	        $ADVERSAIRE = new Joueur($id_cible,true,true,true,true,true,true);
		$succes=Desengagement($PERSO,$ADVERSAIRE,$typeact, false);
		if ($succes)
			traceAction("Desengager", $PERSO, "", $ADVERSAIRE, $typeact);
		
	}

}
else {
	$template_main .= GetMessage("gestionEngagementInterdit")."<br /><br />";
}

if(!defined("__MENU_JEU.PHP")){@include('../game/menu_jeu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}


// passez ensuite au fichier 5.messages.php
?>