<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: EndSession.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/02/04 09:20:29 $

*/

require_once("../include/extension.inc");
if(!defined("__ENDSESSION.PHP") ) {
	Define("__ENDSESSION.PHP",	0);

	if(!defined("__SESSIONLEYM.PHP")){include("../identification/SessionLeym.".$phpExtJeu);}

	if(isset($HTTP_SESSION_VARS['idsess'])) {	
		$idsess=$HTTP_SESSION_VARS['idsess'];	

	//if(isset($HTTP_COOKIE_VARS[NOM_COOKIE])){
	//	$Session = new SessionLeym($_COOKIE[NOM_COOKIE]);
		$Session = new SessionLeym(	$idsess);
		if($Session->existe())
			$Session->detruire();			
		unset($idsess);
		logdate("dans endsession, destruction de session");
		if (isset($HTTP_SESSION_VARS))
			unset($HTTP_SESSION_VARS['idsess']);
			//$HTTP_SESSION_VARS=array();
		
		if (isset($_SESSION))
			unset($_SESSION['idsess']);
			//$_SESSION=array();
		if (isset($PERSO) && $PERSO->roleMJ!="")
			require_once("../identification/EndSessionMJ.".$phpExtJeu);
		else
			session_destroy();
	}	

	if (basename($HTTP_SERVER_VARS['PHP_SELF'])=="logout.".$phpExtJeu ) {
		if (! isset($PERSO) || $PERSO->roleMJ=="")
			$template_main .= file_get_contents ('../templates/'.urldecode($template_name).'/logout.tpl');
	} 
	
}
?>