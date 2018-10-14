<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: EndSessionMJ.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.14 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");
if(!defined("__ENDSESSIONMJ.PHP") ) {
	Define("__ENDSESSIONMJ.PHP",	0);
	if(!defined("__SESSIONLEYMMJ.PHP")){include("../identification/SessionLeymMJ.".$phpExtJeu);}

	if(isset($HTTP_SESSION_VARS['idsessMJ'])) {	
		$idsessMJ=$HTTP_SESSION_VARS['idsessMJ'];	

	//if(isset($HTTP_COOKIE_VARS[NOM_COOKIE_MJ])){
		//$Session = new SessionLeymMJ($_COOKIE[NOM_COOKIE_MJ]);
		$Session = new SessionLeymMJ($idsessMJ);
		if($Session->existe()){
			$Session->detruire();	
		if (isset($HTTP_SESSION_VARS))
			unset($HTTP_SESSION_VARS['idsessMJ']);
		
		if (isset($_SESSION))
			unset($_SESSION['idsessMJ']);
		logdate("dans endsessionMJ, destruction de session");	
		unset($idsessMJ);	
		session_destroy();					
		}
	}

	if (basename($HTTP_SERVER_VARS['PHP_SELF'])=="logout.".$phpExtJeu )  {
		 //$template_main .= "Session correctement detruite";
		 $template_main .= file_get_contents ('../templates/'.urldecode($template_name).'/logout.tpl');
	}	 
}
?>