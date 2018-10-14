<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_map.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.5 $
$Date: 2006/01/31 12:26:16 $

*/
 
require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_map;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

	/**
	Se mettre dans le répertoire admin et lancer la commande suivante pour générer l'image et l'HTML map associée
	dot -Tcmap -o ../lieux/vues/map.cmap -Tgif -o ../lieux/vues/map.gif ../lieux/descriptions/graph.txt
	en ligne de commande pour faire la map HTML et la gif
	
	*/

function ajouteChemin($nomLieu1, $nomLieu2, $typeChemin, $distance, $pass,$difficulte) {
	global $styleEntrer, $stylePassage, $styleGuilde, $styleAller,$styleSecret;
	global $styleEscalader,$styleNager, $stylePeage;

	$desc= "\t\t\"".ConvertAsTXT($nomLieu1)."\" -> \"".ConvertAsTXT($nomLieu2)."\"";
	switch ($typeChemin) {				
		case "0": //entrer
		$desc .=" [".$styleEntrer;
		break;
		case "1": //passage
		$desc .=" [ label=\"".$distance ."\"".$stylePassage;
		break;            
		case "2": //guilde     
		$desc .=" [ label=\"".$pass ."\"".$styleGuilde;
		break;            
		case "3": //aller      
		$desc .=" [ label=\"".$distance ."\"".$styleAller;
		break;            
		case "4": //secret     
		$desc .=" [ label=\"".$difficulte ."\"".$styleSecret;
		break;            
		case "5": //escalader  
		$desc .=" [ label=\"".$difficulte ."\"".$styleEscalader;
		break;            
		case "8": //nager      
		$desc .=" [ label=\"".$difficulte ."\"".$styleNager;
		break;            
		case "9": //peage      
		$desc .=" [ label=\"".$difficulte ."\"".$stylePeage;
		break;
		default: 
		$desc .=" [";
		break;

	}	
	$desc .="];\n";
	return $desc;
}

