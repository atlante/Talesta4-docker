<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: scripts_SA.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.29 $
$Date: 2010/02/28 22:58:10 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $script_sa;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


if ($MJ->ID =="1") {

	if(defined("IN_FORUM")&& IN_FORUM==1) {
		$template_main .= $forum->synchronyseForumJeu();
	}		
	
	$template_main .= "archivage des joueurs non connectes depuis 30 jours<br />\n";	
	$SQL = "select * from ".NOM_TABLE_PERSO." where lastaction < ".time()."-30*24*60*60 and archive=0";
	$result_archive=$db->sql_query($SQL);
	$mag=0;
	while(	$row_archive = $db->sql_fetchrow($result_archive)){
		$SQL = "INSERT INTO ".NOM_TABLE_ARCHIVE." (id_perso,datearchivage) 
			values (" .$row_archive["id_perso"].",now())";
		if ($result=$db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU)) {
			$SQL = "update ".NOM_TABLE_PERSO." set archive = 1 where id_perso=" .$row_archive["id_perso"];
			$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);		
		}
		if ($result)
			$mag++;
	}

	if ($mag >0)
		$template_main .= "<li>Archivage de $mag PJ non connect&eacute;s depuis 30 jours <br /></li>";	

	$template_main .= "fin de archivage des joueurs non connectes depuis 30 jours<br />";	

	$template_main .= "Suppression des enregistrements non integres<br />";
	$SQL = "SELECT tob.id_clef FROM ".NOM_TABLE_PERSOOBJET." tob LEFT JOIN ".NOM_TABLE_OBJET." o ON tob.id_objet = o.id_objet  where o.id_objet is null  ";
	$result_archive=$db->sql_query($SQL);
	$mag=0;
	while(	$row_archive = $db->sql_fetchrow($result_archive)){
		$SQL = "delete from ".NOM_TABLE_PERSOOBJET." where id_clef = " .$row_archive["id_clef"];
		if ($db->sql_query($SQL,"",END_TRANSACTION_JEU))
			$mag++;
	}

	if ($mag >0)
		$template_main .= "<li>Suppression de $mag inventaires sans objets <br /></li>";	

	$SQL = "SELECT pm.id_clef FROM ".NOM_TABLE_PERSOMAGIE." pm  LEFT JOIN ".NOM_TABLE_MAGIE." m
	 ON pm.id_magie = m.id_magie where m.id_magie is null";
	$result_archive=$db->sql_query($SQL);
	$mag=0;
	while(	$row_archive = $db->sql_fetchrow($result_archive)){
		$SQL = "delete from  ".NOM_TABLE_PERSOMAGIE." where id_clef = " .$row_archive["id_clef"];
		if ($db->sql_query($SQL,"",END_TRANSACTION_JEU))
			$mag++;
	}

	if ($mag >0)
		$template_main .= "<li>Suppression de $mag grimoires sans sorts <br /></li>\n";	


	$SQL = "select p.id_perso from ".NOM_TABLE_PERSO." p left join ".NOM_TABLE_PERSOMAGIE." pm
	on pm.id_magie = p.sortprefere where pm.id_clef is null and p.sortprefere is not null";
	$result_archive=$db->sql_query($SQL);
	$mag=0;
	
	while(	$row_archive = $db->sql_fetchrow($result_archive)){
		$SQL = "update ".NOM_TABLE_PERSO." set sortprefere = null where id_perso = " .$row_archive["id_perso"];
		if($db->sql_query($SQL,"",END_TRANSACTION_JEU))
			$mag++;
	}

	if ($mag >0)
		$template_main .= "<li>Mise &agrave; jour de ". $mag. " PJs avec sort pr&eacute;f&eacute;r&eacute; sans sort <br /></li>";	
	
	$template_main .= "Fin de Suppression des enregistrements non integres<br />";

	
	if(defined("SAUVEGARDE")) {
		$template_main .= "sauvegarde des tables <br />\n";	

		if(! file_exists ( dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"]).SAUVEGARDE))
			if (! mkdir(dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"]).SAUVEGARDE,0744)) {
				logDate ("impossible de crer le rep " .$HTTP_SERVER_VARS["PATH_TRANSLATED"].SAUVEGARDE,E_USER_WARNING,1);
				$erreur=1;
			}	
		if (!isset($erreur)) {
			if (!chmod(dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"]).SAUVEGARDE , 0744))
				logDate ("chmod de " .$HTTP_SERVER_VARS["PATH_TRANSLATED"].SAUVEGARDE. " chou",E_USER_WARNING,1);
			$now=dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"]).SAUVEGARDE;	
			$now.=date("d_m_Y_H_i_s");	
			
		  	if (mkdir ($now, 0744)) {	
		  		if (!chmod($now , 0744))
		  			logDate ("chmod de " .$now. " chou",E_USER_WARNING,1);
		  		if (mkdir ($now."/Tables_export/", 0744)) {
		  		    if (!chmod($now."/Tables_export/" , 0744)) 
		  		    	logDate ("chmod de " .$now. "/Tables_export chou",E_USER_WARNING,1);
		  		    if ($db->sql_export($now."/Tables_export/")===FALSE) {
		  		    	logDate ("backup des tables SQL dans " .$now."/Tables_export/ impossible",E_USER_WARNING,1);
		  		    	verifDroits($now."/Tables_export/");
		  		    }	
				}
				else logDate ("impossible de crer le rep " .$now."/Tables_export/",E_USER_WARNING,1);
		  		if (mkdir ($now."/FAs/", 0744)) {	
		  			if (!chmod($now."/FAs/" , 0744))
		  		    	logDate ("chmod de " .$now. "/FAs",E_USER_WARNING,1);
		  			$hndl=opendir("../fas");
					while ($file=readdir($hndl)) {
						if (!is_dir($file)) 
							if (!copy(dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"])."/../fas/".$file, $now."/FAs/".basename($file))) {
								logDate("copy echouee de " . dirname($HTTP_SERVER_VARS["PATH_TRANSLATED"])."/../fas/".$file ." dest = ".$now."/FAs/".basename($file),E_USER_WARNING,1);	
								verifDroits($now."/FAs/".basename($file));
							}	

					}		
				}
				else logDate ("impossible de crer le rep " .$now."/FAs",E_USER_WARNING,1);
			}	
			else logDate ("impossible de crer le rep " .$now,E_USER_WARNING,1);
			$template_main .= "fin sauvegarde des tables";
		}
	}
			
}
else $template_main .= "Vous n'avez pas les droits suffisants pour executer cette page";
$template_main .= "<br />fin du script SA";
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>		