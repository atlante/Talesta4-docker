<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: pbem.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $pbem;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$template_main .= "
<p align='justify'>	<span class='fontSize3'>Un JDR?</span> </p>
<p align='justify'>
Un Jeu de rôles (JDR) est une histoire racontée par un (ou plusieurs) maître(s) de jeu (MJ), 
dans laquelle les joueurs incarnent des personnages (PJ). 
Cela commence par la description du cadre de l'histoire : Le monde, l'époque, la vie quotidienne des PJs ...
Puis surviennent des évènements qui vont faire réagir les PJs. Selon leur réaction et leur manière de les entreprendre, l'histoire évolue de telle ou telle façon.


<span class='fontSize3'> ".NOM_JEU." </span> se rapproche mais n'est donc pas un JDR car les actions proposées sont limitées et les MJs et joueurs ne peuvent pas faire tout ce qu'ils souhaiteraient.
</p>


<p align='justify'>	<span class='fontSize3'>Asynchrone?</span> </p>
Les joueurs se connectant quand cela leur chante, ils ne sont pas tous dans le jeu en même temps. 
Ne vous étonnez donc pas si vous ne recevez pas de réponse à vos tirades ou vos questions dans la minute.
 D'où la nécessité de faire évoluer le temps du jeu différemment du temps réel. L'unité de base du temps du jeu est le tour.
 Un tour dure 3 jours de temps réel.
<p>
A adapter a volonte par chaque Admin.
</p>";

if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>