if($MJ->aDroit($liste_flags_mj["CreerCarte"])){
	$file = "../lieux/descriptions/graph.txt";
	setlocale (LC_TIME, "fr");
	
	if (($f = fopen($file,"wb"))!==false) {
		$styleEntrer = " arrowhead=odiamond ";
		$stylePassage = " arrowhead=dot ";
		$styleGuilde = " arrowhead=odot style=dashed ";
		$styleAller = " arrowhead=normal ";
		$styleSecret = "  arrowhead=empty style=dotted ";
		$styleEscalader = " arrowhead=crow style=dashed fontcolor=grey color=grey labelfontcolor=grey ";
		$styleNager = " arrowhead=open style=dashed color=blue fontcolor=blue labelfontcolor=blue ";
		$stylePeage = " arrowhead=tee style=dashed color=red labelfontcolor=red fontcolor=red ";

		$desc = "digraph G {\n";
		$desc .= "\tgraph [ rankdir=\"LR\"  labelloc=top label=\"Carte du monde de ".NOM_JEU." au ". strftime ("%d %B %Y %Hh%Mmin%Ss")."\"]\n";
		$desc.= "\tedge[labelfontsize=\"8\" fontsize=\"8\" labeldistance=\"0.8\" arrowsize=\"0.9\" dir=\"none\"]\n"; 
		$desc.= "\tnode [width=\"0\" height=\"0\" fontsize=\"10\" pencolor=black,color=white];\n";

		$desc.= "\tsubgraph cluster_LEGENDE {\n";		
		//$desc .= "\t\trankdir=LR";
		$desc .= "\t\tnode [shape=none pencolor=black]\n";
		$desc.= "\t\tlabel = \"Légende des chemins\";\n";
		$desc.= "\t\t\"chemins nager\" -> \"&nbsp;\" [". $styleNager. " label = \"difficulté\"];\n";
		$desc.= "\t\t\"chemins aller\" -> \"&nbsp;&nbsp;\" [". $styleAller. " label = distance " ."];\n";
		$desc.= "\t\t\"chemins entrer\" -> \"&nbsp;&nbsp;&nbsp;\" [". $styleEntrer/*. " label = distance "*/ ."];\n";
		$desc.= "\t\t\"chemins passage\" -> \"&nbsp;&nbsp;&nbsp;&nbsp;\" [". $stylePassage. " label = \"distance \"];\n";
		$desc.= "\t\t\"chemins escalader\" -> \"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\" [". $styleEscalader. " label = \"difficulté\"];\n";
		$desc.= "\t\t\"chemins péage\" -> \"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\" [". $stylePeage. " label = \"montant\"];\n";		
		$desc.= "\t\t\"chemins secret\" -> \"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\" [". $styleSecret. " label = \"difficulté\"];\n";		
		$desc.= "\t\t\"chemins guilde\" -> \"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\" [". $styleGuilde. " label = \"mot de passe\"];\n";		
		$desc.= "\t}\n";


		//gestion des trigrammes
		$SQL_trigramme = "SELECT distinct trigramme FROM ".NOM_TABLE_LIEU;
		
		$result_trigramme = $db->sql_query($SQL_trigramme);
		while(	$row_trigramme = $db->sql_fetchrow($result_trigramme)) {
			if ($row_trigramme['trigramme']<>"") {

				$desc.= "\tsubgraph cluster_" .  $row_trigramme['trigramme']." {\n";
				$desc.= "\t\tnode [URL=\"\\\N.html\" tooltip=\"\\\N\" width=\"0\" height=\"0\" fontsize=\"10\" style=filled fillcolor=red pencolor=black,color=white];\n";
				$desc.= "\t\tlabel = \"". $row_trigramme['trigramme']."\";\n";

			
				$SQL_lieux="select id_lieu, nom FROM ".NOM_TABLE_LIEU." where trigramme = '". $row_trigramme['trigramme']."'";
				$result_lieux = $db->sql_query($SQL_lieux);
				$lieuTrigramme = array();
				while(	$row_lieux = $db->sql_fetchrow($result_lieux)) {
					array_push($lieuTrigramme, ConvertAsTXT($row_lieux['nom'])."\" [URL=\"lien_map_voirLieu.".$phpExtJeu."?lieu=".$row_lieux['id_lieu']."\"];" );
				}

				//gestion des chemins dans trigramme
				$SQL_chemin = "select id_clef, l1.nom as l1nom, l2.nom as l2nom,id_lieu_1, id_lieu_2, type, difficulte, pass, distance from ".NOM_TABLE_CHEMINS.", ".NOM_TABLE_LIEU." l1, ".NOM_TABLE_LIEU." l2 where id_lieu_1=l1.id_lieu and id_lieu_2=l2.id_lieu and l1.trigramme= '".$row_trigramme['trigramme']."' and l2.trigramme='".$row_trigramme['trigramme']."'";
				$result_chemin = $db->sql_query($SQL_chemin);
				$lieuAvecCheminInterne=array();
				while(	$row_chemin = $db->sql_fetchrow($result_chemin)) {
					$desc.=ajouteChemin($row_chemin['l1nom'], $row_chemin['l2nom'], $row_chemin['type'],$row_chemin['distance'], $row_chemin['pass'],$row_chemin['difficulte']);
					//array_push($lieuAvecCheminInterne , ConvertAsTXT($row_chemin['l1nom'])."\" [URL=\"lien_map_voirLieu.".$phpExtJeu."?lieu=".$row_chemin['id_lieu_1']."\"];" );
					//array_push($lieuAvecCheminInterne , ConvertAsTXT($row_chemin['l2nom'])."\" [URL=\"lien_map_voirLieu.".$phpExtJeu."?lieu=".$row_chemin['id_lieu_2']."\"];" );
				}

				/*
				$lieuSansCheminInterne = array_diff ( $lieuTrigramme, $lieuAvecCheminInterne);
				//gestion des lieux sans chemin dans trigramme
				foreach($lieuSansCheminInterne as $numLieu => $nomLieu) {
					//$desc.= "\t\t\"".ConvertAsTXT($nomLieu)."\";\n";
					$desc.= "\t\t\"".$nomLieu."\n";
				}	
				*/

				foreach($lieuTrigramme as $numLieu => $nomLieu) {
					//$desc.= "\t\t\"".ConvertAsTXT($nomLieu)."\";\n";
					$desc.= "\t\t\"".$nomLieu."\n";
				}				
				$desc.= "\t}\n";
			}
			//else $desc.=  $row_trigramme['trigramme'].";\n";
		}
		
		//gestion des chemins entre 2 trigrammes
		$SQL_chemin = "select id_clef, l1.nom as l1nom, l2.nom as l2nom,id_lieu_1, id_lieu_2, type, difficulte, pass, distance from ".NOM_TABLE_CHEMINS.", ".NOM_TABLE_LIEU." l1, ".NOM_TABLE_LIEU." l2
		 where id_lieu_1=l1.id_lieu and id_lieu_2=l2.id_lieu and l1.trigramme<> l2.trigramme";
		$result_chemin = $db->sql_query($SQL_chemin);

		while(	$row_chemin = $db->sql_fetchrow($result_chemin)) {
			$desc.=ajouteChemin($row_chemin['l1nom'], $row_chemin['l2nom'], $row_chemin['type'],$row_chemin['distance'], $row_chemin['pass'],$row_chemin['difficulte']);
		}
		// fin du graph
		$desc .= "}\n";

		if (fwrite($f,stripslashes($desc))===false) {
			$template_main .= "Probleme à l'écriture de '".$file."'";
		}
		else  {
			if (fclose ($f)===false)
				$template_main .= "Probleme à la fermeture de '".$file."'";
			else $MJ->OutPut("fichier '".$file."' correctement modifi&eacute;");
		}	
	}
	else $template_main .="impossible d'ouvrir le fichier '".$file."'";	
}
else $template_main .= GetMessage("droitsinsuffisants");


if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
