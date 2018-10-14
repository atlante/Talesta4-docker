<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: ApparitionMonstre.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:51:00 $

*/

        
	$SQL = "SELECT * FROM ".NOM_TABLE_APPARITION_MONSTRE." T1 WHERE T1.id_perso = ".$id_cible;
	$resultPJQ = $db->sql_query($SQL);
	if($db->sql_numrows($resultPJQ) > 0){		
	        $template_main .= "\nLieux d'apparition de ".span($libelle,"pj")."<br />";
                $template_main .= "<table class='detailscenter'>";
                $template_main .= "<tr><td>&nbsp;</td><td>Type de lieu</td><td>Pourcentage de chance d'apparition</td><td>nb max par apparition </td><td>nb max dans lieu (-1 pour illimité)</td></tr>";
                while($row = $db->sql_fetchrow($resultPJQ)) {                
                	$template_main .= "<tr>";
                	$template_main .= "<td>Supprimer<input type='checkbox' name='del[".$row['id_apparitionmonstre']."]' /></td>";
                	$template_main .= "<td>".$liste_type_lieu_apparition[$row['id_typelieu']];
                	$template_main .= "</td>";
                	$template_main .= "<td><input type='hidden' value='".$row['chance_apparition']."' name='old_chance_apparition[".$row['id_apparitionmonstre']."]' /><input type='text' value='".$row['chance_apparition']."' name='chance_apparition[".$row['id_apparitionmonstre']."]' /></td>";
                  	$template_main .= "<td><input type='hidden' value='".$row['nb_max_apparition']."' name='old_nb_max_apparition[".$row['id_apparitionmonstre']."]' /><input type='text' value='".$row['nb_max_apparition']."' name='nb_max_apparition[".$row['id_apparitionmonstre']."]' /></td>";
                	$template_main .= "<td><input type='hidden' value='".$row['nb_max_lieu']."' name='old_nb_max_lieu[".$row['id_apparitionmonstre']."]' /><input type='text' value='".$row['nb_max_lieu']."' name='nb_max_lieu[".$row['id_apparitionmonstre']."]' /></td>";
                	$template_main .= "</tr>\n";
                }
                $template_main .= "</table><br />";
		
	} else {
		$template_main .= span($libelle,"pj")." n'apparaitra dans aucun lieu";
	}


?>