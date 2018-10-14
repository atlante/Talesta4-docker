<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_wrp_coment_one.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_wrp_com;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$template_main .= "<title>".NOM_JEU." Modifier Aspect Commentaire</title>\n";

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilit : PHP4, MySQL3  
*/

// FICHIER news_wrp_comment_one.php

	if (isset ($_POST['mod'])) {
		if (($fichier = fopen ('../news/html/comment_one.html', "w+b"))!==false) {
			if(fwrite ($fichier, $_POST['contenu'])===false) {
				$template_main .= "Probleme  l'criture de '../news/html/comment_one.html'";
			}
			else 
			if (fclose ($fichier)===false)
				$template_main .= "Probleme  la fermeture de '../news/html/comment_one.html'";
		}
		else die ("impossible d'ouvrir le fichier '../news/html/comment_one.html' en ecriture");	
	} else {
		$template_main .="<center>
		<table width='750'>
		<tr><td><center><br />Modification de coment_one.html</center></td></tr>
		<tr><td>
		<center><br />
		<form method='post' name='form'>
		<input type='hidden' name='mod' value='1' />
		<table>
		
		<tr><td colspan='2'>
		<center>
		<input type='button' value='titre' onclick=\"window.document.form.contenu.value +='#TITLE#';\" />
		<input type='button' value='Archives (lien)' onclick=\"window.document.form.contenu.value +='#ARCHIVE#';\" />
		<input type='button' value='Proposition (lien)' onclick=\"window.document.form.contenu.value +='#PROPOSE#';\" />
		<input type='button' value='Contenu' onclick=\"window.document.form.contenu.value +='#CONTENT#';\" />
		</center>
		</td></tr>
		<tr><td>Fichier :</td><td><textarea cols='50' rows='12' name='contenu'>";
		$template_main .= file_get_contents ('../news/html/comment_one.html');
		$template_main .="</textarea></td></tr>
		<tr><td colspan='2'><center><input type='submit' value='Modifier' /></center>
		
		</td></tr>
		
		</table>
		</form>
		</center>
		</td></tr>
		</table>
		</center>";
	}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>