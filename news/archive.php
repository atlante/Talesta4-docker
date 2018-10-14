<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: archive.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/01/31 12:26:28 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
//$template_main .= "<title>".NOM_JEU." Page des archives News</title>";

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilit : PHP4, MySQL3  
*/

// FICHIER archive.php

include ('function.'.$phpExtJeu);
$template_main .= "<center><h3><hr>News archives de ". NOM_JEU."</hr></h3></center><br /><br />";

/* On recupere la config dapuis la base */
//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
//mysql_select_db (DB_BASE);
$sql ='SELECT * FROM '.NOM_TABLE_CONFIG_NEWS.' ORDER BY id ASC LIMIT 0,1';
$query = $db->sql_query ($sql);
$data = $db->sql_fetchrow($query);
$title = $data['title'];
$nbre_news = $data['nbre_news'];
$nom_archive = $data['nom_archive'];
$nom_proposer = $data['nom_proposer'];
$nom_commentaires = $data['nom_commentaires'];
$nom_index = $data['nom_index'];

/* Comptage du nombre total de news */
$sql ='SELECT * FROM '.NOM_TABLE_NEWS;
$query = $db->sql_query ($sql);
$total_news = $db->sql_numrows ($query);
$nbre_page = ceil ($total_news / $nbre_news) -1;

/* Schema d'une news */
$shema_news = file_get_contents ('./html/news.html');

/* Schema de la page */
$shema_page = file_get_contents ('./html/archive.html');

/* Si une page est definie dans l'url, on commence a partir de cele la, sinon, on commence de la page 0 */
if (isset ($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 0;
}
$debut = ($page+1) * $nbre_news;
$sql2 ='SELECT * FROM '.NOM_TABLE_NEWS.' ORDER BY news_date DESC limit '.$debut.', '.$nbre_news;
$query = $db->sql_query ($sql2);
$news ='';
while ($data =  $db->sql_fetchrow($query)) {
	$resultat = $shema_news;
	$sql2 ='SELECT * FROM '.NOM_TABLE_COMMENT_NEWS.' WHERE news='.$data['id'];
	$query2 = $db->sql_query ($sql2);
	$nbre_commentaires = $db->sql_numrows ($query2);
	$lien_commentaires = str_replace ('#', $nbre_commentaires, $nom_commentaires);
	$resultat = str_replace ('#DATE#', date ("d-m-Y", $data['news_date']), $resultat);
	$resultat = str_replace ('#TITRE#', $data['titre'], $resultat);
	$resultat = str_replace ('#AUTEUR#', $data['auteur'], $resultat);
//	$resultat = str_replace ('#TEXTE#', nl2br ($data['texte']), $resultat);
	$resultat = str_replace ('#TEXTE#',replace_smiley (replace_bbcode (nl2br (stripslashes ($data['texte']))), '../news/smiley/'), $resultat);
	if (defined("IN_FORUM") && IN_FORUM==1)
		$resultat = replace_smiley ($resultat, $forum->cheminRepertoireSmyley());
	
	$resultat = str_replace ('#COMMENT#', '<a href="javascript:commentaires(\''.$data['id'].'\')">'.$lien_commentaires.'</a>', $resultat);
	$news .= $resultat;
}
//$db->sql_close ();

/* On genere la bare de navigation */
$i = 0;
$navigation ='| ';
while ($i != $nbre_page) {
	$navigation .='<a href="archive.$phpExtJeu?page='.$i.'">'.($i+1).'</a> | ';
	$i++;
}

/* Formation de la page */
$page = str_replace ('#TITLE#', $title, $shema_page);
$page = str_replace ('#CONTENT#', $news, $page);
$page = str_replace ('#NAVIGATION#', $navigation, $page);
$page = str_replace ('#INDEX#', '<a href="index.".$phpExtJeu>'.$nom_index.'</a>', $page);

/* On affiche le resultat */
$template_main .= $page;
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>