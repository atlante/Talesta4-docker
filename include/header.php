<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: header.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.23 $
$Date: 2006/09/05 06:44:59 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP") ) {
	if(!defined("__HTTPGETPOST.PHP")) {
		include('../include/http_get_post.'.$phpExtJeu);
	}	
	
	
	Define("__HEADER.PHP",	0);	
	if(!isset($dejasession)){unset($MJ);unset($PERSO);}
	if(!defined("__DES.PHP")){include('../include/des.'.$phpExtJeu);}
	if(defined("PAGE_EN_JEU")){include('../game/actions.'.$phpExtJeu);}
        include('../include/fctAdminGame.'.$phpExtJeu);
	//chaine de separation entre ID et libelle dans les infos envoyees des combobox dans les form
	$sep='ID_LIB';
	if( (!isset($sessionfoiree)) && (!isset($dejasession)) ){
		//supprime cette condition pour pouvoir afficher le menu game si on clicke sur un menu admin et qu'on est connecte en PJ avec role MJ
		//if(!defined("PAGE_ADMIN")){
			if(!defined("__BEGINSESSION.PHP")){  include('../identification/BeginSession.'.$phpExtJeu);}
			if (isset($PERSO) && $PERSO->roleMJ!="") {
				if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
				if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
			}	
		//} 
		if(defined("PAGE_ADMIN")){
			if(!defined("__BEGINSESSIONMJ.PHP")){ include('../identification/BeginSessionMJ.'.$phpExtJeu);}
		}
	}
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");           
   
    // Chargement du template header.tpl
    $template_head = file_get_contents ('../templates/'.urldecode($template_name).'/header.tpl');
	if (isset($titrepage )) {
		$template_head .= file_get_contents ('../templates/'.urldecode($template_name).'/titre.tpl');	
	}	
		
	if(defined("PAS_DE_QUERY")){
		if($HTTP_SERVER_VARS["QUERY_STRING"] != ""){ 
			$template_head .= "<br /><a href='../game/menu.".$phpExtJeu."'>Retour</a></div>"; 
			erreurFatale();
			//if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
			//die();
		}
	}

	if(defined("PAGE_EN_JEU") && (!isset($PERSO)) ){
		$template_head .= "<div class ='centerSimple'>".GetMessage("IdentificationPJKO")."<br /><a href='../commun/login.".$phpExtJeu."'>".GetMessage("Identification")."</a></div></div>";
		//if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
		//die();
		erreurFatale();
	}
	if(defined("PAGE_ADMIN") && (!isset($MJ)) ){
		$template_head .= "<div class ='centerSimple'>".GetMessage("IdentificationMJKO")."<br /><a href='../commun/login.$phpExtJeu?Admin=1'>".GetMessage("Identification")."</a></div></div>";
		//if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
		//die();
		erreurFatale();
	}
} 

?>