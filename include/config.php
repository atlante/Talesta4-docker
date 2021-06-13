<?php
// config.php créée Sun Jun 13 10:45:57 2021
//Par Talesta Install Script

Define("NOM_TABLE_SESSIONS",				"tlt_sessions");
Define("NOM_TABLE_REGISTRE",				"tlt_perso");
Define("NOM_TABLE_PERSO",					"tlt_perso");
Define("NOM_TABLE_ENTITECACHEECONNUEDE",	"tlt_entitecacheeconnuede");
Define("NOM_TABLE_ENTITECACHEE",			"tlt_entitecachee");
Define("NOM_TABLE_LIEU",					"tlt_lieu");
Define("NOM_TABLE_COMP",					"tlt_comp");
Define("NOM_TABLE_SPEC",					"tlt_spec");
Define("NOM_TABLE_SPECNOM",				"tlt_specnom");
Define("NOM_TABLE_PERSOSPEC",				"tlt_persospec");
Define("NOM_TABLE_OBJET",					"tlt_objets");
Define("NOM_TABLE_PERSOOBJET",				"tlt_persoobjets");
Define("NOM_TABLE_PERSOMAGIE",				"tlt_persomagie");
Define("NOM_TABLE_MAGIE",					"tlt_magie");
Define("NOM_TABLE_CHEMINS",				"tlt_chemins");
Define("NOM_TABLE_MJ",						"tlt_mj");
Define("NOM_TABLE_PERSOETATTEMP",			"tlt_persoetattemp");
Define("NOM_TABLE_ETATTEMP",				"tlt_etattemp");
Define("NOM_TABLE_ETATTEMPNOM",			"tlt_etattempnom");
Define("NOM_TABLE_MAGASIN",				"tlt_zone");
Define("NOM_TABLE_INSCRIPTION",			"tlt_inscriptions");
Define("NOM_TABLE_ARCHIVE",				"tlt_archive");
Define("NOM_TABLE_GROUPE",					"tlt_groupe");
Define("NOM_TABLE_COMPOSITIONGROUPE",		"tlt_compositiongroupe");
Define("NOM_TABLE_TYPEETAT",				"tlt_typeetattemp");
Define("NOM_TABLE_QCM",					"tlt_qcm");
Define("NOM_TABLE_ENGAGEMENT",			"tlt_engagement");
Define("NOM_TABLE_NEWS",			"tlt_n_news");
Define("NOM_TABLE_COMMENT_NEWS",			"tlt_n_commentaires");
Define("NOM_TABLE_CONFIG_NEWS",			"tlt_n_config");
Define("NOM_TABLE_INSCRIPT_ETAT",			"tlt_inscriptetattemp");
Define("NOM_TABLE_QUETE",			"tlt_quetes");
Define("NOM_TABLE_RECOMPENSE_QUETE",			"tlt_recompensequete");
Define("NOM_TABLE_PERSO_QUETE",			"tlt_persoquete");
Define("NOM_TABLE_APPARITION_MONSTRE",			"tlt_apparitionmonstre");
Define("NOM_TABLE_PPA",			"tlt_ppa");
Define("NOM_TABLE_TRACE_ACTIONS",			"tlt_traceactions");





$hostbd = "db.local";
$userbd = "talesta4";
$passbd = "6nn79WQcQ";
$bdd = "talesta4";
$dbmsJeu= "mysql"; //type de base de données 








/// ATTENTION CE COMMENTAIRE DOIT TOUJOURS SE TROUVER A LA LIGNE 60 (soixante).

Define("VERSION", "V3_6");	// version du moteur
Define("INSCRIPTIONS_OUVERTES", 1);      	//0 pour empecher les inscriptions
Define("MAINTENANCE_MODE", 0);         		// Mettez a 0 pour que tous les PJS et MJS puissent se connecter
                      						// Mettez a 1 cette variable pour bloquer le jeu (tous les PNJ et PJ)
                      						// Mettez a 2 cette variable pour bloquer le jeu (tous les PNJ et PJ) et les MJ sauf celui créé à l'init de la base
