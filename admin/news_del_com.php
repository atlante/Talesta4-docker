<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_del_com.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.3 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_del_com;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
/*
* Talesta-News par Chub modifi par Ryuden

* Script de news avec gestion commentaires et BBcode
* Compatibilit : PHP4, MySQL3  
*/

// FICHIER news_del_com.php


	if (!isset ($_POST['del'])) {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='SELECT * FROM '.NOM_TABLE_COMMENT_NEWS.' ORDER BY news_date DESC';
		$result = $db->sql_query ($sql);
		$template_main .= "
		<center>
		<table width='750'>
		<tr><td><center><br />Quel commentaire voulez vous supprimer ?</center></td></tr>
		<tr><td colspan='2'>
		<center>
		<form method='post' action='".NOM_SCRIPT."'>
		<input type='hidden' name='del' value='1' />
		<font color='red'><b>Nous vous demandons d'etre tres attentif a ne pas vous tromper de commentaire. Il n'y aura pas d'autre demande de confirmation.</b></font><br />";
		$template_main .= '<select size="5" name="id">';
		while ($data = $db->sql_fetchrow ($result)) {
			$template_main .= '<option value="'.$data['id'].'">'.$data['texte'].'   par   '.$data['auteur'].'</option>';
		}
		$template_main .= '</select><br />';
		$template_main .= "<input type='submit' value='Supprimer' />
		</form>
		</center>
		</td></tr>
		</table>
		</center>";

		//$db->sql_close ();
	} else {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='DELETE FROM '.NOM_TABLE_COMMENT_NEWS.' WHERE id='.$_POST['id'];
		if ($db->sql_query ($sql))
			$template_main .= 'Le commentaire a bien t supprim';
		//$db->sql_close ();
	}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
