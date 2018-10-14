<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: pbem.php,v $
*/

/**
Brive Description  mettre ici
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
Un Jeu de rles (JDR) est une histoire raconte par un (ou plusieurs) matre(s) de jeu (MJ), 
dans laquelle les joueurs incarnent des personnages (PJ). 
Cela commence par la description du cadre de l'histoire : Le monde, l'poque, la vie quotidienne des PJs ...
Puis surviennent des vnements qui vont faire ragir les PJs. Selon leur raction et leur manire de les entreprendre, l'histoire volue de telle ou telle faon.


<span class='fontSize3'> ".NOM_JEU." </span> se rapproche mais n'est donc pas un JDR car les actions proposes sont limites et les MJs et joueurs ne peuvent pas faire tout ce qu'ils souhaiteraient.
</p>


<p align='justify'>	<span class='fontSize3'>Asynchrone?</span> </p>
Les joueurs se connectant quand cela leur chante, ils ne sont pas tous dans le jeu en mme temps. 
Ne vous tonnez donc pas si vous ne recevez pas de rponse  vos tirades ou vos questions dans la minute.
 D'o la ncessit de faire voluer le temps du jeu diffremment du temps rel. L'unit de base du temps du jeu est le tour.
 Un tour dure 3 jours de temps rel.
<p>
A adapter a volonte par chaque Admin.
</p>";

if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>

