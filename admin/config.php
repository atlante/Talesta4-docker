<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: config.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.17 $
$Date: 2010/05/15 08:50:22 $

*/
require_once("../include/extension.inc");
define("SESSION_POUR_MJ", 1);
require_once("../include/fct_installUpdate.".$phpExtJeu);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $config;
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

 			$liste_type_debug=array(
				"0"=>"Pas de debug",
				"1"=>"Debug simple (warning, erreur)",
				"2"=>"Debug simple (warning, erreur) + SQL",
				"3"=>"Debug simple (warning, erreur) + SQL + backtrace",
				"4"=>"Debug simple (warning, erreur) + backtrace"
			);

$tab_listes = array (
        array("liste_type_lieu_apparitionPerso", "Liste des Types de lieu (critère pour les apparitions automatiques de monstres)",2,"valeur","cle"),
        array("liste_magiePerso", "Liste des magies",1001,"cle","valeur")
);     

$liste_type_remise=array(
	"TOTALE"=>"Remise Totale (les point remontent à leur maximum)",
	"PARTIELLE"=>"Remise Partielle (les point ne remontent qu'en partie)"
);

if(isset($action) && $action=='write')
{
	$MessageWarning="";
	// ON RECUPERE LES VARIABLES DU FORMULAIRE
	$monfichier = fopen('../include/config.'.$phpExtJeu, 'r+b');
	//$tabfich = file('../include/config.'.$phpExtJeu);
	//RECUPERONS LES 60 PREMIERES LIGNES... POUR PLACER LE CURSEUR OU NOUS AVONS BESOIN D'ECRIRE...
	$texte="";
	for($i = 0; $i < 60; $i++)
	{
	
		$texte .= fgets($monfichier).'\n';
	}

	//MODIFIONS LES LIGNES APRES 60...
	$Modif = "\n";
	if (defined("VERSION")) {
		//$Modif .="Define (\"VERSION\", \"".VERSION."\");	// version du moteur\n";	
		$Modif .=versionMoteur(VERSION);
	}	
	
	$Modif .="Define(\"INSCRIPTIONS_OUVERTES\", ".$inscription.");      	//0 pour empecher les inscriptions\n";
	$Modif .="Define(\"MAINTENANCE_MODE\", ".$maintenance.");         		// Mettez a 0 pour que tous les PJS et MJS puissent se connecter\n";
	$Modif .="                      						// Mettez a 1 cette variable pour bloquer le jeu (tous les PNJ et PJ)\n";
	$Modif .="                      						// Mettez a 2 cette variable pour bloquer le jeu (tous les PNJ et PJ) et les MJ sauf celui créé à l'init de la base\n";
	$Modif .="Define(\"IN_NEWS\", ".$news.") ;               		//commentez la ligne pour ne pas utiliser les news ou mettre 0\n";
	$Modif .="Define(\"COUNT_QCM\", ".$qcm.");               		//Pour definir le nombre de question que l'on veut poser avant l'inscription. Si 0 => Inscription sans questionnaire\n";


	if (($inscription) && $qcm >0){
		$SQL    = "SELECT COUNT(id_question) as c FROM ".NOM_TABLE_QCM;
		$result = $db->sql_query($SQL);	
		$row = $db->sql_fetchrow($result);
		$nbQuestions = $row["c"];
		if ($qcm > $nbQuestions) {
			$MessageWarning.="Il n'y a que $nbQuestions questions pour le QCM actuellement.<br />";	
		}	
	}
	$valeurDebug = array_search($mode_debug, $liste_type_debug);
	if ($valeurDebug===FALSE)
		$valeurDebug=0;
	$Modif .="Define(\"DEBUG_MODE\", ".$valeurDebug .");          		// Mettez a 0, pour ne pas avoir de debug; 1 si vous voulez voir des infos de warning, erreur; 2 si pour les requetes SQL en plus; 3 pour SQL + bachtrace en plus; 4 pour erreurs warning + backtrace . Ne laissez surtout pas a 1,2,3,4 pdt le deroulement du jeu reel\n";
	if (!isset($debug_jeu_only))
		$debug_jeu_only=1;
	$Modif .="Define(\"DEBUG_JEU_ONLY\", ".$debug_jeu_only .");          	// Mettez a 0, pour avoir du debug du jeu + forum +...; 1 pour le jeu uniquement\n";
	$Modif .="Define(\"DEBUG_HTML\", ".$mode_html.");             		// Mettez a 1 si vous voulez stocker les fichiers HTML générés pour valider la syntaxe HTML . Ne laissez surtout pas a 1 pdt le deroulement du jeu reel\n";
	$Modif .="Define(\"SHOW_TIME\", ".$time_sql.");             		// Mettez a 1 si vous voulez voir les temps d'execution (SQL et PHP) (Rem: L'affichage se fera de toute facon si DEBUG_MODE=1). Ne laissez surtout pas a 1 pdt le deroulement du jeu reel\n";
	$Modif .="Define(\"AFFICHE_CONNECTES\", ".$whosonline.");       	// Mettez a 1 si vous voulez voir le sous-menu des PJ et MJ connectes dans le menu de gauche (genere plus de SQL)\n";

	if (!isset($typeforum))
		$typeforum="";
	if (!isset($hautMaxLieu) || $hautMaxLieu=="")
	        $hautMaxLieu="-1";
	if (!isset($largMaxLieu) || $largMaxLieu=="")
	        $largMaxLieu="-1";	
	if (!isset($creeMembrePNJ))
	        $creeMembrePNJ=0;	
	$Modif .=listeParamForum($foruminclus, $lien_forum, $typeforumChoix,$typeforum, $aff_ava, $nb_aff_ava, $hautMaxLieu, $largMaxLieu , $creeMembrePNJ);
	$Modif .= "Define(\"AFFICHE_XP\",	".$xp_lvl."); // Mettez a 1 si vous voulez voir les infos d'XP et de niveau\n";
	$Modif .= "Define(\"AFFICHE_PV\",	".$pv_degat."); // Mettez a 1 si vous voulez voir les infos des Points de vie, dégats\n";
	$Modif .= "\n";
	if ($pj_autop=="")
	        $pj_autop=80;
	if ($pj_blesse=="")
	        $pj_blesse=60;
	if ($pj_abime=="")
	        $pj_abime=40;
	if ($pj_critique=="")
	        $pj_critique=20;
	$Modif .= "Define(\"POURCENTAGE_PV_PERSO_AUTOP\", ".$pj_autop.");\n";
	$Modif .= "Define(\"POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE\", ".$pj_blesse.");\n";
	$Modif .= "Define(\"POURCENTAGE_PV_PERSO_ABIME\", ".$pj_abime.");\n";
	$Modif .= "Define(\"POURCENTAGE_PV_PERSO_CRITIQUE\", ".$pj_critique.");\n";
	$Modif .= "\n";
	if ($basePa=="")
	        $basePa=20;
	if ($basePv=="")
	        $basePv=25;
	if ($basePi=="")
	        $basePi=20;
	if ($basePo=="")
	        $basePo=20;
	$Modif .="Define(\"BASE_PAS\", ".$basePa.");		//nb de PA formant la base des PAs des joueurs  \n";
	$Modif .="Define(\"BASE_PVS\", ".$basePv.");		//nb de PV formant la base des PVs des joueurs  \n";
	$Modif .="Define(\"BASE_PIS\", ".$basePi.");		//nb de PI formant la base des PIs des joueurs  \n";
	$Modif .="Define(\"BASE_POS\", ".$basePo.");		//nb de PO formant la base des POs des joueurs  \n";


	if ($qRemisePa=="")
	        $qRemisePa=5;
	if ($qRemisePv=="")
	        $qRemisePv=2;
	if ($qRemisePi=="")
	        $qRemisePi=5;
	if ($qRemisePo=="")
	        $qRemisePo=0;
	        
	$Modif .="Define(\"QUANTITE_REMISE_PAS\", ".$qRemisePa.");		//nb de PA ajoutés à chaque remise de PAs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_PVS\", ".$qRemisePv.");		//nb de PV ajoutés à chaque remise de PVs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_PIS\", ".$qRemisePi.");		//nb de PI ajoutés à chaque remise de PIs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_POS\", ".$qRemisePo.");		//nb de PO ajoutés à chaque remise de POs  \n";
	
	if ($remisePI=="")
	        $remisePI=90;
	if ($remisePA=="")
	        $remisePA=72;

	$Modif .= "Define (\"INTERVAL_REMISEPI\", ".$remisePI."); //intervalle de temps (en heures) pour la remise des PI\n";
	$Modif .= "Define (\"INTERVAL_REMISEPA\", ".$remisePA."); //intervalle de temps (en heures) pour la remise des PA\n";

	$Modif .= "Define (\"META_KEYWORDS\", \"".$meta_keywords."\"); //meta keywords\n";         
	$Modif .= "Define (\"META_DESCRIPTION\", \"".$meta_description."\"); //meta description\n";
	$Modif .= "Define (\"NOM_JEU\", \"".$nom_jeu."\"); //nom du jeu affiche dans la barre du navigateur\n";

        if ($taille_max_fa=="")
                $taille_max_fa=10;


	$Modif .="Define (\"TAILLE_MAX_FA\", ".$taille_max_fa."); //taille max en Ko du Fichier d'actions avant qu'il se fasse effacer et soit envoye par mail.) \n";
	$Modif .="\$langue = \"".$langueChoix."\"; \n"; 
	
        if(isset($groupePJs) && $groupePJs==1) $params2["groupePJs"]= $groupePJs;
        else $params2["groupePJs"]= 0;

        if(isset($riposteAuto) && $riposteAuto==1) $params2["riposteAuto"]= $riposteAuto;
        else $params2["riposteAuto"]= 0;

        if(isset($riposteGroupee) && $riposteGroupee==1) $params2["riposteGroupee"]= $riposteGroupee;
        else $params2["riposteGroupee"]= 0;

        if(isset($engagement) && $engagement==1) $params2["engagement"]= $engagement;
        else $params2["engagement"]= 0;

        if(isset($secacher) && $secacher==1) $params2["secacher"]= $secacher;
        else $params2["secacher"]= 0;        	

        if(isset($distance_cri) && $distance_cri!="") $params2["distance_cri"]= $distance_cri;
        else $params2["distance_cri"]= 2;        	

        if(isset($resurrection) && $resurrection==1) $params2["resurrection"]= $resurrection;
        else $params2["resurrection"]= 0;        	

        if(isset($pv_resurrection) && $pv_resurrection!="") $params2["pv_resurrection"]= $pv_resurrection;
        else $params2["pv_resurrection"]= 10;        	
   	
        if(isset($nb_max_resurrection) && $nb_max_resurrection!="") $params2["nb_max_resurrection"]= $nb_max_resurrection;
        else $params2["nb_max_resurrection"]= 0;        	

        if(isset($lieu_resurrection) && $lieu_resurrection!="") $params2["lieu_resurrection"]= $lieu_resurrection;
        else $params2["lieu_resurrection"]= "Le PJ ne change pas de lieu";        	

        if(isset($affiche_prix_objet_sort) && $affiche_prix_objet_sort==1) $params2["affiche_prix_objet_sort"]= $affiche_prix_objet_sort;
        else $params2["affiche_prix_objet_sort"]= 0;        	

        if(isset($fouille_objets_equipes) && $fouille_objets_equipes==1) $params2["fouille_objets_equipes"]= $fouille_objets_equipes;
        else $params2["fouille_objets_equipes"]= 0;


        if(isset($reussite_auto_fouille_objets_equipes) && $reussite_auto_fouille_objets_equipes==1) $params2["reussite_auto_fouille_objets_equipes"]= $reussite_auto_fouille_objets_equipes;
        else $params2["reussite_auto_fouille_objets_equipes"]= 0;


        if(isset($delai_suppression_monstresmorts) && $delai_suppression_monstresmorts!="") $params2["delai_suppression_monstresmorts"]= $delai_suppression_monstresmorts;
        else $params2["delai_suppression_monstresmorts"]= -1;

        if(isset($mail_fa_archives) && $mail_fa_archives!="") $params2["mail_fa_archives"]= $mail_fa_archives;
        else $params2["mail_fa_archives"]= "";

        $params2["REMISE_PV"]=$remise_pv;
        $params2["REMISE_PI"]=$remise_pi;

        $Modif .=listeParamMAJ_config($params2);
	if (isset($template_name))
		$Modif .="\$template_name = \"".urldecode($template_name)."\"; \n";

        
        foreach($liste_pas_config as $key => $value) {		
                if ($value=="") $liste_pas_config[$key]=0;
        }        

        foreach($liste_pis_config  as $key => $value) {		
                if ($value=="") $liste_pis_config[$key]=0;
        }   
        
        foreach($liste_actions_tracees as $key => $value) {		
                if ($value=="") $liste_actions_tracees[$key]=0;
        }     
        		
        $Modif .=majConfigListe("liste_pas_actions",$liste_pas_config,array());
        $Modif .=majConfigListe("liste_pis_actions",$liste_pis_config,array());
        $Modif .=majConfigListe("liste_actions_tracees",$liste_actions_tracees,array());
        //$Modif .=listeActionsTracees();


                /* $posLibelle indique ou est le libelle dans le tableau (cle ou valeur)
                 $posID indique ou est le libelle dans le tableau (cle ou valeur)
                 Ex: pour 
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
                	le libelle est dans la cle et l'ID dans la valeur
                C'est l'inverse pour 	$liste_type_lieu_apparitionPerso=array(	            
                                                2=>"Foret",                    
                                                3=>"Caverne, Sous-terrain",
                                                4=>"Ville fortitiée")	
                */
               function modifieListe($nomliste,$cleMini,$posLibelle="valeur", $posID="cle") {
                        global ${"chaine_".$nomliste};
                        global ${"del_".$nomliste};
                        global ${"libelle_".$nomliste};
                        global ${"old_libelle_".$nomliste};
                        global ${$nomliste};
                        
                        /*if (!isset(${$nomliste}))
                                ${$nomliste}=array();*/
                        $temp ="";
        		$result=true;
        		if ($posLibelle=="cle" && $posID=="valeur"){
        		        $nb=0;
        		        foreach(${$nomliste} as $key => $value) {
        		                $nb=max($value,$nb);
        		        } 
        		        $nb=$nb+1;
        		}        
        		if(isset(${"chaine_".$nomliste})){
        			$liste2 = explode(";",${"chaine_".$nomliste});
        			for($i=0;($i<count($liste2)-1)&&($result!==false);$i++){
        			        if (isset(${$nomliste})) {
                                                if ($posLibelle=="valeur" && $posID=="cle")
                                                        array_push (${$nomliste}, ConvertAsHTML($liste2[$i]));
                                                else if ($posLibelle=="cle" && $posID=="valeur")
                                                        ${$nomliste}[ConvertAsHTML($liste2[$i])]= $i+$nb;                                                
                                        } else {
                                                if ($posLibelle=="valeur" && $posID=="cle")
                                                        ${$nomliste}[$cleMini]= ConvertAsHTML($liste2[$i]);
                                                else if ($posLibelle=="cle" && $posID=="valeur")
                                                        ${$nomliste}[ConvertAsHTML($liste2[$i])]= $cleMini;                                                
                                        }        
        			}
        		}
        		
        		if(isset(${"del_".$nomliste}) && ($result!==false)){
        			$toto = array_keys(${"del_".$nomliste});
        			$tata = array_values(${"del_".$nomliste});
        			$nb=count(${"del_".$nomliste});
        			for($i=0;$i<$nb;$i++){
        				if($tata[$i] == "on"){
                                               unset(${$nomliste}[$toto[$i]]); 
        				}
        			}
        			
        		}
        		if(isset(${"libelle_".$nomliste}) && $result){
        			$toto = array_keys(${"libelle_".$nomliste});
        			$tata = array_values(${"libelle_".$nomliste});
        			$oldtata = array_values(${"old_libelle_".$nomliste});
        			$nb=count(${"libelle_".$nomliste});
        			for($i=0;$i<$nb;$i++){
        			        //on ne fait les updates que si on n'a pas fait de del sur cet enregistrement
        			        if ((!isset(${"del_".$nomliste})) || (!array_key_exists ( $toto[$i], ${"del_".$nomliste}))) {
        			                if ((!isset($oldtata[$i])) || $oldtata[$i]<>  $tata[$i]) {
        			                        if ($posLibelle=="valeur" && $posID=="cle") 
       					                        ${$nomliste}[$toto[$i]]= ConvertAsHTML($tata[$i]);
       					                else if ($posLibelle=="cle" && $posID=="valeur") {        
       					                        ${$nomliste}[ConvertAsHTML($tata[$i])]=${$nomliste}[ConvertAsHTML($toto[$i])];
       					                        unset(${$nomliste}[ConvertAsHTML($oldtata[$i])]);
       					                }        
        					}        
        				}	
        			}
        		}		
        		/*	
                        foreach (${$nomliste} as $key => $value) {
                                $temp .=$key ."=".$value."<br />";
                        }
                        return $temp;*/
                }        

                $template_main .= "<br />";
                foreach ( $tab_listes as $key => $value) {
                        $template_main .= modifieListe($value[0],$value[2],$value[3],$value[4]);
                        $Modif .=majConfigListe($value[0], ${$value[0]},array());
		        
        	}    
		
		
		
	$Modif .= "?>";
	
	if (fwrite($monfichier , $Modif)===false) {
		$template_main .= "Probleme à l'écriture de '".$monfichier."'";
	}
	//efface ce qui reste si on n'est pas a la fin
	if (feof ($monfichier)===false)
		ftruncate($monfichier,ftell ($monfichier));
	// SURTOUT PAS OUBLIER DE FERMER LE FICHIER...
	if (fclose($monfichier)===false)
		$template_main .= "Probleme à la fermeture de '".$monfichier."'";
	
	$template_main .="Constantes reconfigurées... ";
	if (isset($MessageWarning) && $MessageWarning<>"") $template_main .= "<br />Mais, ". $MessageWarning;
}
else
{
        
                
                /* $posLibelle indique ou est le libelle dans le tableau (cle ou valeur)
                 $posID indique ou est le libelle dans le tableau (cle ou valeur)
                 Ex: pour 
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
                	le libelle est dans la cle et l'ID dans la valeur
                C'est l'inverse pour 	$liste_type_lieu_apparitionPerso=array(	            
                                                2=>"Foret",                    
                                                3=>"Caverne, Sous-terrain",
                                                4=>"Ville fortitiée")	
                */
        function traiteListe($listeTraitee,$libelle,$posLibelle="valeur", $posID="cle"){
                global ${$listeTraitee};
                $temp="";
        	$compteur=count(${$listeTraitee});
                $temp .= "<table class='detailscenter'>";
                $temp .= "<tr><td colspan='2' align='center'>$libelle</td></tr>";
                if ($compteur==0) {
                        $temp .= "<tr><td colspan='2'>Aucun élément</td></tr>";        
                }                
                else {
                        if ($posLibelle=="valeur" && $posID=="cle")
                                foreach (${$listeTraitee} as $key => $value){
                                	$temp .= "<tr>";
                                	$temp .= "<td>Supprimer<input type='checkbox' name='del_". $listeTraitee . "[".$key."]' /></td>";
                                	$temp .= "<td><input type='text' name='libelle_". $listeTraitee . "[".$key."]' value=\"".ConvertAsHTML($value)."\" />
                                	<input type='hidden' name='old_libelle_". $listeTraitee . "[".$key."]' value=\"".ConvertAsHTML($value)."\" /></td>";
                                	$temp .= "</tr>\n";
                                }
                        else if ($posLibelle=="cle" && $posID=="valeur")
                                foreach (${$listeTraitee} as $key=> $value){
                                	$temp .= "<tr>";
                                	$temp .= "<td>Supprimer<input type='checkbox' name='del_". $listeTraitee . "[".$key."]' /></td>";
                                	$temp .= "<td><input type='text' name='libelle_". $listeTraitee . "[".$key."]' value=\"".ConvertAsHTML($key)."\" />
                                	<input type='hidden' name='old_libelle_". $listeTraitee . "[".$key."]' value=\"".ConvertAsHTML($key)."\" /></td>";
                                	$temp .= "</tr>\n";
                                }

                }
                $temp .= "</table>";
        	$temp .= "<input type='hidden' name='chaine_".$listeTraitee."' value='' /><br />";
                return $temp;
        }
        
        function ajoute($nomliste, $libelle) {
                
                $temp ="<script type='text/javascript'>
                	var chainetemp = '';
                
                	function ajoute".$nomliste."(Obj){
                		//Obj.value = Obj.value + document.forms[1].libelle.value+';';
                		Obj.value = Obj.value + document.forms['".$nomliste."'].libelle.value+';';
                		//document.forms[1].ListAdd.options.length = document.forms[1].ListAdd.options.length +1;
                		//document.forms[1].ListAdd.options[(document.forms[1].ListAdd.options.length -1)].text =  document.forms[1].libelle.value ;
                		document.forms['".$nomliste."'].ListAdd.options.length = document.forms['".$nomliste."'].ListAdd.options.length +1;
                		document.forms['".$nomliste."'].ListAdd.options[(document.forms['".$nomliste."'].ListAdd.options.length -1)].text =  document.forms['".$nomliste."'].libelle.value ;
                	}
                
                	function RAZ".$nomliste."(){
                		//document.forms[1].ListAdd.options.length = 0;
                		document.forms[".$nomliste."].ListAdd.options.length = 0;
                		document.forms[0].chaine_".$nomliste.".value ='';
                		chainetemp= '';
                	}
                				
                </script>";
                
                
                $temp .= "\t<form id='".$nomliste."' name='". $nomliste."' action='".NOM_SCRIPT."' method='post'>";        
                $temp .= "<table class='detailscenter'>";
                $temp .= "<tr><td>Nouvel élément dans ". $libelle."</td><td><input type='text' name='libelle' value='' /></td></tr>";
                $temp .= "<tr><td><select name=\"ListAdd\" size='5'></select></td>";
                $temp .= "<td><input value=\"Ajouter\" type='button' onclick=\"ajoute".$nomliste."(document.forms[0].chaine_".$nomliste.")\" />\n";
                $temp .= "<br /><br /><input value=\"Remettre a Zero\" type='button' onclick=\"RAZ".$nomliste."()\" /></td></tr>";
                $temp .= "</table>";
                $temp .= "</form><br />\n";
                return $temp;
        }             
        
	/// FORMULAIRE DE MODIF CONST
	$template_main .= "<center>
	<form action='".NOM_SCRIPT."' method='post'>
	Vous devez avoir mis les droits d'écriture sur le fichier <tt>\"include/config.".$phpExtJeu."\"</tt> afin de modifier la configuration.<br /><br /><br />";
	
	$template_main .= "<input type='hidden' name='action' value='write' />
	<table width='90%'><tr><td colspan='2' align='center'><b><u>Options du site<br /><br /></u></b></td>
	</tr>";
	if (defined("VERSION") && VERSION!="")
		$template_main .= "<tr><td width='50%'>Version du moteur :</td><td>".VERSION."</td></tr>";
	
	$template_main .= "<tr><td width='50%'>Nom du jeu :</td><td>
	<input type='texte' name='nom_jeu' value='";
	 if(defined("NOM_JEU")) $template_main .= NOM_JEU; 
	$template_main .= "' /> </td></tr>";
	
	
	$template_main .= "<tr><td width='50%'>Maintenance :</td><td><select name='maintenance'><option value='0' ";
	
	 if( (!defined("MAINTENANCE_MODE"))|| MAINTENANCE_MODE==0) $template_main .='selected="selected"';
	$template_main .= ">Pas de maintenance</option><option value='1' ";
	 if(defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==1) $template_main .='selected="selected"';
	$template_main .= ">Maintenance pour les PJs</option><option value='2' ";
	 if(defined("MAINTENANCE_MODE") && MAINTENANCE_MODE==2) $template_main .='selected="selected"';
	$template_main .= ">Maintenance pour les PJs et les MJs</option></select></td>
	</tr>
	<tr>
		<td width='50%'>Afficher le questionnaire à l'inscription ?<br />(Pensez à modifier dans la gestion des questions...) :</td><td><select name='qcm'><option value='0' ";
	 if((!defined("COUNT_QCM"))||COUNT_QCM==0) $template_main .='selected="selected"';
	$template_main .= ">NON</option><option value='1' ";
	 if(defined("COUNT_QCM") && COUNT_QCM==1) $template_main .='selected="selected"';
	$template_main .= ">OUI, avec 1 réponse</option><option value='2' ";
	 if(defined("COUNT_QCM") && COUNT_QCM==2) $template_main .='selected';
	$template_main .= ">OUI, avec 2 réponses</option><option value='3' ";
	 if(defined("COUNT_QCM") && COUNT_QCM==3) $template_main .='selected="selected"';
	$template_main .= ">OUI, avec 3 réponses</option><option value='4' ";
	 if(defined("COUNT_QCM") && COUNT_QCM==4) $template_main .='selected="selected"';
	$template_main .= ">OUI, avec 4 réponses</option><option value='5' ";
	 if(defined("COUNT_QCM") && COUNT_QCM==5) $template_main .='selected="selected"';
	$template_main .= ">OUI, avec 5 réponses</option></select></td>
	</tr>
	<tr>
		<td width='50%'>Inscriptions ouvertes ? :</td><td><input type='radio' name='inscription' value='1' ";
	 if((!defined("INSCRIPTIONS_OUVERTES")) || INSCRIPTIONS_OUVERTES==1) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='inscription' value='0' ";
	 if(defined("INSCRIPTIONS_OUVERTES")&& INSCRIPTIONS_OUVERTES==0) $template_main .='checked="checked"';
	$template_main .= " /></td>
	</tr>
	<tr>
		<td width='50%'>Afficher les news ? :</td><td><input type='radio' name='news' value='1' ";
	 if(defined("IN_NEWS") && IN_NEWS==1) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='news' value='0' ";
	 if((!defined("IN_NEWS"))|| IN_NEWS==0) $template_main .='checked="checked"';
	$template_main .= " /></td>
	</tr>
	<tr>
		<td width='50%'>Afficher les connectés ? :</td><td><input type='radio' name='whosonline' value='1' ";
	 if(defined("AFFICHE_CONNECTES") && AFFICHE_CONNECTES==1) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='whosonline' value='0' ";
	 if((!defined("AFFICHE_CONNECTES"))|| AFFICHE_CONNECTES==0) $template_main .='checked="checked"';
	$template_main .= " /></td>
	</tr>
	<tr>
		<td width='50%'>Utiliser un forum conjointement avec le jeu ? (Attention : Ne pas oublier d'installer le forum avant, ni d'exécuter le script SQL correspondant au forum (PostInstallPHORUM.sql ou PostInstallPHPBB.sql) dans phpmyadmin avant :</td><td><input type='radio' name='foruminclus' value='1' ";
	 if(defined("IN_FORUM") && IN_FORUM==1) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='foruminclus' value='0' ";
	 if((!defined("IN_FORUM"))|| IN_FORUM==0) $template_main .='checked="checked"';
	$template_main .= " /></td>
	</tr>
	<tr>
		<td width='50%'>Si utilisation d'un forum, chemin (relatif au repertoire talesta/include <!--ou absolu en http://.....-->) d'acces ? (Attention : Ne pas oublier le '/' à la fin. <!--Une URL absolue ne fonctionne pas sous Windows, et nécessite que allow_url_fopen soit à 1 dans php.ini sous Unix-->) :</td><td><input type='texte' name='lien_forum' value='";
	 if(defined("IN_FORUM") && IN_FORUM==1 && defined("CHEMIN_FORUM")) $template_main .= CHEMIN_FORUM; 
	$template_main .= "' /> </td>
	</tr>
	<tr>
	<td width='50%'>Si utilisation d'un forum, type du forum ? :</td><td>";

			if(!defined("__MISEENPAGE.PHP")){include('../include/miseenpage.'.$phpExtJeu);}
			/*
			$liste_type_forum=array(
				"0"=>"phpBB",
				"2"=>"punBB",
				"1"=>"phorum",
				"3"=>"IBP"
			);*/
			$liste_type_forum=array(
				"0"=>"phpBB",
				"1"=>"phorum"
			);
			if (isset($typeforum))
				$var=faitSelect("typeforumChoix","","",$typeforum,array(),$liste_type_forum);
			else 	$var=faitSelect("typeforumChoix","","",-50,array(),$liste_type_forum);
			$template_main .= $var[1]. "</td></tr>";

$template_main .= " <tr><td width='50%'>Si utilisation d'un forum, créer un membre du forum par PNJ (nécessite que chaque PNJ ait une adresse email unique) ? :</td><td><input type='radio' name='creeMembrePNJ' value='1' ";
if(defined("CREE_MEMBRE_PNJ") &&  CREE_MEMBRE_PNJ==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='creeMembrePNJ' value='0' ";
if((!defined("CREE_MEMBRE_PNJ"))||CREE_MEMBRE_PNJ==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>";

			
	$template_main .= "<tr><td colspan='2' align='center'><b><u><br /><br /><br />Options du jeu<br /><br /></u></b></td></tr>";

	  $liste_template=array();
	  $dir = opendir("../templates/");
	  if ($dir != FALSE) {
		  while($file = readdir($dir)) {
		  	if ($file!="."  && $file!="..") {
			     	if (is_dir ("../templates/".$file)) {
			     		array_push($liste_template, $file);
			     	}	
			}
		}
	}
			
	if (count($liste_template)<>0) {
		$template_main .= "<tr><td width='50%'>Template utilisé ? :</td><td>";
		
		if (isset($template_name)) {
			$var=faitSelect("template_name","","",urldecode($template_name),array(),$liste_template);
		}	
		else 	{
			$var=faitSelect("template_name","","",-50,array(),$liste_template);
		}	
		$template_main .= $var[1]. "</td></tr>";
	}
	
	$template_main .= "<tr><td width='50%'>Langue utilisée ? :</td><td>";

			if (isset($langue))
				$var=faitSelect("langueChoix","","",$langue,array(),$liste_langue);
			else 	$var=faitSelect("langueChoix","","",-50,array(),$liste_langue);
			$template_main .= $var[1]. "</td>";
			
$template_main .= "</tr>";

$template_main .= "<tr><td width='50%'>Voir les infos d'XP et de niveau ? :</td><td><input type='radio' name='xp_lvl' value='1' ";

 if(defined("AFFICHE_XP") && AFFICHE_XP==1) 
 	$template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='xp_lvl' value='0' ";
 if((!defined("AFFICHE_XP"))|| AFFICHE_XP==0) $template_main .='checked="checked"';
$template_main .= " /></td>
</tr>
<tr><td width='50%'>Voir les infos des Points de vie, dégats ? :</td><td><input type='radio' name='pv_degat' value='1' ";

 if(defined("AFFICHE_PV") && AFFICHE_PV==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='pv_degat' value='0' ";
 if((!defined("AFFICHE_PV"))|| AFFICHE_PV==0) $template_main .='checked="checked"';



$template_main .= " /></td></tr>";
$template_main .= " <tr><td width='50%'>Afficher les avatars du forum dans le jeu ? :</td><td><input type='radio' name='aff_ava' value='1' ";

 if(defined("AFFICHE_AVATAR_FORUM") &&  AFFICHE_AVATAR_FORUM==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='aff_ava' value='0' ";
 if((!defined("AFFICHE_AVATAR_FORUM"))||AFFICHE_AVATAR_FORUM==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>";
$template_main .= "<tr><td width='50%'>Nombre d'avatars max par lieu ? :</td><td><input type='text' name='nb_aff_ava' size='3' value='";

 if (defined("AFFICHE_NB_MAX_AVATAR")) $template_main .= AFFICHE_NB_MAX_AVATAR; else $template_main .= "0"; 
$template_main .= "' /> ( -1 = Pas de limite )</td></tr>";

$template_main .= "<tr><td width='50%'>Largeur max d'une image de lieu ? (-1 pour illimitée, attention à l'affichage) :</td><td><input type='text' name='largMaxLieu' size='3' value='";

 if (defined("LARG_MAX_LIEU")) $template_main .= LARG_MAX_LIEU; else $template_main .= "400"; 
$template_main .= "' /> </td></tr>";
$template_main .= "<tr><td width='50%'>Hauteur max d'une image de lieu ? (-1 pour illimitée, attention à l'affichage) :</td><td><input type='text' name='hautMaxLieu' size='3' value='";

 if (defined("HAUT_MAX_LIEU")) $template_main .= HAUT_MAX_LIEU; else $template_main .= "200"; 
$template_main .= "' /> </td></tr>";

$template_main .= "<tr><td width='50%'>taille max en Ko du FA avant qu'il se fasse effacer et soit envoye par mail ? :</td><td><input type='text' name='taille_max_fa' size='3' value='";

 if (defined("TAILLE_MAX_FA")) $template_main .= TAILLE_MAX_FA; else $template_main .= "10"; 
$template_main .= "' /> </td></tr>";

$template_main .= " <tr><td>Mail d'un MJ pour recevoir les archives des FA de tous les PJs (laisser vide pour ne pas le faire)</td><td>";
$template_main .= " <input type='text' name='mail_fa_archives' value='";
if(defined("MAIL_FA_ARCHIVES") && MAIL_FA_ARCHIVES!="") 
        $template_main .= MAIL_FA_ARCHIVES;
$template_main .= "' /></td></tr>"; 


$template_main .= "<tr><td colspan='2' align='center'><hr width='25%' /></td></tr>
<tr><td width='50%'>Pourcentage de PV pour lesquels l'état du PJ est considéré comme \"Au top\" :</td><td><input type='text' name='pj_autop' size='3' value='";

 if (defined("POURCENTAGE_PV_PERSO_AUTOP")) $template_main .= POURCENTAGE_PV_PERSO_AUTOP; else $template_main .= "80"; 
$template_main .= "' /> ( Défaut = 80 )</td>
</tr>
<tr>
	<td width='50%'>Pourcentage de PV pour lesquels l'état du PJ est considéré comme \"Légèrement blessé\" :</td><td><input type='text' name='pj_blesse' size='3' value='";
 if (defined("POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE")) $template_main .= POURCENTAGE_PV_PERSO_LEGEREMENTBLESSE; else $template_main .= "60"; 
$template_main .= "' /> ( Défaut = 60 )</td>
</tr>
<tr><td width='50%'>Pourcentage de PV pour lesquels l'état du PJ est considéré comme \"Abimé\" :</td><td><input type='text' name='pj_abime' size='3' value='";
 if (defined("POURCENTAGE_PV_PERSO_ABIME")) $template_main .= POURCENTAGE_PV_PERSO_ABIME; else $template_main .= "40"; 
$template_main .= "' /> ( Défaut = 40 )</td>
</tr>
<tr><td width='50%'>Pourcentage de PV pour lesquels l'état du PJ est considéré comme \"Critique\" :</td><td><input type='text' name='pj_critique' size='3' value='";

 if (defined("POURCENTAGE_PV_PERSO_CRITIQUE")) $template_main .= POURCENTAGE_PV_PERSO_CRITIQUE; else $template_main .= "20"; 
$template_main .= "' /> ( Défaut = 20 )</td>
</tr>
<tr><td colspan='2' align='center'><hr width='25%' /></td></tr>";


	if(defined("REMISE_PV")) {
		$var=faitSelect("remise_pv","","",$liste_type_remise[REMISE_PV],array(),$liste_type_remise);
	}	
	else 	{
		$var=faitSelect("remise_pv","","",-50,array(),$liste_type_remise);
	}	
	$template_main .= "	<tr><td width='50%'>Type de Remise de PVs :</td><td>";
	$template_main .= $var[1];
$template_main .= "</td></tr>";


$template_main .= "<tr>
	<td width='50%'>Base du nombre de PVs que recoit un nouveau PJ (si le type de remise de PVs est partielle):</td><td><input type='text' name='basePv' size='3' value='";
 if (defined("BASE_PVS")) $template_main .= BASE_PVS; else $template_main .= "25"; 
$template_main .= "' /> ( Défaut = 25 )</td>
</tr>
<tr>
	<td width='50%'>Base du nombre de PAs que recoit un nouveau PJ :</td><td><input type='text' name='basePa' size='3' value='";
 if (defined("BASE_PAS")) $template_main .= BASE_PAS; else $template_main .= "20"; 
$template_main .= "' /> ( Défaut = 20 )</td>
</tr>
<tr>
	<td width='50%'>Base du nombre de POs que recoit un nouveau PJ:</td><td><input type='text' name='basePo' size='3' value='";
 if (defined("BASE_POS")) $template_main .= BASE_POS; else $template_main .= "20"; 
$template_main .= "' /> ( Défaut = 20 )</td></tr>";



	if(defined("REMISE_PI")) {
		$var=faitSelect("remise_pi","","",$liste_type_remise[REMISE_PI],array(),$liste_type_remise);
	}	
	else 	{
		$var=faitSelect("remise_pi","","",-50,array(),$liste_type_remise);
	}	
	$template_main .= "	<tr><td width='50%'>Type de Remise de PIs :</td><td>";
	$template_main .= $var[1];
$template_main .= "</td></tr>";


$template_main .= "<tr>
	<td width='50%'>Base du nombre de PIs que recoit un nouveau PJ (si le type de remise de PIs est partielle):</td><td><input type='text' name='basePi' size='3' value='";
 if (defined("BASE_PIS")) $template_main .= BASE_PIS; else $template_main .= "20"; 
$template_main .= "' /> ( Défaut = 20 )</td></tr>";


$template_main .= "<tr>
	<td width='50%'>Base du nombre de PAs que recoit un PJ à chaque remise de PAs :</td><td><input type='text' name='qRemisePa' size='3' value='";
 if (defined("QUANTITE_REMISE_PAS")) $template_main .= QUANTITE_REMISE_PAS; else $template_main .= "5"; 
$template_main .= "' /> ( Défaut = 5 )</td>
</tr>
<tr>
	<td width='50%'>Base du nombre de PVs que recoit un PJ à chaque remise de PVs:</td><td><input type='text' name='qRemisePv' size='3' value='";
 if (defined("QUANTITE_REMISE_PVS")) $template_main .= QUANTITE_REMISE_PVS; else $template_main .= "2"; 
$template_main .= "' /> ( Défaut = 2 )</td>
</tr>
<tr>
	<td width='50%'>Base du nombre de PIs que recoit un PJ à chaque remise de PIs :</td><td><input type='text' name='qRemisePi' size='3' value='";
 if (defined("QUANTITE_REMISE_PIS")) $template_main .= QUANTITE_REMISE_PIS; else $template_main .= "5"; 
$template_main .= "' /> ( Défaut = 5 )</td>
</tr>
<tr>
	<td width='50%'>Base du nombre de POs que recoit un PJ à chaque remise de POs:</td><td><input type='text' name='qRemisePo' size='3' value='";
 if (defined("QUANTITE_REMISE_POS")) $template_main .= QUANTITE_REMISE_POS; else $template_main .= "0"; 
$template_main .= "' /> ( Défaut = 0 )</td>
</tr>

<tr>
	<td width='50%'>Intervalle de temps (en heures) pour la remise des PI :</td><td><input type='text' name='remisePI' size='3' value='";
 if (defined("INTERVAL_REMISEPI")) $template_main .= INTERVAL_REMISEPI; else $template_main .= "90"; 
$template_main .= "' /> ( Défaut = 90 )</td>
</tr>
<tr>
	<td width='50%'>Intervalle de temps (en heures) pour la remise des PA :</td><td><input type='text' name='remisePA' size='3' value='";
 if (defined("INTERVAL_REMISEPA")) $template_main .= INTERVAL_REMISEPA; else $template_main .= "72"; 
$template_main .= "' /> ( Défaut = 72 )</td></tr>";

$template_main .="<tr><td colspan='2' align='center'><hr width='25%' /></td></tr>
<tr>
	<td width='50%'>Permettre aux PJs de se regrouper en groupe (pour déplacements communs par ex.) ? :</td><td><input type='radio' name='groupePJs' value='1' ";
 if(defined("GROUPE_PJS") && GROUPE_PJS==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='groupePJs' value='0' ";
 if((!defined("GROUPE_PJS")) || GROUPE_PJS==0) $template_main .='checked="checked"';
$template_main .= " /></td>
</tr>
<tr>
	<td width='50%'>Permettre aux PJs de riposter automatiquement en cas d'aggression pendant leur absence ? :</td><td><input type='radio' name='riposteAuto' value='1' ";
 if(defined("RIPOSTE_AUTO") && RIPOSTE_AUTO==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='riposteAuto' value='0' ";
 if((!defined("RIPOSTE_AUTO")) || RIPOSTE_AUTO==0) $template_main .='checked="checked"';
$template_main .= " /></td>
</tr>
<tr>
	<td width='50%'>Permettre la riposte de groupe (Si un joueur d'un groupe est attaque et que l'assaillant n'est pas dans un groupe, alors tous les membres du groupe ripostent) ? :</td><td><input type='radio' name='riposteGroupee' value='1' ";
 if(defined("RIPOSTE_GROUPEE") && RIPOSTE_GROUPEE==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='riposteGroupee' value='0' ";
 if((!defined("RIPOSTE_GROUPEE")) || RIPOSTE_GROUPEE==0) $template_main .='checked="checked"';
$template_main .= " /></td>
</tr>
<tr>
	<td width='50%'>Gérer l'engagement/dégagement lors d'une attaque avec une arme de toucher ? :</td><td><input type='radio' name='engagement' value='1' ";
 if(defined("ENGAGEMENT") && ENGAGEMENT==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='engagement' value='0' ";
 if((!defined("ENGAGEMENT")) || ENGAGEMENT==0) $template_main .='checked="checked"';
$template_main .= " /></td>
</tr>
<tr>
	<td width='50%'>Permettre aux joueurs de se dissimuler dans un lieu ? :</td><td><input type='radio' name='secacher' value='1' ";
 if(defined("SECACHER") && SECACHER==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='secacher' value='0' ";
 if((!defined("SECACHER")) || SECACHER==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr><tr>
	<td width='50%'>Distance en PA Maxi entre 2 lieux à laquelle porte un cri :</td><td><input type='text' name='distance_cri' size='3' value='";
 if (defined("DISTANCE_CRI")) $template_main .= DISTANCE_CRI; else $template_main .= "2"; 
$template_main .= "' /> ( Défaut = 2 )</td></tr>"; 
$template_main .= "<tr><td colspan='2' align='center'><hr width='25%' /></td></tr>";

$template_main .= " <tr>
	<td width='50%'>Permettre aux PJs de ressuciter ? (sinon le joueur doit refaire un PJ) :</td><td><input type='radio' name='resurrection' value='1' ";
 if(defined("RESURRECTION") && RESURRECTION==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='resurrection' value='0' ";
 if((!defined("RESURRECTION")) || RESURRECTION==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>"; 
 
$template_main .= " <tr>
	<td width='50%'>Nombre de PV d'un joueur ressucité:</td><td><input type='text' name='pv_resurrection' value=' ";
 if(defined("PV_RESURRECTION")) $template_main .= PV_RESURRECTION; else $template_main .= "10"; 
$template_main .= "' /></td></tr>"; 
 
$template_main .= " <tr>
	<td width='50%'>Nb Max de résurrections pour un PJ ? (-1 pour illimité) :</td><td><input type='text' name='nb_max_resurrection' size='3' value='";
 if(defined("NB_MAX_RESURRECTION")) $template_main .= NB_MAX_RESURRECTION; else $template_main .= "0"; 
$template_main .= "' /></td></tr>"; 
 
$template_main .= " <tr><td>Lieu ou se retrouve le PJ apres resurrection</td><td>";
 
$SQL_lieu = "Select T1.id_lieu as idselect, concat(concat(T1.trigramme,'-'),T1.nom) as labselect from ".NOM_TABLE_LIEU." T1 ORDER BY T1.trigramme, T1.nom ASC";

	$liste_choix_lieu=array(
		"0"=>"Le PJ ne change pas de lieu"
	);

if(defined("LIEU_RESURRECTION"))
        $var= faitSelect("lieu_resurrection",$SQL_lieu,"",LIEU_RESURRECTION,array(),$liste_choix_lieu);
else $var= faitSelect("lieu_resurrection",$SQL_lieu,"","",array(),$liste_choix_lieu);        
$template_main .= $var[1];

$template_main .= " </td></tr>"; 



$template_main .= " <tr><td>Autorise la récupération d'objets équipés sur un mort pendant la fouille du cadavre</td><td>";
$template_main .= " <input type='radio' name='fouille_objets_equipes' value='1' ";
 if(defined("FOUILLE_OBJETS_EQUIPES") && FOUILLE_OBJETS_EQUIPES==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='fouille_objets_equipes' value='0' ";
 if((!defined("FOUILLE_OBJETS_EQUIPES")) || FOUILLE_OBJETS_EQUIPES==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>"; 


$template_main .= " <tr><td>Si oui,  réussite automatique de la fouille du cadavre sur un objet équipé</td><td>";
$template_main .= " <input type='radio' name='reussite_auto_fouille_objets_equipes' value='1' ";
 if(defined("REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES") && REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='reussite_auto_fouille_objets_equipes' value='0' ";
 if((!defined("REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES")) || REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>"; 


$template_main .= " <tr><td>Délai laissé aux PJS pour fouiller les monstres morts avant leur suppression (en h)</td><td>";
$template_main .= " <input type='text' name='delai_suppression_monstresmorts' value='";
if(defined("DELAI_SUPPRESSION_MONSTRESMORTS") && DELAI_SUPPRESSION_MONSTRESMORTS!="") 
        $template_main .= DELAI_SUPPRESSION_MONSTRESMORTS;
$template_main .= "' /></td></tr>"; 

$template_main .= "<tr><td colspan='2' align='center'><hr width='25%' /></td></tr>";

$template_main .= " <tr><td>Affiche le prix des objets et des sorts</td><td>";
$template_main .= " <input type='radio' name='affiche_prix_objet_sort' value='1' ";
 if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='affiche_prix_objet_sort' value='0' ";
 if((!defined("AFFICHE_PRIX_OBJET_SORT")) || AFFICHE_PRIX_OBJET_SORT==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>"; 
$template_main .= "<tr><td colspan='2' align='center'><hr width='25%' /></td></tr></table>";


$template_main .= "<table width='90%'><tr><td colspan='3' align='center'><b><u>Les couts de PA et PI des actions</u></b><br />Rappel les montants sont négatifs s'ils coutent des PA et/ou des PI au PJ qui fait l'action</td></tr>";

$template_main .= "<tr><td>Action</td><td>Cout en PA</td><td>Cout en PI</td></tr>";
foreach($liste_pas_actions as $key => $value) {
        $template_main .= "<tr><td>$key</td><td><input type='text' name='liste_pas_config[$key]' value='$value' /></td><td><input type='text' name='liste_pis_config[$key]' value='".$liste_pis_actions[$key]."' /></td></tr>";
}        


$template_main .= "</table><table width='90%'><tr><td colspan='2' align='center'><hr width='25%' /></td></tr>";


$template_main .= "<table  class='detailscenter' width='90%'><tr><td colspan='3' align='center'><b><u>Les actions de joueurs a tracer</u></b></tr>";

$template_main .= "<tr><td>Action</td><td>A surveiller</td></tr>";
foreach($liste_actions_tracees as $key => $value) {
        $template_main .= "<tr><td>$key</td><td>
        <input type='radio' name='liste_actions_tracees[$key]' value='1' ";
	 if($value) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='liste_actions_tracees[$key]' value='0' ";
	 if(!$value) $template_main .='checked="checked"';
	$template_main .= " /></td></tr>";
        
        
        //<input type='text' name='liste_actions_tracees[$key]' value='$value' /></td></tr>";
}  

 
$template_main .= "<tr><td colspan='2' align='center'><b><u><br /><br /><br />Fonctions de débuggages<br /><br /></u></b></td></tr><tr>";

			if(defined("DEBUG_MODE")) {
				$var=faitSelect("mode_debug","","",$liste_type_debug[DEBUG_MODE],array(),$liste_type_debug);
			}	
			else 	{
				$var=faitSelect("mode_debug","","",-50,array(),$liste_type_debug);
			}	
	$template_main .= "	<td width='50%'>Mode debug ? :</td><td>";
	$template_main .= $var[1];

$template_main .= "</td></tr>";
$template_main .= "<tr>";

	$template_main .= "	<td width='50%'>Ne debugguer que le jeu et pas le forum et autres ? :</td>";
$template_main .= "<td><input type='radio' name='debug_jeu_only' value='1' ";
 if(defined("DEBUG_JEU_ONLY") && DEBUG_JEU_ONLY==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='debug_jeu_only' value='0' ";
 if((!defined("DEBUG_JEU_ONLY")) || DEBUG_JEU_ONLY==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>";

$template_main .="<tr>
	<td width='50%'>Stocker les fichiers HTML générés pour valider la syntaxe HTML ? :</td><td><input type='radio' name='mode_html' value='1' ";
 if(defined("DEBUG_HTML") && DEBUG_HTML==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='mode_html' value='0' ";
 if((!defined("DEBUG_HTML")) || DEBUG_HTML==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr><tr>
	<td width='50%'>Voir les temps d'execution SQL et PHP (L'affichage se fera de toute facon si le mode debug est autre que 'PAs de debug' ) ? :</td><td><input type='radio' name='time_sql' value='1' ";
 if(defined("SHOW_TIME") && SHOW_TIME==1) $template_main .='checked="checked"';
$template_main .= " /> OUI |NON <input type='radio' name='time_sql' value='0' ";
 if((!defined("SHOW_TIME"))|| SHOW_TIME==0) $template_main .='checked="checked"';
$template_main .= " /></td></tr>

<tr><td colspan='2' align='center'><b><u><br /><br /><br />META Mots clefs/Description qui seront ins&eacute;r&eacute;s dans les codes HTML.<br /><br /></u></b></td></tr>
<tr><td nowrap=\"nowrap\">Mots clefs :</td><td><input type=\"text\" size=\"50\" name=\"meta_keywords\" value='";
 if (defined("META_KEYWORDS")) $template_main .= META_KEYWORDS; else $template_main .= ""; 
$template_main .= "' /></td></tr>    
<tr><td nowrap=\"nowrap\">Description :</td><td><input type=\"text\" size=\"50\" name=\"meta_description\" value='";
 if (defined("META_DESCRIPTION")) $template_main .= META_DESCRIPTION; else $template_main .= ""; 
$template_main .= "' /></td></tr></table><br />";


$template_main .="<table width='90%'><tr><td colspan='3' align='center'><b><u><br /><br /><br />Listes des contantes personnalisables. <br /><br /></u></b></td></tr></table><br /><br />";
        // debut de la partie des anciennes constantes des listes => tous les defines ajouter doivent etre au dessus
        foreach ( $tab_listes as $key => $value) {
                $template_main.=  traiteListe($value[0],$value[1],$value[3],$value[4]);
	}          
	$template_main .= "<br /><input type='submit' value='MODIFIER' onclick=\"return confirm('Etes vous sur de vouloir supprimer les constantes eventuellement selectionnes ?')\" />";
	//$template_main .= "<input type='hidden' name='etape' value='2' />";
	$template_main .= "</form></center>";

        foreach ( $tab_listes as $key => $value) {
                $template_main.=  ajoute($value[0],$value[1]);
	}

}	


$template_main .= "<br /><p>&nbsp;</p>";

if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}
?>