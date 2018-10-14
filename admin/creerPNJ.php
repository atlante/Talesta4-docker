<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: creerPNJ.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.29 $
$Date: 2010/05/15 08:55:00 $

*/

require_once("../include/extension.inc");
//if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__HTTPGETPOST.PHP")) {include('../include/http_get_post.'.$phpExtJeu);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
if (!isset($pnj))
	$pnj=1;
if ($pnj==1)
	$titrepage = $creer_pnj;
else 	$titrepage = $creer_bestaire;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if (defined("IN_FORUM")&& IN_FORUM==1)
	$imagetype=$forum->image_avatar_remote;


if(!isset($etape)){$etape=0;}

if($etape==2){
	if(($pnj==1 && $MJ->aDroit($liste_flags_mj["InscrirePJ"]))||($pnj==2 && $MJ->aDroit($liste_flags_mj["CreerBestiaire"]))){
	        if ($pnj==2)
	                $id_lieu="null";
    $nom=ConvertAsHTML($nom);	                
		$SQL="select nom from ".NOM_TABLE_MJ." where nom = '". $nom."'";
		$recherche1 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_PERSO." where nom = '". $nom."'";
		$recherche2 = $db->sql_query($SQL);
		$SQL="select nom from ".NOM_TABLE_INSCRIPTION." where nom = '". $nom."'";
		$recherche3 = $db->sql_query($SQL);

		if(($db->sql_numrows($recherche3)==0) && ($db->sql_numrows($recherche1)==0) && ($db->sql_numrows($recherche2)==0)) {
			
			if(!(defined("IN_FORUM") && IN_FORUM==1 && defined("CREE_MEMBRE_PNJ") &&  CREE_MEMBRE_PNJ==1 && $pnj==1 && (($forum->uservalide($nom)===false) || (in_array (strtoupper($nom), $forum->nomsReservesForum))))) {
				$erreur="";
				// Pas de gestion de l'email pour les monstres ni du forum
				if ($pnj==1) {
					if( (!isset($email)) || (!verif_email($email))){
						$erreur .= "Adresse Mail incorrecte <br />";
					}
					if(defined("IN_FORUM")&& IN_FORUM==1 && defined("CREE_MEMBRE_PNJ") &&  CREE_MEMBRE_PNJ==1 && ($forum->emailvalide($email)===false))
						$erreur .= "Adresse Mail incorrecte ou bannie par le forum <br />";
				}
				else 
				if ($pnj==2) {
				        $email="";
				}        
				$SQLType = "select id_typeetattemp, nomtype, critereinscription from ".NOM_TABLE_TYPEETAT." where critereinscription>=1";
			        $resultType = $db->sql_query($SQLType);
			        $etattemp = array ();
				while(	$rowType = $db->sql_fetchrow($resultType)) {
					$nomTypeVariabilise=preg_replace("/[^(a-zA-Z0-9_\x7f-\xff)]/","",$rowType['nomtype']);
					$id_etat="id_".$nomTypeVariabilise;
					if (${$id_etat}!='')
						array_push($etattemp, $nomTypeVariabilise);
					else if ($rowType['critereinscription']==2) {
						$erreur.= "Il manque l'info de type: ".$rowType['nomtype']. "<br />";
					}	
				}
				
				if ($erreur=="") {		
					if (!isset($interval_remisepa) || $interval_remisepa=="")
						$interval_remisepa = INTERVAL_REMISEPA;
					if (!isset($interval_remisepi) || $interval_remisepi=="")
						$interval_remisepi = INTERVAL_REMISEPI;
					//le max pour les gros intervalles de remises (Ex: mannequin qui ne doit pas avoir de remises)
					$derniere_remisepa = max(time()-($interval_remisepa*3600),0);
					$derniere_remisepi = max(time()-($interval_remisepi*3600),0);					
					$SQL = "INSERT INTO ".NOM_TABLE_REGISTRE." (nom,pass,pa,pv,po,pi,id_lieu,email,interval_remisepa,derniere_remisepa,interval_remisepi , derniere_remisepi , lastaction,fanonlu,pnj,relation,reaction,actionsurprise,phrasepreferee, dissimule, background,commentaires_mj,pourcentage_reaction )
						 VALUES ('".ConvertAsHTML($nom)."','".md5(ConvertAsHTML($pass))."','".$pa."','".$pv."','".$po."','".$pi."',".ConvertAsHTML($id_lieu).",'".$email."','".$interval_remisepa."','".$derniere_remisepa ."','".$interval_remisepi."','".$derniere_remisepi."','".time()."','0',$pnj,'".ConvertAsHTML($relation)."','".ConvertAsHTML($reaction)."'      ,'".ConvertAsHTML($actionsurprise)."' ,'".ConvertAsHTML($phrasepreferee)."','".$dissimule."','".ConvertAsHTML($background)."','".$commentaires_mj."',$pourcentage_reaction)";
					$result=$db->sql_query($SQL,"",BEGIN_TRANSACTION_JEU);
					$SortParDefautID="";
					if (!($result)) 
						$erreur=$db->erreur;
					if ($erreur=="") {
						$result_id = $db->sql_nextid();
						$id_perso = $result_id;
						$nb_etattempSelectionnes = count($etattemp);
						$i=0;
						$result=true;
						while ($i<$nb_etattempSelectionnes && $result) {
						        $id_etattempCourant= ${"id_".array_pop($etattemp)};
							$SQL = "INSERT INTO ".NOM_TABLE_PERSOETATTEMP." (id_perso,id_etattemp,fin) VALUES (".$id_perso.",".$id_etattempCourant.",-1)";
							$result =$db->sql_query($SQL); 	
                					if (!($result)) 
                						$erreur.=$db->erreur;

                					$SQLType = "select T2.objetsfournis, T2.sortsfournis from ".NOM_TABLE_ETATTEMPNOM." T2 where T2.id_etattemp = " . $id_etattempCourant;
                				        $resultType = $db->sql_query($SQLType);
                					while($erreur=="" && $rowType = $db->sql_fetchrow($resultType)) {
                                                        	$tmp=explode(";",$rowType["objetsfournis"]);
                                                        	$obj=0;
                                                        	while ($erreur=="" && $tmp[$obj]) {	
                        						$Objet = new Objet($tmp[$obj]);
                        						if ($Objet!=false) {
                                						$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions, equipe) VALUES ('".$id_perso."',$tmp[$obj],'".$Objet->durabilite."','".$Objet->munitions."',0)";
                                						$result=$db->sql_query($SQL);
                        							if (!$result) 
                        								$erreur.=$db->erreur;
                                                                        }
                                                        		$obj++;		
                                                                }
                                                                
                        					if($erreur=="") {
                                                                	$tmp=explode(";",$rowType["sortsfournis"]);
                                                                	$obj=0;
                                                                	while ($erreur=="" && $tmp[$obj]) {	
                                						$Sort = new Magie($tmp[$obj]);
                                						if ($Sort!=false) {
                                        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$id_perso."',$tmp[$obj],'".$Sort->charges."')";
                                        						$result=$db->sql_query($SQL);
                                							if (!$result) 
                                								$erreur.=$db->erreur;
                                							else if ($SortParDefautID==""	) {
                                							        $SortParDefautID =$db->sql_nextid();
                                							}        
                                                                                }
                                                                		$obj++;		
                                                                        }
                                                                }                                                
                					}        

                						
							$i++;
						}	
					}
					if ($erreur=="") {
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
                                        }
					if ($erreur=="") {	
						$Objet = new Objet(1);
						if ($Objet!=false) {
        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions, equipe) VALUES ('".$id_perso."','1','".$Objet->durabilite."','".$Objet->munitions."',$equipePoing)";
        						$result=$db->sql_query($SQL);
        						if (!$result) 
        							$erreur.=$db->erreur;
                                                }										
					}
					if ($erreur=="") {	
						$Sort = new Magie(1);
						if ($Sort!=false) {						
        						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$id_perso."','1','".$Sort->charges."')";
        						$result=$db->sql_query($SQL);
        						if (!$result) 
        							$erreur.=$db->erreur;
        						else if ($SortParDefautID =="")
        							$SortParDefautID =$db->sql_nextid();	
                                                }
                                        }        
                                        if ($erreur=="") {
                                                if ($SortParDefautID !="") {
                		                        $SQL="update ".NOM_TABLE_REGISTRE." set sortprefere = ".$SortParDefautID;
        		                                $SQL.="  where id_perso = ".$id_perso;
                                                        $result=$db->sql_query($SQL);
        						if (!$result) 
        							$erreur.=$db->erreur;													
                                                }
					}
					if ($erreur=="") {	
						if ($dissimule) {	
							$toto = array_keys($liste_type_objetSecret);		
							$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$id_perso.",'".ConvertAsHTML($id_lieu)."',". $toto[2].",'".ConvertAsHTML($nom)."')";
							$result=$db->sql_query($SQL);
							if (!$result) 
								$erreur.=$db->erreur;
						}
					}
					if ($erreur=="") {	
						if ($pnj==1 && defined("CREE_MEMBRE_PNJ") &&  CREE_MEMBRE_PNJ==1) {
							if(defined("IN_FORUM")&& IN_FORUM==1) {
								$result=$forum->CreationMembre($nom,$pass,$email,"PJ",$imageforum);
								if (!$result) 
									$erreur.=$db->erreur;
							}
						}
					}

					if ($erreur=="") {
						$SQL = "INSERT INTO ".NOM_TABLE_COMP." (id_perso,id_comp,XP) VALUES ('".$id_perso."','1','1')";
						$result=$db->sql_query($SQL,"",END_TRANSACTION_JEU);
						if (!$result) 
							$erreur.=$db->erreur;
			
						$description = str_replace("<?php","",$description);
						$description = str_replace("?>","",$description);
					}
				}	
				if ($erreur<>"") {
					$MJ->OutPut($erreur,true);		
					$etape=-1;
				}
				else {
					//efface l'ancien FA au cas ou il existerait
					if(file_exists("../fas/pj_".$result_id.".fa"))
						if ((unlink ("../fas/pj_".$result_id.".fa"))===false)
							$template_main .= "Impossible d'effacer le fichier '../fas/pj_".$result_id.".fa'";
							
                        		if(! file_exists ("../pjs/descriptions/"))
                        			if (! mkdir("../pjs/descriptions/",0744)) {
                        				logDate ("impossible de créer le rep '../pjs/descriptions/'",E_USER_WARNING,1);
                        				$erreur=1;
                        			}							
					if(($f = fopen("../pjs/descriptions/desc_".$result_id.".txt","w+b"))!==false) {
						if (fwrite($f,$description)===false) {
							$template_main .= "Probleme à l'écriture de '../pjs/descriptions/desc_".$result_id.".txt'";
						}
						else 
						if (fclose ($f)===false)
							$template_main .= "Probleme à la fermeture de '../pjs/descriptions/desc_".$result_id.".txt'";
					}	
					else $template_main .= "impossible d'ouvrir le fichier '../pjs/descriptions/desc_".$result_id.".txt'";
					if ($pnj==1) 
						$MJ->OutPut("pnj ".span(ConvertAsHTML($nom),"pj")." correctement inscrit",true);
					else 	$MJ->OutPut(span(ConvertAsHTML($nom),"pj")." correctement ajouté au bestaire",true);
					$etape=0;
				}
			}
			else {
				$MJ->OutPut("PJ ".span(ConvertAsHTML($nom),"pj")." est d&eacute;j&agrave; utilis&eacute; ou interdit par le forum",true);		
				$etape=-1;
			}
			
		}
		else {
			$MJ->OutPut("pnj ".span(ConvertAsHTML($nom),"pj")." est d&eacute;j&agrave; utilis&eacute;",true);		
			$etape=-1;
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}

