<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: modifier_info_pj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.28 $
$Date: 2010/05/15 08:53:54 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}

if(!isset($pnj)){$pnj=0;}

if ($pnj==0 || $pnj==1)
	$titrepage = $mod_info_pj;
else 
if ($pnj==2)
  $titrepage = $mod_info_bestiaire;
	
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$liste_champs=array(
		"nom","pa","pv","po","banque","pi","id_lieu","email","interval_remisepa","interval_remisepi","pnj",
		"phrasepreferee","sortprefere","actionsurprise","relation","reaction", "dissimule","background",
		"commentaires_mj","pourcentage_reaction","pass"
	);


if(!isset($etape)){$etape=0;}


if($etape==2){
	if(($pnj<>2 && $MJ->aDroit($liste_flags_mj["ModifierInfoPJ"]))||($pnj==2 && $MJ->aDroit($liste_flags_mj["ModifierBestiaire"]))){
	        if ($pnj==2)
	                $id_lieu="";
		$erreur="";
		if (isset($pass)) {
        if ($pass=="")
            unset($pass);
        else
        $pass=md5($pass);  
    }

		if ($pnj==1) {
			if( (!isset($email)) || (!verif_email($email))){
				$erreur .= "Adresse Mail incorrect <br />";
			}
			
								
			if ($ancienemail<>$email)
				if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->emailvalide($email)===false))
					$erreur .= "Adresse Mail incorrecte ou bannie par le forum <br />";

			if ($anciennom<>$nom) {		
				if (defined("IN_FORUM")&& IN_FORUM==1 && (in_array (strtoupper($nom), $forum->nomsReservesForum))) {
		   			$erreur .= "nom déjà utilisé pour le forum <br />";
				}
		
				if(defined("IN_FORUM")&& IN_FORUM==1 && ($forum->uservalide($nom)===false))
					$erreur .= "Nom interdit par le forum <br />";
			}
		}
		$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where critereinscription>=1";
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
			
		if ($erreur=="") {
			
			$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET ";
			$nbchamps = count($liste_champs);
			for($i=0;$i<$nbchamps;$i++){
					if (isset($$liste_champs[$i]))
						$SQL.=$liste_champs[$i]."= '".ConvertAsHTML($$liste_champs[$i])."',";
			}
			$SQL = substr($SQL,0,strlen($SQL)-1);
			$SQL .= " WHERE id_perso = ".$id_cible;
			$result=$db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU);
	        
			$nb_etattempSelectionnes = count($etattemp);
			$i=0;
			while ($i<$nb_etattempSelectionnes && $result) {
				$nomTypeVariabilise =array_pop($etattemp);
				$id_etat="id_".$nomTypeVariabilise;
				$id_old_etat="old".$nomTypeVariabilise;
				if (isset(${$id_old_etat})) {
					if (${$id_etat}!=${$id_old_etat}) {
						if (${$id_etat}=='') {
							$SQL= "delete from ".NOM_TABLE_PERSOETATTEMP." where id_etattemp =${$id_old_etat} and id_perso =$id_cible";
							$result=$db->sql_query($SQL);
						}
						else {
							$SQL= "update ".NOM_TABLE_PERSOETATTEMP." set id_etattemp = ${$id_etat} where id_perso= $id_cible AND id_etattemp = ${$id_old_etat}";
							$result=$db->sql_query($SQL);
						}	
					}
				}	
				else {
					if (${$id_etat}!='') {
						$SQL= "insert into ".NOM_TABLE_PERSOETATTEMP." (id_etattemp ,id_perso,fin)  values (${$id_etat},$id_cible,-1)";
						$result=$db->sql_query($SQL);
					}	
				}	
				$i++;
			}
	
			if ($result)
				if(defined("IN_FORUM")&& IN_FORUM==1 && $pnj==1)  {
					$result=$forum->MAJuser($nom, $email,$imageforum,$ancienne_image,$anciennom,"");
				}


			if ($result) {
				if ($anciendissimule<>$dissimule) {
					if ($dissimule) {
						$toto = array_keys($liste_type_objetSecret);		
						$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$id_cible.",'".ConvertAsHTML($id_lieu)."',". $toto[2].", '".ConvertAsHTML($nom)."')";
						$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
					}	
					else {
						global $liste_type_objetSecret;
						$toto = array_keys($liste_type_objetSecret);
						$SQL = "select id from ".NOM_TABLE_ENTITECACHEE ." where    id_entite = ".$id_cible .
						" and id_lieu = ".$id_lieu." and type = ".$toto[2];
						$result=$db->sql_query($SQL);
						if ($db->sql_numrows($result)>0) {
							$row = $db->sql_fetchrow($result);
							$SQL="delete from ".NOM_TABLE_ENTITECACHEECONNUEDE ." where id_entitecachee =" . $row["id"];
							if($result=$db->sql_query($SQL)) {
								$SQL="delete from ".NOM_TABLE_ENTITECACHEE ." where id=" . $row["id"];
								$result = $db->sql_query($SQL,"",END_TRANSACTION_JEU);
							}	
						}
					}
				}					
			}	
			
			if ($result) {
				$nom_fichier="../pjs/descriptions/desc_".$id_cible.".txt";			
				$description = str_replace("<?php","",$description);
				$description = str_replace("?>","",$description);
				if (($f = fopen($nom_fichier,"w+b"))!==false) {
					if (fwrite($f,$description)===false) {
						$template_main .= "Probleme à l'écriture de '".$nom_fichier."'";
					}
					else 
					if (fclose ($f)===false)
						$template_main .= "Probleme à la fermeture de '".$nom_fichier."'";
				}	
				else die ("impossible d'ouvrir le fichier '".$nom_fichier."' en ecriture");					
				$MJ->OutPut("Informations de ".span(ConvertAsHTML($nom),"pj")." correctement modifi&eacute;es",true);
				$etape=0;
			}
			else {
				$template_main .= $MJ->OutPut($db->erreur);
				$etape=1;
				$id_cible=$id_cible.$sep.$pnj;
			}	
		}
		else {
			$MJ->OutPut($erreur,true);
			$etape=1;
			$id_cible=$id_cible.$sep.$pnj;
		}		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
	
}
if($etape=="1"){
	$pos = strpos($id_cible, $sep);
	$pnj=substr($id_cible, $pos+strlen($sep)); 
	$id_cible=substr($id_cible, 0,$pos);
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	if(defined("IN_FORUM")&& IN_FORUM==1  && $forum->champimage!=null && 
	   ($pnj==0 || ($pnj==1 && defined("CREE_MEMBRE_PNJ") && CREE_MEMBRE_PNJ==1)))  
		$SQL = "SELECT p.* , T2.id_etattemp, T3.nom as nometat, T4.nomtype, u.".$forum->champimage.",u.".$forum->champtypeimage."
			FROM ".$forum->nomtableUsers." u,".NOM_TABLE_REGISTRE." p
			LEFT JOIN 
			". NOM_TABLE_PERSOETATTEMP. " T2
			 ON T2.id_perso = p.id_perso 
			left join ".NOM_TABLE_ETATTEMPNOM." T3 on T3.id_etattemp = T2.id_etattemp
			left join  ".NOM_TABLE_TYPEETAT." T4 ON T4.id_typeetattemp = T3.id_typeetattemp
			WHERE u.username = p.nom and p.id_perso  =".$id_cible;
	else	$SQL = "SELECT p.* , T2.id_etattemp, T3.nom as nometat, T4.nomtype
			FROM ".NOM_TABLE_REGISTRE." p
			LEFT JOIN 
			". NOM_TABLE_PERSOETATTEMP. " T2
			 ON T2.id_perso = p.id_perso 
			left join ".NOM_TABLE_ETATTEMPNOM." T3 on T3.id_etattemp = T2.id_etattemp
			left join  ".NOM_TABLE_TYPEETAT." T4 ON T4.id_typeetattemp = T3.id_typeetattemp
			WHERE p.id_perso  =".$id_cible;
	$resultType = $db->sql_query($SQL);
	if ($resultType!==FALSE) {
	$rowType = $db->sql_fetchrow($resultType);

        	if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage!=null &&
        	  ($pnj==0 || ($pnj==1 && defined("CREE_MEMBRE_PNJ") && CREE_MEMBRE_PNJ==1)))  {
        		$imagetype = $rowType[$forum->champtypeimage];
        		$imageforum = $rowType[$forum->champimage];
        		$template_main .= "<input type='hidden' name='ancienne_image' value='".$imageforum ."' />";
        	}	
        	else {
        		$imagetype = "";
        		$imageforum = "";
        		$template_main .= "<input type='hidden' name='ancienne_image' value='".$imageforum ."' />";
        	}
	$row = $rowType;

	$nbchamps = count($liste_champs);
	for($i=0;$i<$nbchamps;$i++){
		$$liste_champs[$i] = $row[$liste_champs[$i]];
	}
	$oldValue="";
	do {
		$nomTypeVariabilise =preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
		$id_etat = "id_".$nomTypeVariabilise;
		${$id_etat}=$rowType["id_etattemp"];
		$oldValue.= "<input type='hidden' name='old".$nomTypeVariabilise."' value='".${$id_etat}."' />";

	}
	while(	$rowType = $db->sql_fetchrow($resultType));

	$nom_fichier = "../pjs/descriptions/desc_".$id_cible.".txt";
	if(file_exists($nom_fichier)){
		$content_array = file($nom_fichier);
		$content = implode("", $content_array);
		$description= stripslashes($content);
	}else $description="";		
	include('forms/infospj.form.'.$phpExtJeu);

	$template_main .= "<br /><input type='submit' value='Envoyer' onclick=\"return confirm('Etes vous sur de vouloir modifier ces informations ?')\" />";
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= "<input type='hidden' name='anciennom' value='".$nom."' />";
	$template_main .= "<input type='hidden' name='ancienemail' value='".$email."' />";
	$template_main .= $oldValue;
	$template_main .= "<input type='hidden' name='anciendissimule' value='".$dissimule."' />";

	$template_main .= "</form>";
	$template_main .= "</div>";
	}
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";	
	$template_main .= "Les infos de quel PJ voulez vous modifier ?<br />";
	$SQL = "Select concat(concat(T1.id_perso,'$sep'),T1.pnj) as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ";
	if ($pnj==2)
		$SQL .=" where T1.pnj=2 ";
        else $SQL .=" where T1.pnj<>2 ";		
	$SQL .=" ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>