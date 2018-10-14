<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: liste_qcm.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.4 $
$Date: 2006/01/31 12:26:18 $

*/

require_once("../include/extension.inc");
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $reg_qcm;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

	$SQL = "SELECT * FROM ".NOM_TABLE_QCM." ORDER BY id_question ";
	$result2 = $db->sql_query($SQL);
		
	$template_main .= "<table class='details'>";
	$template_main .= "<tr><td colspan='14' align='center'><span class='c7'>Table des questions</span></td></tr>";
	$template_main .= "<tr>
<td><span class='c5'>N°</span></td>
<td><span class='c0'>question</span></td>
<td><span class='c5'>reponse1</span></td>
<td><span class='c7'>reponse2</span></td>
<td><span class='c3'>reponse3</span></td>
<td><span class='c0'>reponse4</span></td>
<td><span class='c5'>bonne</span></td></tr>";
	for($i=0;$i<$db->sql_numrows($result2);$i++){
		$row = $db->sql_fetchrow($result2);
		$template_main .= "<tr><td><span class='c5'>".$row["id_question"]."</span></td>";
		$template_main .= "<td><span class='c0'>".$row["question"]."</span></td>";
		$template_main .= "<td><span class='c5'>".$row["reponse1"]."</span></td>";
		$template_main .= "<td><span class='c7'>".$row["reponse2"]."</span></td>";
		$template_main .= "<td><span class='c3'>".$row["reponse3"]."</span></td>";
		$template_main .= "<td><span class='c0'>".$row["reponse4"]."</span></td>";
		$template_main .= "<td><span class='c5'>".$row["bonne"]."</span></td>";
		$template_main .= "</tr>";
	}
    $template_main .= "</table>";
	
	

if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
