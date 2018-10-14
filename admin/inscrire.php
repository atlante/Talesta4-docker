<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: inscrire.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.29 $
$Date: 2010/05/15 08:54:36 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $inscrire_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape==2){
	if($MJ->aDroit($liste_flags_mj["InscrirePJ"])){
		$ok = true;
		if ($fichier1<>"") {
			if (substr($fichier1,0,4)=="http" || file_exists($fichier1)) {
				$files = array ($fichier1);
			}
			else {
				logDate("fichier '". $fichier1 . "' introuvable",E_USER_NOTICE,true);
				$ok = false;
			}	
		}
		else $files=array();
		logdate("valider =".$valider."fin valider");
		if ($valider=="ChoixNonFait") {
			$template_main .= "Vous devez choisir de valider ou refuser cette inscription";
			$ok=false;
			$etape=1;
		}	
		if ($ok) {
			if($valider=="0"){//on tej
				$message = "Dsol, mais l'inscription de votre personnage a t refuse.\n Ressayez avec une meilleure description, ou contactez les MJs.\n";
				$message .= "Pour rappel, votre description tait: " . $Desc ."\n";
				$message .= "          et votre background tait: " . $Back ."\n";
				if (isset($Commentaires) && $Commentaires<>"")				 
					$message .= "Commentaires du valideur d'inscription: ". $Commentaires;
				EnvoyerMail($MJ->email,$email,"[".NOM_JEU." - Inscription]",$message, $files);
				$SQL = "DELETE from ".NOM_TABLE_INSCRIPT_ETAT." where id_inscript= ".$id_cible;
				if ($db->sql_query($SQL)) {
					$SQL = "DELETE FROM ".NOM_TABLE_INSCRIPTION." WHERE ID = ".$id_cible;
					if ($db->sql_query($SQL))
						$MJ->OutPut("PJ ".span(ConvertAsHTML($nom),"pj")." a correctement &eacute;t&eacute; refus&eacute; &agrave; l'inscription",true);
				}		
			} else { // on prend
				$erreur="";
				$mess = "\nPour rappel, votre description est: " . $Desc ."\n";
				$mess .= "          et votre background est: " . $Back ."\n";
				if(defined("IN_FORUM")&& IN_FORUM==1) $mess .= " PS: Vous &ecirc;tes aussi inscrit avec les m&ecirc;me login/mot de passe sur le forum ";
				EnvoyerMail($MJ->email,$email,"[".NOM_JEU." - Inscription]","Votre inscription a t accepte. Vous pouvez dsormais jouer en entrant votre nom de personnage et votre mot de passe, en cliquant sur \"jouer\" dans le menu du site.\n bonne chance et bon jeu. \n \t".$mess, $files);
				if(defined("BASE_PAS"))
					$nbPAs = BASE_PAS;
				else 	$nbPAs =20;
				if(defined("BASE_PIS"))
					$nbPIs = BASE_PIS;
				else 	$nbPIs =20;
				if(defined("BASE_PVS"))
					$nbPVs = BASE_PVS;
				else 	$nbPVs =20;
				if(defined("BASE_POS"))
					$nbPOs = BASE_POS;
				else 	$nbPOs =20;
                                $SortParDefautID ="";
				$SQL = "INSERT INTO ".NOM_TABLE_REGISTRE." (nom,pass,pa,pv,po,pi,id_lieu,email,interval_remisepa,derniere_remisepa,interval_remisepi , derniere_remisepi ,lastaction,fanonlu, background) VALUES ('".ConvertAsHTML($nom)."','".ConvertAsHTML($pass)."','$nbPAs','$nbPVs','$nbPOs','$nbPIs','1','".ConvertAsHTML($email)."','".INTERVAL_REMISEPA."','".(time()-(INTERVAL_REMISEPA*3600))."','".INTERVAL_REMISEPI."','".(time()-(INTERVAL_REMISEPI*3600))."','".time()."','0', '".ConvertAsHTML($Back)."')";
				if($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
					$result_id =$db->sql_nextid();
					$id_perso = $result_id;
                                        $id_lieuReel = "";
					$SQLType = "select T1.id_etattemp,T3.nomtype, T2.id_lieudepart, T2.objetsfournis, T2.sortsfournis from ".NOM_TABLE_INSCRIPT_ETAT." T1, ".NOM_TABLE_ETATTEMPNOM." T2, ".NOM_TABLE_TYPEETAT." T3 where T3.id_typeetattemp = T2.id_typeetattemp  and T1.id_etattemp = T2.id_etattemp and T1.id_inscript= $id_cible";
				        $resultType = $db->sql_query($SQLType);
					while($erreur=="" && $rowType = $db->sql_fetchrow($resultType)) {
						$id_etat="id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
						if ($rowType["id_lieudepart"]>1)
						        $id_lieuReel = $rowType["id_lieudepart"];
						if (${$id_etat}!='') {
							$SQL = "INSERT INTO ".NOM_TABLE_PERSOETATTEMP." (id_perso,id_etattemp,fin) VALUES (".$id_perso.",".${$id_etat}.",-1)";
							$result=$db->sql_query($SQL);
							if (!$result) 
								$erreur.=$db->erreur;
							$i++;
						}

        					if($erreur=="") {
                                                	$tmp=explode(";",$rowType["objetsfournis"]);
                                                	$obj=0;
                                                	while ($erreur=="" && $tmp[$obj]) {	
                						$Objet = new Objet($tmp[$obj]);
						                if ($Objet!=false) {                						
                        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions, equipe) VALUES ('".$result_id."',$tmp[$obj],'".$Objet->durabilite."','".$Objet->munitions."',0)";
                        						$result=$db->sql_query($SQL);
                							if (!$result) 
                								$erreur.=$db->erreur;
                                                                }
                                                		$obj++;		
                                                        }
                                                }
                                                
        					if($erreur=="") {
                                                	$tmp=explode(";",$rowType["sortsfournis"]);
                                                	$obj=0;
                                                	while ($erreur=="" && $tmp[$obj]) {	
                						$Sort = new Magie($tmp[$obj]);
                						if ($Sort!=false) {
                        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$result_id."',$tmp[$obj],'".$Sort->charges."')";
                        						$result=$db->sql_query($SQL);
                							if (!$result) 
                								$erreur.=$db->erreur;
                							else if ($SortParDefautID=="") {
                							        $SortParDefautID =$db->sql_nextid();
                						        }	        
        							}        
                                                		$obj++;		
                                                        }
                                                }                                                
					}
					//charge l'inventaire du PJ
					$PJ= new Joueur($id_perso, false,false,false,false, true,false, false);
                        		$i=0;
                        		$equipePoing=1;
                        		$poing = $liste_type_objs["ArmeMelee;Arts Martiaux"];
                        		$partieCorpsPoing = $poing[1];
                        		$nb_objets= count($PJ->Objets);
                        		while ($erreur=="" && $i< $nb_objets) {
                        		        if ($PJ->Objets[$i]->type=='Armure'||$PJ->Objets[$i]->type=='ArmeMelee'||$PJ->Objets[$i]->type== 'ArmeJet') {
                                		        $deja1=false;
        						if (array_key_exists ($PJ->Objets[$i]->PartieCorps,$PJ->EquipOccupe)) 
        							if ($PJ->EquipOccupe[$PJ->Objets[$i]->PartieCorps]+$PJ->Objets[$i]->QteOccupee>$PJ->Objets[$i]->QteTotale){$deja1=true;}
        							else $PJ->EquipOccupe[$PJ->Objets[$i]->PartieCorps]+=$PJ->Objets[$i]->QteOccupee;
        						else $PJ->EquipOccupe[$PJ->Objets[$i]->PartieCorps]=$PJ->Objets[$i]->QteOccupee;	
                                                        if (!$deja1) {//on peut equiper
                        					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 1 WHERE id_clef = ".$PJ->Objets[$i]->id_clef." AND id_perso = ".$id_perso;
                        					$result=$db->sql_query($SQL);   
                                                                if (!$result) 
                							$erreur.=$db->erreur;                					                                                    
                        					if ($equipePoing && $PJ->Objets[$i]->PartieCorps ==$partieCorpsPoing)
                        					        $equipePoing=0;
                                                        }                
                                                }        
                        			$i++;
                        		}
					
					if($erreur=="") {	
						$Objet = new Objet(1);
						if ($Objet!=false) {
        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions, equipe) VALUES ('".$result_id."','1','".$Objet->durabilite."','".$Objet->munitions."',$equipePoing)";
                					$result=$db->sql_query($SQL);   
                                                        if (!$result) 
                						$erreur.=$db->erreur;   						
                                                }       
                                        }                                                 
					if($erreur=="") {
						$Sort = new Magie(1);
						if ($Sort!=false) {
        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$result_id."','1','".$Sort->charges."')";
                					$result=$db->sql_query($SQL);   
                                                        if (!$result) 
                						$erreur.=$db->erreur;
                					else
        					        if ($SortParDefautID =="")
        					                $SortParDefautID =$db->sql_nextid();
                						
                                                }
                                        }        
					if($erreur=="") {
                                                if ($SortParDefautID !="") {
                		                        $SQL="update ".NOM_TABLE_REGISTRE." set sortprefere = ".$SortParDefautID;
        		                                $SQL.="  where id_perso = ".$id_perso;
                                                        $result=$db->sql_query($SQL);
        						if (!$result) 
        							$erreur.=$db->erreur;													
                                                }	
                                       }         	
                                       
					if ($erreur=="") {	
						if(defined("IN_FORUM")&& IN_FORUM==1) {
							$result=$forum->CreationMembre($nom,$pass,$email,"PJ");
							if (!$result) 
								$erreur.=$db->erreur;
						}
					}                                       
                                       if($erreur=="") { 					                
						$SQL = "INSERT INTO ".NOM_TABLE_COMP." (id_perso,id_comp,xp) VALUES ('".$result_id."','1','1')";
						if($result=$db->sql_query($SQL)) {
							$SQL = "DELETE from ".NOM_TABLE_INSCRIPT_ETAT." where id_inscript= ".$id_cible;
							if ($db->sql_query($SQL)) {
								$SQL = "DELETE FROM ".NOM_TABLE_INSCRIPTION." WHERE ID = ".$id_cible;
								if ($db->sql_query($SQL,"")) {
									//efface l'ancien FA au cas ou il existerait
									if(file_exists("../fas/pj_".$result_id.".fa"))
										if ((unlink ("../fas/pj_".$result_id.".fa"))===false)
											$template_main .= "Impossible d'effacer le fichier '../fas/pj_".$result_id.".fa'";												
									$description = str_replace("<?php","",$Desc);
									$description = str_replace("?>","",$description);
                                                        		if(! file_exists ("../pjs/descriptions/"))
                                                        			if (! mkdir("../pjs/descriptions/",0744)) {
                                                        				logDate ("impossible de crer le rep '../pjs/descriptions/'",E_USER_WARNING,1);
                                                        				$erreur=1;
                                                        			}
									if (($f = fopen("../pjs/descriptions/desc_".$result_id.".txt","w+b"))!==false) {
										if (fwrite ($f, $description)===false) {
											$template_main .= "Probleme  l'criture de '../pjs/descriptions/desc_".$result_id.".txt'";
										}
										if (fclose ($f)===false)
											$template_main .= "Probleme  la fermeture de '../pjs/descriptions/desc_".$result_id.".txt'";
									}	
									else die ("impossible d'ouvrir le fichier ../pjs/descriptions/desc_".$result_id.".txt en ecriture");	
									$MJ->OutPut("PJ ".span(ConvertAsHTML($nom),"pj")." correctement inscrit",true);
									$etape=0;
								}
							}
						}
						
					}
				}
			}	
			$MJ->OutPut($db->erreur);	
		}
		
	}	
	else $template_main .= GetMessage("droitsinsuffisants");
