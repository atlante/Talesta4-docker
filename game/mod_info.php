<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: mod_info.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.27 $
$Date: 2010/05/13 21:36:12 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $modifier_info;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
//$nom_fichier = "../pjs/descriptions/desc_".$PERSO->ID.".txt";

if(!isset($etape)){
	$etape="0";
}	

if($etape=="1"){
	$erreur="";
	$change_pass = false;
	if ($pass_old!="") {
		if($PERSO->pass == md5($pass_old)){
			if( ($pass_new1 == $pass_new2) && ($pass_new1 != '') ){$change_pass = true; }
			else $erreur= "Les 2 saisies du nouveau mot de passe sont différentes ou vides. "; 
		}
	  	else $erreur.= "L'ancien mot de passe n'est pas correct. "; 		  	
	}	
	if( (!isset($email)) || (!verif_email($email))){
		$erreur .= "Adresse Mail incorrect <br />";
	}

	if ($ancienemail<>$email)
		if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->emailvalide($email)===false))
			$erreur .= "Adresse Mail incorrecte ou bannie par le forum <br />";
	
	
	$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where modifiableparpj=1";
        $resultType = $db->sql_query($SQLType);
        $etattemp = array ();
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		$nomTypeVariabilise=preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
		$id_etat="id_".$nomTypeVariabilise;
		array_push($etattemp, $nomTypeVariabilise);
		if (${$id_etat}=='' && $rowType['critereinscription']==2) {
			$erreur.= "Il manque l'info de type: ".$rowType['nomtype']. "<br />";
		}	
	}
	
	if ($erreur<>"") {
		$template_main .= $erreur . "Informations non modifi&eacute;es <br />";
		$etape="0.5";
	}
	else {	
		$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET lastaction = '".time()."', email='".$email . "',wantmail = '".$wantmail."',wantmusic = '".$wantmusic."',  sortprefere = '".$SortPrefere."', reaction = '".$Reaction."'";
		if($change_pass)
			$SQL .= ", pass = '".md5(ConvertAsHTML($pass_new1))."' ";
		if ($PERSO->pnj) 
			$SQL .= ", actionsurprise = '".$actionsurprise."', phrasepreferee ='".$phrasepreferee."' ";
		 $SQL .= " WHERE id_perso = ".$PERSO->ID;
        	$result=$db->sql_query($SQL);


		$nb_etattempSelectionnes = count($etattemp);
		$i=0;
		while ($i<$nb_etattempSelectionnes && $result) {
			$nomTypeVariabilise =array_pop($etattemp);
			$id_etat="id_".$nomTypeVariabilise;
			$id_old_etat="old".$nomTypeVariabilise;
			if (isset(${$id_old_etat})) {
				if (${$id_etat}!=${$id_old_etat}) {
					if (${$id_etat}=='') {
						$SQL= "delete from ".NOM_TABLE_PERSOETATTEMP." where id_etattemp =${$id_old_etat} and id_perso =$PERSO->ID";
						$result=$db->sql_query($SQL);
					}
					else {
						$SQL= "update ".NOM_TABLE_PERSOETATTEMP." set id_etattemp = ${$id_etat} where id_perso= $PERSO->ID AND id_etattemp = ${$id_old_etat}";
						$result=$db->sql_query($SQL);
					}	
				}
			}	
			else {
				if (${$id_etat}!='') {
					$SQL= "insert into ".NOM_TABLE_PERSOETATTEMP." (id_etattemp ,id_perso,fin)  values (${$id_etat},$PERSO->ID,-1)";
					$result=$db->sql_query($SQL);
				}	
			}	
			$i++;
		}



		if(defined("IN_FORUM")&& (IN_FORUM==1) && ($result!==false)) {
			$forum->MAJuser($PERSO->nom, $email,$imageforum,"",$PERSO->nom, $pass_new1);
			$PERSO->imageforum = $imageforum;
		}	
		if ($result!==false) {
			/*$description = str_replace("<?php","",$description);
			$description = str_replace("?>","",$description);
			if (($f = fopen($nom_fichier,"w+b"))!==false) {
				if (fwrite($f,$description)===false) {
					$template_main .= "Probleme à l'écriture de '".$nom_fichier."'";
				}
				if (fclose ($f)===false)
					$template_main .= "Probleme à la fermeture de '".$nom_fichier."'";
			}	
			else $template_main .= "impossible d'ouvrir le fichier '".$nom_fichier."' en ecriture";
			*/
			$PERSO->setDescription($description) ;
			$PERSO->OutPut("Informations correctement modifi&eacute;es");
		}
	}	
}

