<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: install.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/01/24 10:30:06 $

*/

require_once("../include/extension.inc");
require_once("../include/fct_installUpdate.".$phpExtJeu);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $install;

/*
install.php
INSTALL SCRIPT POUR TALESTA 4.
Copyright (c) 2005, Anthor <anthor@videos-numeriks.net>
*/
$nb_test=0;
$nb_test_ok=0;
// On empeche l'execution du nombre de connects... Parce que cela provoque un bug aussi... (Demande d'accs a la BDD)
Define("AFFICHE_CONNECTES",	0);
//on empeche sessionleym et sessionleymmj (comprennent des acces a la base)
Define("__BEGINSESSIONMJ.PHP",0);
Define("__BEGINSESSION.PHP",0);
Define("SHOW_TIME",   1); 

/*
// ON EMPECHE L'OUVERTURE DE BDD... BEN OUI YA PAS ENCORE INSTALL ! hii donc ca ferez bugger... 
Define("__BDD.PHP",	0);
// Et on cr  une fonction closebdd vide...(Pour simuler la fermeture de BDD ds footer.php)
function closeBDD(){}
*/

function writeSQL($db, $config,$dbmsJeu,$versionLivree) {
        global $phpExtJeu;
        global $template_main;
        global $nb_test;
        global $nb_test_ok;
        if (file_exists($db->fichier_install)) { 
        	$file=file_get_contents($db->fichier_install);
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
        				//teste si c'est l'insertion de MJ d'ID=1
        				if (strrpos($requete, "INSERT INTO tlt_mj")!==FALSE ) {
        					//insere le mot de passe, le nom et l'email
        					$requete=str_replace('votremotdepasse',ConvertAsHTML($config["pass_admin"]),$requete);
        					$requete=str_replace('votreemail',ConvertAsHTML($config["email_admin"]),$requete);
        					$requete=str_replace('admin',ConvertAsHTML($config["nom_admin"]),$requete);
        				}	
        				$requete=str_replace('tlt_',$config["table_prefix"],$requete);
        				test(str_replace("\n","<br />",$requete.$db->delimiter), $db->sql_query($requete),"",0);
        			}	
        		}	
        	}						
        	//renomme les tables avec le bon prefixe
        	//$db->prefixe_table($config["table_prefix"]);						
        	$table_prefix = $config["table_prefix"];
        	$nom_jeu = $config["nom_jeu"];
        	$mysql_host = $config["mysql_host"];
        	$mysql_user = $config["mysql_user"];
        	$mysql_password = $config["mysql_password"];
        	$mysql_database = $config["mysql_database"];
        	
        	$template_main .="<p>$nb_test_ok instructions se sont executes correctement sur $nb_test.</p><br />
        	<p>
        	A l'&eacute;tape suivante, le programme d'installation va essayer d'&eacute;crire le fichier de configuration <tt>include/config.$phpExtJeu</tt>.<br />
        	Assurez vous que le serveur web a bien le droit d'&eacute;crire dans ce fichier, sinon vous devrez le modifier manuellement.  </p>
        	
        	<form action=\"install.$phpExtJeu\" method=\"post\">
        	<div align='right'>
        	<input type='hidden' name='action' value='writeconfig' />
        	<input type=\"hidden\" name=\"config[table_prefix]\" value=\"$table_prefix\" />
        	<input type=\"hidden\" name=\"config[nom_jeu]\" value=\"$nom_jeu\" />
        	<input type=\"hidden\" name=\"config[mysql_host]\" value=\"$mysql_host\" />
        	<input type=\"hidden\" name=\"config[mysql_user]\" value=\"$mysql_user\" />
        	<input type=\"hidden\" name=\"config[mysql_password]\" value=\"$mysql_password\" />
        	<input type=\"hidden\" name=\"config[mysql_database]\" value=\"$mysql_database\" />
        	<input type=\"hidden\" name=\"config[meta_keywords]\" value=\"$config[meta_keywords]\" />
        	<input type=\"hidden\" name=\"config[meta_description]\" value=\"$config[meta_description]\" />
        	<input type=\"hidden\" name=\"dbmsJeu\" value=\"$dbmsJeu\" />
        	<input type=\"hidden\" name=\"versionLivree\" value='$versionLivree' />
        	<input type=\"submit\" value=\"Continuer\" /></div>
        	</form>
        	";
        }
        else $template_main .= "Fichier '".$db->fichier_install."' inexistant";
       
}        


