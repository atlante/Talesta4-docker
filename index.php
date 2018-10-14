<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Bri�ve Description � mettre ici
.\file
$Revision: 1.10 $
$Date: 2006/01/31 15:04:19 $

*/


/*! \mainpage Documentation Technique de Talesta 4+
 
  \section intro_sec Introduction 
 
  \section histo_sec Historique
 
  \subsection step1 Premi�re version de cette doc technique correspondant � la V3_5_000 du moteur
  \subsubsection step1_1 Remerciements aux Contributeurs
  Merci � C�dric G�rard qui m'a permis d'int�grer la g�n�ration de cette documentation en plus de la livraison
  de compl�te ou en delta du moteur.
 
  Merci � KyuJaq de m'avoir fait d�couvrir l'outil doxygen qui permet de sortir de la doc sous diff�rents formats
  � partir des commentaires se trouvant dans le source. 
 
  Malheureusement, il n'y en a pas beaucoup, ce qui fait que cette doc n'est pas une encyclop�die... Mais, comme elle n'a
  pour but de remplacer ni le forum, ni le wiki, ni le r�pertoire docs, il faut plutot y voir un outil en plus.
 
   \subsubsection step1_2 Ce qui est fait dans cette version
   	\li \c 1. Premier inventaire des fonctionnalit�s de doxygen.
   	\li \c 2. Tentative de rendre la doc au style de talesta.....
 
  \subsubsection step1_3 Ce qui est � faire (Avis aux amateurs)
   	 \li \c 1. D�terminer ce qui est utile pour supprimer ce qui est superflu dans la doc
 	 \li \c 2. Trouver un/des moyens de supprimer ce qui est superflu
   	 \li \c 3. Am�liorer le rendu (je suis vraiment trop nul pour les couleurs...)
 	 \li \c 4. et bien sur  <b>AJOUTER DES COMMENTAIRES DANS LE CODE...</b> Sur ce dernier point, la documentation etant faite fonction par fonction (ou membre de classe par membre de classe, tout d�veloppeur ayant un peu de temps peut apporter sa pierre � l'�difice...)
 	 \li \c 5. point de derni�re minute: Ajouter de commentaires pour chaque table aussi. Ex: ALTER TABLE xxxxx COMMENT = '' foncionne depuis Mysql3.23, assez ancien pour qu'on puisse sans servir sans probl�me de compatibilit�s et cela appara�t bien nettement dans phpmyadmin.
 	 
 
  
 */



require_once("./include/extension.inc");
// Test de pr�sence du fichier d'installation du moteur...
$filename = './main/install.'.$phpExtJeu;
if (file_exists($filename))
{
header("Location: ./main/install.$phpExtJeu?action=newinstall");
}
// Redirection si moteur install�...
else
{
//if (!isset($HTTP_SERVER_VARS) && isset($_SERVER))
//	$HTTP_SERVER_VARS= $_SERVER;
	
header("Location: ./main/index.".$phpExtJeu);
}
?>