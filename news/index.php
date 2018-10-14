<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/01/31 12:26:28 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
//$template_main .= "<center>";
/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

$template_main .= "<center><hr /><h3>News de ". NOM_JEU."</h3></center><br /><br />";


//Modif Chub
// FICHIER index.php

/* On inclut la configuration MySQL et les fonctions */
include ('function.'.$phpExtJeu);

/* Schema de la page */
$schema_page = file_get_contents ('html/index.html');

/* Recuperation de la config */
$sql ='SELECT * FROM '.NOM_TABLE_CONFIG_NEWS.' ORDER BY id ASC LIMIT 0,1';
$requete = $db->sql_query ($sql);
$data =  $db->sql_fetchrow($requete);
$title = $data['title'];
$nbre_news = $data['nbre_news'];
$nom_archive = $data['nom_archive'];
$nom_proposer = $data['nom_proposer'];
$nom_commentaires = $data['nom_commentaires'];

/* Schema d'une news */
$shema_news = file_get_contents ('html/news.html');

/* Recuperation des news */
$sql ='SELECT * FROM '.NOM_TABLE_NEWS.' ORDER BY news_date DESC LIMIT 0,'.$nbre_news;
$requete = $db->sql_query ($sql);
$news ='';
while ($data = $db->sql_fetchrow($requete)) {
	$resultat = $shema_news;
	$sql2 ='SELECT * FROM '.NOM_TABLE_COMMENT_NEWS.' WHERE news='.$data['id'];
	$requete2 = $db->sql_query ($sql2);
	$nbre_commentaires = $db->sql_numrows ($requete2);
	$lien_commentaires = str_replace ('#', $nbre_commentaires, $nom_commentaires);
	

	$resultat = str_replace ('#DATE#', date ("d-m-Y", $data['news_date']), $resultat);
	$resultat = str_replace ('#TITRE#', $data['titre'], $resultat);
	$resultat = str_replace ('#AUTEUR#', $data['auteur'], $resultat);
	$resultat = str_replace ('#TEXTE#',replace_smiley (replace_bbcode (nl2br (stripslashes ($data['texte']))), '../news/smiley/'), $resultat);
	if (defined("IN_FORUM") && IN_FORUM==1)
		$resultat = replace_smiley ($resultat, $forum->cheminRepertoireSmyley());
	$resultat = str_replace ('#COMMENT#', '<a href="javascript:commentaires(\''.$data['id'].'\')">'.$lien_commentaires.'</a>', $resultat);
	$news .= $resultat;
}

/* Formation de la page */
$page = str_replace ('#TITLE#', $title, $schema_page);
$page = str_replace ('#CONTENT#', $news, $page);
if ($db->sql_numrows ($requete)>=$nbre_news)
	$page = str_replace ('#ARCHIVE#', '<a href="archive.".$phpExtJeu>'.$nom_archive.'</a>', $page);
else $page = str_replace ('#ARCHIVE#', '&nbsp;', $page);	
$page = str_replace ('#PROPOSE#', '<a href="propose.".$phpExtJeu>'.$nom_proposer.'</a>', $page);

/* Affichage de la page */
$template_main .= $page;


if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>