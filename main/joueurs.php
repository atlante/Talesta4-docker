<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: joueurs.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2010/05/15 08:42:16 $

*/

require_once("../include/extension.inc");
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $joueur;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$template_main .= "<div class ='centerSimple'>";
$template_main .= "<h1><br />&nbsp; Les participants<br />&nbsp;</h1>";


	$select = "SELECT p.nom ";
	$from = " FROM ".NOM_TABLE_REGISTRE." p ";
	$where= " where p.pnj=0  ";
	
	$nbEtats=0;
	if ($dbmsJeu!="mysql" || $db->versionServerDiscriminante()>=4.1) {
		//requete a utiliser en sql 4.1 et + pour mysql ou pour les autres bases
		$SQLType = "select te.id_typeetattemp, te.nomtype from ".NOM_TABLE_TYPEETAT." te where te.critereinscription=2 and exists (select 1 from ".NOM_TABLE_ETATTEMPNOM." e where e.visible=1 and e.id_typeetattemp = te.id_typeetattemp)";
	}
	else {	
		//requete de merde en 4.0 et - pour mysql qui n'est pas compatible SQL92
		$SQLType = "select distinct te.id_typeetattemp, te.nomtype from ".NOM_TABLE_TYPEETAT." te, ".NOM_TABLE_ETATTEMPNOM." e where te.critereinscription=2 and e.visible=1 and e.id_typeetattemp = te.id_typeetattemp";
	}
        $resultType = $db->sql_query($SQLType);
        $etattemp = array ();
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		$nbEtats++;		
		$nomTypeVariabilise=strtolower(preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']));
		$select .= ", e".$nbEtats.".nom as ".$nomTypeVariabilise;
		$from .= ", ". NOM_TABLE_PERSOETATTEMP. " pe".$nbEtats.",".NOM_TABLE_ETATTEMPNOM." e".$nbEtats;
		$where .= " and pe".$nbEtats.".id_perso = p.id_perso and pe".$nbEtats.".id_etattemp = e".$nbEtats.".id_etattemp and e".$nbEtats.".id_typeetattemp = ".$rowType['id_typeetattemp'];
		array_push($etattemp,$nomTypeVariabilise);
	}
	$SQL= $select . $from .$where;


	$result = $db->sql_query($SQL);
	if ($db->sql_numrows($result)>0) {
		$template_main .= "
		<br />&nbsp; Les persos accept&eacute;s
		<br />&nbsp;	
		<table class='detailscenter'> 
		<tr><td>Nom </td>";

		foreach ($etattemp as $value) {
		 	$template_main .= "<td><span class='c7'>".$value."</span></td>";
		}

		if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->PrivateMessageAutorise()===true)) {
			$template_main .= "<td>Pour le joindre</td>";
		}	
		$template_main .="</tr>";

		while(	$row = $db->sql_fetchrow($result)) {
	
			$template_main .= "<tr><td> ". span($row["nom"],"pj")."</td>";
			foreach ($etattemp as $value) {
	 			 $template_main .= "<td><span class='c7'>".$row[preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$value)]."</span></td>";
			}			
			
			if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->PrivateMessageAutorise()===true)) {
				$template_main .= "<td>";
				$SQL = 	$forum->selectMembres($row["nom"]);
				$result_pm = $db->sql_query($SQL);
				$row_pm = $db->sql_fetchrow($result_pm);
				if ($row_pm["idselect"]) {		
					$template_main .= "&nbsp;<a href='".$forum->ScriptPrivateMessage($row_pm["idselect"],$row["nom"])."'>";
					if ($forum->URLimagePrivateMessage()<>null) 
						$template_main .= "<img src='".$forum->URLimagePrivateMessage()."' alt='icon_pm.gif' border='0' />";
					else $template_main .= "Envoi d'un message Priv";	
					$template_main .= "</a>";
				}
				$template_main .= "</td>";
			}
				
			$template_main .= "</tr>";
		}
	
			
		$template_main .="	</table>";
	}	
	else $template_main .= "Aucun Perso pour le moment <br /><br />";

	
	//$SQL = "select T1.nom, T2.nom, T4.nomtype from ".NOM_TABLE_INSCRIPTION." T1, ".NOM_TABLE_ETATTEMPNOM." T2, ".NOM_TABLE_INSCRIPT_ETAT." T3, ". NOM_TABLE_TYPEETAT." T4 where T4.id = T2.id_typeetattemp and T3.id_inscript=T1.id and T3.id_etattemp = T2.id_etattemp order by T1.nom, T2.nom, T4.nomtype";
	$select = "SELECT p.nom ";
	$from = " FROM ".NOM_TABLE_INSCRIPTION." p ";
	$where= " where p.id >=1  ";
	
	$nbEtats=0;
	//requete a utiliser en sql 4.1 et +
	//$SQLType = "select te.id_typeetattemp, te.nomtype from ".NOM_TABLE_TYPEETAT." te where te.critereinscription=2 and exists (select 1 from ".NOM_TABLE_ETATTEMPNOM." e where e.visible=1 and e.id_typeetattemp = te.id_typeetattemp)";
	//requete de merde en 4.0 et -
	$SQLType = "select distinct te.id_typeetattemp, te.nomtype from ".NOM_TABLE_TYPEETAT." te, ".NOM_TABLE_ETATTEMPNOM." e where te.critereinscription=2 and e.visible=1 and e.id_typeetattemp = te.id_typeetattemp";
        $resultType = $db->sql_query($SQLType);
        $etattemp = array ();
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		$nbEtats++;		
		$nomTypeVariabilise=strtolower(preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']));
		$select .= ", e".$nbEtats.".nom as ".$nomTypeVariabilise;
		$from .= ", ". NOM_TABLE_INSCRIPT_ETAT. " pe".$nbEtats.",".NOM_TABLE_ETATTEMPNOM." e".$nbEtats;
		$where .= " and pe".$nbEtats.".id_inscript = p.id and pe".$nbEtats.".id_etattemp = e".$nbEtats.".id_etattemp and e".$nbEtats.".id_typeetattemp = ".$rowType['id_typeetattemp'];
		array_push($etattemp,$nomTypeVariabilise);
	}
	$SQL= $select . $from .$where;

	$result = $db->sql_query($SQL);
	if ($db->sql_numrows($result)>0) {
		$template_main .= "<br />&nbsp;
		<br />&nbsp; Les inscriptions en attente
		<br />&nbsp;
		<table class='detailscenter'> 
		<tr><td>Nom </td>";
		foreach ($etattemp as $value) {
		 	$template_main .= "<td><span class='c7'>".$value."</span></td>";
		}		
		$template_main .= "</tr>";

		while(	$row = $db->sql_fetchrow($result)) {
			$template_main .= "<tr><td> ". span($row["nom"],"pj")."</td>";
			foreach ($etattemp as $value) {
	 			 $template_main .= "<td><span class='c7'>".$row[preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$value)]."</span></td>";
			}			
			$template_main .="</tr>";
		}
		
		$template_main .= "</table>";
	}
	else $template_main .= "Aucune inscription en attente pour le moment <br /><br />";
