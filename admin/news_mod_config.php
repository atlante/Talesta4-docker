<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_mod_config.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_mod_conf;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER news_mod_config.php



	if (isset ($_POST['mod'])) {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='UPDATE '.NOM_TABLE_CONFIG_NEWS.' SET title="'.$_POST['title'].'", nbre_news="'.$_POST['nbre_news'].'", nom_archive="'.$_POST['nom_archive'].'", nom_proposer="'.$_POST['nom_proposer'].'", nom_commentaires="'.$_POST['nom_commentaires'].'", nom_index="'.$_POST['nom_index'].'" WHERE id="'.$_POST['id'].'"';
		$template_main .= $sql;
		if ($db->sql_query ($sql))
			$template_main .= 'Votre configuration a bien été modifiée';
		else $template_main .= $db->erreur;	
	} else {
		//mysql_connect (DB_HOST, DB_LOGIN, DB_PASS);
		//mysql_select_db (DB_BASE);
		$sql ='SELECT * FROM '.NOM_TABLE_CONFIG_NEWS.' ORDER BY id ASC LIMIT 0,1';
		$result = $db->sql_query ($sql);
		$data = $db->sql_fetchrow ($result);
		?>
		<html>
		<body>
		<center>
		<table width="750">
		<tr><td><center><br />Modification de la 			configuration</center><br /></td></tr>
		<tr><td colspan="2">
		<form method="post">
		<input type="hidden" name="mod" value="1">
		<input type="hidden" name="id" value="<?php $template_main .= $data['id'] ?>">
		<table>
		<tr><td>Titre des pages :</td><td><input type="text" name="title" size="25" maxlength="30" value="		<?php
 $template_main .= $data['title'] ?>"></td></tr>
		<tr><td>Nombre de news par pages :</td><td><input type="text" name="nbre_news" size="2" maxlength="2" value="<?php
 $template_main .= $data['nbre_news'] ?>"></td></tr>
		<tr><td>Nom du lien vers les archives :</td><td><input type="text" name="nom_archive" size="25" maxlength="25" value="<?php
 $template_main .= $data['nom_archive'] ?>"></td></tr>
		<tr><td>Nom du lien vers la proposition de news :</td><td><input type="text" name="nom_proposer" size="25" maxlength="25" value="<?php
 $template_main .= $data['nom_proposer'] ?>"></td></tr>
		<tr><td>Nom du lien pour les commentaires (# pour le nombre) :</td><td><input type="text" name="nom_commentaires" size="25" maxlength="25" value="<?php
 $template_main .= $data['nom_commentaires'] ?>"></td></tr>
		<tr><td>Nom des liens vers l'index :</td><td><input type="text" name="nom_index" size="25" maxlength="25" value="<?php
 $template_main .= $data['nom_index'] ?>"></td></tr>
		<tr><td></td><td><input type="submit" value="Modifier"></td></tr>
		</table>
		</form>
		</td></tr>
		</table>
		</center>
<?php
	}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>