if ($etape=="0") {
	/*if(file_exists($nom_fichier)){
		$content_array = file($nom_fichier);
		$content = implode("", $content_array);
		$description=stripslashes($content);
	}
	*/
	$description = $PERSO->getDescription();
	$etape="0.5";
}	


if ( $etape=="0.5" ) {
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Modifier vos Informations<br />";
	$template_main .= "<input type='hidden' name='ancienemail' value='".$PERSO->email."' />";
	$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td>Description</td>";
	$template_main .= "<td><textarea name='description' rows='20' cols='50'>";
	$template_main .= $description;
	$template_main .="</textarea></td></tr>";
	$template_main .= "<tr><td>Ancien Mot de passe</td><td><input type='password' name='pass_old' /></td></tr>";
	$template_main .= "<tr><td>Nouveau Mot de passe</td><td><input type='password' name='pass_new1' /></td></tr>";
	$template_main .= "<tr><td>Nouveau Mot de passe</td><td><input type='password' name='pass_new2' /></td></tr>";
	$template_main .= "<tr><td>Email</td><td><input type='text' name='email' size='35' maxlength='80' value='".$PERSO->email."' /></td></tr>";	
	$template_main .= "<tr><td>Etre averti par mail</td><td>".faitOuiNon("wantmail","",$PERSO->wantmail)."</td></tr>";
	$template_main .= "<tr><td>Entendre les fonds sonores des lieux</td><td>".faitOuiNon("wantmusic","",$PERSO->wantmusic)."</td></tr>";
	if(defined("IN_FORUM")&& IN_FORUM==1) {
		$template_main .= "<tr><td>image du PJ: </td><td><input type='text' name='imageforum' maxlength='100' value='"; 
		$template_main .= $PERSO->imageforum;
		$template_main .="' size='40' />";
		if ($PERSO->imageforum<>"")
			$template_main .= "<img src='$PERSO->imageforum' alt='Avatar' align='middle' border='0' />";		 
		 $template_main .= "</td></tr>\n";
	}
	else $PERSO->imageforum="";
	
	$template_main .= "<tr><td>R&eacute;actions lors d'une aggression ou d'un vol</td><td><select name='Reaction'>";
	$toto = array_values($liste_reactions);
	$tata = array_keys($liste_reactions);
	for($i=0;$i<count($liste_reactions);$i++){
		$template_main .= "<option value='".$tata[$i]."'";
		//logdate("reaction" . $PERSO->Reaction . "////".$tata[$i]);
		if ($tata[$i]== $PERSO->Reaction) $template_main .= " selected='selected'";
		$template_main .= ">".$toto[$i]."</option>\n";	
	}
	$template_main .= "</select></td></tr>";

	if ($PERSO->pnj) {
		$template_main .= "<tr><td>Relation (Attitude lors d'une arriv&eacute;e d'un PJ)</td><td><select name='relation'>";
		$toto = array_values($liste_relations);
		$tata = array_keys($liste_relations);
		for($i=0;$i<count($liste_relations);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if ($tata[$i]== $PERSO->Relation) $template_main .= " selected='selected'";			
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
		$template_main .= "</select></td></tr>";		
		$template_main .= "<tr><td>Action surprise lors d'une arriv&eacute;e d'un PJ</td><td><select name='actionsurprise'>";
		$toto = array_values($liste_ActionSurprise);
		$tata = array_keys($liste_ActionSurprise);
		for($i=0;$i<count($liste_ActionSurprise);$i++){
			$template_main .= "<option value='".$tata[$i]."'";
			if ($tata[$i]== $PERSO->actionsurprise) $template_main .= " selected='selected'";			
			$template_main .= ">".$toto[$i]."</option>\n";	
		}
		$template_main .= "</select></td></tr>";
	
		$template_main .= "<tr><td>Phrase Pr&eacute;f&eacute;r&eacute;e</td>";
		$template_main .= "<td><textarea name='phrasepreferee' rows='20' cols='50'>";
			$template_main .= $PERSO->phrasepreferee;
		$template_main .="</textarea></td></tr>";
		
	}


	$valeurVide="";

	$oldValue="";
	$nbEtats=count($PERSO->EtatsTemp);
	for($i=0;$i<$nbEtats;$i++){
		$nomTypeVariabilise =  preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$PERSO->EtatsTemp[$i]->Type);
		$id_etat = "id_".$nomTypeVariabilise;
		${$id_etat}=$PERSO->EtatsTemp[$i]->ID;
		$oldValue.= "<input type='hidden' name='old".$nomTypeVariabilise."' value='".${$id_etat}."' />";

	}


	$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where modifiableparpj=1";
        $resultType = $db->sql_query($SQLType);
	while(	$rowType = $db->sql_fetchrow($resultType)) {
		if ($PERSO->pnj) 
			$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' ORDER BY T1.id_etattemp";
		else 	$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' and utilisableinscription=1 ORDER BY T1.id_etattemp";
		$id_etat = "id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
		if ($rowType['critereinscription']==2)
			$valeurNulle=array();
		else 	$valeurNulle=array("&nbsp;");
		if (isset(${$id_etat}))
			$var = faitSelect($id_etat,$SQL,"", ${$id_etat},array(),$valeurNulle);
		else 	$var = faitSelect($id_etat,$SQL,"", "",array(),$valeurNulle);
		if (($rowType['critereinscription']==2 && $var[0]>0)
		|| ($rowType['critereinscription']==1 && $var[0]>1)) {	
			$template_main .= "<tr><td>".$rowType['nomtype']."</td>";
			$template_main .= "<td>";
			$template_main .= $var[1];	
			$template_main .= "</td></tr>";
		}
		else $valeurVide.="<input type='hidden' name='$id_etat' value='' />";
	}    

	
	$id='id_clef';
	$idname1='nom';
	$query="select ".NOM_TABLE_PERSOMAGIE.".$id,$idname1 from ".NOM_TABLE_PERSOMAGIE.",".NOM_TABLE_MAGIE." where ".NOM_TABLE_PERSOMAGIE.".id_magie=".NOM_TABLE_MAGIE.".id_magie and ".NOM_TABLE_PERSOMAGIE.".id_perso = $PERSO->ID and sortdistant =0 and typecible =1 order by $idname1";
	$results = $db->sql_query($query);
	
	$numrows=$db->sql_numrows($results);  
	if ($numrows>0) {  
	    $template_main .= "<tr><td>Sort pr&eacute;f&eacute;r&eacute;</td><td><select name='SortPrefere'>  "; 
	    $x=0;  
	    while ($x<$numrows){
	    	    $row = $db->sql_fetchrow($results);
		    $theId=$row[$id];  
		    $theName1=$row[$idname1];  
		     
		    $template_main .= "<option value=\"$theId\"";
		    if ($theId==$PERSO->SortPrefere) $template_main .= " selected='selected'";
		    $template_main .= ">$theName1 </option>\n";  
		    $x++;  
		    } 
		$template_main .= "	</select></td></tr>";
	} 	

	
	$template_main .= "<tr><td colspan='2'>".BOUTON_ENVOYER."</td></tr>";
	$template_main .= "</table>";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= $oldValue . $valeurVide;
	$template_main .= "</form>";	
}

	$template_main .= "<table border='0' width='100%' class='container'>
	<tr><td colspan='1'><a href=\"javascript:a('voir_desc.$phpExtJeu?id_perso=$PERSO->ID');\"><span class='c0'>Voir votre description</span></a></td></tr>
</table><br />";
	$template_main .= "<br /><p>&nbsp;</p>";

if ($etape=="0.5")
	$template_main .= "</div>";

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>