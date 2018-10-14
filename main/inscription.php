<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: inscription.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.27 $
$Date: 2010/05/15 08:41:50 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $inscription;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$err_inscriptionsfermees = "Les inscriptions sont fermées pour le moment.";
if(!isset($etape)) {
	if ((!defined("INSCRIPTIONS_OUVERTES")) || INSCRIPTIONS_OUVERTES){
		if ((!defined("COUNT_QCM")) || (COUNT_QCM ==0)) {
	 		$etape="1";
	 		$nbReponsesADonner=0;
	 	}	
		else {
			$SQL    = "SELECT COUNT(id_question) as c FROM ".NOM_TABLE_QCM;
			$result = $db->sql_query($SQL);	
			$row = $db->sql_fetchrow($result);
			$nbQuestions = $row["c"];
			$nbReponsesADonner = min ($nbQuestions, COUNT_QCM);
			if ($nbReponsesADonner==0) {
				$etape="1";
	 			$nbReponsesADonner=0;	 			
	 		}	
		}	
	}		
	else  	{
		//pour ne plus rien faire de ce qu'il y a apres
		$etape="5";
	
		$template_main .= "<span class='c0'>".$err_inscriptionsfermees."</span><br />";
	}	
}
	
if(isset($etape)){
	
	if ($etape=="2") {
		$erreur="";

		$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where critereinscription>=1";
	        $resultType = $db->sql_query($SQLType);
	        $etattemp = array ();
		while(	$rowType = $db->sql_fetchrow($resultType)) {
			$id_etat="id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
			if (${$id_etat}!='')
				array_push($etattemp, ${$id_etat});
			else if ($rowType['critereinscription']==2) {
				$erreur.= "Il manque l'info de type: ".$rowType['nomtype']. "<br />";
			}	
		}

		if( (!isset($nom))|| $nom=="" ){
			$erreur .= "Nom vide <br />";
		}
		if( (!isset($Desc))|| $Desc=="" ){
			$erreur .= "Description vide <br />";
		}
		if( (!isset($Back))|| $Back=="" ){
			$erreur .= "Background vide <br />";
		}
	
		if( (!isset($pass1))|| $pass1=="" || (!isset($pass2)) || ($pass1 != $pass2) ){
			$erreur .= "Mot de passe incorrect, ou diff&eacute;rent entre les 2 saisies <br />";
		}
		if( (!isset($email)) || (!verif_email($email))){
			$erreur .= "Adresse Mail incorrecte <br />";
		}
		
		if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->emailvalide($email)===false))
			$erreur .= "Adresse Mail incorrecte ou bannie par le forum <br />";
			
		if(defined("IN_FORUM")&& IN_FORUM==1 && (in_array (strtoupper($nom), $forum->nomsReservesForum))) 
			$erreur .= "Nom déjà utilisé pour le forum <br />";

		if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->uservalide($nom)===false))
			$erreur .= "Nom interdit par le forum <br />";

		$nom = ConvertAsHTML($nom);
		logDate("nom".$nom);		
		$SQL="select nom from ".NOM_TABLE_MJ." where nom = '". $nom."'";
		$recherche1 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_PERSO." where nom = '". $nom."'";
		$recherche2 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_INSCRIPTION." where nom = '". $nom."'";
		$recherche3 = $db->sql_query($SQL);
		
		if(($db->sql_numrows($recherche3)>0) ||($db->sql_numrows($recherche1)>0) || ($db->sql_numrows($recherche2)>0)) {
			$erreur .= "nom d&eacute;j&agrave; existant <br />";	
	
		}	
	
		if($erreur==""){
			$Desc = ConvertAsHTML($Desc);
			$Back = ConvertAsHTML($Back);
			$pass1 = ConvertAsHTML($pass1);
			$email = ConvertAsHTML($email);

			$SQL = "INSERT INTO ".NOM_TABLE_INSCRIPTION." (nom,pass,email,description, background) VALUES ('".$nom."','".md5($pass1)."','".$email."','".$Desc."','".$Back."')";
			//fin Modif Hixcks
			if ($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
				$id_inscription = $db->sql_nextid();
				$nb_etattempSelectionnes = count($etattemp);
				logdate ("nb_etattempSelectionnes".$nb_etattempSelectionnes);
				$i=0;
				$ok=true;
				while ($i<$nb_etattempSelectionnes && $ok) {
					$SQL = "INSERT INTO ".NOM_TABLE_INSCRIPT_ETAT." (id_inscript,id_etattemp) VALUES (".$id_inscription.",".array_pop($etattemp).")";
					$ok =$db->sql_query($SQL); 					
					$i++;
				}	
				if ($ok) {
					$template_main .= "Vous serez inscrits sous peu, des que les MJs auront trait&eacute; votre demande.<br />";
					$template_main .= "Vous pourrez alors configurer vos options dans la page \"modifier Infos\". ";
					$template_main .= "En attendant, vous pouvez prendre connaissance des r&egrave;gles dans la page correspondante ! <br />N'h&eacute;sitez pas non plus &agrave; parcourir tout le site et &agrave; utiliser tous les outils mis &agrave; votre disposition.<br />";
				}
				else $template_main .= $db->erreur;
			}
			else $template_main .= $db->erreur;
			
		}
		else $etape="1";
	}


	if($etape=="1" && ((!defined("INSCRIPTIONS_OUVERTES")) || INSCRIPTIONS_OUVERTES)){
		if(isset($erreur))
			$ok_QCM = true;
		else {	
			    $ok_QCM = true; // Vérifier si l'Internaute a répondu à toutes les questions
			    for($idx = 0; ($idx < $nbReponsesADonner) && $ok_QCM; $idx++)
			      	//$ok_QCM = $_POST['reponse_QCM'.$idx] > 0;		      	
			    if ($ok_QCM && COUNT_QCM >0)   {
			    	$SQL = 'SELECT * FROM '.NOM_TABLE_QCM.' WHERE id_question IN(';
			      	for($idxID = 0; $idxID < $nbReponsesADonner; $idxID++) {
			        	if ($idxID) $SQL .= ', ';
			        	$SQL .= $_POST['ID_QCM'.$idxID];
			      	}
			      	$SQL .= ')';
			      	$result = $db->sql_query($SQL);
				
		        	for($idx = 0; $idx < $nbReponsesADonner; $idx++) {
		        		$row = $db->sql_fetchrow($result);
		          		$repList[$row['id_question']] = array('question' => $row['question'],
		                                                                       'Rep1'     => $row['reponse1'],
		                                                                       'Rep2'     => $row['reponse2'],
		                                                                       'Rep3'     => $row['reponse3'],
		                                                                       'Rep4'     => $row['reponse4'],
		                                                                       'bonne'    => $row['bonne']);
		                }                                                       
			        for($idx = 0; $idx < $nbReponsesADonner; $idx++)
			        {
			          ($repList[$_POST['ID_QCM'.$idx]]['bonne'] == $_POST['reponse_QCM'.$idx]) ? $resultQCM[$idx] = 1 : $resultQCM[$idx] = 0;
			          if ($ok_QCM) $ok_QCM = $resultQCM[$idx];
			        }
			}	
		}
	        if ($ok_QCM) {
			if (!INSCRIPTIONS_OUVERTES)
				$template_main .= "<span class='c0'>".$err_inscriptionsfermees."</span><br />";

			if(!isset($nom)){$nom='';}
			if(!isset($email)){$email='';}
			if(!isset($Back)){$Back='';}
		
			if(!isset($Desc)){$Desc='';}
			$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
			$template_main .= "S'inscrire<br />";
			//if(isset($erreur)){$template_main .= "<font color='red'>".$erreur."</font><br />";}
			if(isset($erreur)){$template_main .= "<span class='c0'>".$erreur."</span><br />";}
			$valeurVide="";
			$template_main .= "<table class='detailscenter'>";
			$template_main .= "<tr><td>Nom du Personnage</td><td><input type='text' name='nom' size='35' maxlength='25' value='".$nom."' /></td></tr>";
			$template_main .= "<tr><td>Mot de Passe</td><td><input type='password' name='pass1' size='35' maxlength='50' /></td></tr>";
			$template_main .= "<tr><td>Retappez votre Mot de Passe</td><td><input type='password' name='pass2' size='35' maxlength='50' /></td></tr>";
			$template_main .= "<tr><td>Email</td><td><input type='text' name='email' size='35' maxlength='80' value='".$email."' /></td></tr>";

			$SQLType = "select id_typeetattemp, nomtype,critereinscription from ".NOM_TABLE_TYPEETAT." where critereinscription>=1";
		          $resultType = $db->sql_query($SQLType);
			   while(	$rowType = $db->sql_fetchrow($resultType)) {
				$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' and utilisableinscription=1 ORDER BY T1.id_etattemp";
				$id_etat = "id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
			   	if(!isset(${$id_etat})){${$id_etat}='';}
				if ($rowType['critereinscription']==2)//obligatoire on n'ajoute pas de ligne vide
					$valeurNulle=array();
				else 
				if ($rowType['critereinscription']==1)
					$valeurNulle=array("&nbsp;");	//facultatif on ajoute une ligne vide
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

			$template_main .= "<tr><td colspan='2'>Description<b>(visible par les autres PJS. environ 20 lignes. OBLIGATOIRE)</b></td></tr>";
			$template_main .= "<tr><td colspan='2'><textarea name='Desc' cols='40' rows='10'>".$Desc."</textarea></td></tr>";
	
			$template_main .= "<tr><td colspan='2'>Background<b>(invisible par les autres PJS. environ 20 lignes. OBLIGATOIRE)</b></td></tr>";
			$template_main .= "<tr><td colspan='2'><textarea name='Back' cols='40' rows='10'>".$Back."</textarea></td></tr>";
		
			$template_main .= "<tr><td colspan='2'><input type='submit' value='Inscrire' /></td></tr>";
			$template_main .= "</table>";
			$template_main .= $valeurVide;
			$template_main .= "<input type='hidden' name='etape' value='2' />";
			$template_main .= "</form></div>";
		}
	        else
	          unset($etape); // réafficher le QCM

	 }
  	
}

