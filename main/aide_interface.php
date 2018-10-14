<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: aide_interface.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $aide_interface;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


$template_main .="
<a name='top'></a>

<p align='center'><span class='c0'><span class='fontSize3'>1. Voici les differentes actions possibles dans le jeu</span></span></p>
<p><a name='actionsPJ'></a>Voici la liste des differentes actions que peut entreprendre votre personnage. Dans le jeu, &agrave; cot&eacute; de chaque action, apparaîtra un chiffre qui vous indiquera son coût en PAs (X signifiant que le joueur choisit le nombre de PAs &agrave; utiliser). Il est &eacute;vident que certaines actions ne sont possibles que dans lieux sp&eacute;cifiques</p>
<ul>
<li><span class='c3'>Situation</span>: Affiche une image et une description du lieu ainsi qu'une liste des autres PJs presents.</li>
<li><span class='c3'>Fiche de Perso</span>: Consulter la fiche des caract&eacute;risques de son personnage</li>
<li><span class='c3'>Modifier Infos</span>: Modifier des informations de son personnage.</li>
<li><span class='c3'>Fichier d'Action</span>: Voir son fichier d'action et eventuellement l'effacer.</li>
<li><span class='c3'>Parler </span>: Envoyer un message dans le fichier d'action d'un ou plusieurs PJs presents dans le m&ecirc;me lieu que vous.</li>
<li><span class='c3'>Se Deplacer </span>: Quitter un lieu pour un autre via un chemin : 
<ul class='listeTypeNone'>			<li>Chemin Entrer. Se deplacer via les chemins de type Lieu Entrer. Efface les objets temporaires.</li>
						<li>Chemin passage. Il sera propos&eacute; d'utiliser une clef, crocheter avec ou sans un passe partout. Il est en outre possible de maintenir la porte ouverte pour un ou plusieurs PJs present dans le meme lieu que vous. Ces derniers recevront une clef temporaire de la porte. Efface les objets temporaires</li>
						<li>Chemin Guilde. Nécessite le bon mot de passe. Efface les objets temporaires.</li>
						<li>Chemin Aller, Chemin Nager, Chemin Escalader. Le cout est variable en fonction de la distance. Vous serez assug&eacute;ti &agrave; un test de competence pour savoir si vous vous faîtes aggresser en route (perte de PVs). Efface les objets temporaires.</li>
						<li>Chemin Péage. Nécessite de payer. Efface les objets temporaires.</li>
</ul>
</li>
<li><span class='c3'>Se Nourrir </span>: Consommer un objet de type Nourriture.</li>
<li><span class='c3'>Lire un Livre </span>: Lire un Livre</li>
<li><span class='c3'>Attaquer </span>: Attaquer un autre PJ(ou pnj).</li>
<li><span class='c3'>Magie </span>: Lancer un sort sur un autre PJ(ou pnj) ou sur soi-meme</li>
<li><span class='c3'>Oublier Sort </span>: Effacer un sort de son grimoire.</li>
<li><span class='c3'>Abandonner Objet</span>: Retirer un objet de son sac.</li>
<li><span class='c3'>Ramasser Objet</span>: Mettre un objet trouvé dans le lieu dans son sac.</li>
<li><span class='c3'>Donner Objet </span>: Donner un objet a un autre PJ present dans le meme lieu que vous</li>
<li><span class='c3'>Donner Argent </span>: Donner des POs a un autre PJ present dans le meme lieu que vous</li>
<li><span class='c3'>Enlever Arme/Armure </span>: Enlever une piece d'armure que l'on porte sur soi ou une arme équipée.</li>
<li><span class='c3'>Mettre Arme/Armure </span>: S'equiper d'une piece d'armure ou d'une arme. On ne peut porter,&agrave; la fois, qu'un type de pieces d'armure donn&eacute;.</li>
<li><span class='c3'>Recharger Arme </span>: Utiliser un objet de type Munition pour recharger un objet de type arme.</li>
<li><span class='c3'>banque </span>: Entrer dans une banque, pour deposer ou retirer de l'argent.</li>
<li><span class='c3'>Armurerie </span>: Entrer dans un magasin de type Armurerie</li>
<li><span class='c3'>Quincaillerie </span>: Entrer dans un magasin de type Quincaillerie</li>
<li><span class='c3'>Magasin Magique </span>: Entrer dans un magasin de type Magasin Magique</li>
<li><span class='c3'>Fouiller Cadavre </span>: Faire les poches d'un PJ mort (PVs n&eacute;gatifs ou nuls) pour essayer de r&eacute;cup&eacute;rer des objets.</li>
<li><span class='c3'>Voler un PJ </span>: Voler des pi&egrave;ces d'or &agrave; un PJ.</li>
<li><span class='c3'>Proposer Action </span>: Proposer une action &agrave; un MJ. Se traduit par l'envoi d'un message dans le FA du MJ</li>
<li><span class='c3'>Fouiller Lieu </span>: Essayer de découvrir des choses (objets, chemins, persos) cachées dans le lieu .</li>
<li><span class='c3'>Se cacher </span>: Essayer de se cacher dans un lieu pour ne plus être visible dans ce lieu. </li>
<li><span class='c3'>Sortir de l'ombre</span>: Redevenir visible aux personnes présentes dans le lieu. </li>
<li><span class='c3'>Apprendre</span>: Suivre un enseignement dans une compétence ou une caractéristique pour essayer d'augmenter cette carac ou compétence. </li>
<li><span class='c3'>Créer un groupe </span>: Fonder un groupe de PJs dans lequel d'autres PJs pourront vous rejoindre pour vous déplacer ou lutter ensemble par exemple.</li>
<li><span class='c3'>Entrer dans un groupe </span>: Rejoindre un groupe de PJs présent dans votre lieu.</li>
<li><span class='c3'>Quitter un groupe </span>: Quitter un groupe de PJs pour reprendre votre indépendance.</li>
<li><span class='c3'>Archiver/désarchiver</span>: Disparaître du jeu le temps d'un congé par exemple. Vous êtes invisible au jeu et inversement. Pour les petits malins qui en useraient, il y a des contrôles...</li>
<li><span class='c3'>Deconnexion</span>: Fermer la session de jeu</li>
</ul><a href='#top'>Retour au d&eacute;but de la page</a>

