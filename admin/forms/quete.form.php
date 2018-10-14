<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: quete.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/01/24 16:37:10 $

*/



	$template_main .="<script type='text/javascript'>

		function selectionneTypeQuete(){
			var type = document.forms[0].type_quete.options[document.forms[0].type_quete.selectedIndex].value;
			if (type=='1'||type=='7') { 
				//affiche lieu
				document.forms[0].detail_type_queteLieu.style.display= 'inline';
				//cache reste
				document.forms[0].detail_type_quetePJ.style.display= 'none';
				document.forms[0].detail_type_quetePJ.style.display= 'none';
				document.forms[0].detail_type_queteOBJ.style.display= 'none';
				document.forms[0].detail_type_quetePO.style.display= 'none';
				document.forms[0].detail_type_queteSort.style.display= 'none';				
			}
			else 
			if (type=='2'||type=='4'||type=='5'){
				//montre pj
				document.forms[0].detail_type_quetePJ.style.display= 'inline';
				//cache reste
				document.forms[0].detail_type_queteLieu.style.display= 'none';
				document.forms[0].detail_type_queteOBJ.style.display= 'none';
				document.forms[0].detail_type_quetePO.style.display= 'none';				
				document.forms[0].detail_type_queteSort.style.display= 'none';
			}
			else 
			if (type=='3'){
				//montre obj
				document.forms[0].detail_type_queteOBJ.style.display= 'inline';
				//cache reste
				document.forms[0].detail_type_queteLieu.style.display= 'none';
				document.forms[0].detail_type_quetePJ.style.display= 'none';
				document.forms[0].detail_type_quetePO.style.display= 'none';				
				document.forms[0].detail_type_queteSort.style.display= 'none';

			}
			else
			if (type=='6'){
					//montre po
					document.forms[0].detail_type_quetePO.style.display= 'inline';
					//cache reste
					document.forms[0].detail_type_queteLieu.style.display= 'none';
					document.forms[0].detail_type_quetePJ.style.display= 'none';
					document.forms[0].detail_type_queteOBJ.style.display= 'none';				
					document.forms[0].detail_type_queteSort.style.display= 'none';
			}

		}
	

		function selectionnePublique(){
			/*
			var public = document.forms[0].public.options[document.forms[0].public.selectedIndex].value;
			if (public=='1') {
				document.forms[0].proposant_anonyme.style.display= 'inline';
				document.forms[0].id_lieu.style.display= 'inline';
			}
			else {
				document.forms[0].proposant_anonyme.style.display= 'none';
				document.forms[0].id_lieu.style.display= 'none';
			}*/
		}	

		function selectionneMJ_PJ(){
			var propose = document.forms[0].proposepartype.options[document.forms[0].proposepartype.selectedIndex].value;
			if (propose=='1') {
				document.forms[0].id_proposeMJ.style.display= 'inline';
				//cache pj
				document.forms[0].id_proposePJ.style.display= 'none';
			}
			else {
				document.forms[0].id_proposeMJ.style.display= 'none';
				//montre pj
				document.forms[0].id_proposePJ.style.display= 'inline';
			}
			
		}
	
	</script>";


	if (NOM_SCRIPT==("supprimer_quete.".$phpExtJeu))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }


	$template_main .= "<table class='detailscenter' width='60%'>";
	$template_main .= "<tr><td colspan='2'><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_quete.htm')\">Aide</a></td></tr>";
	$template_main .= "<tr><td>nom de la quete: </td><td><input type='text' $readonly name='nom_quete' value='".$nom_quete."' size='60' maxlength='50' /></td></tr>";
	$template_main .= "<tr><td>durée (en jours réels, -1 pour infini) avant laquelle le pj doit terminer la quete: </td><td><input type='text' $readonly name='duree_quete' value='".$duree_quete."' size='5' /></td></tr>";
	$template_main .= "<tr><td>permanente (une fois terminée, la quete recommence à zéro pour un autre PJ): </td><td>".faitOuiNon("cyclique",$disabled ,$cyclique)."</td></tr>";
	$template_main .= "<tr><td>type : </td><td><select $disabled name='type_quete' onchange='selectionneTypeQuete()'>";
	$toto = array_keys($liste_type_quete);
	$tata = array_values($liste_type_quete);
	for($i=0;$i<count($liste_type_quete);$i++){
		$template_main .= "\t<option value='".$toto[$i]."'";
		if($toto[$i] == $type_quete){ $template_main .= " selected='selected'";}
		$template_main .= ">".$tata[$i]."</option>\n";	
	}
	$template_main .= "</select>";
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where T1.pnj<>2 ORDER BY T1.nom ASC";
	$var=faitSelect("detail_type_quetePJ",$SQL,$disabled,$detail_type_quetePJ);
	$template_main .= $var[1];
	$SQL ="Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type)
	,'  --> '),T1.nom),'   - '),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', Prix '),T1.prix_base),')') as labselect 
	from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("detail_type_queteOBJ",$SQL,$disabled,$detail_type_queteOBJ);
	$template_main .= $var[1];
	$SQL = "Select T1.id_magie as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'  --> '),T1.nom),'   - (Cha:'),T1.charges),', Degs '),T1.degats_min),'-'),T1.degats_max),', place '),T1.place),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_MAGIE." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
	$var=faitSelect("detail_type_queteSort",$SQL,$disabled,$detail_type_queteSort);
	$template_main .= $var[1];
	$SQL_lieu = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";
	$var= faitSelect("detail_type_queteLieu",$SQL_lieu,$disabled,$detail_type_queteLieu);
	$template_main .= $var[1];
		$template_main .= "<input type='text' $readonly name='detail_type_quetePO' value='".$detail_type_quetePO."' size='10' />";
		$template_main .= "</td></tr>\n";

		$template_main .= "<tr><td>Proposée par : </td><td><select $disabled name='proposepartype' onchange='selectionneMJ_PJ()'>";
		$toto = array_keys($liste_type_propose_quete);
		$tata = array_values($liste_type_propose_quete);
		for($i=0;$i<count($liste_type_propose_quete);$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $proposepartype){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>&nbsp;";

		$SQL = "Select T1.id_mj as idselect, concat(concat(T1.nom,' - '),T1.titre) as labselect from ".NOM_TABLE_MJ." T1 ORDER BY T1.nom ASC";
		$var=faitSelect("id_proposeMJ",$SQL,$disabled,$id_proposeMJ);
		$template_main .= $var[1];
		$template_main .= "&nbsp;";		
		$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ORDER BY T1.nom ASC";
		$var=faitSelect("id_proposePJ",$SQL,$disabled,$id_proposePJ);
		$template_main .= $var[1];
		
		$template_main .= "</td></tr>\n";
		$template_main .= "<tr><td>Quete doit être validée par le proposant: </td><td>".faitOuiNon("validationquete",$disabled ,$validationquete)."</td></tr>";	
		$template_main .= "<tr><td>Quete Publique : </td><td>".faitOuiNon("public",$disabled ,$public, "onchange='selectionnePublique()'")."</td></tr>";
		$template_main .= "<tr><td>Le proposant reste anonyme (Quete Publique uniquement): </td><td>".faitOuiNon("proposant_anonyme",$disabled ,$proposant_anonyme)."</td></tr>";
		$template_main .= "<tr><td>Lieu ou est affiché la quête (Quete Publique uniquement): </td><td>";
		$var= faitSelect("id_lieu",$SQL_lieu,$disabled,$id_lieu);
        	$template_main .= $var[1];
		
		$template_main .="</td></tr>";
		$SQLetat = "Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1 WHERE T1.id_etattemp > 1 ORDER BY T1.nom ASC";

		$template_main .= "<tr><td>Accessible uniquement par</td><td>";
	        $var=faitSelect("id_etattempspecifique",$SQLetat,$disabled,$id_etattempspecifique, array(), array("&nbsp;"));	
	        $template_main .= $var[1];
	        $template_main .= "</td></tr>";

		$template_main .= "<tr><td>la quete peut être refusée par le PJ (Inutile sur Quete Publique) </td><td>".faitOuiNon("refuspossible",$disabled ,$refuspossible)."</td></tr>";
		$template_main .= "<tr><td>la quete peut être abandonnée par le PJ </td><td>".faitOuiNon("abandonpossible",$disabled ,$abandonpossible)."</td></tr>";
		$template_main .= "<tr><td>texte de proposition au PJ : </td><td><textarea name='texteproposition' rows='10' $readonly cols='40'>";
		$template_main .= $texteproposition;
		$template_main .="</textarea></td></tr>";
		$template_main .= "<tr><td>texte affiché en cas de réussite : </td><td><textarea name='textereussite' rows='10' $readonly cols='40'>";
		$template_main .= $textereussite;
		$template_main .="</textarea></td></tr>";
		$template_main .= "<tr><td>texte affiché en cas d'echec : </td><td><textarea name='texteechec' rows='10' $readonly cols='40'>";
		$template_main .= $texteechec;
		$template_main .="</textarea></td></tr>";
	


	$toto = array_keys($liste_type_recompense);
	$tata = array_values($liste_type_recompense);

	$template_main .= "<tr><td>";
	$template_main .= "Récompenses</td><td>";

	//affiche la liste des recompenses deja affectees
	if (NOM_SCRIPT<>"creer_quete.".$phpExtJeu && NOM_SCRIPT<>"creer_Quetes.".$phpExtJeu) { 
		$SQL = "SELECT T1.id_recompensequete, T1.type_recompense, T1.recompense FROM ".NOM_TABLE_RECOMPENSE_QUETE." T1 WHERE  T1.recompense > 0 and T1.id_quete = ".$id_cible;
		$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
			$template_main .= "<table class='detailscenter'>";
			while($row = $db->sql_fetchrow($result)) {
				$erreur=false;
				switch($row["type_recompense"]) {
					case 1: //XP
						$SQL2="";
						$chaineAffichee = span($row["recompense"]." XPs","xp");
						break;
					case 2: //PO
						$SQL2="";
						$chaineAffichee = span($row["recompense"]." POs","po");
						break;
					case 3:	//objet
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_OBJET."  WHERE id_objet = ".$row["recompense"];
						break;
					case 4: //sort
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_MAGIE."  WHERE id_magie = ".$row["recompense"];
						break;
					case 5: //competence
						$SQL2="";
						$chaineAffichee=array_search($row["recompense"],$liste_comp_full);
						if($chaineAffichee===false)
							$erreur=true;
						$chaineAffichee = span($chaineAffichee,"etattemp");
						break;	
					case 6: //etat temp
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_ETATTEMPNOM."  WHERE  id_etattemp = ".$row["recompense"];
						break;	
				}

				if ($SQL2!="" && $erreur==false) {	
					$erreur=true;
					if ($result2 = $db->sql_query($SQL2)) {
						if($row2 = $db->sql_fetchrow($result2)) {		
							$chaineAffichee=$row2["nom"];
							$erreur=false;
						}
					}
					
				}		
				if (($SQL2=="") ||($erreur==false)) {	
					$template_main .= "<tr>";
					$template_main .= "<td>Supprimer<input type='checkbox' $disabled name='delRecompense[".$row["id_recompensequete"]."]' /></td>";
					//$template_main .= "<td><a href=\"javascript:a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat=".$row["id_etattemp"]."')\">".span($row["nom"],"etattemp")."</a></td>";
					$template_main .= "<td>".$liste_type_recompense[$row["type_recompense"]]." &nbsp;".$chaineAffichee ." </td>";
					$template_main .= "</tr>";
				}	
			}
			$template_main .= "</table>";
		} else {
			$template_main .= " Aucune Récompense accordée actuellement.<br />";
		}
	}
	
	if (NOM_SCRIPT<>"supprimer_quete.".$phpExtJeu) {
		$template_main .= "<select $disabled name='type_recompense' onchange='selectionneRecompense()'>";
		$nbTypeRecompense=count($liste_type_recompense);
		for($i=0;$i<$nbTypeRecompense;$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $type_recompense){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>\n";

		$SQL ="Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type)
		,'  --> '),T1.nom),'   - '),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', Prix '),T1.prix_base),')') as labselect 
		from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
		$var=faitSelect("recompenseOBJ",$SQL,"",$recompenseOBJ);
		$template_main .= $var[1];
		$SQL = "Select T1.id_magie as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'  --> '),T1.nom),'   - (Cha:'),T1.charges),', Degs '),T1.degats_min),'-'),T1.degats_max),', place '),T1.place),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_MAGIE." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
		$var=faitSelect("recompenseSort",$SQL,$disabled,$recompenseSort);
		$template_main .= $var[1];

		$SQL = "Select T1.id_etattemp as idselect, concat(concat(T2.nomtype,' - '),T1.nom) as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp ORDER BY labselect ASC";
		$var=faitSelect("recompenseEtat",$SQL,$disabled,$recompenseEtat);
		$template_main .= $var[1];

		$template_main .= "<input type='text' $readonly name='recompenseMontant' value='".$recompenseMontant."' size='10' />";

		$template_main .="<select $disabled name='recompenseComp'>";
		$tata = array_keys($liste_comp_full);
		$toto = array_values($liste_comp_full);
		for($i=0;$i<count($liste_comp_full);$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $recompenseComp){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}		
		$template_main .= "</select>";
		$template_main .= "<br />";
		
		$template_main .= "<input value=\"Ajouter la récompense\" type='button' onclick=\"ajouterecompenses()\" />\n";
		$template_main .= "<input name='boutonVoirEtat' value=\"Voir l'etat\" type='button' onclick=\"a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat='+document.forms[0].recompenseEtat.options[document.forms[0].recompenseEtat.selectedIndex].value)\" />";
		$template_main .= "<input name='boutonVoirSort' value=\"Voir le sort\" type='button' onclick=\"a('../bdc/sort.$phpExtJeu?for_mj=1&amp;num_sort='+document.forms[0].recompenseSort.options[document.forms[0].recompenseSort.selectedIndex].value)\" />";
		$template_main .= "<input name='boutonVoirOBJ' value=\"Voir l'objet\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[0].recompenseOBJ.options[document.forms[0].recompenseOBJ.selectedIndex].value)\" />";
		//$template_main .= "<input name='boutonVoirComp' value=\"Voir l'objet\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[0].recompenseComp.options[document.forms[0].recompenseComp.selectedIndex].value)\" />";

		$template_main .= "<br />&nbsp;<br />";
	}
	//$template_main .= "<select name=\"ListAddRecompense\" size='5'>".$recompensesValue."</select><br />&nbsp;<br />";
	$template_main .= "<select name=\"ListAddRecompense\" size='5'></select><br />&nbsp;<br />";
	if (NOM_SCRIPT<>"supprimer_quete.".$phpExtJeu) 
		$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ1()\" />";
	//$template_main .= "</form></td></tr>";
	$template_main .= "</td></tr>";
	


	$toto = array_keys($liste_type_punition);
	$tata = array_values($liste_type_punition);

	$template_main .= "<tr><td>";
	$template_main .= "punitions</td><td>";

	//affiche la liste des punitions deja affectees
	if (NOM_SCRIPT<>"creer_quete.".$phpExtJeu && NOM_SCRIPT<>"creer_Quetes.".$phpExtJeu) { 
		$SQL = "SELECT T1.id_recompensequete, T1.type_recompense, T1.recompense FROM ".NOM_TABLE_RECOMPENSE_QUETE." T1 WHERE T1.recompense < 0 and T1.id_quete = ".$id_cible;
		$result = $db->sql_query($SQL);
		if($db->sql_numrows($result) > 0){
			$template_main .= "<table class='detailscenter'>";
			while($row = $db->sql_fetchrow($result)) {
				$erreur=false;
				switch($row["type_recompense"]) {
					case 1: //XP
						$SQL2="";
						$chaineAffichee = span(-$row["recompense"]." XPs","xp");
						break;
					case 2: //PO
						$SQL2="";
						$chaineAffichee = span(-$row["recompense"]." POs","po");
						break;
					case 3:	//objet
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_OBJET."  WHERE id_objet = -".$row["recompense"];
						break;
					case 4: //sort
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_MAGIE."  WHERE id_magie = -".$row["recompense"];
						break;
					case 5: //competence
						$SQL2="";
						$chaineAffichee=array_search(-$row["recompense"],$liste_comp_full);
						if($chaineAffichee===false)
							$erreur=true;
						$chaineAffichee = span($chaineAffichee,"etattemp");
						break;	
					case 6: //etat temp
						$SQL2 = "SELECT nom  FROM ".NOM_TABLE_ETATTEMPNOM."  WHERE  id_etattemp = -".$row["recompense"];
						break;	
				}

				if ($SQL2!="" && $erreur==false) {	
					$erreur=true;
					if ($result2 = $db->sql_query($SQL2)) {
						if($row2 = $db->sql_fetchrow($result2)) {		
							$chaineAffichee=$row2["nom"];
							$erreur=false;
						}
					}
					
				}		
				if (($SQL2=="") ||($erreur==false)) {	
					$template_main .= "<tr>";
					$template_main .= "<td>Supprimer<input type='checkbox' $disabled name='delPunition[".$row["id_recompensequete"]."]' /></td>";
					//$template_main .= "<td><a href=\"javascript:a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat=".$row["id_etattemp"]."')\">".span($row["nom"],"etattemp")."</a></td>";
					$template_main .= "<td>".$liste_type_punition[$row["type_recompense"]]." &nbsp;".$chaineAffichee ." </td>";
					$template_main .= "</tr>";
				}	
			}
			$template_main .= "</table>";
		} else {
			$template_main .= " Aucune punition accordée actuellement.<br />";
		}
	}
	
	if (NOM_SCRIPT<>"supprimer_quete.".$phpExtJeu) {
		$template_main .= "<select $disabled name='type_punition' onchange='selectionnePunition()'>";
		$nbTypePunition=count($liste_type_punition);
		for($i=0;$i<$nbTypePunition;$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $type_punition){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}
		$template_main .= "</select>\n";

		$SQL ="Select T1.id_objet as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type)
		,'  --> '),T1.nom),'   - '),' (Mun:'),T1.munitions),', Dur:'),T1.durabilite),', Degs '),T1.degats_min),'-'),T1.degats_max),', Prix '),T1.prix_base),')') as labselect 
		from ".NOM_TABLE_OBJET." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
		$var=faitSelect("punitionOBJ",$SQL,"",$punitionOBJ);
		$template_main .= $var[1];
		$SQL = "Select T1.id_magie as idselect, concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T1.type,'- '),T1.sous_type),'  --> '),T1.nom),'   - (Cha:'),T1.charges),', Degs '),T1.degats_min),'-'),T1.degats_max),', place '),T1.place),', Prix '),T1.prix_base),')') as labselect from ".NOM_TABLE_MAGIE." T1 ORDER BY T1.type, T1.sous_type, T1.nom ASC";
		$var=faitSelect("punitionSort",$SQL,$disabled,$punitionSort);
		$template_main .= $var[1];

		$SQL = "Select T1.id_etattemp as idselect, concat(concat(T2.nomtype,' - '),T1.nom) as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp ORDER BY labselect ASC";
		$var=faitSelect("punitionEtat",$SQL,$disabled,$punitionEtat);
		$template_main .= $var[1];

		$template_main .= "<input type='text' $readonly name='punitionMontant' value='".$punitionMontant."' size='10' />";

		$template_main .="<select $disabled name='punitionComp'>";
		$tata = array_keys($liste_comp_full);
		$toto = array_values($liste_comp_full);
		for($i=0;$i<count($liste_comp_full);$i++){
			$template_main .= "\t<option value='".$toto[$i]."'";
			if($toto[$i] == $punitionComp){ $template_main .= " selected='selected'";}
			$template_main .= ">".$tata[$i]."</option>\n";	
		}		
		$template_main .= "</select>";
		$template_main .= "<br />";
		
		$template_main .= "<input value=\"Ajouter la punition\" type='button' onclick=\"ajoutepunitions()\" />\n";
		$template_main .= "<input name='boutonVoirPunitionEtat' value=\"Voir l'etat\" type='button' onclick=\"a('../bdc/etat.$phpExtJeu?for_mj=1&amp;num_etat='+document.forms[0].punitionEtat.options[document.forms[0].punitionEtat.selectedIndex].value)\" />";
		$template_main .= "<input name='boutonVoirPunitionSort' value=\"Voir le sort\" type='button' onclick=\"a('../bdc/sort.$phpExtJeu?for_mj=1&amp;num_sort='+document.forms[0].punitionSort.options[document.forms[0].punitionSort.selectedIndex].value)\" />";
		$template_main .= "<input name='boutonVoirPunitionOBJ' value=\"Voir l'objet\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[0].punitionOBJ.options[document.forms[0].punitionOBJ.selectedIndex].value)\" />";
		//$template_main .= "<input name='boutonVoirPunitionComp' value=\"Voir l'objet\" type='button' onclick=\"a('../bdc/objet.$phpExtJeu?for_mj=1&amp;num_obj='+document.forms[0].punitionComp.options[document.forms[0].punitionComp.selectedIndex].value)\" />";

		$template_main .= "<br />&nbsp;<br />";
	}
	//$template_main .= "<select name=\"ListAddPunition\" size='5'>".$punitionsValue."</select><br />&nbsp;<br />";
	$template_main .= "<select name=\"ListAddPunition\" size='5'></select><br />&nbsp;<br />";
	if (NOM_SCRIPT<>"supprimer_quete.".$phpExtJeu) 
		$template_main .= "<input value=\"Remettre a Zero\" type='button' onclick=\"RAZ1Punition()\" />";
	//$template_main .= "</form></td></tr>";
	$template_main .= "</td></tr>";
	$template_main .= "</table>";
	
        	$template_main .="<script type='text/javascript'>
        		selectionneTypeQuete();
        		selectionneMJ_PJ();
        		selectionnePublique();

		var chainetempRecompense2 = '';

		function selectionneRecompense(){
			var type = document.forms[0].type_recompense.options[document.forms[0].type_recompense.selectedIndex].value;
			if (type=='1'|| type=='2') { 
				//affiche montant
				document.forms[0].recompenseMontant.style.display= 'inline';
				//cache reste
				document.forms[0].recompenseSort.style.display= 'none';
				document.forms[0].boutonVoirSort.style.display= 'none';
				document.forms[0].recompenseOBJ.style.display= 'none';
				document.forms[0].boutonVoirOBJ.style.display= 'none';
				document.forms[0].recompenseComp.style.display= 'none';
				//document.forms[0].boutonVoirComp.style.display= 'none';
				document.forms[0].recompenseEtat.style.display= 'none';
				document.forms[0].boutonVoirEtat.style.display= 'none';
			}
			else 
			if (type=='4'){
				//montre sort
				document.forms[0].recompenseSort.style.display= 'inline';
				document.forms[0].boutonVoirSort.style.display= 'inline';
				//cache reste
				document.forms[0].recompenseOBJ.style.display= 'none';
				document.forms[0].boutonVoirOBJ.style.display= 'none';
				document.forms[0].recompenseMontant.style.display='none';
				document.forms[0].recompenseComp.style.display= 'none';
				//document.forms[0].boutonVoirComp.style.display= 'none';
				document.forms[0].recompenseEtat.style.display= 'none';
				document.forms[0].boutonVoirEtat.style.display= 'none';
			}
			else 
			if (type=='3'){
				//montre obj
				document.forms[0].recompenseOBJ.style.display= 'inline';
				document.forms[0].boutonVoirOBJ.style.display= 'inline';
				//cache reste
				document.forms[0].recompenseSort.style.display= 'none';
				document.forms[0].boutonVoirSort.style.display= 'none';
				document.forms[0].recompenseMontant.style.display='none';
				document.forms[0].recompenseComp.style.display= 'none';
				//document.forms[0].boutonVoirComp.style.display= 'none';
				document.forms[0].recompenseEtat.style.display= 'none';
				document.forms[0].boutonVoirEtat.style.display= 'none';
			}
			else 
			if (type=='5'){
				//montre competence
				document.forms[0].recompenseComp.style.display= 'inline';
				//document.forms[0].boutonVoirComp.style.display= 'inline';
				//cache reste
				document.forms[0].recompenseSort.style.display= 'none';
				document.forms[0].boutonVoirSort.style.display= 'none';
				document.forms[0].recompenseMontant.style.display='none';
				document.forms[0].recompenseOBJ.style.display= 'none';
				document.forms[0].boutonVoirOBJ.style.display= 'none';
				document.forms[0].recompenseEtat.style.display= 'none';
				document.forms[0].boutonVoirEtat.style.display= 'none';
			}
			else 
			if (type=='6'){
				//montre etat
				document.forms[0].recompenseEtat.style.display= 'inline';
				document.forms[0].boutonVoirEtat.style.display= 'inline';
				//cache reste
				document.forms[0].recompenseSort.style.display= 'none';
				document.forms[0].boutonVoirSort.style.display= 'none';
				document.forms[0].recompenseMontant.style.display='none';
				document.forms[0].recompenseOBJ.style.display= 'none';
				document.forms[0].boutonVoirOBJ.style.display= 'none';
				document.forms[0].recompenseComp.style.display= 'none';
				//document.forms[0].boutonVoirComp.style.display= 'none';
			}
		}	

		function ajouterecompenses(){
			var recompense=null;
			var recompenseV=null;
			var type_recompense = document.forms[0].type_recompense.options[document.forms[0].type_recompense.selectedIndex].value;
			var recompenseOBJ = document.forms[0].recompenseOBJ.options[document.forms[0].recompenseOBJ.selectedIndex].value;
			var recompenseSort = document.forms[0].recompenseSort.options[document.forms[0].recompenseSort.selectedIndex].value;
			var recompenseEtat = document.forms[0].recompenseEtat.options[document.forms[0].recompenseEtat.selectedIndex].value;
			var recompenseMontant = document.forms[0].recompenseMontant.value;
			var recompenseComp= document.forms[0].recompenseComp.value;

			if (type_recompense==1) {
					if (recompenseMontant=='')
						alert ('Pas de quantité d\'XP indiquée comme récompense');						
					else {
						recompenseV = recompenseMontant;
						recompenseT = recompenseMontant;
					}				
			}
			else
			if (type_recompense==2) {
					if (recompenseMontant=='')
						alert ('Pas de quantité de PO indiquée comme récompense');						
					else {
						recompenseV = recompenseMontant;
						recompenseT = recompenseMontant;
					}				
			}
			else 
			if (type_recompense==3) {
					if (recompenseOBJ=='')
						alert ('Pas d\'objet indiqué comme récompense');						
					else {
						recompenseV = recompenseOBJ;
						recompenseT = document.forms[0].recompenseOBJ.options[document.forms[0].recompenseOBJ.selectedIndex].text;
					}	
			}	
			else if (type_recompense==4) {
					if (recompenseSort=='')
						alert ('Pas de sort indiqué comme récompense');	
					else {
						recompenseV = recompenseSort;	
						recompenseT = document.forms[0].recompenseSort.options[document.forms[0].recompenseSort.selectedIndex].text;
					}
			}	
			else 
			if (type_recompense==5) {
					if (recompenseComp=='')
						alert ('Pas de compétence indiquée comme récompense');						
					else {
						recompenseV = recompenseComp;
						recompenseT = document.forms[0].recompenseComp.options[document.forms[0].recompenseComp.selectedIndex].text;
					}				
			}
			else 
			if (type_recompense==6) {
					if (recompenseEtat=='')
						alert ('Pas d\'etat temp indiqué comme récompense');						
					else {
						recompenseV = recompenseEtat;
						recompenseT = document.forms[0].recompenseEtat.options[document.forms[0].recompenseEtat.selectedIndex].text;
					}				
			}
			if (recompenseV != null) {
				chainetempRecompense2 = chainetempRecompense2 + type_recompense+'|'+recompenseV+';';
				document.forms[0].ListAddRecompense.options.length = document.forms[0].ListAddRecompense.options.length +1;
				document.forms[0].ListAddRecompense.options[(document.forms[0].ListAddRecompense.options.length -1)].text = document.forms[0].type_recompense.options[document.forms[0].type_recompense.selectedIndex].text+' '+recompenseT;
				//alert(chainetemp);
				document.forms[0].chaineRecompenses2.value = chainetempRecompense2;
						
			}
		}
	
		function RAZ1(){
			document.forms[0].ListAddRecompense.options.length = 0;
			document.forms[0].chaineRecompenses2.value = '';
			chainetempRecompense2= '';
		}
	</script>";

	
	$template_main .="<script type='text/javascript'>
		selectionneRecompense();	
	</script>";		
	
	
	
      	$template_main .="<script type='text/javascript'>
		var chainetempPunition2 = '';

		function selectionnePunition(){
			var type = document.forms[0].type_punition.options[document.forms[0].type_punition.selectedIndex].value;
			if (type=='1'|| type=='2') { 
				//affiche montant
				document.forms[0].punitionMontant.style.display= 'inline';
				//cache reste
				document.forms[0].punitionSort.style.display= 'none';
				document.forms[0].boutonVoirPunitionSort.style.display= 'none';
				document.forms[0].punitionOBJ.style.display= 'none';
				document.forms[0].boutonVoirPunitionOBJ.style.display= 'none';
				document.forms[0].punitionComp.style.display= 'none';
				//document.forms[0].boutonVoirPunitionComp.style.display= 'none';
				document.forms[0].punitionEtat.style.display= 'none';
				document.forms[0].boutonVoirPunitionEtat.style.display= 'none';
			}
			else 
			if (type=='4'){
				//montre sort
				document.forms[0].punitionSort.style.display= 'inline';
				document.forms[0].boutonVoirPunitionSort.style.display= 'inline';
				//cache reste
				document.forms[0].punitionOBJ.style.display= 'none';
				document.forms[0].boutonVoirPunitionOBJ.style.display= 'none';
				document.forms[0].punitionMontant.style.display='none';
				document.forms[0].punitionComp.style.display= 'none';
				//document.forms[0].boutonVoirPunitionComp.style.display= 'none';
				document.forms[0].punitionEtat.style.display= 'none';
				document.forms[0].boutonVoirPunitionEtat.style.display= 'none';
			}
			else 
			if (type=='3'){
				//montre obj
				document.forms[0].punitionOBJ.style.display= 'inline';
				document.forms[0].boutonVoirPunitionOBJ.style.display= 'inline';
				//cache reste
				document.forms[0].punitionSort.style.display= 'none';
				document.forms[0].boutonVoirPunitionSort.style.display= 'none';
				document.forms[0].punitionMontant.style.display='none';
				document.forms[0].punitionComp.style.display= 'none';
				//document.forms[0].boutonVoirPunitionComp.style.display= 'none';
				document.forms[0].punitionEtat.style.display= 'none';
				document.forms[0].boutonVoirPunitionEtat.style.display= 'none';
			}
			else 
			if (type=='5'){
				//montre competence
				document.forms[0].punitionComp.style.display= 'inline';
				//document.forms[0].boutonVoirPunitionComp.style.display= 'inline';
				//cache reste
				document.forms[0].punitionSort.style.display= 'none';
				document.forms[0].boutonVoirPunitionSort.style.display= 'none';
				document.forms[0].punitionMontant.style.display='none';
				document.forms[0].punitionOBJ.style.display= 'none';
				document.forms[0].boutonVoirPunitionOBJ.style.display= 'none';
				document.forms[0].punitionEtat.style.display= 'none';
				document.forms[0].boutonVoirPunitionEtat.style.display= 'none';
			}
			else 
			if (type=='6'){
				//montre etat
				document.forms[0].punitionEtat.style.display= 'inline';
				document.forms[0].boutonVoirPunitionEtat.style.display= 'inline';
				//cache reste
				document.forms[0].punitionSort.style.display= 'none';
				document.forms[0].boutonVoirPunitionSort.style.display= 'none';
				document.forms[0].punitionMontant.style.display='none';
				document.forms[0].punitionOBJ.style.display= 'none';
				document.forms[0].boutonVoirPunitionOBJ.style.display= 'none';
				document.forms[0].punitionComp.style.display= 'none';
				//document.forms[0].boutonVoirPunitionComp.style.display= 'none';
			}
		}	

		function ajoutepunitions(){
			var punition=null;
			var punitionV=null;
			var type_punition = document.forms[0].type_punition.options[document.forms[0].type_punition.selectedIndex].value;
			var punitionOBJ = document.forms[0].punitionOBJ.options[document.forms[0].punitionOBJ.selectedIndex].value;
			var punitionSort = document.forms[0].punitionSort.options[document.forms[0].punitionSort.selectedIndex].value;
			var punitionEtat = document.forms[0].punitionEtat.options[document.forms[0].punitionEtat.selectedIndex].value;
			var punitionMontant = document.forms[0].punitionMontant.value;
			var punitionComp= document.forms[0].punitionComp.value;

			if (type_punition==1) {
					if (punitionMontant=='')
						alert ('Pas de quantité d\'XP indiquée comme punition');						
					else {
						punitionV = punitionMontant;
						punitionT = punitionMontant;
					}				
			}
			else
			if (type_punition==2) {
					if (punitionMontant=='')
						alert ('Pas de quantité de PO indiquée comme punition');						
					else {
						punitionV = punitionMontant;
						punitionT = punitionMontant;
					}				
			}
			else 
			if (type_punition==3) {
					if (punitionOBJ=='')
						alert ('Pas d\'objet indiqué comme punition');						
					else {
						punitionV = punitionOBJ;
						punitionT = document.forms[0].punitionOBJ.options[document.forms[0].punitionOBJ.selectedIndex].text;
					}	
			}	
			else if (type_punition==4) {
					if (punitionSort=='')
						alert ('Pas de sort indiqué comme punition');	
					else {
						punitionV = punitionSort;	
						punitionT = document.forms[0].punitionSort.options[document.forms[0].punitionSort.selectedIndex].text;
					}
			}	
			else 
			if (type_punition==5) {
					if (punitionComp=='')
						alert ('Pas de compétence indiquée comme punition');						
					else {
						punitionV = punitionComp;
						punitionT = document.forms[0].punitionComp.options[document.forms[0].punitionComp.selectedIndex].text;
					}				
			}
			else 
			if (type_punition==6) {
					if (punitionEtat=='')
						alert ('Pas d\'etat temp indiqué comme punition');						
					else {
						punitionV = punitionEtat;
						punitionT = document.forms[0].punitionEtat.options[document.forms[0].punitionEtat.selectedIndex].text;
					}				
			}
			if (punitionV != null) {
				chainetempPunition2 = chainetempPunition2 + type_punition+'|'+punitionV+';';
				document.forms[0].ListAddPunition.options.length = document.forms[0].ListAddPunition.options.length +1;
				document.forms[0].ListAddPunition.options[(document.forms[0].ListAddPunition.options.length -1)].text = document.forms[0].type_punition.options[document.forms[0].type_punition.selectedIndex].text+' '+punitionT;
				//alert(chainetemp);
				document.forms[0].chainePunitions2.value = chainetempPunition2;
						
			}
		}
	
		function RAZ1Punition(){
			document.forms[0].ListAddPunition.options.length = 0;
			document.forms[0].chainePunitions2.value = '';
			chainetempPunition2= '';
		}
	</script>";	
	
	$template_main .="<script type='text/javascript'>
		selectionnePunition();	
	</script>";		
?>