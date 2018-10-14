<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_add_news.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/04/17 21:24:51 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_add_news;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER news_add_news.php

include ('../news/function.'.$phpExtJeu);

if (isset ($add)) {
	//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
	//mysql_select_db (DB_BASE);
	$texte = addslashes ($texte);
	$sql ="INSERT INTO ".NOM_TABLE_NEWS." (NEWS_DATE,TITRE,AUTEUR,TEXTE) VALUES ('".time ()."', '".$titre."', '".$pseudo."', '".$texte."')";
	if($result = $db->sql_query ($sql,"",END_TRANSACTION_JEU))
		$template_main .= 'Votre news a correctement été ajoutée';
	else $template_main .= $db->erreur;	
} else {
	$template_main .= "<div class ='centerSimple'><table width='750'><tr><td><br />Ajouter une news<br /><br /></td></tr>
	<tr><td colspan='2'><form method='post' name='form' action='".NOM_SCRIPT."'>
	<input type='hidden' name='add' value='1' /><table class='stats'><tr><td colspan='2'>";
	$template_main.=write_bbcode (); 
	$template_main .= "</td></tr><tr><td colspan='2'>";
	$template_main .= read_smiley ('../news/smiley/'); 
	if (defined('IN_FORUM') && (IN_FORUM==1))  $template_main .=read_smiley ($forum->cheminRepertoireSmyley());
	$template_main .= "</td></tr><tr><td>Titre :</td><td><input type='text' name='titre' size='50' maxlength='25' /></td></tr>
	<tr><td>Auteur :</td><td><input type='text' name='pseudo' size='50' maxlength='25' value='" .$MJ->nom."' /></td></tr>
	<tr><td>Texte :</td><td><textarea cols='50' rows='20' name='texte'></textarea></td></tr>
	<tr><td></td><td><input type='submit' value='Ajouter' /></td></tr>
	</table></form></td></tr></table></div>";
}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>