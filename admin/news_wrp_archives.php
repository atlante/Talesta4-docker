<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: news_wrp_archives.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:19 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $news_wrp_arch;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER news_wrp_archive.php

	if (isset ($_POST['mod'])) {
		if (($fichier = fopen ('../news/html/archive.html', "w+b"))!==false) {
			if (fwrite ($fichier, $_POST['contenu'])===false) {
				$template_main .= "Probleme à l'écriture de '../news/html/archive.html'";
			}
			if (fclose ($fichier)===false)
				$template_main .= "Probleme à la fermeture de '../news/html/archive.html'";
		}	
		else die ("impossible d'ouvrir le fichier '../news/html/archive.html'");
	} else {
		$template_main .="
		<center>
		<table width='750'>
		<tr><td><center><br />Modiication de archive.html</center></td></tr>
		<tr><td>
		<center><br />
		<form method='post' name='form' action='".NOM_SCRIPT."'>";
		$template_main .="<input type='hidden' name='mod' value='1' />
		<table>
		<tr><td colspan='2'>
		<center>
		<input type='button' value='titre' onclick=\"window.document.form.contenu.value +='#TITLE#';\" />
		<input type='button' value='Index (lien)' onclick=\"window.document.form.contenu.value +='#INDEX#';\" />
		<input type='button' value='Contenu' onclick=\"window.document.form.contenu.value +='#CONTENT#';\" />
		<input type='button' value='Barre de navigation' onclick=\"window.document.form.contenu.value +='#NAVIGTION#';\" />
		</center>
		</td></tr>
		<tr><td>Fichier :</td><td><textarea cols='50' rows='12' name='contenu'>";
		$template_main .= file_get_contents ('../news/html/archive.html'); 		
		$template_main .="</textarea></td></tr><tr><td colspan='2'><center><input type='submit' value='Modifier' /></center></td></tr>
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