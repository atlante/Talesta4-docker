<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: footer.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/04/11 18:46:15 $

*/

require_once("../include/extension.inc");
if(!defined("__FOOTER.PHP") ) {
	Define("__FOOTER.PHP",	0);
	
	if ((defined("DEBUG_MODE") && DEBUG_MODE>=1)||(defined("SHOW_TIME") && SHOW_TIME==1)) {
		$endtime = gettime();
		$gentime = round(($endtime - $starttime), 4);
		/*
		$db->sql_time n'existe pas dans admin/config quand on sette le forum.
		Je pense qu'il utilise le $db du forum et non le $db du jeu dans cette page... A creuser
		*/
		if (isset($db) && isset($db->sql_time))	{
			$sql_part = round($db->sql_time / $gentime * 100);
			$executed_queries = $db->num_queries;
		}
		else 	{
			$sql_part = 0;
			$executed_queries = 0;
		}
		$php_part = 100 - $sql_part;
	
	        Define("DEBUG_SQL_TIME" , 'Page générée en : '. $gentime .'s (PHP: '. $php_part .'% - SQL: '. $sql_part .'%) - Requêtes SQL : '. $executed_queries  .'');
	}	
	else{Define("DEBUG_SQL_TIME" , '');}

    
    /* TEMPLATE */
    
    // Chargement du template footer.tpl
    $template_foot = file_get_contents ('../templates/'.urldecode($template_name).'/footer.tpl');
    
    /* FIN TEMPLATE */



	// OK IL NE RESTE PLUS QU'A PARSER LES VARIABLES DU TEMPLATES ET A AFFICHER LES DIFFERENTES PARTIES......
	include_once('../include/template.inc');
	parsetemplate($template_head);
	parsetemplate($template_main);
	parsetemplate($template_foot);
	
	// ferme la connection a la base s'il y a 
	if (isset($db))
		$db->sql_close();
	if(defined("DEBUG_HTML")  && DEBUG_HTML==1) {
		$str=ob_get_contents ();
		if(! file_exists ( REP_HTML))
			if (! mkdir(REP_HTML,0700))
				logDate ("impossible de créer le rep " . REP_HTML,E_USER_WARNING,1);
		if (@phpversion() >= '5.0.0') 
			file_put_contents ( REP_HTML. NOM_SCRIPT .date("d_m_Y_H_i_s").".html",$str);
		else {
			if (($file=fopen(REP_HTML. NOM_SCRIPT .date("d_m_Y_H_i_s").".html","wb"))!==false) {
				if (fwrite($file,$str)===false) {
					logDate ("Probleme à l'écriture de '".REP_HTML. NOM_SCRIPT .date("d_m_Y_H_i_s").".html'",E_USER_WARNING,1);
				}
				if (fclose ($file)===false)
					logDate ( "Probleme à la fermeture de '".REP_HTML. NOM_SCRIPT .date("d_m_Y_H_i_s").".html'",E_USER_WARNING,1);
			}
			else logDate ( "impossible d'ouvrir le fichier '".REP_HTML. NOM_SCRIPT .date("d_m_Y_H_i_s").".html'",E_USER_WARNING,1);
		}	
	
		ob_get_flush();
	
	}

} 

?>