Define("IN_NEWS", 1) ;               		//commentez la ligne pour ne pas utiliser les news ou mettre 0
Define("COUNT_QCM", 1);               		//Pour definir le nombre de question que l'on veut poser avant l'inscription. Si 0 => Inscription sans questionnaire
Define("DEBUG_MODE", 1);          		// Mettez a 0, pour ne pas avoir de debug; 1 si vous voulez voir des infos de warning, erreur; 2 si pour les requetes SQL en plus; 3 pour SQL + bachtrace en plus; 4 pour erreurs warning + backtrace . Ne laissez surtout pas a 1,2,3,4 pdt le deroulement du jeu reel
Define("DEBUG_JEU_ONLY", 1);          	// Mettez a 0, pour avoir du debug du jeu + forum +...; 1 pour le jeu uniquement
Define("DEBUG_HTML", 0);             		// Mettez a 1 si vous voulez stocker les fichiers HTML générés pour valider la syntaxe HTML . Ne laissez surtout pas a 1 pdt le deroulement du jeu reel
Define("SHOW_TIME", 1);             		// Mettez a 1 si vous voulez voir les temps d'execution (SQL et PHP) (Rem: L'affichage se fera de toute facon si DEBUG_MODE=1). Ne laissez surtout pas a 1 pdt le deroulement du jeu reel
Define("AFFICHE_CONNECTES", 1);       	// Mettez a 1 si vous voulez voir le sous-menu des PJ et MJ connectes dans le menu de gauche (genere plus de SQL)
Define("IN_FORUM", 0);       		// commentez la ligne pour ne pas utiliser les liens vers les forums ou mettre 0
Define("AFFICHE_XP",	1); // Mettez a 1 si vous voulez voir les infos d'XP et de niveau
Define("AFFICHE_PV",	1); // Mettez a 1 si vous voulez voir les infos des Points de vie, dégats

Define("POURCENTAGE_PV_PERSO_AUTOP", 80);
Define("POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE", 60);
Define("POURCENTAGE_PV_PERSO_ABIME", 40);
Define("POURCENTAGE_PV_PERSO_CRITIQUE", 20);

