<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: add_comment.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/04/17 21:25:34 $

*/

require_once("../include/extension.inc");
/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilit : PHP4, MySQL3  
*/

// FICHIER add_comment.php
if(file_exists ("../include/config.".$phpExtJeu)) {
	include_once('../include/config.'.$phpExtJeu);	
}	
if(!defined("__HTTPGETPOST.PHP")) {
	include('../include/http_get_post.'.$phpExtJeu);
}	
//if(!defined("__BDD.PHP")){include('../include/bdd.'.$phpExtJeu);}	

//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
//mysql_select_db (DB_BASE);
$sql ="INSERT INTO ".NOM_TABLE_COMMENT_NEWS." (news, news_date, auteur ,texte) VALUES ('".$news."', '".time ()."', '".$pseudo."','".$texte."')";
//$db->sql_query ($sql, BEGIN_TRANSACTION_JEU);
$db->sql_query ($sql,"",END_TRANSACTION_JEU);
//force le commit puisqu'on ne passe pas par le close
//$db->sql_close ();
header ('Location: commentaires.'.$phpExtJeu.'?news='.$news);
?>