$template_main .="
<br />&nbsp;
<br />&nbsp; Les MJ
<br />&nbsp;


	
<table class='detailscenter'> 
<tr><td>Nom </td>
	<td>Titre</td>";
	if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->PrivateMessageAutorise()===true)) {
		$template_main .= "<td>Pour le joindre</td>";
	}	
	
	$template_main .= "</tr>";

	$SQL = "select nom,titre from ".NOM_TABLE_MJ." ";

	$result = $db->sql_query($SQL);
	
	while(	$row = $db->sql_fetchrow($result)) {
		$template_main .= "<tr><td> ". span($row["nom"],"pj")."</td>
			<td>". span($row["titre"],"race")."</td>";
		if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->PrivateMessageAutorise()===true)) {
			$template_main .= "<td>";
			$SQL = 	$forum->selectMembres($row["nom"]);
			$result_pm = $db->sql_query($SQL);
			$row_pm = $db->sql_fetchrow($result_pm);
			if ($row_pm["idselect"]) {		
				$template_main .= "&nbsp;<a href='".$forum->ScriptPrivateMessage($row_pm["idselect"],$row["nom"])."'>";
				if ($forum->URLimagePrivateMessage()<>null) 
					$template_main .= "<img src='".$forum->URLimagePrivateMessage()."' alt='icon_pm.gif' border='0' />";
				else $template_main .= "Envoi d'un message Priv";	
				$template_main .= "</a>";

			}
			$template_main .= "</td>";
		}
			
		$template_main .= "</tr>";


		$i++;
	}


$template_main .= "
</table>";


$template_main .="<br /><br />
Sans compter les nombreux PNJ que je vous laisse d&eacute;couvrir...
</div><br />";




if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}

?>