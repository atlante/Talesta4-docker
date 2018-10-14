<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: ApparitionMonstre2.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:51:00 $

*/

        $template_main .= "\n<table class='detailscenter'>";
	$template_main .= "<tr><td>Type de Lieu</td>";
        $template_main .= "<td><select name='Itype_lieu_apparition'>";
		$toto = array_keys($liste_type_lieu_apparition);
		$tata = array_values($liste_type_lieu_apparition);
		$nb=count($liste_type_lieu_apparition);
		for($i=0;$i<$nb;$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			//if($toto[$i] == $type_lieu_apparition){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>&nbsp;";
	$template_main .= "</td></tr>";	
	$template_main .= "<tr><td> Nombre Max par lieu (-1 pour illimité)</td><td> <input value='-1' name='Inb_max_lieu' type='text' /></td></tr>";
	$template_main .= "<tr><td> Nombre Max par apparition </td><td> <input value='1' name='Inb_max_apparition' type='text' /></td></tr>";
	$template_main .= "<tr><td> Pourcentage de chance d'apparition </td><td><input value='' name='Ichance_apparition' type='text' /></td></tr>";
	$template_main .= "</table>";
?>