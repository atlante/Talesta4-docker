<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: BeginSessionMJ.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");
if(!defined("__BEGINSESSIONMJ.PHP") ) {
	Define("__BEGINSESSIONMJ.PHP",	0);

	if(!defined("__SESSIONLEYMMJ.PHP")){include("../identification/SessionLeymMJ.".$phpExtJeu);}
	include("../classe/liste.".$phpExtJeu);
	if(isset($HTTP_SESSION_VARS['idsessMJ'])) {
		$idsessMJ=$HTTP_SESSION_VARS['idsessMJ'];
		$Session = new SessionLeymMJ($idsessMJ);
		if($Session->existe()){
			$id_mj = $Session->id_mj;	
			$MJ = new MJ($id_mj);
			if (defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==2) {
				if  (isset ($id_mj) &&  $id_mj!=1)  {
					if(!defined("__ENDSESSIONMJ.PHP")){include('../identification/EndSessionMJ.'.$phpExtJeu);}			
					header("Location: ../Docs/maintenance.htm");
					exit();
				}	
			}			
		} else {
			logdate("dans beginsessionMJ session n'existe pas");
			if(isset($id_mj)){unset($id_mj);}
		}
	}
	else logdate("HTTP_SESSION_VARS['idsessMJ'] non settee");
}
?>