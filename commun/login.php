<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: login.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/01/24 16:36:02 $

*/

require_once("../include/extension.inc");
//require_once("../include/config.".$phpExtJeu);

if(!defined("__HTTPGETPOST.PHP")) {include('../include/http_get_post.'.$phpExtJeu);}
function autoconnectForum ($nomconnecte) {
	global $forum;
	$forum->autoconnect ($nomconnecte);
}


logDate("Admin".teste("Admin","1"). "isset(MJ)". isset($MJ) . "isset(PERSO)" . isset($PERSO) ." isset(x1)" . isset($x1) ." isset(x0)" . isset($x0));
if ( (teste("Admin","1") && (!isset($MJ))) || ( (! teste("Admin","1")) && (!isset($PERSO)))) {	
	if( (isset($x0)) && (isset($x1)) ){	
		if(!defined("__IDENTIFICATION.PHP")){include("identification.".$phpExtJeu);}		
		if (teste("Admin","1"))
			if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
		$dejasession = 4;
		define("NO_UNSET_SESSION",0);		
		if(isset($sessionfoiree)){ // On s'est gouré dans les params pour se connecter
				if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
				$template_main .= GetMessage("loginrefuse");

		}
	} else { //ON a pas passer les param d'ident, on les demande donc via une form
		if (teste("Admin","1"))
			if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
		else if (defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==1) {
				header("Location: ../Docs/maintenance.htm");
				exit();
		}

		if(defined("IN_FORUM")&& IN_FORUM==1) {
			$sessionID=$forum->CreeCookie ();
			if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
			$forum->CreeSession ($sessionID);
		}
		else if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
		if(!defined("__SAISIEPASS.PHP")){
		        //unset $MJ et $PERSO pour eviter d'afficher le form d'identification
		        // + bienvenue XXXX par ici.
		        unset($MJ);
		        unset($PERSO);
		        include('../identification/saisiepass.'.$phpExtJeu);
		}
		
	}
}


if (teste("Admin","1")) {
	if(isset($MJ)) {
		//$template_main .= "Bienvenu ".span($MJ->nom,"mj")."<br /><a href='../admin/menu.'.$phpExtJeu>Hop par ici, m'sieur MJ</a>";
		if(defined("IN_FORUM")&& IN_FORUM==1) 
			autoconnectForum($MJ->nom);
		header ("Location: ../admin/menu.".$phpExtJeu);
		exit;
		}
}		
else 
	if(isset($PERSO)) {
		if(defined("IN_FORUM")&& IN_FORUM==1 && 
			(($PERSO->pnj==1 && defined("CREE_MEMBRE_PNJ") &&  CREE_MEMBRE_PNJ==1) ||  $PERSO->pnj==0))  //on ne se connecte pas au forum si monstre ou bestiaire
			autoconnectForum($PERSO->nom);
		if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
		$template_main .= GetMessage("bienvenue")." ".span($PERSO->nom,"pj")."<br />".GetMessage("heureServeur");
		//remplace par celui de miseenpage
		//setlocale(LC_TIME, "fr");
		$template_main .= strftime ("  %d %B %Y %Hh%Mmin%Ss")."<br />";
		if (!$PERSO->Archive)	 {
        		if (!$PERSO->RIP())	 {
        			$template_main .= GetMessage("prochaineRemisePA").": " . timestampTostring( $PERSO->Derniere_RemisePA + ($PERSO->interval_remisepa * 3600));
        			$template_main .= "<br />".GetMessage("prochaineRemisePI").": " . timestampTostring( $PERSO->Derniere_RemisePI + ($PERSO->interval_remisepi * 3600))."<br />";
        			
        		}
        		else {
        			//on ne ressucite pas les monstres ou le bestiaire
        			if ($PERSO->pnj<=1)
        		   $PERSO->resurrection();     
        		}        
                }
		$template_main .= "<br /><a href='../game/menu.".$phpExtJeu."'>".GetMessage("hopIci")."</a>";		
	}
		
		

if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>