<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: const.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.36 $
$Date: 2010/01/24 19:33:13 $

*/

require_once("../include/extension.inc");
if(!defined("__CONST.PHP") ) {
	Define("__CONST.PHP",	0);


	Define("FICHIER_LOG", "../logs/error.log"); //Fichier dans lequel les erreurs sont stockes si DEBUG_MODE >= 1. 

	Define("REP_HTML", "../html/"); //Rpertoire dans lequel les fichiers gnrs sont stockes si DEBUG_HTML gale 1. 

	/** malus cumulatifs lors d'attaques multples: 
	// attaque main droite => -2 ; attaque main gauche => -2 + -4 => -6
	//les bonusmalus sont ajoutes dans le calcul de reussite => Il faut bien les mettre en negatifs ici
	**/
	Define("MALUS_ATTAQUE1_LORS_PLUSIEURS_ATTAQUES", -2);
	Define("MALUS_ATTAQUE2_LORS_PLUSIEURS_ATTAQUES", -4);
	

	Define("SAUVEGARDE","/Sauvegarde/");   //Chemin de sauvegarde (A commenter si vous ne voulez pas l'utiliser ou pour FREE ou cela ne fonctionne pas)


	//Fin des variables de personalisation du jeu

	Define("BOUTON_ENVOYER",	"<input type=\"submit\" value=\"Envoyer\" />");
	Define("HR",	'<hr />');
	Define("HR2",	'<hr />');

	Define("PALIER_MAX_XP_EXPONENTIELLE",	256); // a partir de combien d'xp le niveau suivant n'est plus a 2 puissance niveau mais a niveau precedent + palier_max


	$liste_caracs = array(
			"Force"=>1,	
			"Sagesse"=>2,
			"Dexterite"=>3,
			"Intelligence"=>4,
			"Constitution"=>5,
			"Charisme"=>6
	);

	/**
		L'affichage dans la fiche de perso ne depand pas du n affecte
		mais uniquement de l'ordre d'apparition dans le tableau.
		competences sont decoupees en competencesArmes et competenceAutres pour pouvoir crer des armures protegeant
		des competencesArmes
	*/	
	$liste_competencesArmes = array(
		"Lame Courte"=>201,		"Lame Longue"=>202,		 	
		"Masse Legere"=>301,		"Masse Lourde"=>302,		 	
		"Hache Courte"=>401,		"Hache Longue"=>402,		 	 
		"Arc Court"=>501,		"Arc Long"=>502,		 	
		"Petite Fronde"=>601,		"Grande Fronde"=>602,		 	 
		"Lance Courte"=>801,		"Lance Longue"=>802,		 	
		"Petite Arbalete"=>901,		"Grande Arbalete"=>902,		 	"Arts Martiaux"=>903,
		"Artefact Mineur"=>701,		"Artefact Majeur"=>702,	
			
	);      
	
	/**
		L'affichage dans la fiche de perso ne depand pas du n affecte
		mais uniquement de l'ordre d'apparition dans le tableau
	*/	
	$liste_competencesAutres = array(
		"Vol"=>203,		
		"Crochetage"=>303,	
		"Dissimulation"=>403,	 
		"Vigilance"=>503,     
		"Aura"=>603,            
		"Alphabetisation"=>803,
		"Observation"=>703,	
		"Escalade"=>911,        	
    "Nage"=>912, 			 	
		"AttaqueSournoise"=>913,     	
    "Combat2Armes"=>914   	
	);      
	
	$liste_competences = array_merge($liste_competencesArmes,$liste_competencesAutres);
                
        $liste_artisanat =array (
        		"Bucheron"=> 211,    	
            "Mineur"=>212,             	
            "ArtisanCuir"=>213,	
		        "Carriere"=>214,      	
            "Cueilleur"=>215,		
            "Ebeniste"=> 221, 	
		        "Forgeron"=> 222, 	
            "Tanneur"=> 223, 		
            "Macon"=> 224,	      
        		"Soin Naturel"=>225,	
            "Tisseur"=>226,    		
            "Brasseur"=>227,
        		"ArtisanArmure"=>231,	
            "ArtisanArmeMelee"=>232,  	
            "ArtisanArmeJet"=>233,           
        		"ArtisanOutil"=>241,    
            "ArtisanParcheminMagique"=>242,	
            "ArtisanEnsorceleur"=>243,  
        		"Orfvre"=>244      	
        );       
        

        if ( array_key_exists  ("liste_magiePerso", get_defined_vars()) &&  is_array ( $liste_magiePerso ))
   	        $liste_magie = $liste_magiePerso;
   	else    $liste_magie =array();     

	$liste_comp_full = array_merge($liste_magie,$liste_competences,$liste_caracs, $liste_artisanat);

	$liste_flags_lieux=array(
			"Manger"=>0,
			"Attaquer"=>1,
			"Magie"=>2,
			"Lire"=>3,
			"Prier"=>4,
			"Voler"=>5,
			"Banque"=>6,
			"SeCacher"=>7,
			"FouillerCadavre"=>8,
			"FouillerLieu"=>9,
			"EntendreCriExterieur"=>10,
			"Parler"=>11,
			"Recevoir des sorts extrieurs"=>12,
			"SoignerAvecObjet"=>13
			
	);
	
	


	$liste_flags_mj=array(
		"Status"=>0,
		"FAs"=>1,
		"CreerEtat"=>2,
		"ModifierEtat"=>3,
		"CreerSpec"=>4,
		"ModifierSpec"=>5,
		"SupprimerSpec"=>6,
		"CreerObjet"=>7,
		"ModifierObjet"=>8,
		"SupprimerObjet"=>9,
		"CreerMagie"=>10,
		"ModifierMagie"=>11,
		"SupprimerMagie"=>12,
		"ModifierXP"=>13,
		"ModifierInv"=>14,
		"ModifierGrim"=>15,
		"ModifierSpecPJ"=>16,
		"ModifierEtatPJ"=>17,
		"ModifierInfoPJ"=>18,
		"ParlerPJ"=>19,
		"ParlerLieu"=>20,
		"CreerLieu"=>21,
		"SupprimerLieu"=>22,
		"ModifierLieu"=>23,
		"ModifierDescLieu"=>24,
		"CreerChemin"=>25,
		"SupprimerChemin"=>26,
		"ModifierChemin"=>27,
		"ModifierMagasinMagique"=>28,
		"ModifierArmurerie"=>29,
		"ModifierQuincaillerie"=>30,
		"CreerMJ"=>31,
		"ModifierMJ"=>32,
		"SupprimerMJ"=>33,
		"InscrirePJ"=>34,
		"SupprimerPJ"=>35,
		"DeplacerPJ"=>36,
		"ModifierLieuCompetences"=>37,
		"ParlerMJ"=>38,
		"VoirLieu"=>39,
		"Registre"=>40,
		"RegistreLieux"=>41,
		"listeObjets"=>42,
		"listeSorts"=>43,
		"SupprimerEtat"=>44,
		"Gestion News"=>45,
		"listeEtats"=>46,	
	
		//Question
		"CreerQuestion"=>47,
		"ModifierQuestion"=>48,
		"SupprimerQuestion"=>49,
		"listeSpecs"=>50,
		"CreerCarte"=>51,
		"VoirCarte"=>52,
		"ModifierProductionNaturelle"=>53,
		"DonnerDroitsMJauxPJs"=>54,
		"SupprimerDroitsMJauxPJs"=>55,
		"ModifierDroitsMJauxPJs"=>56,
		"listeMagasins"=>57,
		"CacherObjet"=>58,
		"CreerQuete"=>59,
		"ModifierQuete"=>60,
		"SupprimerQuete"=>61,
		"ModifierQuetePJ"=>62,
		"ProposerQuetePJ"=>63,
		"listeQuetes"=>64,
		"CreerBestiaire"=>65,
		"ModifierBestiaire"=>66,
		"SupprimerBestiaire"=>67,
		"VoirLogs"=>68,
		"PurgerLogs"=>69,
		"VoirActionsTracess"=>70,
		"SupprimerActionsTracees"=>71
		
	);

	$liste_types_chemins=array(
		"Lieu Entrer"=>0,
		"Lieu Passage"=>1,
		"Lieu Guilde"=>2,
		"Lieu Aller"=>3,
		"Lieu Secret"=>4,	
		"Lieu Escalader"=>5,
		"Lieu Nager"=>8,
		"Lieu Peage"=>9
	);

	$liste_types_magasins=array(
		"Armurerie"=>0,				///< un magasin qui vend, achete des objets de type ArmeMelee, ArmeJet, Armure, Munition
		"Magasin Magique"=>1,			///< un magasin qui vend des sorts
		"Quincaillerie"=>2,			///< un magasin qui vend, achete des objets de type Outil, ObjetSimple, Divers, ProduitNaturel, Soins
		"Armurerie-Recharge"=>3,		///< un magasin qui recharge des objets de type ArmeMelee, ArmeJet, Armure
		"Armurerie-Repare"=>4,			///< un magasin qui repare des objets de type ArmeMelee, ArmeJet, Armure
		"Magasin Magique-Recharge"=>5,		///< un magasin qui permet de recharger des sorts
		"Lieu d'apprentissage"=>6,		///< un magasin qui permet de se former  une comptence
		"Produits Naturels"=>7			///< pas un magasin mais un lieu de production de produits naturels (carriere, foret, ....)
	);

	$liste_relations=array(
		0=>"Alli",
		1=>"Amical",
		2=>"Neutre",
		3=>"Inamical",
		4=>"Ennemi"
	);


	$liste_reactions=array(
		0=>"Tenter de fuir",
		1=>"Appeler  l'aide (ou au voleur)",
		2=>"Riposter avec arme(s) quipe(s)",
		3=>"Riposter avec sort prfr",
		4=>"Pas de raction",
		5=>"Voler"		
	);


	$liste_ActionSurprise=array(
		0=>"Voler",
		1=>"Attaquer (arme)",
		2=>"Lancer un sort (magie)",
		4=>"Pas d'action",
		5=>"Parler"		
	);


	$liste_type_objetSecret=array(
		0=>"Passage",
		1=>"Objet",
		2=>"Perso",
		3=>""		
	);

	$liste_type_cible=array(
		1=>"1 PJ",
		2=>"1 Zone",
		3=>"Lanceur"		
	);

	$liste_type_quete=array(
		1=>"Trouver Lieu",
		2=>"Trouver pj",
		3=>"Trouver objet",
		4=>"Tuer pj",
		5=>"Voler pj",
		6=>"Trouver PO",
		7=>"Tuer monstres du lieu"
	);
	
	$liste_type_recompense=array(
		//1=>"Gain d'XP",
		2=>"Gain de PO",
		3=>"gain d'objet",
		4=>"gain de sort",
		5=>"gain de competence",
		6=>"gain d'etat temporaire"
	);

	$liste_type_punition=array(
		//1=>"Gain d'XP",
		2=>"Perte de PO",
		3=>"perte d'objet",
		4=>"perte de sort",
		5=>"perte de competence",
		6=>"perte d'etat temporaire"
	);

	$liste_etat_quete=array(
		1=>"Propose",					///< le proposant propose la quete au PJ
		2=>"Accepte (en cours)",       		///< le PJ accepte la quete
		3=>"Refuse",					///< le PJ refuse la quete
		4=>"Abandonne",				///< le PJ abandonne la quete
		5=>"Echoue",					///< le PJ a echoue
		6=>"Russie (en attente de validation)",	///< le PJ declare avoir temine la quete
		7=>"Russie (valide)",				///< la quete est bien russie (valide par le proposant ou automatiquement)
		8=>"Annule par proposant",
		9=>"Echoue (temps limite atteint)"		///< le PJ a echoue a cause du temps			
	);
	
	$liste_type_propose_quete=array(
		1=>"MJ",
		2=>"PJ"
	);	
	
	$liste_type_lieu_apparition=array(	            
                    1=>"Autre/Indfini"
	);	

        if (array_key_exists  ("liste_type_lieu_apparitionPerso", get_defined_vars()) && is_array($liste_type_lieu_apparitionPerso))
                //array merge ne va pas ici car il reindexe les valeurs numeriques.... il faut donc faire un +
           	$liste_type_lieu_apparition = $liste_type_lieu_apparition + $liste_type_lieu_apparitionPerso;
     
	//For MJ
	/** Modif Hixcks pour grer les armes  deux mains ....
	La valeur du tableau est elle meme un tableau qui contient: 
		- La chaine de caractere affiche lors de la cration de l'objet
		- La partie du corps qui sera quipe avec l'objet
		- la quantit totale disponible pour un PJ normal :
			2 bras (pour les armes et les boucliers), 10 doigts (pour les bagues), 1 tte (pour les casques...)
			, 1 paire de jambes (pour les bottes, jambires ...), 1 corps (pour les armures, plastron ), 1 cou (pour les amulettes, colliers ....)
		- la quantit qu'occupe l'objet (1 bras pour une pe courte, 2 bras pour une pe  2 mains)
		- nouveaute de la 3.4, la comptence utilise pour rparer l'objet ou pour le crer en combinant d'autres objets
	=> On pourra mettre 10 bagues (1 par doigt), 1 pe courte et un bouclier, ou 2 pes  une main
	Pour les objets que l'on utilise mais dont on ne s'quipe pas (Ex: livre ..) , on peut laisser le denier paramtre  0 et mettre n'importe quoi a l'avant dernier
	*/
	
		$liste_type_objs=array(
       			"ArmeMelee;Lame Courte"=>array("Arme - Lame Courte","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Lame Longue  une main"=>array("Arme - Lame Longue  une main","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Lame Longue  2 mains"=>array("Arme - Lame  2 mains","Bras",2,2,"ArtisanArmeMelee"),
			"ArmeMelee;Masse Legere"=>array("Arme - Masse Legere","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Masse Lourde"=>array("Arme - Masse Lourde","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Hache Courte"=>array("Arme - Hache Courte","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Hache Longue"=>array("Arme - Hache Longue","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeJet;Arc Court"=>array("Arme - Arc Court","Bras",2,2,"ArtisanArmeJet"),
			"ArmeJet;Arc Long"=>array("Arme - Arc Long","Bras",2,2,"ArtisanArmeJet"),
			"ArmeJet;Petite Fronde"=>array("Arme - Petite Fronde","Bras",2,2,"ArtisanArmeJet"),
			"ArmeJet;Grande Fronde"=>array("Arme - Grande Fronde","Bras",2,2,"ArtisanArmeJet"),
			"ArmeJet;Artefact Mineur"=>array("Arme - Artefact Mineur","Bras",2,1,"ArtisanEnsorceleur"),
			"ArmeJet;Artefact Majeur"=>array("Arme - Artefact Majeur","Bras",2,1,"ArtisanEnsorceleur"),
			"ArmeMelee;Lance Courte"=>array("Arme - Lance Courte","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeMelee;Lance Longue"=>array("Arme - Lance Longue","Bras",2,1,"ArtisanArmeMelee"),
			"ArmeJet;Petite Arbalete"=>array("Arme - Petite Arbalete","Bras",2,1,"ArtisanArmeJet"),
			"ArmeJet;Grande Arbalete"=>array("Arme - Grande Arbalete","Bras",2,1,"ArtisanArmeJet"),
			"ArmeMelee;Arts Martiaux"=>array("Arme - Arts Martiaux","Bras",2,0,""),
			"Armure;Casque"=>array("Armure - Casque","Tete",1,1,"ArtisanArmure"),
			"Armure;Plastron"=>array("Armure - Plastron","Corps",1,1,"ArtisanArmure"),
			"Armure;Gants"=>array("Armure - Gants","Mains",1,1,"ArtisanArmure"),
			"Armure;Jambieres"=>array("Armure - Jambieres","Jambes",1,1,"ArtisanArmure"),
			"Armure;Bague"=>array("Armure - Bague","Doigts",10,1,"ArtisanEnsorceleur"),
			"Armure;Amulette"=>array("Armure - Amulette","Cou",1,1,"ArtisanEnsorceleur"),
			"Armure;Bouclier"=>array("Armure - Bouclier","Bras",2,1,"ArtisanArmure"),
			"Divers;Divers"=>array("Divers - Divers","Divers",1,0,""),
			"Divers;Livre"=>array("Divers - Livre","Divers",1,0,""),
			"Divers;Parchemin"=>array("Divers - Parchemin","Divers",1,0,"ArtisanParchemins"),
			"Divers;Passe Partout"=>array("Divers - Passe Partout","Divers",1,0,""),
			"Divers;Clef"=>array("Divers - Clef","Divers",1,0,""),
			//"Divers;Nourriture"=>array("Divers - Nourriture","Divers",1,0,""),
			"Quete;Divers"=>array("Objet de Quete","Divers",1,0,""),
			"Munition;Arc Court"=>array("Munition - Arc Court","Bras",2,0,"ArtisanArmeJet"),
			"Munition;Arc Long"=>array("Munition - Arc Long","Bras",2,0,"ArtisanArmeJet"),
			"Munition;Petite Fronde"=>array("Munition - Petite Fronde","Bras",2,0,"ArtisanArmeJet"),
			"Munition;Grande Fronde"=>array("Munition - Grande Fronde","Bras",2,0,"ArtisanArmeJet"),
			"Munition;Petite Arbalete"=>array("Munition - Petite Arbalete","Bras",2,0,"ArtisanArmeJet"),
			"Munition;Grande Arbalete"=>array("Munition - Grande Arbalete","Bras",2,0,"ArtisanArmeJet"),
			"Soins;Fiole"=>array("Soins - Fiole de soin","Soins",1,0,"Soin Naturel"),
			"SoinsPI;Fiole"=>array("SoinsPI - Fiole de Restauration de PI","SoinsPI",1,0,"Soin Naturel"),
			"Outil;Carriere"=>array("Outil - Carriere","Bras",2,1,"ArtisanOutil"),
			"Outil;Mineur"=>array("Outil - Mineur","Bras",2,1,"ArtisanOutil"),
			"Outil;Bucheron"=>array("Outil - Bucheron","Bras",2,1,"ArtisanOutil"),
			"Outil;Cueilleur"=>array("Outil - Cueilleur","Bras",2,1,"ArtisanOutil"),
			"Outil;Forgeron"=>array("Outil - Forgeron","Bras",2,1,"ArtisanOutil"),
			"Outil;Ebeniste"=>array("Outil - Ebeniste","Bras",2,1,"ArtisanOutil"),
			"Outil;Macon"=>array("Outil - Macon","Bras",2,1,"ArtisanOutil"),
			"Outil;Tisseur"=>array("Outil - Tisseur","Bras",2,1,"ArtisanOutil"),
			"Outil;Brasseur"=>array("Outil - Brasseur","Bras",2,1,"ArtisanOutil"),
			"Outil;ArtisanArmeJet"=>array("Outil - ArtisanArmeJet","Bras",2,1,"ArtisanOutil"),
			"Outil;ArtisanArmeMelee"=>array("Outil - ArtisanArmeMelee","Bras",2,1,"ArtisanOutil"),
			"Outil;ArtisanArmure"=>array("Outil - ArtisanArmure","Bras",2,1,"ArtisanOutil"),
			"Outil;ArtisanOutil"=>array("Outil - ArtisanOutil","Bras",2,1,"ArtisanOutil"),
			"ProduitNaturel;Vegetaux"=>array("ProduitNaturel - Vegetaux","Divers",2,1,""),
			"ProduitNaturel;Metal"=>array("ProduitNaturel - Metal","Divers",2,1,""),
			"ProduitNaturel;Bois"=>array("ProduitNaturel - Bois","Divers",2,1,""),
			"ProduitNaturel;Pierre"=>array("ProduitNaturel - Pierre","Divers",2,1,""),
			"ProduitNaturel;Nourriture"=>array("ProduitNaturel - Nourriture","Divers",2,1,""),
			"ObjetSimple;Metal"=>array("ObjetSimple - Metal","Divers",2,1,""),
			"ObjetSimple;Bois"=>array("ObjetSimple - Bois","Divers",2,1,""),
			"ObjetSimple;Pierre"=>array("ObjetSimple - Pierre","Divers",2,1,""),
			"Argent;Bourse"=>array("Argent - Bourse","Divers",1,0,""),
			"Nourriture;Nourriture"=>array("Nourriture - Nourriture","Nourriture",2,1,""),
	                "Nourriture;Dopant"=>array("Nourriture - Dopant","Nourriture",2,1,""),
		        "Nourriture;Stimulant"=>array("Nourriture - Stimulant","Nourriture",2,1,""),
		        "Nourriture;Consistant"=>array("Nourriture - Consistant","Nourriture",2,1,""),
		        "Nourriture;Vitaminant"=>array("Nourriture - Vitaminant","Nourriture",2,1,""),
		        "Nourriture;Revigorant"=>array("Nourriture - Revigorant","Nourriture",2,1,""),
		        "Nourriture;Rare"=>array("Nourriture - Rare","Nourriture",2,1,"")			
		) ;

		$liste_stype_sorts=array(
			"Paralysie"=>"Paralysie",
			"Attaque"=>"Attaque",
			"Soin"=>"Soin",
			"Teleport"=>"Teleport",
			"Teleport Self"=>"Teleport Self",
			"Transfert"=>"Transfert",
			"Resurrection"=> "Resurrection" /*,
			"Rparation d'objet"=>"Rparation d'objet", 
			"Invocation de monstres"=>"Invocation de monstres",
			"Invocation objet"=>"Invocation objet"*/
		);



	$liste_langue=array(
		"fr"=>"Francais"/*,
		"en"=>"Anglais"
		*/
	);
	
	
	/**
	Cette fonction sert a transformer les donnes saisies par l'utilisateur
	avant de les stocker en base	
	*/
	function ConvertAsHTML($msg){
		$msg = stripslashes($msg);
		$msg = stripslashes($msg);
		$msg = str_replace("<?php","",$msg);
		//supprimer les balises forme php simple (au cas ou short_open_tag serait a on)
		$msg = str_replace("<?","",$msg);
		//supprimer les balises forme asp (au cas ou asp_tags serait a on)
		$msg = str_replace("<%","",$msg);
		$msg = str_replace("\?\>","",$msg);
		$msg = str_replace("'","&#39;",$msg);
		$msg = str_replace(",","&#44;",$msg);
		$msg = str_replace('"',"&#34;",$msg);
		$msg = str_replace("\\","&#92;",$msg);
		//$msg = nl2br($msg);
		return $msg;
	}


	function ConvertAsTXT($msg){
		$msg = stripslashes($msg);
		$msg = stripslashes($msg);
		$msg = str_replace("&#39;","'",$msg);
		$msg = str_replace("&#44;",",",$msg);
		$msg = str_replace("&#92;","\\",$msg);
		$msg = str_replace("&#34;",'"',$msg);
		$msg = str_replace("<br />","",$msg);
		$msg = htmlentities($msg);
		$msg = str_replace(" ","&nbsp;",$msg);
		return $msg;
	}

}
?>
