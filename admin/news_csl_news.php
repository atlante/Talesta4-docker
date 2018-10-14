<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_csl_news.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_csl_news;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilit : PHP4, MySQL3  
*/

// FICHIER news_csl_news.php


include ('../news/function.'.$phpExtJeu);

	$sql ='SELECT * FROM '.NOM_TABLE_NEWS.' ORDER BY news_date ASC';
	$result = $db->sql_query ($sql);
	$template_main .="<center><br />Consultation des news<br /><br />";
	while ($data = $db->sql_fetchrow ($result)) {		
		$template_main .="<center><table class='details' border='1' width='750'><tr><td width='100%'><font size='2'><center><b>";
		$template_main .= $data['titre'];
		$template_main .="</b></center></font></td></tr>";
		$template_main .="<tr><td>";
		$temp = replace_smiley (replace_bbcode (nl2br (stripslashes ($data['texte']))), '../news/smiley/');
		  if (IN_FORUM)
		  	$temp = replace_smiley ($temp, $forum->cheminRepertoireSmyley());
		  $template_main .= $temp."<br /><br />";
		$template_main .="<div align='right'><font size='1'><i>Par ";
		$template_main = $template_main . $data['auteur'].", le ";
		$template_main .= date ('d-m-Y \a H\hi', $data['news_date']);
		$template_main .="</i></font></div></td></tr></table></center><br /><br />";
	}
	$template_main .="</center>";

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>