Define("BASE_PAS", 20);		//nb de PA formant la base des PAs des joueurs  
Define("BASE_PVS", 25);		//nb de PV formant la base des PVs des joueurs  
Define("BASE_PIS", 20);		//nb de PI formant la base des PIs des joueurs  
Define("BASE_POS", 20);		//nb de PO formant la base des POs des joueurs  
Define("QUANTITE_REMISE_PAS", 5);		//nb de PA ajoutés à chaque remise de PAs  
Define("QUANTITE_REMISE_PVS", 2);		//nb de PV ajoutés à chaque remise de PVs  
Define("QUANTITE_REMISE_PIS", 5);		//nb de PI ajoutés à chaque remise de PIs  
Define("QUANTITE_REMISE_POS", 0);		//nb de PO ajoutés à chaque remise de POs  
Define ("INTERVAL_REMISEPI", 90); //intervalle de temps (en heures) pour la remise des PI
Define ("INTERVAL_REMISEPA", 72); //intervalle de temps (en heures) pour la remise des PA
Define ("META_KEYWORDS", ""); //meta keywords
Define ("META_DESCRIPTION", ""); //meta description
Define ("NOM_JEU", "Talesta 4+"); //nom du jeu affiche dans la barre du navigateur
Define ("TAILLE_MAX_FA", 10); //taille max en Ko du Fichier d'actions avant qu'il se fasse effacer et soit envoye par mail.) 
$langue = "Francais"; 
Define ("GROUPE_PJS", 0); //Mettez 1 pour permettre aux PJs de se regrouper en groupe (pour déplacements communs par ex.) 
Define ("RIPOSTE_AUTO", 0); //Mettez 1 pour permettre aux PJs de riposter automatiquement en cas d'absence 
Define ("RIPOSTE_GROUPEE", 0); //Mettez 1 pour permettre la riposte de groupe (Si un joueur d'un groupe est attaque et que l'assaillant n'est pas dans un groupe, alors tous les membres du groupe ripostent) 
Define ("ENGAGEMENT", 0); //Mettez 1 pour gérer l'engagement/dégagement lors d'une attaque avec une arme de toucher 
Define ("SECACHER", 0); //Mettez 1 pour Permettre aux joueurs de se dissimuler dans un lieu  
Define ("DISTANCE_CRI", 2); //distance en PA Maxi entre 2 lieux ou porte un cri 
Define ("RESURRECTION", 0); //Mettez 1 pour autoriser la résurrection des PJ (sinon le joueur doit refaire un nouveau PJ)  
Define ("PV_RESURRECTION",  10); //Mettez le nombre de pv qu'a un pj réssucité  
Define ("NB_MAX_RESURRECTION", 0); //Nb Max de resurrections 
Define ("LIEU_RESURRECTION", 'Le PJ ne change pas de lieu'); //Lieu ou se retrouve le PJ apres resurrection (Pas de changement, sinon ID du lieu) 
Define ("AFFICHE_PRIX_OBJET_SORT", '0'); //Affiche le prix des objets et des sorts
Define ("FOUILLE_OBJETS_EQUIPES", '0'); //Autorise ou non la recuperation des objets equipes sur un mort
Define ("REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES", '0'); //La fouille d'un mort est automatiquement une reussite pour un objet equipe
Define ("DELAI_SUPPRESSION_MONSTRESMORTS", '-1'); //Délai avant suppression des monstres morts
Define ("MAIL_FA_ARCHIVES", ''); //Adresse mail de réception des FA archives
$template_name = "Original"; 

      	$liste_pas_actions=array(
                         "Manger"=>"-1",
                         "Attaquer"=>"-2",
                         "EnleverArmure"=>"0",
                         "MettreArmure"=>"0",
                         "Magie"=>"0",
                         "FouillerCadavre"=>"-1",
                         "FouillerLieu"=>"-2",
                         "LieuGuilde"=>"-2",
                         "LieuPassage"=>"-2",
                         "LieuEscalader"=>"-3",
                         "LieuNager"=>"-3",
                         "LieuEntrer"=>"0",
                         "Lire"=>"0",
                         "DonnerObjet"=>"-2",
                         "DonnerArgent"=>"-1",
                         "VolerPJ"=>"-2",
                         "Armurerie"=>"-2",
                         "MagasinMagique"=>"-2",
                         "Quincaillerie"=>"-2",
                         "Parler"=>"0",
                         "OublierSort"=>"0",
                         "AbandonnerObjet"=>"0",
                         "Banque"=>"-2",
                         "RechargerObjet"=>"-1",
                         "SeCacher"=>"-2",
                         "Reveler"=>"-1",
                         "SoinObjet"=>"-2",
                         "MontrerCachette"=>"-1",
                         "Crier"=>"2",
                         "CombinerObjets"=>"-1",
                         "Miner"=>"-4",
                         "Cueillir"=>"-2",
                         "Scier"=>"-4",
                         "Carriere"=>"-4",
                         "ReparerObjet"=>"-2",
                         "CreerObjet"=>"-1",
                         "SurcoutDeplacementDiscret"=>"-1",
                         "MagieMajeure"=>"0"
      	);


      	$liste_pis_actions=array(
                         "Manger"=>"0",
                         "Attaquer"=>"0",
                         "EnleverArmure"=>"0",
                         "MettreArmure"=>"0",
                         "Magie"=>"-2",
                         "FouillerCadavre"=>"-1",
                         "FouillerLieu"=>"-2",
                         "LieuGuilde"=>"0",
                         "LieuPassage"=>"0",
                         "LieuEscalader"=>"0",
                         "LieuNager"=>"0",
                         "LieuEntrer"=>"0",
                         "Lire"=>"-1",
                         "DonnerObjet"=>"0",
                         "DonnerArgent"=>"0",
                         "VolerPJ"=>"0",
                         "Armurerie"=>"0",
                         "MagasinMagique"=>"0",
                         "Quincaillerie"=>"0",
                         "Parler"=>"0",
                         "OublierSort"=>"0",
                         "AbandonnerObjet"=>"0",
                         "Banque"=>"0",
                         "RechargerObjet"=>"0",
                         "SeCacher"=>"-2",
                         "Reveler"=>"0",
                         "SoinObjet"=>"-2",
                         "MontrerCachette"=>"-1",
                         "Crier"=>"0",
                         "CombinerObjets"=>"-4",
                         "Miner"=>"-1",
                         "Cueillir"=>"-1",
                         "Scier"=>"-1",
                         "Carriere"=>"-1",
                         "ReparerObjet"=>"-3",
                         "CreerObjet"=>"-4",
                         "SurcoutDeplacementDiscret"=>"-1",
                         "MagieMajeure"=>"-4"
      	);


      	$liste_actions_tracees=array(
                         "Manger"=>"0",
                         "Attaquer"=>"0",
                         "Magie"=>"0",
                         "FouillerCadavre"=>"0",
                         "FouillerLieu"=>"0",
                         "VolerPJ"=>"0",
                         "SeCacher"=>"0",
                         "SoinObjet"=>"0",
                         "Desengager"=>"0",
                         "Crocheter"=>"0",
                         "MotPasse"=>"0",
                         "DonnerArgent"=>"0",
                         "DonnerObjet"=>"0",
                         "RamasserObjet"=>"0",
                         "AbandonnerObjet"=>"0",
                         "CacherObjet"=>"0",
                         "DetruireObjet"=>"0",
                         "SeDeplacer"=>"0"
      	);


      	$liste_type_lieu_apparitionPerso=array(
                         "2"=>"Foret",
                         "3"=>"Caverne, Sous-terrain",
                         "4"=>"Ville fortitiée",
                         "5"=>"Campagne",
                         "6"=>"Village",
                         "7"=>"Intérieur d'un batiment",
                         "8"=>"Désert",
                         "9"=>"Plaine"
      	);


      	$liste_magiePerso=array(
                         "Air"=>"1001",
                         "Terre"=>"1002",
                         "Feu"=>"1003",
                         "Eau"=>"1004",
                         "Lumiere"=>"1005",
                         "Tenebre"=>"1006",
                         "Illusion"=>"1007",
                         "Psychique"=>"1008"
      	);

?>