//	$etape=0;
}
if($etape=="1"){
	
	$SQL = "SELECT T1.* , T2.id_etattemp, T3.nom as nometat, T4.nomtype
		FROM ".NOM_TABLE_INSCRIPTION." T1
		LEFT JOIN ". NOM_TABLE_INSCRIPT_ETAT. " T2
		ON T2.id_inscript = T1.id
		left join ".NOM_TABLE_ETATTEMPNOM." T3 on T3.id_etattemp = T2.id_etattemp
		left join  ".NOM_TABLE_TYPEETAT." T4 ON T4.id_typeetattemp = T3.id_typeetattemp
		WHERE T1.id =".$id_cible;
	$resultType = $db->sql_query($SQL);
	if ( $resultType) {
	
	$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";	
	$rowType = $db->sql_fetchrow($resultType);
	$row = $rowType;
	$template_main .= "<table class='detailscenter'>";
	$template_main .= "<tr><td>nom du Personnage</td><td><input type='text' name='nom' size='35' maxlength='25' value='".ConvertAsHTML($row["nom"])."' /></td></tr>";
//	$template_main .= "<tr><td>Mot de passe</td><td><input type='text' name='pass' value='".$row["pass"]."' size='35' maxlength='50' /></td></tr>";
	$template_main .= "<tr><td>email</td><td><input type='text' name='email' size='35' maxlength='80' value='".ConvertAsHTML($row["email"])."' /></td></tr>";
	$valeurVide="";
	do {
		if ($rowType['nomtype']!="") {
			$SQL ="Select T1.id_etattemp as idselect, T1.nom as labselect from ".NOM_TABLE_ETATTEMPNOM." T1, ".NOM_TABLE_TYPEETAT." T2 WHERE T1.id_typeetattemp = T2.id_typeetattemp AND T2.nomtype  = '".$rowType['nomtype']."' and utilisableinscription=1 ORDER BY T1.id_etattemp";
			$id_etat = "id_".preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
			$var = faitSelect($id_etat,$SQL,"", $rowType["id_etattemp"]);
			if ($var[0]>0) {		
				$template_main .= "<tr><td>".$rowType['nomtype']."</td>";
				$template_main .= "<td>";
				$template_main .= $var[1];	
				$template_main .= "</td></tr>";
			}
			else $valeurVide.="<input type='hidden' name='$id_etat' value='' />";
		}
	}	
	while($rowType = $db->sql_fetchrow($resultType));

	$template_main .= "<tr><td>description</td>";
	$template_main .= "<td><textarea name='Desc' cols='40' rows='10'>".$row["description"]."</textarea></td></tr>";
	$template_main .= "<tr><td>BackGround</td>";
	$template_main .= "<td><textarea name='Back' cols='40' rows='10'>".$row["background"]."</textarea></td></tr>";

	$template_main .= "<tr><td>Valider ? : </td><td><select name='valider'><option value='ChoixNonFait'>&nbsp;</option><option value='0'>Non</option><option value='1'>Oui</option></select></td></tr>";
	$template_main .= "<tr><td>Commentaires envoy&eacute;s au joueur en cas de refus: </td><td><textarea name='Commentaires' cols='40' rows='10'></textarea></td></tr>";
	$template_main .= "<tr><td>Fichier  envoyer en attachement du mail (Ex: Fichier de regles ou fichier de bienvenue ... Attention, il faut donner le chemin complet depuis le rpertoire admin) : </td><td><input type='text' name='fichier1' size='50' value='' /></td></tr>";
	$template_main .= "</table><br />";
	$template_main .= "<input type='hidden' name='pass' value='".$row["pass"]."' />";
	$template_main .= "<div class ='centerSimple'><br />".BOUTON_ENVOYER;
	$template_main .= "</div><input type='hidden' name='etape' value='2' />";
	$template_main .= "<input type='hidden' name='id_cible' value='".$id_cible."' />";
	$template_main .= $valeurVide;
	$template_main .= "</form>";
	}
	else $template_main .= $db->erreur;
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "Select T1.id as idselect, T1.nom as labselect from ".NOM_TABLE_INSCRIPTION." T1 ORDER BY T1.nom";
	$var=faitSelect("id_cible",$SQL,"",-1);
	if ($var[0]>0) {
		$template_main .= "Quelle inscription voulez vous voir ?<br />";
		$template_main .= $var[1];	
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= "Aucune nouvelle inscription en attente. <br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>