// PAGE D'INSTALL
if(isset($_GET['action']))
{
	if($_GET['action']=="newinstall")
		{
		//$executed_queries=0;
		// ON EMPECHE L'OUVERTURE DE BDD... BEN OUI YA PAS ENCORE INSTALL ! hii donc ca ferez bugger... 
		Define("__BDD.PHP",	0);
		Define("DEBUG_MODE",1);
		// Et on cr  une fonction closebdd vide...(Pour simuler la fermeture de BDD ds footer.php)
		function closeBDD(){}
		//on efface config.php au cas ou il existerait s'une ancienne install foiree
		if(file_exists ("../include/config.".$phpExtJeu)) {
			if(file_exists ("../include/config.".$phpExtJeu.".old")) {
				if((unlink("../include/config.".$phpExtJeu.".old"))==false) {
					$template_main .= "Impossible d'effacer le fichier '../include/config.".$phpExtJeu.".old'";		
				}	
			}		
			if((rename("../include/config.".$phpExtJeu, "../include/config.".$phpExtJeu.".old"))==false) {
				$template_main .= "Impossible de renommer le fichier '../include/config.".$phpExtJeu."'";
			}	
		}		
		// INITIALISATION HEADER DE TALESTA
		if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
		$liste_type_base=array(
			0=>"mysql",
			1=>"oracle",
			2=>"postgres" 
		);

		  $versionLivree="";
		  $rep = "../include/db/";
		  $dir = opendir($rep);
		  $debutFichier = "UpdateTalesta4.mysql";
		  if ($dir != FALSE) {
			  while((FALSE !==($file = readdir($dir))) && ($versionLivree=="")) {
			  	if (strpos($file,$debutFichier)!==FALSE) {
				  	$versionLivree=substr($file,strpos($file,"vers")+4,-4);
				}
			  }
			  closedir($dir);
		 } 	  
		 else $template_main .= "Impossible d'accder  '". $rep ."'";


		$template_main .="<form action='install.$phpExtJeu' method='post'>
		<input type='hidden' name='action' value='writesql' />
		<table>
			<tr><td></td><td>Vous &ecirc;tes sur le point d'installer ";
			if ($versionLivree<>"") 
				$template_main .="la version $versionLivree de ";	
			
			$template_main .="Talesta4+. Veuillez configurer votre moteur en utilisant le formulaire suivant.";
      
      $template_main .=" ATTENTION !!! Pour une mise  jour,  ce n'est pas la bonne procdure.... Veuillez vous referez au 
      <a href='../Docs/update.htm'>document concernant la mise  jour</a>"; 
      
      $template_main .="<input type=\"hidden\" name=\"versionLivree\" value='$versionLivree' /></td></tr>
	
			<tr><td></td><td><br />NOTE: Ce programme d'installation va essayer de modifier les options de configurations dans le fichier <tt>include/config.$phpExtJeu</tt>, situ&eacute; dans votre r&eacute;pertoire include. Pour que cela fonctionne, veuillez vous assurez que votre serveur a les droits d'acc&egrave;s en &eacute;criture pour ce fichier. Si pour une raison quelconque vous ne pouvez pas faire &ccedil;a vous devrez modifier ce fichier manuellement (ce programme d'installation vous dira comment).</td></tr>
	
			<tr><td></td><td><br /><b>Configuration de la base de donn&eacute;es</b></td></tr>
			<tr><td></td><td>Type de serveur de base de donnes. (Seuls Mysql, Oracle et PostGreSQL sont supports pour le moment.)</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Type de base de donnes :</td><td>";
			$var=faitSelect("dbmsJeu","","",0,array(),$add=$liste_type_base);
			$template_main .= $var[1]. "</td></tr>";
			
			$template_main .= "<tr><td></td><td>La machine sur laquelle se trouve votre serveur de base de donnes.   (Ex: Machine pour Mysql (En g&eacute;n&eacute;ral c'est \"127.0.0.1\" si vous installez tout en local sur votre machine), Alias prsent dans TNSNAMES.ORA pour oracle).</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Machine de base de donnes :</td><td><input type=\"text\" size=\"50\" name=\"config[mysql_host]\" /></td></tr>
			<tr><td></td><td>La base de donn&eacute;es &agrave; utiliser pour Talesta4. Cette base de donn&eacute;es doit d&eacute;j&agrave; exister avant de pouvoir continuer. Remarque pour PostgreSQL, l'encodage choisi  la cration de la base doit tre SQL_ASCII pour plus de compatibilits avec les moteurs PHP et pour pouvoir grer les accents (Ex : Nom de base pour mysql ou postgreSQL, laissez  vide pour oracle)</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Base de donn&eacute;es MySQL ou postgreSQL:</td><td><input type=\"text\" size=\"50\" name=\"config[mysql_database]\" /></td></tr>
			<tr><td></td><td>Nom et mot de passe de l'utilisateur de base de donnes qui sera utilis&eacute; pour se connecter &agrave; votre base de donn&eacute;es.</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Nom de l'utilisateur base de donnes :</td><td><input type=\"text\" size=\"50\" name=\"config[mysql_user]\" /></td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Mot de passe de l'utilisateur base de donnes :</td><td><input type=\"password\" size=\"50\" name=\"config[mysql_password]\" /></td></tr>
			<tr><td></td><td>Pr&eacute;fixe &agrave; utiliser pour toutes les tables utilis&eacute;es par Talesta4+. Ceci vous permet d'utiliser plusieurs moteurs sur une m&ecirc;me base de donnn&eacute;es en donnant diff&eacute;rents pr&eacute;fixes aux tables.</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Prefixe des tables :</td><td><input type=\"text\" size=\"50\" name=\"config[table_prefix]\" value='tlt_' /> (Pour plus de compatibilit (en cas de mise a un de lower_case_table_names par ex), il est conseill de mettre les noms de tables en minuscules...)</td></tr>

			<tr><td></td><td><br /><b>Configuration de votre site Talesta4</b></td></tr>

			<tr><td align=\"right\" nowrap=\"nowrap\">Le nom de votre site :</td><td><input type=\"text\" size=\"50\" name=\"config[nom_jeu]\" value=\"Talesta 4+\" /></td></tr>

			<tr><td align=\"right\" nowrap=\"nowrap\">Nom du MJ principal de votre site :</td><td><input type=\"text\" size=\"50\" name=\"config[nom_admin]\" value=\"admin\" /></td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Mot de passe du MJ principal de votre site:</td><td><input type=\"password\" size=\"50\" name=\"config[pass_admin]\" value=\"\" /></td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Confirmation du Mot de passe du MJ principal de votre site:</td><td><input type=\"password\" size=\"50\" name=\"config[pass_admin2]\" value=\"\" /></td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">E-mail du MJ principal de votre site:</td><td><input type=\"text\" size=\"50\" name=\"config[email_admin]\" value=\"\" /></td></tr>

			<tr><td></td><td>META Mots clefs/Description qui seront ins&eacute;r&eacute;s dans les codes HTML.</td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Mots clefs :</td><td><input type=\"text\" size=\"50\" name=\"config[meta_keywords]\" /></td></tr>
			<tr><td align=\"right\" nowrap=\"nowrap\">Description :</td><td><input type=\"text\" size=\"50\" name=\"config[meta_description]\" /></td></tr>

			<tr><td></td><td align='right'><input type=\"submit\" value=\"Continuer\" /></td></tr>
		</table>
		</form>
		";
	}		
}		
elseif(isset($_POST['action'])) {
	if($_POST['action']=="writesql")
		{
			// INITIALISATION HEADER DE TALESTA

			if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
			if(!isset($config["mysql_database"]))
				$config["mysql_database"]="";
			if ($config["pass_admin2"] != $config["pass_admin"]) {
				$template_main .= GetMessage("PassMJdifferents");
			}
			else {	
				// test configuration
				$template_main .= "<b>Test de la configuration</b><br />\n";
				if  ((($db = new sql_dbTalesta($config["mysql_host"], $config["mysql_user"], $config["mysql_password"], $config["mysql_database"],false))===false) || !$db->db_connect_id)
					$template_main .= GetMessage("BaseInexistante");
				else {
				        $sql="select count(1) from tlt_mj";
				        $sql=str_replace('tlt_',$config["table_prefix"],$sql);
				        if ($db->sql_query($sql)===false) {
				                Define("DEBUG_MODE",1);
					        writeSQL($db, $config,$dbmsJeu,$versionLivree);
					}        
					else {
                                                $template_main .= "<form action=\"install.$phpExtJeu\" method=\"post\">
                                        	<div align='right'>
                                        	<input type='hidden' name='action' value='writesql2' />
                                        	<input type=\"hidden\" name=\"config[table_prefix]\" value=\"$config[table_prefix]\" />
                                        	<input type=\"hidden\" name=\"config[nom_jeu]\" value=\"$config[nom_jeu]\" />
                                        	<input type=\"hidden\" name=\"config[mysql_host]\" value=\"$config[mysql_host]\" />
                                        	<input type=\"hidden\" name=\"config[mysql_user]\" value=\"$config[mysql_user]\" />
                                        	<input type=\"hidden\" name=\"config[mysql_password]\" value=\"$config[mysql_password]\" />
                                        	<input type=\"hidden\" name=\"config[mysql_database]\" value=\"$config[mysql_database]\" />
                                        	<input type=\"hidden\" name=\"config[meta_keywords]\" value=\"$config[meta_keywords]\" />
                                        	<input type=\"hidden\" name=\"config[meta_description]\" value=\"$config[meta_description]\" />
                                        	<input type=\"hidden\" name=\"config[nom_admin]\" value=\"$config[nom_admin]\" />
                                        	<input type=\"hidden\" name=\"config[pass_admin]\" value=\"$config[pass_admin]\" />
                                        	<input type=\"hidden\" name=\"config[email_admin]\" value=\"$config[email_admin]\" />
                                        	<input type=\"hidden\" name=\"dbmsJeu\" value=\"$dbmsJeu\" />
                                        	<input type=\"hidden\" name=\"versionLivree\" value='$versionLivree' />";
                                        	$template_main .= GetMessage("TablesDejaExistantesContinue");
                                        	$template_main .= faitOuiNon("Continue","");
                                        	$template_main .= "<input type=\"submit\" value=\"Continuer\" /></div>
                                        	</form>	";					        
					}                
				}
			}
		}
	elseif($_POST['action']=="writesql2")	{
	        Define("DEBUG_MODE",1);
	        if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	        if ($Continue==1) {
        		if  ((($db = new sql_dbTalesta($config["mysql_host"], $config["mysql_user"], $config["mysql_password"], $config["mysql_database"],false))===false) || !$db->db_connect_id)
        			$template_main .= GetMessage("BaseInexistante");
                        else writeSQL($db,  $config,$dbmsJeu,$versionLivree);			
                }
                else $template_main .= GetMessage("InstallAnnulee");
	}        
	elseif($_POST['action']=="writeconfig")
		{
		Define("DEBUG_MODE",1);
		// INITIALISATION HEADER DE TALESTA
		if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
			
		
		// CONVERTI LE TABLEAU DE CONFIG EN VARIABLES PHP
		$tmpCode = enteteInstall();
		$configCode = $tmpCode[0];
		$nb_lignes = $tmpCode[1];		
		$tmpCode =listeTables($config["table_prefix"]);
		$configCode .= $tmpCode[0];
		$nb_lignes += $tmpCode[1];
		$tmpCode =paramConnection ($config);
		$configCode .= $tmpCode[0];
		$nb_lignes += $tmpCode[1];
		while ($nb_lignes<59) {
			$configCode .="\n";
			$nb_lignes++;
		}	
		$configCode .="/// ATTENTION CE COMMENTAIRE DOIT TOUJOURS SE TROUVER A LA LIGNE 60 (soixante).\n";
		//$configCode .="Define(\"VERSION\", \"".$versionLivree."\");	// version du moteur\n";	
		$configCode .=versionMoteur($versionLivree);
                $params["INSCRIPTIONS_OUVERTES"]= 0;
                $params["MAINTENANCE_MODE"]=0;
                $params["IN_NEWS"]=0;
                $params["COUNT_QCM"]=1;
                $params["DEBUG_MODE"]=1;
                $params["DEBUG_JEU_ONLY"]=1;
                $params["DEBUG_HTML"]=0;
                $params["SHOW_TIME"]=1;
                $params["AFFICHE_CONNECTES"]=1;
                //$params["IN_FORUM"]=0;
                $params["AFFICHE_XP"]=1;
                $params["AFFICHE_PV"]=1;
                //$params["AFFICHE_AVATAR_FORUM"]=1;
                //$params["AFFICHE_NB_MAX_AVATAR"]=5;
                $params["POURCENTAGE_PV_PERSO_AUTOP"]=80;
                $params["POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE"]=60;
                $params["POURCENTAGE_PV_PERSO_ABIME"]=40;
                $params["POURCENTAGE_PV_PERSO_CRITIQUE"]=20;
                
                $params["BASE_PAS"]= 20;
                $params["BASE_PVS"]= 25;
                $params["BASE_PIS"]= 20;
                $params["BASE_POS"]= 20;
                
                $params["QUANTITE_REMISE_PAS"]= 5;
                $params["QUANTITE_REMISE_PVS"]= 2;
                $params["QUANTITE_REMISE_PIS"]= 5;
                $params["QUANTITE_REMISE_POS"]= 0;
                
                $params["INTERVAL_REMISEPI"]= 90; 
                $params["INTERVAL_REMISEPA"]= 72; 
                $params["META_KEYWORDS"]= $config["meta_keywords"];
                $params["META_DESCRIPTION"]= $config["meta_description"];
                $params["NOM_JEU"]=$config["nom_jeu"];
		
		$params["TAILLE_MAX_FA"] = 10;
		
		$configCode .=listeParamInstall($params);
                $liste_pas_config=listeCoutPA();
                $liste_pis_config=listeCoutPI();
                $liste_actionstracees_config=listeActionsTracees();
                $liste_type_lieu_apparitionPerso=listeTypeLieuApparitionPerso();
                $liste_magiePerso = listeMagiePerso();
 
                $configCode .=majConfigListe("liste_pas_actions",$liste_pas_config,array());
                $configCode .=majConfigListe("liste_pis_actions",$liste_pis_config,array());
                $configCode .=majConfigListe("liste_actions_tracees",$liste_actionstracees_config,array());
		$configCode .=majConfigListe("liste_type_lieu_apparitionPerso", $liste_type_lieu_apparitionPerso);
		$configCode .=majConfigListe("liste_magiePerso", $liste_magiePerso);
		$configCode .=listeParamForum(0, "", "","", 0, -1, -1, -1,0 );		
		$configCode .="?>";
		
		// ESSAYONS D'ECRIRE LE FICHIER...
		$template_main .= "<b>Cr&eacute;ation du fichier de configuration en cours...</b><br />\n";
		$fp = @fopen("../include/config.".$phpExtJeu, "w+b");
		test("&Eacute;criture du fichier de configuration <tt>../include/config.$phpExtJeu</tt>...", array($fp,""), "", 0);

		if ($fp)
		{
			if(fwrite($fp, $configCode)===false) {
				$template_main .= "Probleme  l'criture de '../include/config.".$phpExtJeu."'";
			}
			// write
			if (fclose($fp)===false)
				$template_main .= "Probleme  la fermeture de '../include/config.".$phpExtJeu."'";
		
			
			$template_main .= "<p>Voila c'est termin&eacute; ! Vous pouvez <a href=\"../\">retourner sur votre site Talesta4+</a>. Vous pouvez vous y connecter en tant qu'admin pour personaliser votre jeu. Ensuite, vous pourrez autoriser les joueurs  s'inscrire</p>";
			
			if(file_exists ( "../admin/maj_version_talesta.".$phpExtJeu)) {
				if(file_exists ( "../admin/maj_version_talesta.bak"))
					if((unlink("../admin/maj_version_talesta.bak"))===false)
						$template_main .= "Impossible d'effacer le fichier 'admin/maj_version_talesta.bak'";
				if(!rename("../admin/maj_version_talesta.".$phpExtJeu, "../admin/maj_version_talesta.bak")){	$template_main .= "<p><span class='failed'>AVERTISSEMENT :</span> Impossible de renommer. Veuillez renommer manuellement le fichier admin/maj_version_talesta.$phpExtJeu en admin/maj_version_talesta.bak, sinon il y a un risque de mise  jou intenpestive de votre jeu.</p>";}
			}			
			if(file_exists ( "install.bak"))
				if((unlink("install.bak"))===false)
					$template_main .= "Impossible d'effacer le fichier 'install.bak'";
			if(!rename("install.".$phpExtJeu, "install.bak")){	$template_main .= "<p><span class='failed'>AVERTISSEMENT :</span> Impossible de renommer. Veuillez renommer manuellement le fichier install.$phpExtJeu en install.bak, sinon vous ne pourrez pas accder au moteur.</p>";}
		}
		else
		{
		
			// Not write ! hii
			$template_main .="<p><span class=\"failed\">AVERTISSEMENT:</span> Le
		fichier de configuration <tt>include/config.$phpExtJeu</tt> n'a pu &ecirc;tre
		cr&eacute;&eacute;. Veuillez vous assurez que votre serveur a les droits d'acc&egrave;s en &eacute;criture pour ce fichier. Si pour une raison quelconque vous ne pouvez pas faire &ccedil;a vous devez copier les informations suivantes dans un fichier et les transf&eacute;rer au moyen d'un logiciel de transfert de fichier (ftp) sur le serveur dans le rpertoire include.</p>\n";
			$template_main .="<form action='install.$phpExtJeu' method='post'>
			<input type='hidden' name='action' value='writeconfig' />
			<input type='hidden' name='config' value='".  htmlentities(serialize($config2))."' />
			<input type='submit' value='Essayer &agrave; nouveau' />
			</form>	";
			$template_main .="<div style=\"background-color: olive; padding: 10px 10px;\">\n<xmp>".$configCode."</xmp>\n</div>\n";
		}
	}
	else{		
		// ON EMPECHE L'OUVERTURE DE BDD... BEN OUI YA PAS ENCORE INSTALL ! hii donc ca ferez bugger... 
		Define("__BDD.PHP",	0);
		// Et on cr  une fonction closebdd vide...(Pour simuler la fermeture de BDD ds footer.php)
		function closeBDD(){}
		// INITIALISATION HEADER DE TALESTA
		if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
		$template_main .='Tentative de piratage...';
	}
}
else
{

		// ON EMPECHE L'OUVERTURE DE BDD... BEN OUI YA PAS ENCORE INSTALL ! hii donc ca ferez bugger... 
		Define("__BDD.PHP",	0);
		// Et on cr  une fonction closebdd vide...(Pour simuler la fermeture de BDD ds footer.php)
		function closeBDD(){}
		// INITIALISATION HEADER DE TALESTA
		if(!defined("__HEADER.PHP")){@include('../include/header.'.$phpExtJeu);}
		$template_main .='Ce fichier ne peut tre execut seul...';
}

// FINALISATION TALESTA
if(!defined("__MENU_SITE.PHP")){@include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}
?>