if($etape===0) {
        $nom="";
        if (defined("BASE_PAS"))
        	$pa=BASE_PAS;
        else $pa="";	
        if (defined("BASE_PVS"))
                $pv=BASE_PVS;
        else $pv="";
        if (defined("BASE_POS"))
                $po=BASE_POS;
        else $po="";
        $banque="";
        if (defined("BASE_PIS"))
                $pi=BASE_PIS;
        else $pi="";
        $id_lieu="";
        $email="";
        if (defined("INTERVAL_REMISEPA"))
        	$interval_remisepa=INTERVAL_REMISEPA;
        else 	$interval_remisepa=72;
        if (defined("INTERVAL_REMISEPI"))
        	$interval_remisepi=INTERVAL_REMISEPI;
        else 	$interval_remisepi=90;
        $relation="";
        $reaction="";
        $actionsurprise="";
        $phrasepreferee="";
        $description="";
        $dissimule="";
        $imageforum="";
        $background="";
        $commentaires_mj="";
        $banque=0;
        $pourcentage_reaction=100;
}

if($etape==-1){
     $nom=ConvertAsHTML($nom);
     $pass=ConvertAsHTML($pass);
     $relation=ConvertAsHTML($relation);
     $relation=ConvertAsHTML($reaction);
     $actionsurprise=ConvertAsHTML($actionsurprise);
     $phrasepreferee=ConvertAsHTML($phrasepreferee);
     $description=ConvertAsHTML($description);
     $background=ConvertAsHTML($background);
     $commentaires_mj=ConvertAsHTML($commentaires_mj);

}

if($etape==0||$etape==-1){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$lieu='';
//	$pnj=1;
	include('forms/infospj.form.'.$phpExtJeu);
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='2' />";
	
	$template_main .= "</form></div>";
}



if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
