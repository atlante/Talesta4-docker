<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: configActions.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2010/02/28 22:58:02 $

*/
require_once("../include/extension.inc");
define("SESSION_POUR_MJ", 1);
require_once("../include/fct_installUpdate.".$phpExtJeu);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $config;
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}


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
	$Modif .="                      						// Mettez a 2 cette variable pour bloquer le jeu (tous les PNJ et PJ) et les MJ sauf celui cr  l'init de la base\n";
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
		$debug_jeu_only=0;
	$Modif .="Define(\"DEBUG_JEU_ONLY\", ".$debug_jeu_only .");          	// Mettez a 0, pour avoir du debug du jeu + forum +...; 1 pour le jeu uniquement\n";
	$Modif .="Define(\"DEBUG_HTML\", ".$mode_html.");             		// Mettez a 1 si vous voulez stocker les fichiers HTML gnrs pour valider la syntaxe HTML . Ne laissez surtout pas a 1 pdt le deroulement du jeu reel\n";
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
	$Modif .= "Define(\"AFFICHE_PV\",	".$pv_degat."); // Mettez a 1 si vous voulez voir les infos des Points de vie, dgats\n";
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
	        
	$Modif .="Define(\"QUANTITE_REMISE_PAS\", ".$qRemisePa.");		//nb de PA ajouts  chaque remise de PAs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_PVS\", ".$qRemisePv.");		//nb de PV ajouts  chaque remise de PVs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_PIS\", ".$qRemisePi.");		//nb de PI ajouts  chaque remise de PIs  \n";
	$Modif .="Define(\"QUANTITE_REMISE_POS\", ".$qRemisePo.");		//nb de PO ajouts  chaque remise de POs  \n";

	if ($remisePI=="")
	        $remisePI=90;
	if ($remisePA=="")
	        $remisePA=72;
	
	$Modif .= "Define (\"INTERVAL_REMISEPI\", ".$remisePI."); //intervalle de temps (en heures) pour la remise des PI\n";
	$Modif .= "Define (\"INTERVAL_REMISEPA\", ".$remisePA."); //intervalle de temps (en heures) pour la remise des PA\n";

	$Modif .= "Define (\"META_KEYWORDS\", \"".$meta_keywords."\"); //meta keywords\n";         
	$Modif .= "Define (\"META_DESCRIPTION\", \"".$meta_description."\"); //meta description\n";
	$Modif .= "Define (\"NOM_JEU\", \"".$nom_jeu."\"); //nom du jeu affiche dans la barre du navigateur\n";

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

        if(isset($delai_suppression_monstresmorts) && $delai_suppression_monstresmorts!="") $params2["delai_suppression_monstresmorts"]= $delai_suppression_monstresmorts;
        else $params2["delai_suppression_monstresmorts"]= -1;

        $Modif .=listeParamMAJ_config($params2);
	if (isset($template_name))
		$Modif .="\$template_name = \"".urldecode($template_name)."\"; \n";

        
        foreach($liste_pas_config as $key => $value) {		
                if ($value=="") $liste_pas_config[$key]=0;
        }        

        foreach($liste_pis_config  as $key => $value) {		
                if ($value=="") $liste_pis_config[$key]=0;
        }        
        		

        $Modif .=majConfigListe("liste_pas_actions",$liste_pas_config,array());
        $Modif .=majConfigListe("liste_pis_actions",$liste_pis_config,array());

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
                                                4=>"Ville fortitie")	
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
		$template_main .= "Probleme  l'criture de '".$monfichier."'";
	}
	//efface ce qui reste si on n'est pas a la fin
	if (feof ($monfichier)===false)
		ftruncate($monfichier,ftell ($monfichier));
	// SURTOUT PAS OUBLIER DE FERMER LE FICHIER...
	if (fclose($monfichier)===false)
		$template_main .= "Probleme  la fermeture de '".$monfichier."'";
	
	$template_main .="Constantes reconfigures... ";
	if (isset($MessageWarning) && $MessageWarning<>"") $template_main .= "<br />Mais, ". $MessageWarning;
}
else
{

	$template_main .= "<table width='90%'>";
	
	$template_main .= "<tr><td>Action</td><td>Tracer dans le journal des actions</td>";
	$template_main .= "<td>Texte de description de l'action obligatoire</td></tr>";
	foreach($liste_pas_actions as $key => $value) {
	        $template_main .= "<tr><td>$key</td><td>
<input type='radio' name='whosonline' value='1' ";
	 if(defined("AFFICHE_CONNECTES") && AFFICHE_CONNECTES==1) $template_main .='checked="checked"';
	$template_main .= " /> OUI |NON <input type='radio' name='whosonline' value='0' ";
	 if((!defined("AFFICHE_CONNECTES"))|| AFFICHE_CONNECTES==0) $template_main .='checked="checked"';
	$template_main .= " /></td>	        
	        
	        <input type='text' name='liste_config_actions[$key][0]' value='liste_config_actions[$key][0]' />";
	        $template_main .= "</td><td><input type='text' name='liste_pis_config[$key]' value='".$liste_config_actions[$key][1]."' /></td></tr>";
	}        
	
	$template_main .= "</table>";

}	


$template_main .= "<br /><p>&nbsp;</p>";

if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){@include('../include/footer.'.$phpExtJeu);}
?>