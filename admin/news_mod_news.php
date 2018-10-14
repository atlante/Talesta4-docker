<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_mod_news.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_mod_news;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER news_mod_news.php

include ('../news/function.'.$phpExtJeu);

	if (!isset ($_POST['mod'])) {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='SELECT * FROM '.NOM_TABLE_NEWS.' ORDER BY news_date DESC';
		$result = $db->sql_query ($sql);
		$template_main .= "
		<div class ='centerSimple'>
		<table width='750'>
		<tr><td><br />Quelle news voulez vous modifier ?</td></tr>
		<tr><td colspan='2'>
		<form method='post' action='".NOM_SCRIPT."'>
		<input type='hidden' name='mod' value='1' />
		<br />";
		$template_main .= '<select size="5" name="id">';
		while ($data = $db->sql_fetchrow ($result)) {
			$template_main .= '<option value="'.$data['id'].'">'.$data['titre'].' par '.$data['auteur'].'</option>';
		}
		$template_main .= '</select><br />';
		$template_main .= "
		<br />
		<input type='submit' value='Modifier' />
		</form>
		</td></tr>
		</table>
		</div>";
		//$db->sql_close ();
	} else if (isset ($_POST['mod2'])) {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$texte = addslashes ($_POST['texte']);
		$sql ="UPDATE ".NOM_TABLE_NEWS." SET titre='".$_POST['titre']."', texte='".$texte."' WHERE id=".$_POST['id'];
		if ($db->sql_query ($sql))
			$template_main .= "Votre news '".$_POST['titre']."' a bien été modifiée";
		else $template_main .= $db->erreur;	
		//$db->sql_close ();
	} else {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='SELECT * FROM '.NOM_TABLE_NEWS.' WHERE id='.$_POST['id'];
		$result = $db->sql_query ($sql);
		$data = $db->sql_fetchrow ($result);
		$template_main .= "
		<div class ='centerSimple'>
		<table width='750'>
		<tr><td>Modification de news</td></tr>
		<tr><td colspan='2'>
		<form method='post' name='form' action='".NOM_SCRIPT."'>		
		<input type='hidden' name='mod' value= '1' />
		<input type='hidden' name='mod2' value='1' />
		<input type='hidden' name='id' value='". $_POST['id']."' />
		<table>
		<tr><td colspan='2'>"; 
		$template_main.=write_bbcode (); 
		$template_main .= "</td></tr><tr><td colspan='2'>"; 
		$template_main .=read_smiley ('../news/smiley/'); 
		if (defined('IN_FORUM') && (IN_FORUM==1)) 
			 $template_main .= read_smiley ($forum->cheminRepertoireSmyley()); 
		$template_main .= "</td></tr><tr><td>Titre :</td><td><input type='text' name='titre' value='";		
		$template_main .= $data['titre'];
		$template_main .="' size='50' maxlength='25' /></td></tr>";
		$template_main .=  "<tr><td>Texte :</td><td><textarea name='texte' cols='50' rows='20'>";
		$template_main .= stripslashes ($data['texte']);		
		$template_main .=  "</textarea></td></tr><tr><td></td><td><input type='submit' value='Modifier' /></td></tr>
		</table>
		</form>
		</td></tr>
		</table>
		</div>";
	}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>