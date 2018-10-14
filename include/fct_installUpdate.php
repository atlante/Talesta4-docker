<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: fct_installUpdate.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.7 $
$Date: 2010/01/24 19:33:12 $

*/

require_once("../include/extension.inc");

	// FONCTION DE TEST
	function test($text, $SQL, $errorText = "", $stopOnError = 1) {		
		$GLOBALS['nb_test']++;
		global $db;
		global $template_main;
		$template_main .="$text ";
		if ($SQL)
		{
			$template_main .= "<span class=\"ok\">OK</span><br />\n" ;
			$GLOBALS['nb_test_ok']++;
		}
		else
		{
			$template_main .= "<span class=\"failed\">ECHEC: ".$db->erreur."</span>" ;
			if ($errorText) {$template_main .=  ": ".$errorText ;}
			$template_main .=  "<br />\n" ;
			if ($stopOnError) 
				{
				if(!defined("__MENU_SITE.PHP")){@include('../admin/menu_site.'.$phpExtJeu);}
				if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}
				exit;
				}
		}
	}

			
	function enteteInstall () {
		global $phpExtJeu;
		$configCode = "<?php\n// config.$phpExtJeu créée ".strftime("%c")."\n";
		$configCode .="//Par Talesta Install Script\n";
		$configCode .="\n";
		$nblignes = substr_count ( $configCode, "\n");
		$retour= array();
		$retour[0]=$configCode;
		$retour[1]=$nblignes;
		return $retour;
	}	

	function enteteMiseAjour ($secondeLigne, $versionInstallee) {
		global $phpExtJeu;
		$configCode = "<?php\n".substr($secondeLigne,0,-1). " mise à jour le ".strftime("%c")." pour la version ". $versionInstallee."\n";
		$configCode .="//Par Talesta Mise à jour Script\n";
		$configCode .="\n";
		$nblignes = substr_count ( $configCode, "\n");
		$retour= array();
		$retour[0]=$configCode;
		$retour[1]=$nblignes;
		return $retour;
	}	


	function listeTables ($table_prefixe) {
		$configCode  ="Define(\"NOM_TABLE_SESSIONS\",				\"".$table_prefixe."sessions\");\n";
		$configCode .="Define(\"NOM_TABLE_REGISTRE\",				\"".$table_prefixe."perso\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSO\",					\"".$table_prefixe."perso\");\n";
		$configCode .="Define(\"NOM_TABLE_ENTITECACHEECONNUEDE\",	\"".$table_prefixe."entitecacheeconnuede\");\n";
		$configCode .="Define(\"NOM_TABLE_ENTITECACHEE\",			\"".$table_prefixe."entitecachee\");\n";
		$configCode .="Define(\"NOM_TABLE_LIEU\",					\"".$table_prefixe."lieu\");\n";
		$configCode .="Define(\"NOM_TABLE_COMP\",					\"".$table_prefixe."comp\");\n";
		$configCode .="Define(\"NOM_TABLE_SPEC\",					\"".$table_prefixe."spec\");\n";
		$configCode .="Define(\"NOM_TABLE_SPECNOM\",				\"".$table_prefixe."specnom\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSOSPEC\",				\"".$table_prefixe."persospec\");\n";
		$configCode .="Define(\"NOM_TABLE_OBJET\",					\"".$table_prefixe."objets\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSOOBJET\",				\"".$table_prefixe."persoobjets\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSOMAGIE\",				\"".$table_prefixe."persomagie\");\n";
		$configCode .="Define(\"NOM_TABLE_MAGIE\",					\"".$table_prefixe."magie\");\n";
		$configCode .="Define(\"NOM_TABLE_CHEMINS\",				\"".$table_prefixe."chemins\");\n";
		$configCode .="Define(\"NOM_TABLE_MJ\",						\"".$table_prefixe."mj\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSOETATTEMP\",			\"".$table_prefixe."persoetattemp\");\n";
		$configCode .="Define(\"NOM_TABLE_ETATTEMP\",				\"".$table_prefixe."etattemp\");\n";
		$configCode .="Define(\"NOM_TABLE_ETATTEMPNOM\",			\"".$table_prefixe."etattempnom\");\n";
		$configCode .="Define(\"NOM_TABLE_MAGASIN\",				\"".$table_prefixe."zone\");\n";
		$configCode .="Define(\"NOM_TABLE_INSCRIPTION\",			\"".$table_prefixe."inscriptions\");\n";
		$configCode .="Define(\"NOM_TABLE_ARCHIVE\",				\"".$table_prefixe."archive\");\n";
		$configCode .="Define(\"NOM_TABLE_GROUPE\",					\"".$table_prefixe."groupe\");\n";
		$configCode .="Define(\"NOM_TABLE_COMPOSITIONGROUPE\",		\"".$table_prefixe."compositiongroupe\");\n";
		$configCode .="Define(\"NOM_TABLE_TYPEETAT\",				\"".$table_prefixe."typeetattemp\");\n";
		$configCode .="Define(\"NOM_TABLE_QCM\",					\"".$table_prefixe."qcm\");\n";
		$configCode .="Define(\"NOM_TABLE_ENGAGEMENT\",			\"".$table_prefixe."engagement\");\n";
		$configCode .="Define(\"NOM_TABLE_NEWS\",			\"".$table_prefixe."n_news\");\n";
		$configCode .="Define(\"NOM_TABLE_COMMENT_NEWS\",			\"".$table_prefixe."n_commentaires\");\n";
		$configCode .="Define(\"NOM_TABLE_CONFIG_NEWS\",			\"".$table_prefixe."n_config\");\n";		
		$configCode .="Define(\"NOM_TABLE_INSCRIPT_ETAT\",			\"".$table_prefixe."inscriptetattemp\");\n";
		
		$configCode .="Define(\"NOM_TABLE_QUETE\",			\"".$table_prefixe."quetes\");\n";
		$configCode .="Define(\"NOM_TABLE_RECOMPENSE_QUETE\",			\"".$table_prefixe."recompensequete\");\n";
		$configCode .="Define(\"NOM_TABLE_PERSO_QUETE\",			\"".$table_prefixe."persoquete\");\n";
		$configCode .="Define(\"NOM_TABLE_APPARITION_MONSTRE\",			\"".$table_prefixe."apparitionmonstre\");\n";
		$configCode .="Define(\"NOM_TABLE_PPA\",			\"".$table_prefixe."ppa\");\n";
		$configCode .="Define(\"NOM_TABLE_TRACE_ACTIONS\",			\"".$table_prefixe."traceactions\");\n";
		$nblignes = substr_count ( $configCode, "\n");
		$retour= array();
		$retour[0]=$configCode;
		$retour[1]=$nblignes;
		return $retour;
	}
	
	function listeCoutPA() {
		$liste_pas_actions=array(
			"Manger"=>-1,
			"Attaquer"=>-2,
			"EnleverArmure"=>0,
			"MettreArmure"=>0,
			"Magie"=>0,
			"FouillerCadavre"=>-1,
			"FouillerLieu"=>-2,			
			"LieuGuilde"=>-2,
			"LieuPassage"=>-2,
			"LieuEscalader"=>-3,
			"LieuNager"=>-3,
			"LieuEntrer"=>0,
			"Lire"=>0,
			"DonnerObjet"=>-2,
			"DonnerArgent"=>-1,
			"VolerPJ"=>-2,
			"Armurerie"=>-2,
			"MagasinMagique"=>-2,
			"Quincaillerie"=>-2,
			"Parler"=>0,
			"OublierSort"=>0,
			"AbandonnerObjet"=>0,
			"Banque"=>-2,
			"RechargerObjet"=>-1,
			"SeCacher"=>-2,
			"Reveler"=>-1,
			"SoinObjet"=>-2,
			"MontrerCachette"=>-1,
			"Crier"=>2,
			"CombinerObjets"=>-1,
			"Miner"=>-4,			
			"Cueillir"=>-2,
			"Scier"=>-4,
			"Carriere"=>-4,
			"ReparerObjet"=>-2,
			"CreerObjet"=>-1,
			"SurcoutDeplacementDiscret"=>-1,
			"MagieMajeure"=>0
	        );
	        return $liste_pas_actions;
        
        }	        

        function listeCoutPI() {
	        $liste_pis_actions=array(
			"Manger"=>0,
			"Attaquer"=>0,
			"EnleverArmure"=>0,
			"MettreArmure"=>0,
			"Magie"=>-2,
			"FouillerCadavre"=>-1,
			"FouillerLieu"=>-2,			
			"LieuGuilde"=>0,
			"LieuPassage"=>0,
			"LieuEscalader"=>0,
			"LieuNager"=>0,
			"LieuEntrer"=>0,
			"Lire"=>-1,
			"DonnerObjet"=>0,
			"DonnerArgent"=>0,
			"VolerPJ"=>0,
			"Armurerie"=>0,
			"MagasinMagique"=>0,
			"Quincaillerie"=>0,
			"Parler"=>0,
			"OublierSort"=>0,
			"AbandonnerObjet"=>0,
			"Banque"=>0,
			"RechargerObjet"=>0,
			"SeCacher"=>-2,
			"Reveler"=>0,
			"SoinObjet"=>-2,
			"MontrerCachette"=>-1,
			"Crier"=>0,
			"CombinerObjets"=>-4,
			"Miner"=>-1,			
			"Cueillir"=>-1,
			"Scier"=>-1,
			"Carriere"=>-1,
			"ReparerObjet"=>-3,
			"CreerObjet"=>-4,
			"SurcoutDeplacementDiscret"=>-1,
			"MagieMajeure"=>-4
		);	 
		return $liste_pis_actions;
	}	


        function listeActionsTracees() {
	      /*  $liste_actions_tracees=array(
							"Manger"=>0,
							"Attaquer"=>0,
							"EnleverArmure"=>0,
							"MettreArmure"=>0,
							"Magie"=>0,
							"FouillerCadavre"=>0,
							"FouillerLieu"=>0,	
							"LieuGuilde"=>0,
							"LieuPassage"=>0,
							"LieuEscalader"=>0,
							"LieuNager"=>0,
							"LieuEntrer"=>0,
							"Lire"=>0,
							"DonnerObjet"=>0,
							"DonnerArgent"=>0,
							"VolerPJ"=>0,
							"Armurerie"=>0,
							"MagasinMagique"=>0,
							"Quincaillerie"=>0,
							"Parler"=>0,
							"OublierSort"=>0,
							"AbandonnerObjet"=>0,
							"Banque"=>0,
							"RechargerObjet"=>0,
							"SeCacher"=>0,
							"Reveler"=>0,
							"SoinObjet"=>0,
							"MontrerCachette"=>0,
							"Crier"=>0,
							"CombinerObjets"=>0,
							"Miner"=>0,			
							"Cueillir"=>0,
							"Scier"=>0,
							"Carriere"=>0,
							"ReparerObjet"=>0,
							"CreerObjet"=>0,
							"SurcoutDeplacementDiscret"=>0,
							"MagieMajeure"=>0,
							"Desengager"=>0
						);	 */
                        // pour le moment, on ne gere que celles-la						
                        $liste_actions_tracees=array(
                                "Manger"=>0,
				"Attaquer"=>0,
				"Magie"=>0,
				"FouillerCadavre"=>0,
				"FouillerLieu"=>0,	
				"VolerPJ"=>0,
				"SeCacher"=>0,
				"SoinObjet"=>0,
				"Desengager"=>0,
				"Crocheter"=>0,
				"MotPasse"=>0,
				"DonnerArgent"=>0,
				"DonnerObjet"=>0,
				"RamasserObjet"=>0,
				"AbandonnerObjet"=>0,
				"CacherObjet"=>0,
				"DetruireObjet"=>0,
				"SeDeplacer"=>0
				
			);							
		return $liste_actions_tracees;
	}	


        function listeTypeLieuApparitionPerso() {		
        	$liste_type_lieu_apparitionPerso=array(	            
                            2=>"Foret",                    
                            3=>"Caverne, Sous-terrain",
                            4=>"Ville fortitiée",
                            5=>"Campagne",
                            6=>"Village",
                            7=>"Intérieur d'un batiment",
                            8=>"Désert",
                            9=>"Plaine"
        	);	
		return $liste_type_lieu_apparitionPerso;
	}	
	
        function listeMagiePerso() {	
        	$liste_magiePerso = array(
        			"Air"=>1001,	
        			"Terre"=>1002,
        			"Feu"=>1003,
        			"Eau"=>1004,
        			"Lumiere"=>1005,
        			"Tenebre"=>1006,
        			"Illusion"=>1007,
        			"Psychique"=>1008
        	);
        	return $liste_magiePerso;
        }		
		
        function paramConnection ($config) {
		global $dbmsJeu;
		$configCode ="\n";
		$configCode .="\n";
		$configCode .="\n";
		$configCode .="\n";
		$configCode .="\n";
		$configCode .="\$hostbd = \"".$config["mysql_host"]."\";\n";
		$configCode .="\$userbd = \"".$config["mysql_user"]."\";\n";
		$configCode .="\$passbd = \"".$config["mysql_password"]."\";\n";
		$configCode .="\$bdd = \"".$config["mysql_database"]."\";\n";		
		$configCode .="\$dbmsJeu= \"".$dbmsJeu."\"; //type de base de données \n";
		$nblignes = substr_count ( $configCode, "\n");
		$retour= array();
		$retour[0]=$configCode;
		$retour[1]=$nblignes;
		return $retour;
	}
	
	function versionMoteur($versionLivree) {
		$configCode ="Define(\"VERSION\", \"".$versionLivree."\");	// version du moteur\n";
		return $configCode;
	}
	
	
	/**
	* /param $listePerso ne sert qu'a la maj du moteur pour recuperer les valeurs settees dans l'ancienne version
	*/
	function majConfigListe($nomListe, $listeIntallMoteur, $listePerso=array()) {
	        $configCode ="\n";
                $configCode.="      	\$".$nomListe."=array(\n";
                $i=0;
                $nb = count($listeIntallMoteur);
                foreach($listeIntallMoteur as $key => $value) {
                        if (isset($listePerso[$key]) && $listePerso[$key]<> $value)
                             $configCode.="                         \"$key\"=>\"".$listePerso[$key]."\"";
                        else $configCode.="                         \"$key\"=>\"".$value."\"";
                        if ($i<$nb-1) 
                                $configCode.=",\n";
                        else $configCode.="\n";        
                        $i++;
                }        
                $configCode.="      	);\n";
                $configCode.="\n";
                return $configCode;
	}        

