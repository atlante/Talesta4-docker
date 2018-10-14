<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: maj_version_talesta.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2010/02/28 22:58:06 $

*/

require_once("../include/extension.inc");
require_once("../include/fct_installUpdate.".$phpExtJeu);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $maj;
/*
install.php
INSTALL SCRIPT POUR TALESTA 4.
Copyright (c) 2005, Anthor <anthor@videos-numeriks.net>
*/
$nb_test=0;
$nb_test_ok=0;
//define("DEBUG_MODE",1);
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
// PAGE D'update
if(isset($_GET['action']))
{
	if($_GET['action']=="miseajour")
		{
	        if(!defined("DEBUG_MODE")) define("DEBUG_MODE",1);
		// INITIALISATION HEADER DE TALESTA
		if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

		$template_main .="
		<form action=\"".NOM_SCRIPT."\" method=\"post\">
		<input type=\"hidden\" name=\"action\" value=\"writesql\" />
		<table width='90%'>


			<tr><td colspan='2'>Vous &ecirc;tes sur le point de mettre à jour Talesta4+. Il est conseillé d'avoir fait un backup des fichiers HTTP et de la base avant de continuer.<br />
			Il est aussi conseillé de vérifier que le fichier 'include/config.$phpExtJeu' est accessible en écriture par le moteur et que son contenu est en 2 parties autour de la ligne 60:<ol>
			    <li>Une première (du début jusqu'à la ligne 60) avec les définitions des tables et des paramètres de connexion à la base,</li>
			    <li>une seconde (après la ligne 60) avec les définitions des parmètres du jeu.</li>
			    </ol>
			 Si vous avez modifié ce script à la main, ce n'est peut être plus le cas.<br /><br /></td></tr>";

			  $listeversionLivree=array();
			  $rep = "../include/db/";
			  $dir = opendir($rep);
			  $fichierTrouve=false;
			  $continue=0;
			  unset($choix);
			  if ($dir != FALSE) {

				if(defined("VERSION")) {
					//recherche des fichiers menant de la version deja existante
				  	$debutFichier= "UpdateTalesta4.".$dbmsJeu.VERSION."vers";
					while((FALSE !==($file = readdir($dir)))) {
					  	if (($versionInstallable = strpos($file,$debutFichier))!==FALSE) {
						  	array_push ($listeversionLivree,substr($file,strlen($debutFichier),-4));
						}
					  }

						$template_main .= "<tr><td align=\"right\" nowrap=\"nowrap\">Votre version actuelle est :</td><td> ".VERSION."<input type='hidden' name='oldversion' value='".VERSION."' /></td></tr>";
					if (count($listeversionLivree)==0) {
						$template_main .= "<tr><td></td><td>Aucun script de mise à jour n'est présent.</td></tr>";
						$continue=0;
					}						  
					else {
					        if (count($listeversionLivree)==1) {
						        $versionLivree=array_pop($listeversionLivree);
                                                        $template_main .= "<tr><td align=\"right\" nowrap=\"nowrap\">La version à installer est :</td><td>".$versionLivree."<input type=\"hidden\" size=\"50\" name=\"versionLivree\" value='$versionLivree' /></td></tr>";
        					        if ($versionLivree==VERSION) 
                						$continue=-1;
                					else	$continue=1;
		        			}
			        		else {
				        		$template_main .= "<tr><td align=\"right\">Attention, la version à installer n'a pu être déterminée automatiquement. <br />Il vous faut la choisir:</td>";
					        	$var=faitSelect("versionLivree","","",-50,array(),$listeversionLivree);
						        $template_main .="<td valign='bottom'>". $var[1]."</td></tr>";
						$continue=1;
					}
				}
				}
				else {
					$debutFichier = "UpdateTalesta4.".$dbmsJeu;
					$versionsDepart=array();
					  while(FALSE !==($file = readdir($dir))) {
					  	if (($oldVersion = strpos($file,$debutFichier))!==FALSE && ($newVersion = strpos($file,"vers"))!==FALSE) {
						  	$versionDepartDuScript=substr($file,strlen($debutFichier),$newVersion - (strlen($debutFichier)));
						  	$versionLivree=substr($file,$newVersion+4,-4);
                                                  	array_push ($versionsDepart, "Passer de " .$versionDepartDuScript ." à ". $versionLivree);
						}
					  }

					if (count($versionsDepart)>=1) {
						$template_main .= "<tr><td align=\"right\">Attention, votre version actuelle n'a pu être déterminée automatiquement. <br />Il vous faut choisir le bon script de mise à jour (ou tout arreter et faire une installaion complète):</td>";
						$var=faitSelect("choix","","",-50,array(),$versionsDepart);
						$template_main .="<td valign='bottom'>". $var[1]."</td></tr>";
						$continue=1;
					}	
					else { 
						$template_main .= "<tr><td></td><td>Aucun script de mise à jour n'est présent.</td></tr>";
						$continue=0;
					}	
				}	
			  	closedir($dir);
			  	

				//$template_main .= "<tr><td align=\"right\" nowrap=\"nowrap\">La version à installer est :</td><td>".$versionLivree."<input type=\"hidden\" size=\"50\" name=\"versionLivree\" value='$versionLivree' /></td></tr>";
				switch ($continue){
					case -1:	
						$template_main .=" <tr><td></td><td>Rien à faire</td></tr></table>";
						break;
					case 0:
						$template_main .=" <tr><td></td><td>Impossible de mettre à jour votre version</td></tr></table>";
						break;
					case 1:
						$template_main .= "<tr><td align=\"right\" nowrap=\"nowrap\">Machine de base de données :</td><td>$hostbd</td></tr>
						<tr><td align=\"right\" nowrap=\"nowrap\">Base de donn&eacute;es :</td><td>$bdd</td></tr>
						<tr><td align=\"right\" nowrap=\"nowrap\">Nom de l'utilisateur base de données :</td><td>$userbd</td></tr>
						<tr><td align=\"right\" nowrap=\"nowrap\">Mot de passe utilisé :</td><td>$passbd</td></tr>";
						$table_prefix = substr(NOM_TABLE_SESSIONS,0,strpos(NOM_TABLE_SESSIONS,'sessions'));
						$template_main .="<tr><td align=\"right\" nowrap=\"nowrap\">Prefixe des tables :</td><td>$table_prefix";
						$template_main .="<input type=\"hidden\" size=\"50\" name=\"table_prefix\" readonly='readonly' value='".$table_prefix."' /> </td></tr>";				
						$template_main .="<tr><td></td><td align='right'><input type=\"submit\" value=\"Continuer\" /></td></tr></table>";

						break;					
				}	

				$template_main .="</form>";

			 } 	  
			 else $template_main .= "Impossible d'accèder à '". $rep ."'";
		
		}
		else{		
			 if(!defined("DEBUG_MODE")) define("DEBUG_MODE",1);
			if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
			$template_main .= GetMessage("noparam");
		}	
}
else {
	if(isset($_POST['action'])) {
		if($_POST['action']=="writesql") {
		         if(!defined("DEBUG_MODE")) define("DEBUG_MODE",1);
			// INITIALISATION HEADER DE TALESTA

			if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
			// test configuration
			$template_main .= "<b>Test de la configuration</b><br />\n";
			if  ((($db = new sql_dbTalesta($hostbd, $userbd, $passbd, $bdd,false))===false) || !$db->db_connect_id)
				$template_main .= "La base de donn&eacute;es que vous avez choisie n'existe pas, vous devez la cr&eacute;er avant d'installer Talesta4+ !";
			else {
			        if (isset($choix)) {
                                        $debutFichier = "Passer de ";
                                        if (($oldversion = strpos($choix,$debutFichier))!==FALSE && ($newVersion = strpos($choix," à "))!==FALSE) {
                                          	$versionDepartDuScript=substr($choix,strlen($debutFichier),$newVersion - (strlen($debutFichier)));
                                          	$versionLivree=substr($choix,$newVersion+3);
                                          	$oldversion=$versionDepartDuScript;
                                        }
			        }        

				$configBackup = "../include/configBackupPar".$versionLivree.".".$phpExtJeu;
				if(file_exists ( $configBackup))
					if((unlink($configBackup))===false)
						$template_main .= "Impossible d'effacer le fichier '".$configBackup."'";
					if(!copy("../include/config.".$phpExtJeu, $configBackup)){
					        $template_main .= "<p><span class='failed'>AVERTISSEMENT :</span> Impossible de copier. Veuillez copier manuellement le fichier config.$phpExtJeu en $configBackup, afin d'avoir un backup en cas de problemes lors de la mise à jour.</p>";
					}			        
				$fichierAInstaller ="../include/db/UpdateTalesta4.".$dbmsJeu. $oldversion."vers".$versionLivree.".sql";
				if (file_exists($fichierAInstaller)) { 
					$file=file_get_contents($fichierAInstaller);
					$file=str_replace($db->delimiter." ",$db->delimiter,$file);
					$file=str_replace($db->delimiter."\t",$db->delimiter,$file);
					//passage en mode unix
					$file=str_replace("\r","",$file);
					$requetes=explode($db->delimiter."\n",$file);
					$i=0;
					foreach ($requetes as $requete) {
						$requete= trim($requete);					
						if ($requete<>"") {
							$lignesrequete=explode("\n",$requete);
							$commentaire=1;
							foreach ($lignesrequete as $lignerequete) {
								if (trim($lignerequete)<>"" && substr($lignerequete, 0, strlen($db->comment))!=$db->comment) {
									$commentaire=0;
								}	
							}		
							//teste si c'est un commentaire
							if ($commentaire==0) {
								$requete=str_replace('tlt_',$table_prefix,$requete);
								test(str_replace("\n","<br />",$requete.$db->delimiter), $db->sql_query($requete),"",0);
							}	
						}	
					}						

					$template_main .="<p>$nb_test_ok instructions se sont executées correctement sur $nb_test.</p><br />
					<p>
					A l'&eacute;tape suivante, le programme d'installation va essayer d'&eacute;crire le fichier de configuration <tt>include/config.php</tt>.<br />
					Assurez vous que le serveur web a bien le droit d'&eacute;crire dans ce fichier, sinon vous devrez le modifier manuellement.  </p>
					
					<form action=\"".NOM_SCRIPT."\" method=\"post\">
					<div align='right'>
					<input type=\"hidden\" name=\"action\" value=\"writeconfig\" />
					<input type=\"hidden\" size=\"50\" name=\"versionLivree\" value='$versionLivree' />
					<input type=\"hidden\" name=\"table_prefix\" value=\"$table_prefix\" />
					<input type=\"submit\" value=\"Continuer\" /></div>
					</form>
					";
				}
				else $template_main .= "Fichier '".$fichierAInstaller."' inexistant";
			}
		}
		elseif($_POST['action']=="writeconfig")	{
		        //Ici on ne met pas le debug_mode,sinon on perd la valeur du fichie config
			// INITIALISATION HEADER DE TALESTA
			if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
				
			
			// CONVERTI LE TABLEAU DE CONFIG EN VARIABLES PHP
		
			$monfichier = fopen('../include/config.'.$phpExtJeu, 'r+b');
			fgets($monfichier);
			$secondeLigne = fgets($monfichier);
			$tmpCode = enteteMiseAjour($secondeLigne, $versionLivree);
			$configCode = $tmpCode[0];
			$nb_lignesSimulees = $tmpCode[1];		
			$tmpCode2 =listeTables($table_prefix);
			$configCode .= $tmpCode2[0];
			$nb_lignesSimulees += $tmpCode2[1];
	
			$config= array("mysql_host"=>$hostbd,"mysql_user"=>$userbd,"mysql_password"=>$passbd,"mysql_database"=>$bdd);
			$tmpCode3 =paramConnection ($config);
			$configCode .= $tmpCode3[0];
			$nb_lignesSimulees += $tmpCode3[1];
			$nb_lignesLues = 2;	
			while ($nb_lignesLues<59) {
				//lit le fichier en ignorant les données
				fgets($monfichier);
				$nb_lignesLues++;
			}	
			while ($nb_lignesSimulees<59) {
				//Complete avec des \n jusqu'a la ligne 60
				$configCode .= "\n";
				$nb_lignesSimulees++;
			}	
			$LigneAvecVersionTrouvee = false;
			while (!feof ($monfichier)) {
				$temp = fgets($monfichier); 			
				if (($LigneAvecVersionTrouvee == false) && (strpos($temp,"Define(\"VERSION\"")!==FALSE)) {
					$LigneAvecVersionTrouvee = true;
					//modif de la ligne avec le version
					$configCode .=versionMoteur($versionLivree);
				}	
				//else $configCode .= $temp; 
			}    	
			
			/*$configCode=str_replace("?>", "", $configCode);*/
			
			if ($LigneAvecVersionTrouvee==false) {				
				//ajout de la ligne avec le version
				$configCode .=versionMoteur($versionLivree);
			}	


                        if(defined("INSCRIPTIONS_OUVERTES")) $params["INSCRIPTIONS_OUVERTES"]= INSCRIPTIONS_OUVERTES;
                        else $params["INSCRIPTIONS_OUVERTES"]= 0;
                        if(defined("MAINTENANCE_MODE")) $params["MAINTENANCE_MODE"]=MAINTENANCE_MODE;
                        else $params["MAINTENANCE_MODE"]=0;
                        if(defined("IN_NEWS")) $params["IN_NEWS"]=IN_NEWS;
                        else $params["IN_NEWS"]=0;
                        if(defined("COUNT_QCM")) $params["COUNT_QCM"]=COUNT_QCM;
                        else $params["COUNT_QCM"]=1;
                        if(defined("DEBUG_MODE")) $params["DEBUG_MODE"]=DEBUG_MODE;
                        else $params["DEBUG_MODE"]=1;
                        if(defined("DEBUG_HTML")) $params["DEBUG_HTML"]=DEBUG_HTML;
                        else $params["DEBUG_HTML"]=0;
                        if(defined("SHOW_TIME")) $params["SHOW_TIME"]=SHOW_TIME;
                        else $params["SHOW_TIME"]=1;
                        if(defined("AFFICHE_CONNECTES")) $params["AFFICHE_CONNECTES"]=AFFICHE_CONNECTES;
                        else $params["AFFICHE_CONNECTES"]=1;
                        if(defined("IN_FORUM") && IN_FORUM==1) {
                        		$params["IN_FORUM"]=IN_FORUM;
		                        if(defined("CHEMIN_FORUM")) $chemin= CHEMIN_FORUM;
		                        else $chemin="";
		                        if(defined("AFFICHE_AVATAR_FORUM")) $aff_ava= AFFICHE_AVATAR_FORUM;
		                        else $aff_ava=0;
		                        if(defined("AFFICHE_NB_MAX_AVATAR")) $nb_aff_ava= AFFICHE_NB_MAX_AVATAR;
		                        else $nb_aff_ava=0;
		                        if(defined("HAUT_MAX_LIEU")) $hautMaxLieu= HAUT_MAX_LIEU;
		                        else $hautMaxLieu=0;
		                        if(defined("LARG_MAX_LIEU")) $largMaxLieu= LARG_MAX_LIEU;
		                        else $largMaxLieu=0;
		                        if (defined("CREE_MEMBRE_PNJ")) $creeMembrePNJ=CREE_MEMBRE_PNJ;
		                         else $creeMembrePNJ=0;
                      			$configCode .=listeParamForum($params["IN_FORUM"], $chemin, $typeforum, $typeforum, $aff_ava, $nb_aff_ava, $hautMaxLieu, $largMaxLieu,$creeMembrePNJ );
		        }	
                        else {
                        	$params["IN_FORUM"]=0;
                        	$configCode .=listeParamForum($params["IN_FORUM"], "", "","", 0, -1, -1, -1, 0 );
                        }	
                      
                        
                        if(defined("AFFICHE_XP")) $params["AFFICHE_XP"]=AFFICHE_XP;
                        else $params["AFFICHE_XP"]=1;
                        if(defined("AFFICHE_PV")) $params["AFFICHE_PV"]=AFFICHE_PV;
                        else $params["AFFICHE_PV"]=1;
                        if(defined("AFFICHE_AVATAR_FORUM")) $params["AFFICHE_AVATAR_FORUM"]=AFFICHE_AVATAR_FORUM;
                        else $params["AFFICHE_AVATAR_FORUM"]=1;
                        if(defined("AFFICHE_NB_MAX_AVATAR")) $params["AFFICHE_NB_MAX_AVATAR"]=AFFICHE_NB_MAX_AVATAR;
                        else $params["AFFICHE_NB_MAX_AVATAR"]=5;                
                        if(defined("POURCENTAGE_PV_PERSO_AUTOP")) $params["POURCENTAGE_PV_PERSO_AUTOP"]=POURCENTAGE_PV_PERSO_AUTOP;
                        else $params["POURCENTAGE_PV_PERSO_AUTOP"]=80;
                        if(defined("POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE")) $params["POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE"]=POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE;
                        else $params["POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE"]=60;
                        if(defined("POURCENTAGE_PV_PERSO_ABIME")) $params["POURCENTAGE_PV_PERSO_ABIME"]=POURCENTAGE_PV_PERSO_ABIME;
                        else $params["POURCENTAGE_PV_PERSO_ABIME"]=40;
                        if(defined("POURCENTAGE_PV_PERSO_CRITIQUE")) $params["POURCENTAGE_PV_PERSO_CRITIQUE"]=POURCENTAGE_PV_PERSO_CRITIQUE;
                        else $params["POURCENTAGE_PV_PERSO_CRITIQUE"]=20;
                        
                        if(defined("BASE_PAS")) $params["BASE_PAS"]= BASE_PAS;
                        else $params["BASE_PAS"]= 20;
                        if(defined("BASE_PVS")) $params["BASE_PVS"]= BASE_PVS;
                        else $params["BASE_PVS"]= 25;
                        if(defined("BASE_PIS")) $params["BASE_PIS"]= BASE_PIS;
                        else $params["BASE_PIS"]= 20;
                        if(defined("BASE_POS")) $params["BASE_POS"]= BASE_POS;
                        else $params["BASE_POS"]= 20;
                        
                        if(defined("QUANTITE_REMISE_PAS")) $params["QUANTITE_REMISE_PAS"]= QUANTITE_REMISE_PAS;
                        else $params["QUANTITE_REMISE_PAS"]= 5;
                        if(defined("QUANTITE_REMISE_PVS")) $params["QUANTITE_REMISE_PVS"]= QUANTITE_REMISE_PVS;
                        else $params["QUANTITE_REMISE_PVS"]= 2;
                        if(defined("QUANTITE_REMISE_PIS")) $params["QUANTITE_REMISE_PIS"]= QUANTITE_REMISE_PIS;
                        else $params["QUANTITE_REMISE_PIS"]= 5;
                        if(defined("QUANTITE_REMISE_POS")) $params["QUANTITE_REMISE_POS"]= QUANTITE_REMISE_POS;
                        else $params["QUANTITE_REMISE_POS"]= 0;
                        
                        if(defined("INTERVAL_REMISEPI")) $params["INTERVAL_REMISEPI"]= INTERVAL_REMISEPI; 
                        else $params["INTERVAL_REMISEPI"]= 90; 
                        if(defined("INTERVAL_REMISEPA")) $params["INTERVAL_REMISEPA"]= INTERVAL_REMISEPA; 
                        else $params["INTERVAL_REMISEPA"]= 72; 
                        
                        if(defined("TAILLE_MAX_FA")) $params["TAILLE_MAX_FA"]=TAILLE_MAX_FA;
                        else $params["TAILLE_MAX_FA"]=10;
                        
                        if(defined("META_KEYWORDS")) $params["META_KEYWORDS"]= META_KEYWORDS;
                        else $params["META_KEYWORDS"]= "";
                        if(defined("META_DESCRIPTION")) $params["META_DESCRIPTION"]= META_DESCRIPTION;
                        else $params["META_DESCRIPTION"]= "";
                        
                        if(defined("NOM_JEU")) $params["NOM_JEU"]=NOM_JEU;
                        else       $params["NOM_JEU"]="";
        		
        		if(defined("DEBUG_JEU_ONLY")) $params["DEBUG_JEU_ONLY"]=DEBUG_JEU_ONLY;
        		else $params["DEBUG_JEU_ONLY"]="0";
        		
        		$configCode .=listeParamInstall($params);
			if (isset($langue))
				$configCode .="\$langue = \"".$langue."\"; \n"; 

			if (isset($template_name))
				$configCode .="\$template_name = \"".urldecode($template_name)."\"; \n";

                        $liste_pas_config=listeCoutPA();
                        $liste_pis_config=listeCoutPI();
                        $liste_actionstracees_config=listeActionsTracees();

                        $configCode .=majConfigListe("liste_pas_actions",$liste_pas_config,$liste_pas_actions);
                        $configCode .=majConfigListe("liste_pis_actions",$liste_pis_config,$liste_pis_actions);
                        if (is_array($liste_actions_tracees))
                                $configCode .=majConfigListe("liste_actions_tracees",$liste_actionstracees_config,$liste_actions_tracees);
                        else $configCode .=majConfigListe("liste_actions_tracees",$liste_actionstracees_config,array());        
                        if(!isset($liste_type_lieu_apparitionPerso) || !is_array($liste_type_lieu_apparitionPerso))
                                $liste_type_lieu_apparitionPerso = listeTypeLieuApparitionPerso();
                        if(!isset($liste_magiePerso) || !is_array($liste_magiePerso))                                
		                $liste_magiePerso = listeMagiePerso();
		        //$liste_type_lieu_apparitionPerso =$liste_type_lieu_apparition;
		        //$liste_magiePerso = $liste_magie;
		        
		        $configCode .=majConfigListe("liste_type_lieu_apparitionPerso", $liste_type_lieu_apparitionPerso);
		        $configCode .=majConfigListe("liste_magiePerso", $liste_magiePerso);
		        if(defined("GROUPE_PJS") && GROUPE_PJS==1) $params2["groupePJs"]= GROUPE_PJS;
		        else $params2["groupePJs"]= 0;
		
		        if(defined("RIPOSTE_AUTO") && RIPOSTE_AUTO==1) $params2["riposteAuto"]= RIPOSTE_AUTO;
		        else $params2["riposteAuto"]= 0;
		
		        if(defined("RIPOSTE_GROUPEE") && RIPOSTE_GROUPEE==1) $params2["riposteGroupee"]= RIPOSTE_GROUPEE;
		        else $params2["riposteGroupee"]= 0;
		
		        if(defined("ENGAGEMENT") && ENGAGEMENT==1) $params2["engagement"]= ENGAGEMENT;
		        else $params2["engagement"]= 0;
		
		        if(defined("SECACHER") && SECACHER==1) $params2["secacher"]= SECACHER;
		        else $params2["secacher"]= 0;        	
		
		        if (defined("DISTANCE_CRI")) $params2["distance_cri"]= DISTANCE_CRI;
		        else $params2["distance_cri"]= 2;        	
		
		        if(defined("RESURRECTION") && RESURRECTION==1) $params2["resurrection"]= RESURRECTION;
		        else $params2["resurrection"]= 0;        	
		
		        if(defined("PV_RESURRECTION")) $params2["pv_resurrection"]= PV_RESURRECTION;
		        else $params2["pv_resurrection"]= 10;        	
		   	
		        if(defined("NB_MAX_RESURRECTION")) $params2["nb_max_resurrection"]= NB_MAX_RESURRECTION;
		        else $params2["nb_max_resurrection"]= 0;        	
		
		        if(defined("LIEU_RESURRECTION")) $params2["lieu_resurrection"]= LIEU_RESURRECTION;
		        else $params2["lieu_resurrection"]= "Le PJ ne change pas de lieu";        	
		
		        if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1) $params2["affiche_prix_objet_sort"]= AFFICHE_PRIX_OBJET_SORT;
		        else $params2["affiche_prix_objet_sort"]= 0;        	
		
		        if(defined("FOUILLE_OBJETS_EQUIPES") && FOUILLE_OBJETS_EQUIPES==1) $params2["fouille_objets_equipes"]= FOUILLE_OBJETS_EQUIPES;
		        else $params2["fouille_objets_equipes"]= 0;    


		        if(defined("REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES") && REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES==1) $params2["reussite_auto_fouille_objets_equipes"]= REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES;
		        else $params2["reussite_auto_fouille_objets_equipes"]= 0;    
		
                        if(defined("DELAI_SUPPRESSION_MONSTRESMORTS") && DELAI_SUPPRESSION_MONSTRESMORTS!="") $params2["delai_suppression_monstresmorts"]= DELAI_SUPPRESSION_MONSTRESMORTS;
                        else $params2["delai_suppression_monstresmorts"]= -1;

                        if(defined("MAIL_FA_ARCHIVES") && MAIL_FA_ARCHIVES!="") $params2["mail_fa_archives"]= MAIL_FA_ARCHIVES;
                        else $params2["mail_fa_archives"]= "";

		        $configCode .=listeParamMAJ_config($params2);		        
		        
 
			$configCode.= "?>";
			  
			if ((rewind ( $monfichier)===false) ||(fwrite($monfichier , $configCode)===false)) {
				$template_main .= "Probleme à l'écriture de '".$monfichier."'";
			}
			//efface ce qui reste si on n'est pas a la fin
			if (feof ($monfichier)===false)
				ftruncate($monfichier,ftell ($monfichier));
			// SURTOUT PAS OUBLIER DE FERMER LE FICHIER...
			if (fclose($monfichier)===false)
				$template_main .= "Probleme à la fermeture de '".$monfichier."'";
			else {		
				$template_main .=(" Fichier Config correctement modifié... N'oubliez pas d'utiliser le menu Configuration Générale dans Admin pour paramétrer les nouveaux paramètres. ");
				if(file_exists ( "maj_version_talesta.bak"))
					if((unlink("maj_version_talesta.bak"))===false)
						$template_main .= "Impossible d'effacer le fichier 'maj_version_talesta.bak'";
					if(!rename("maj_version_talesta.".$phpExtJeu, "maj_version_talesta.bak")){	$template_main .= "<p><span class='failed'>AVERTISSEMENT :</span> Impossible de renommer. Veuillez renommer manuellement le fichier maj_version_talesta.$phpExtJeu en maj_version_talesta.bak.</p>";}
				
				//renomme le fichier d'install complete
				if(file_exists ( "../main/install.php")) {
					if(file_exists ( "../main/install.bak"))
						if((unlink("../main/install.bak"))===false)
							$template_main .= "Impossible d'effacer le fichier '../main/install.bak'";
					if(!rename("../main/install.".$phpExtJeu, "../main/install.bak")){	$template_main .= "<p><span class='failed'>AVERTISSEMENT :</span> Impossible de renommer. Veuillez renommer manuellement le fichier ../main/install.$phpExtJeu en install.bak, sinon vous ne pourrez pas accéder au moteur.</p>";}
				}
	
			}	
		}
		else{		
		      if(!defined("DEBUG_MODE"))    define("DEBUG_MODE",1);
			if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
			$template_main .= GetMessage("noparam");
		}
	}
}

// FINALISATION TALESTA
if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}
?>