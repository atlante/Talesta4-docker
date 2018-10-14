<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: BeginSession.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:16 $

*/

require_once("../include/extension.inc");
if(!defined("__BEGINSESSION.PHP") ) {
	Define("__BEGINSESSION.PHP",	0);

	if(!defined("__SESSIONLEYM.PHP")){include("../identification/SessionLeym.".$phpExtJeu);}
	include("../classe/liste.".$phpExtJeu);
	
	if(isset($HTTP_SESSION_VARS['idsess'])) {	
		$idsess=$HTTP_SESSION_VARS['idsess'];
		$Session = new SessionLeym($idsess);
		if($Session->existe()){
			$id_joueur = $Session->id_joueur;
			if (NOM_SCRIPT!="menu.".$phpExtJeu && NOM_SCRIPT!="logout.".$phpExtJeu)
			        $PERSO = new Joueur($id_joueur,true,true,true,true,true,true,true);
			else    $PERSO = new Joueur($id_joueur,true,true,false,false,false,false,false);
			if (defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==1) {
				if (isset ($id_joueur) )  
					if(!defined("__ENDSESSION.PHP")){include('../identification/EndSession.'.$phpExtJeu);}	
				header("Location: ../Docs/maintenance.htm");
				exit();
			}
			elseif (defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==2) {
				if (isset ($id_joueur) )  
					if(!defined("__ENDSESSION.PHP")){include('../identification/EndSession.'.$phpExtJeu);}	
				header("Location: ../Docs/maintenance.htm");
				exit();
			}
		} else {
			logdate("dans beginsession session n'existe pas");
			if(isset($id_joueur))unset($id_joueur);
		}	
	
	}
}
?>