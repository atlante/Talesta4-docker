<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: function.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:28 $

*/


/*
* Talesta-News par Chub

* Script de news avec gestion de smileys, commentaires et BBcode
* Compatibilité : PHP4, MySQL3  
*/

// FICHIER function.php

$template_main .="<script type='text/javascript'>

function commentaires (news) {
	window.open ('../news/commentaires.".$phpExtJeu."?news=' + news + '', 'Commentaires', 'directories=no,height=400,location=no menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,width=500');
}
";

$template_main .= "
function aff_menu (id) {
	if (document.getElementById(id).style.display =='none') {
		document.getElementById(id).style.display ='';
	} else {	
		document.getElementById(id).style.display ='none';
	}
}

function news_lien () {
	lien = prompt ('Quel est le lien du site ?','http://');
	nom = prompt ('Quel est le nom du site ?','');
	if (lien == null || lien =='http://') {
		alert ('Vous devez saisir un lien');
	} else if (nom == null) {
		alert ('Vous devez saisir un nom pour votre lien');
	} else {
		window.document.form.texte.value += '[URL='+lien+']'+nom+'[/URL]';
	}
}

quote = 0;
function news_quote () {
	if (quote == 0) {
		window.document.form.texte.value += '[QUOTE]';
		window.document.form.quote.value = 'Citation *';
		quote = 1;
	} else {
		window.document.form.texte.value += '[/QUOTE]';
		window.document.form.quote.value = 'Citation';
		quote = 0;
	}
}

b = 0;
function news_b () {
	if (b == 0) {
		window.document.form.texte.value += '[B]';
		window.document.form.b.value = 'B *';
		b = 1;
	} else {
		window.document.form.texte.value += '[/B]';
		window.document.form.b.value = 'B';
		b = 0;
	}
}

i = 0;
function news_i () {
	if (i == 0) {
		window.document.form.texte.value += '[I]';
		window.document.form.i.value = 'I *';
		i = 1;
	} else {
		window.document.form.texte.value += '[/I]';
		window.document.form.i.value = 'I';
		i = 0;
	}
}

u = 0;
function news_u () {
	if (u == 0) {
		window.document.form.texte.value += '[U]';
		window.document.form.u.value = 'U *';
		u = 1;
	} else {
		window.document.form.texte.value += '[/U]';
		window.document.form.u.value = 'U';
		u = 0;
	}
}

size = 0;
function news_size () {
	if (size == 0) {
		text_size = prompt ('Quelle taille voulez vous utiliser ? (format numerique de 1 a 7, ex:5)', '');
		if (text_size == null) {
			alert ('Vous devez entrer une valeur');
		} else {
			window.document.form.texte.value += '[SIZE='+text_size+']';
			window.document.form.size.value = 'Taille *';
			size = 1;
		}
	} else {
		window.document.form.texte.value += '[/SIZE]';
		window.document.form.size.value = 'Taille';
		size = 0;
	}
}

color = 0;
function news_color () {
	if (color == 0) {
		text_color = prompt ('Quelle couleur voulez vous utiliser ? (format html, ex:#336699, ex:red)', '');
		if (text_color == null) {
			alert ('Vous devez entrer une valeur');
		} else {
			window.document.form.texte.value += '[COLOR='+text_color+']';
			window.document.form.color.value = 'Couleur *';
			color = 1;
		}
	} else {
		window.document.form.texte.value += '[/COLOR]';
		window.document.form.color.value = 'Couleur';
	}
}

function news_img () {
	image = prompt ('Quel est le lien de l\'image', 'http://');
	if (image == null || image =='http://') {
		alert ('Vous devez saisir un lien pour votre image');
	} else {
		window.document.form.texte.value += '[IMG]'+image+'[/IMG]';
	}
}

function smiley (code) {
	window.document.form.texte.value += code;
}
</script> 
";


function replace_bbcode ($string) {
	$schema ='`\[URL=(https?://[a-zA-Z0-9.-]+)\](.*?)\[\/URL\]`si';
	$string = preg_replace ($schema, '<a href="$1" target="_blank">$2</a>', $string);
	$schema ='/\[QUOTE\](.*?)\[\/QUOTE\]/si';
	$string = preg_replace ($schema, '<blockquote>Citation :<table class="details" border="1" width="75%" bgcolor="#29071C"><tr><td><br />$1<br /><br /></td></tr></table></blockquote>', $string);
	$schema ='/\[QUOTE=(.+?)\](.*?)\[\/QUOTE\]/si';
	$string = preg_replace ($schema, '<blockquote>Citation ($1):<table border="1" class="details" width="75%" bgcolor="#29071C"><tr><td>$2</td></tr></table></blockquote>', $string);
	$schema ='`\[B\](.*?)\[/B\]`si';
	$string = preg_replace ($schema, '<b>$1</b>', $string);
	$schema ='`\[I\](.*?)\[/I\]`si';
	$string = preg_replace ($schema, '<i>$1</i>', $string);
	$schema ='`\[U\](.*?)\[/U\]`si';
	$string = preg_replace ($schema, '<u>$1</u>', $string);
	$schema ='`\[COLOR=(.+?)\](.*?)\[/COLOR\]`si';
	$string = preg_replace ($schema, '<font color="$1">$2</font>', $string);
	$schema ='`\[SIZE=(.*?)\](.*?)\[/SIZE\]`si';
	$string = preg_replace ($schema, '<font size="$1">$2</font>', $string);
	$schema ='`\[IMG\](.*?)\[/IMG\]`si';
	$string = preg_replace ($schema, '<img src="$1" border="0" />', $string);
	return $string;
}

function write_bbcode () {	
	$template_main = '<input type="button" name="lien" value="Lien" onclick="news_lien();" />&nbsp;';
	$template_main .= '<input type="button" name="color" value="image" onclick="news_img();" />&nbsp;';
	$template_main .= '<input type="button" name="quote" value="Citation" onclick="news_quote();" />&nbsp;';
	$template_main .= '<input type="button" name="b" value="B" onclick="news_b();" />&nbsp;';
	$template_main .= '<input type="button" name="i" value="I" onclick="news_i();" />&nbsp;';
	$template_main .= '<input type="button" name="u" value="U" onclick="news_u();" />&nbsp;';
	$template_main .= '<input type="button" name="size" value="Taille" onclick="news_size();" />&nbsp;';
	$template_main .= '<input type="button" name="color" value="Couleur" onclick="news_color();" />&nbsp;';
	return $template_main;
}

function read_smiley ($rep) {
	$template_main="";
	$dir = opendir ($rep);
	
	while ($fichier = readdir ($dir)) {
		if ($fichier !='.' && $fichier !='..') {
			$ext = explode ('.', $fichier);
			$nbre_ext = count ($ext);
			$ext = $ext[($nbre_ext-1)];
			if ($ext =='gif') {
				$template_main .= '<a href="javascript:smiley(\':'.basename ($fichier, '.gif').':\');"><img src="'.$rep.$fichier.'" border="0" /></a>&nbsp;';
			}
		}
	}
	return $template_main ;
}

function replace_smiley ($string, $rep) {
	$dir = opendir ($rep);
	while ($fichier = readdir ($dir)) {
		if ($fichier !='.' && $fichier !='..') {
			$ext = explode ('.', $fichier);
			$nbre_ext = count ($ext);
			$ext = $ext[($nbre_ext-1)];
			if ($ext =='gif') {
				$string = str_replace (':'.basename($fichier, '.gif').':', '<img src="'.$rep.$fichier.'" />', $string);
			}
		}
	}
	return $string;
}
?>