// 16/10/2004: The Darkness - QCM
if(!isset($etape)) {
	logdate("nbReponsesADonner".$nbReponsesADonner);
      	if (!isset($repList)) { // Si erreur dans QCM, reprendre les même questions
	        for($idx = 0; $idx < $nbReponsesADonner; $idx++) {
	          $SQL = "SELECT * FROM ".NOM_TABLE_QCM;
	          if ($idx > 0) {
		            $SQL .= ' WHERE id_question NOT IN(';
		            for($idxID = 0; $idxID < count($QCMList); $idxID++) {
		            	if ($idxID) $SQL .= ', ';
		              	$SQL .= $QCMList[$idxID]['ID'];
		            }
		            $SQL .= ')';
	          }
	          $SQL .= ' LIMIT '.(rand(0, $nbQuestions - $idx - 1)).', 1';
	          $result = $db->sql_query($SQL);
		  
	          if ($db->sql_numrows($result) > 0) {
		            $row = $db->sql_fetchrow($result);
		            logDate("idquetsion = ".$row['id_question']);
		            $QCMList[$idx] = array('ID'       => $row['id_question'],
		                                   'question' => $row['question'],
		                                   'Rep1'     => $row['reponse1'],
		                                   'Rep2'     => $row['reponse2'],
		                                   'Rep3'     => $row['reponse3'],
		                                   'Rep4'     => $row['reponse4'],
		                                   );
		  }                         
	  }
	}
  	$template_main .= '<form action="'.NOM_SCRIPT.'" method="post">'
	.'<input type="hidden" name="etape" value="1" />';
	$template_main .= "<center>";
	$template_main .= "<h3>Etape 1 : Questionnaire</h3><br />";
	$template_main .= "Ce simple questionnaire a pour but de vérifier que vous avez bien lu et compris les règles.<br /><br /><br />";
	$template_main .= "</center>";
	$template_main .= '<input type="hidden" name="nbReponsesADonner" value="'.$nbReponsesADonner.'" />';
	$template_main .= '<input type="hidden" name="nbQuestions"   value="'.$nbQuestions.'" />';
	$template_main .= '<table>';
      if (isset($repList)) $template_main .= '<center><b>Vous n\'avez pas bien répondu toutes les questions.</b></center><br/><br/>';
      for($idx = 0; $idx < $nbReponsesADonner; $idx++) {
	        if (!isset($repList))  {
			//Pour modifier l'aparence du questionnaire, c'est ci-dessous
		          $template_main .= '<tr><td colspan="5" align="left"><input type="hidden" name="ID_QCM'.$idx.'" value="'.$QCMList[$idx]['ID'].'" /><img src="../templates/'.urlencode($template_name).'/images/icone.gif" />'.$QCMList[$idx]['question'].'</td></tr><tr>'
		          .'<td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'
		          .'<input value="1" type="radio" name="reponse_QCM'.$idx.'" />'.$QCMList[$idx]['Rep1'].
		          '</td><td>'
		          .'<input value="2" type="radio" name="reponse_QCM'.$idx.'" />'.$QCMList[$idx]['Rep2'].
		          '</td><td>'
		          .'<input value="3" type="radio" name="reponse_QCM'.$idx.'" />'.$QCMList[$idx]['Rep3'].
		          '</td><td>'
		          .'<input value="4" type="radio" name="reponse_QCM'.$idx.'" />'.$QCMList[$idx]['Rep4'].
		          '&nbsp;<br /></td></tr><tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5">&nbsp;</td></tr>'."\n";
		}
	        else // On reprend les mêmes questions et on conserve les réponses justes
	        {
			$template_main .= '<tr><td colspan="5" align="left"><input type="hidden" name="ID_QCM'.$idx.'" value="'.$_POST['ID_QCM'.$idx].'" /><img src="../templates/'.urlencode($template_name).'/images/icone.gif" />'.$repList[$_POST['ID_QCM'.$idx]]['question'].'</td>'
		          .'</tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>';
		          if (($resultQCM[$idx]) && ($repList[$_POST['ID_QCM'.$idx]]['bonne'] == 1))
		            $template_main .= '<input value="1" type="radio" name="reponse_QCM'.$idx.'" checked="checked" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep1'].'';
		          else
		            $template_main .= '<input value="1" type="radio" name="reponse_QCM'.$idx.'" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep1'].'';
		          if (($resultQCM[$idx]) && ($repList[$_POST['ID_QCM'.$idx]]['bonne'] == 2))
		            $template_main .= '</td><td><input value="2" type="radio" name="reponse_QCM'.$idx.'" checked="checked" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep2'].'';
		            $template_main .= '</td><td><input value="2" type="radio" name="reponse_QCM'.$idx.'" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep2'].'';
		          if (($resultQCM[$idx]) && ($repList[$_POST['ID_QCM'.$idx]]['bonne'] == 3))
		            $template_main .= '</td><td><input value="3" type="radio" name="reponse_QCM'.$idx.'" checked="checked" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep3'].'';
		          else
		            $template_main .= '</td><td><input value="3" type="radio" name="reponse_QCM'.$idx.'" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep3'].'';
		          if (($resultQCM[$idx]) && ($repList[$_POST['ID_QCM'.$idx]]['bonne'] == 4))
		            $template_main .= '</td><td><input value="4" type="radio" name="reponse_QCM'.$idx.'" checked="checked" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep4'].'';
		          else
		            $template_main .= '</td><td><input value="4" type="radio" name="reponse_QCM'.$idx.'" />'.$repList[$_POST['ID_QCM'.$idx]]['Rep4'].'';
		          $template_main .= '</td><tr>'."\n";
		}
      }
      $template_main .= '</table><center>';
	$template_main .= '<input type="submit" value="Etape&nbsp;2&nbsp;:&nbsp;Inscription" />';
	$template_main .= '</center></form>';



}

if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>