/*	
	function coutsActionsPA($liste_pas_config, $liste_pas_perso=array()){
	        $configCode ="\n";
                $configCode.="      	\$liste_pas_actions=array(\n";
                $i=0;
                $nb = count($liste_pas_config);
                foreach($liste_pas_config as $key => $value) {
                        if (isset($liste_pas_perso[$key]) && $liste_pas_perso[$key]<> $value)
                             $configCode.="                         \"$key\"=>".$liste_pas_perso[$key];
                        else $configCode.="                         \"$key\"=>".$value;
                        if ($i<$nb-1) 
                                $configCode.=",\n";
                        else $configCode.="\n";        
                        $i++;
                }        
                $configCode.="      	);\n";
                $configCode.="\n";
                return $configCode;
	}        

	function coutsActionsPI($liste_pis_config, $liste_pis_perso){
	        $configCode ="\n";
                $configCode.="      	\$liste_pis_actions=array(\n";
                $i=0;
                $nb = count($liste_pis_config);
                foreach($liste_pis_config as $key => $value) {
                        if (isset($liste_pis_perso[$key]) && $liste_pis_perso[$key]<> $value)
                             $configCode.="                         \"$key\"=>".$liste_pis_perso[$key];
                        else $configCode.="                         \"$key\"=>".$value;                        
                        if ($i<$nb-1) 
                                $configCode.=",\n";
                        else $configCode.="\n";        
                        $i++;
                }        
                $configCode.="      	);\n";
                $configCode.="\n";
                return $configCode;
	}     

	
	function listeParamPerso($nomListe, $listeValeurs){
	        $configCode ="\n";
                $configCode.="      	\$".$nomListe."=array(\n";
                $i=0;
                $nb = count($listeValeurs);
                foreach($listeValeurs as $key => $value) {
                        $configCode.="                         \"$key\"=>\"".$value."\"";
                        if ($i<$nb-1) 
                                $configCode.=",\n";
                        else $configCode.="\n";        
                        $i++;
                }        
                $configCode.="      	);\n";
                $configCode.="\n";
                return $configCode;
	}     
*/		
	/**
	*       fonction utilisée lors de l'install et de la mise a jour
	*/
	function listeParamInstall($params){
		$configCode  ="Define(\"INSCRIPTIONS_OUVERTES\",". $params["INSCRIPTIONS_OUVERTES"].");      	//0 pour empecher les inscriptions\n";
		$configCode .="Define(\"MAINTENANCE_MODE\",".$params["MAINTENANCE_MODE"].");         		// Mettez a 0 pour que tous les PJS et MJS puissent se connecter\n";
	        $configCode .="                      				// Mettez a 1 cette variable pour bloquer le jeu (tous les PNJ et PJ)\n";
        	$configCode .="                      				// Mettez a 2 cette variable pour bloquer le jeu (tous les PNJ et PJ) et les MJ sauf celui créé à l'init de la base\n";
		$configCode .="Define(\"IN_NEWS\",".$params["IN_NEWS"].") ;               		//commentez la ligne pour ne pas utiliser les news ou mettre 0\n";
		$configCode .="Define(\"COUNT_QCM\",". $params["COUNT_QCM"].");               		//Pour definir le nombre de question que l'on veut poser avant l'inscription. Si 0 => Inscription sans questionnaire.\n";
		$configCode .="Define(\"DEBUG_MODE\",".   $params["DEBUG_MODE"].");          		// Mettez a 0, pour ne pas avoir de debug; 1 si vous voulez voir des infos de warning, erreur; 2 si pour les requetes SQL en plus; 3 pour SQL + bachtrace en plus; 4 pour erreurs warning + backtrace . Ne laissez surtout pas a 1,2,3,4 pdt le deroulement du jeu reel\n";
        	$configCode .="Define(\"DEBUG_JEU_ONLY\", ".$params["DEBUG_JEU_ONLY"] .");          	// Mettez a 0, pour avoir du debug du jeu + forum +...; 1 pour le jeu uniquement\n";
		$configCode .="Define(\"DEBUG_HTML\",". $params["DEBUG_HTML"].");             		// Mettez a 1 si vous voulez stocker les fichiers HTML générés pour valider la syntaxe HTML . Ne laissez surtout pas a 1 pdt le deroulement du jeu reel\n";
		$configCode .="Define(\"SHOW_TIME\",".   $params["SHOW_TIME"].");             		// Mettez a 1 si vous voulez voir les temps d'execution (SQL et PHP) (Rem: L'affichage se fera de toute facon si DEBUG_MODE=1). Ne laissez surtout pas a 1 pdt le deroulement du jeu reel\n";
		$configCode .="Define(\"AFFICHE_CONNECTES\",".   $params["AFFICHE_CONNECTES"].");       	// Mettez a 1 si vous voulez voir le sous-menu des PJ et MJ connectes  dans le menu de gauche (genere plus de SQL)\n";
		//$configCode .="Define(\"IN_FORUM\",". $params["IN_FORUM"].");       		// commentez la ligne pour ne pas utiliser les liens vers les forums ou mettre 0\n";
		$configCode .="Define(\"AFFICHE_XP\",".	$params["AFFICHE_XP"]."); // Mettez a 1 si vous voulez voir les infos d'XP et de niveau\n";
		$configCode .="Define(\"AFFICHE_PV\",".	$params["AFFICHE_PV"]."); // Mettez a 1 si vous voulez voir les infos des Points de vie, dégats\n";
		//$configCode .="Define(\"AFFICHE_AVATAR_FORUM\",".	$params["AFFICHE_AVATAR_FORUM"]."); // Mettez a 1 si vous voulez afficher les images des PJs dans le lieu\n";
		//$configCode .="Define(\"AFFICHE_NB_MAX_AVATAR\",".$params["AFFICHE_NB_MAX_AVATAR"]."); // Nombre de PJs/MJs/PNJs max dans un meme lieu au dela duquel on n'affiche pas les images (pour des raisons de temps de chargement et de surcharge de la page (-1 = pas de limitation). Ne sert que si AFFICHE_AVATAR_FORUM = 1.\n";

		$configCode .="Define(\"POURCENTAGE_PV_PERSO_AUTOP\",". $params["POURCENTAGE_PV_PERSO_AUTOP"].");\n";
		$configCode .="Define(\"POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE\",". $params["POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE"].");\n";
		$configCode .="Define(\"POURCENTAGE_PV_PERSO_ABIME\",". $params["POURCENTAGE_PV_PERSO_ABIME"].");\n";
		$configCode .="Define(\"POURCENTAGE_PV_PERSO_CRITIQUE\",". $params["POURCENTAGE_PV_PERSO_CRITIQUE"].");\n";

		$configCode .="Define(\"BASE_PAS\",". $params["BASE_PAS"].");		//nb de PA formant la base des PAs des joueurs  \n";
		$configCode .="Define(\"BASE_PVS\",". $params["BASE_PVS"].");		//nb de PV formant la base des PVs des joueurs  \n";
		$configCode .="Define(\"BASE_PIS\",". $params["BASE_PIS"].");		//nb de PI formant la base des PIs des joueurs  \n";
		$configCode .="Define(\"BASE_POS\",". $params["BASE_POS"].");		//nb de PO formant la base des POs des joueurs  \n";
		
		$configCode .="Define(\"QUANTITE_REMISE_PAS\",". $params["QUANTITE_REMISE_PAS"].");		//nb de PA ajoutés à chaque remise de PAs  \n";
		$configCode .="Define(\"QUANTITE_REMISE_PVS\",". $params["QUANTITE_REMISE_PVS"].");		//nb de PV ajoutés à chaque remise de PVs  \n";
		$configCode .="Define(\"QUANTITE_REMISE_PIS\",". $params["QUANTITE_REMISE_PIS"].");		//nb de PI ajoutés à chaque remise de PIs  \n";
		$configCode .="Define(\"QUANTITE_REMISE_POS\",". $params["QUANTITE_REMISE_POS"].");		//nb de PO ajoutés à chaque remise de POs  \n";
		
		$configCode .="Define(\"INTERVAL_REMISEPI\",". $params["INTERVAL_REMISEPI"]."); //intervalle de temps (en heures) pour la remise des PI\n";
		$configCode .="Define(\"INTERVAL_REMISEPA\",". $params["INTERVAL_REMISEPA"]."); //intervalle de temps (en heures) pour la remise des PA\n";
		$configCode .="Define(\"META_KEYWORDS\", \"".$params["META_KEYWORDS"]."\"); //meta keywords\n";
		$configCode .="Define(\"META_DESCRIPTION\", \"".$params["META_DESCRIPTION"]."\"); //meta description\n";
		$configCode .="Define(\"NOM_JEU\",\"".$params["NOM_JEU"]."\");\n";	
		$configCode  .= "Define (\"TAILLE_MAX_FA\", ".$params["TAILLE_MAX_FA"]."); //taille max en Ko du Fichier d'actions avant qu'il se fasse effacer et soit envoye par mail.) \n";  
		 return $configCode;      
	}        
	
	
	/**
	*       fonction utilisée lors de la mise a jour et du menu config
	*/	
	function listeParamMAJ_config($params){
        	$configCode = "Define (\"GROUPE_PJS\", ".$params["groupePJs"]."); //Mettez 1 pour permettre aux PJs de se regrouper en groupe (pour déplacements communs par ex.) \n";
        	$configCode .= "Define (\"RIPOSTE_AUTO\", ".$params["riposteAuto"]."); //Mettez 1 pour permettre aux PJs de riposter automatiquement en cas d'absence \n";
        	$configCode .= "Define (\"RIPOSTE_GROUPEE\", ".$params["riposteGroupee"]."); //Mettez 1 pour permettre la riposte de groupe (Si un joueur d'un groupe est attaque et que l'assaillant n'est pas dans un groupe, alors tous les membres du groupe ripostent) \n";
        	$configCode .= "Define (\"ENGAGEMENT\", ".$params["engagement"]."); //Mettez 1 pour gérer l'engagement/dégagement lors d'une attaque avec une arme de toucher \n";
        	$configCode .= "Define (\"SECACHER\", ".$params["secacher"]."); //Mettez 1 pour Permettre aux joueurs de se dissimuler dans un lieu  \n";
        	$configCode .= "Define (\"DISTANCE_CRI\", ".$params["distance_cri"]."); //distance en PA Maxi entre 2 lieux ou porte un cri \n";
        	$configCode .= "Define (\"RESURRECTION\", ".$params["resurrection"]."); //Mettez 1 pour autoriser la résurrection des PJ (sinon le joueur doit refaire un nouveau PJ)  \n";
        	$configCode .= "Define (\"PV_RESURRECTION\", ".$params["pv_resurrection"]."); //Mettez le nombre de pv qu'a un pj réssucité  \n";
        	$configCode .= "Define (\"NB_MAX_RESURRECTION\", ".$params["nb_max_resurrection"]."); //Nb Max de resurrections \n";
        	$configCode .= "Define (\"LIEU_RESURRECTION\", '".$params["lieu_resurrection"]."'); //Lieu ou se retrouve le PJ apres resurrection (Pas de changement, sinon ID du lieu) \n";
        
        	$configCode .= "Define (\"AFFICHE_PRIX_OBJET_SORT\", '".$params["affiche_prix_objet_sort"]."'); //Affiche le prix des objets et des sorts\n";
          $configCode .= "Define (\"FOUILLE_OBJETS_EQUIPES\", '".$params["fouille_objets_equipes"]."'); //Autorise ou non la recuperation des objets equipes sur un mort\n";
          $configCode .= "Define (\"REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES\", '".$params["reussite_auto_fouille_objets_equipes"]."'); //La fouille d'un mort est automatiquement une reussite pour un objet equipe\n";
	        $configCode .= "Define (\"DELAI_SUPPRESSION_MONSTRESMORTS\", '".$params["delai_suppression_monstresmorts"]."'); //Délai avant suppression des monstres morts\n";
	        $configCode .= "Define (\"MAIL_FA_ARCHIVES\", '".$params["mail_fa_archives"]."'); //Adresse mail de réception des FA archives\n";
		return $configCode;      
	}        
	
	
	function listeParamForum($foruminclus, $lien_forum, $typeforumChoix, $typeforum, $aff_ava, $nb_aff_ava, $hautMaxLieu, $largMaxLieu,$creeMembrePNJ=0 ) {
			if ($foruminclus) {
				//cherche si index.php du forum existe avec le lien fourni
				logdate("fichier cherche: $lien_forum");
				if (file_exists($lien_forum)) {
					logdate("fichier $lien_forum existant ");
				}
				else logdate("fichier $lien_forum inexistant");
				if (substr($lien_forum,0,4)=="http" || file_exists($lien_forum)) {
					$Modif ="Define(\"IN_FORUM\", ".$foruminclus.");       		// commentez la ligne pour ne pas utiliser les liens vers les forums ou mettre 0\n";
					$Modif .="Define(\"CHEMIN_FORUM\",\"".$lien_forum."\"); \n";
					$Modif .="\$typeforum = \"".$typeforumChoix."\"; \n";
					$Modif .= "Define(\"AFFICHE_AVATAR_FORUM\",	".$aff_ava."); // Mettez a 1 si vous voulez afficher les images des PJs dans le lieu\n";
					$Modif .= "Define(\"AFFICHE_NB_MAX_AVATAR\",	".$nb_aff_ava."); // Nombre de PJs/MJs/PNJs max dans un meme lieu au dela duquel on n'affiche pas les images (pour des raisons de temps de chargement et de surcharge de la page (-1 = pas de limitation). Ne sert que si AFFICHE_AVATAR_FORUM = 1.\n";
					if (!isset($hautMaxLieu) || $hautMaxLieu=="")
					        $hautMaxLieu="-1";
					if (!isset($largMaxLieu) || $largMaxLieu=="")
					        $largMaxLieu="-1";
					$Modif .= "Define(\"HAUT_MAX_LIEU\",	".$hautMaxLieu."); // Hauteur Max s'une image de lieu. (-1 = pas de limitation). \n";
					$Modif .= "Define(\"LARG_MAX_LIEU\",	".$largMaxLieu."); // Largeur Max s'une image de lieu. (-1 = pas de limitation). \n";
		                        if (!isset($creeMembrePNJ) || $creeMembrePNJ=="")
		                                $creeMembrePNJ=0;		                        
		                        $Modif .= "Define(\"CREE_MEMBRE_PNJ\",	".$creeMembrePNJ."); // Créé un membre du forum par PNJ (nécessite que chaque PNJ ait une adresse email unique). (0 = non 1 = Oui). \n";
					
					if ((!isset($typeforum)) || $typeforumChoix <> $typeforum) {
						$forum = instancieForum ($typeforumChoix,$lien_forum);
						$forum->postInstall();
						$forum->synchronyseForumJeu();
					}	
				}
				else {
					$Modif ="Define(\"IN_FORUM\", 0);       		// commentez la ligne pour ne pas utiliser les liens vers les forums ou mettre 0\n";
					$GLOBALS['MessageWarning'].="Forum introuvable avec le chemin fourni. Le forum est-il déjà installé ? En attendant, le moteur va se comporter sans forum.";
				}	
			}	
			else $Modif ="Define(\"IN_FORUM\", 0);       		// commentez la ligne pour ne pas utiliser les liens vers les forums ou mettre 0\n";
			return $Modif;
	}		
	
	        
?>