<p align='center'><span class='c0'><span class='fontSize3'>2. Differentes comp&eacute;tences et comment les augmenter</span></span></p>
<p><a name='comp'></a>Il existe dans le jeu, 6 \"caract&eacute;ristiques\" et 34 \"comp&eacute;tences\". Nous allons les lister ici, avec &agrave; chaque fois, la fa&ccedil;on dont on peut les augmenter et &agrave; quoi elles servent. <span class='c3'>Il est important de se rappeler qu'un Livre peut augmenter n'importe quelle competence. Nous ne le listerons donc pas comme un moyen.</span></p>
<p>&nbsp;</p>
<table class='details'>
<tr><td>competence</td><td>Augmente quand</td><td>Sert pour</td></tr>
<tr><td valign='top'>Force</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Dext&eacute;rit&eacute;</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Constitution</td><td valign='top'><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Habilet&eacute;</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Sagesse</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Intelligence</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Charisme</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>


<tr><td colspan='3' align='center'><hr /></td></tr>
<tr><td colspan='3' align='center'>Une attaque ou un sort, qu'il touche ou non sa cible, fait toujours augmenter les comp&eacute;tences suivantes. De m&ecirc;me, ces comp&eacute;tences servent toujours dans les attaques ou sorts qui sont bas&eacute;s sur ces competences l&agrave;.</td></tr>
<tr><td colspan='3' align='center'>Compet&eacute;nces d'Armes</td></tr>
<tr><td valign='top'></td><td valign='top'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Lame courte</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Lame longue</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Masse légère</td><td valign='top'><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Masse lourde</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Hache courte</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Hache longue</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Arc court</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Arc long</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Petite Fronde</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Grande Fronde</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Artefact Mineur</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Artefact Majeur</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Lance Courte</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Lance Longue</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Petite Arbalète</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Grande Arbalète</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>




<tr><td colspan='3' align='center'>Compet&eacute;nces de Sorts</td></tr>

<tr><td valign='top'></td><td valign='top'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Air</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Terre</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Feu</td><td valign='top'><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Eau</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Lumiere</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Tenebre</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Illusion</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Psychique</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>

<tr><td colspan='3' align='center'>competences Diverses</td></tr>
<tr><td valign='top'></td><td valign='top'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Vol</td><td valign='top'></td><td valign='top'>&nbsp;</td></tr>
<tr><td valign='top'>Crochetage</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Dissimulation</td><td valign='top'><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Vigilance</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Aura</td><td valign='top'>&nbsp;</td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Observation</td><td valign='top'></td><td valign='top'><br /><br /></td></tr>
<tr><td valign='top'>Alphabétisation</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Nage</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>
<tr><td valign='top'>Escalade</td><td valign='top'><br /><br /><br /><br /></td><td valign='top'><br /><br /><br /></td></tr>


<tr><td valign='top'></td><td valign='top'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>

</table>
<a href='#top'>Retour au d&eacute;but de la page</a>";




require_once("../include/extension.inc");
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>