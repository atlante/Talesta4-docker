<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: commentaires.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:28 $

*/

require_once("../include/extension.inc");
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
//$template_main .= "<title>".NOM_JEU." Page de News - Commentaires</title>";
/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER commentaires.php

/* Si on a pas d'id de news, on quite le script */
if (!isset ($news)) {
	$template_main .= 'Vous devez selectionner une news</div>';
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
	exit ();
}

/* On inclut la configuration MySQL et les fonctions */

/* On recupere le schema de la page commentaires */
$sh_page = file_get_contents ('html/comment.html');

/* On recupere le schema de un commentaire */
$sh_comment = file_get_contents ('html/comment_one.html');

/* On recupere les commentaires depuis la bdd */
//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
//mysql_select_db (DB_BASE);
$sql ='SELECT * FROM '.NOM_TABLE_COMMENT_NEWS.' WHERE news='.$news.' ORDER BY news_date DESC';
$requete = $db->sql_query ($sql);
$total_comment ='';
while ($data =  $db->sql_fetchrow($requete)) {
	$comment = $sh_comment;
	$comment = str_replace ('#AUTEUR#', $data['auteur'], $comment);
	$comment = str_replace ('#DATE#', date("d-m-Y \à H\hi", $data['news_date']), $comment);
	$comment = str_replace ('#TEXTE#', nl2br ($data['texte']), $comment);
	$total_comment .= $comment;
}
//$db->sql_close ();

/* On edit le schema de la page */
$page = $sh_page;
if (isset($PERSO))
	$page = str_replace ('AUTEUR', $PERSO->nom, $page);
else	
if (isset($MJ))
	$page = str_replace ('AUTEUR', $MJ->nom, $page);
else	$page = str_replace ('AUTEUR', 'Pseudo', $page);
$page = str_replace ('#CONTENT#', $total_comment, $page);
$page = str_replace ('#NEWS#', $news, $page);
$page = str_replace ('$phpExtJeu', $phpExtJeu, $page);

/* On affiche le resultat */
$template_main .= $page;
$template_main .= "</div>";
//if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>