<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: miseenpage.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.23 $
$Date: 2010/01/24 19:33:12 $

*/

if(!defined("__MISEENPAGE.PHP") ) {
	Define("__MISEENPAGE.PHP",	0);

	$spans=array(
			"defaut"=>"c0",
			"pj"	=>"c0",
			"pa"	=>"c1",
			"po"	=>"c2",
			"lieu"	=>"c3",
			"xp"	=>"c4",
			"pv"	=>"c5",
			"race"	=>"c6",
			"specialite"	=>"c7",
			"comp"	=>"c8",
			"bonus"	=>"c9",
			"malus" =>"c10",
			"etattemp" =>"c11",
			"date" =>"c12",
			"objet" =>"c13",
			"sort" =>"c14",
			"dur" =>"c15",
			"mun" =>"c16",
			"degats"=>"c17",
			"poids"=>"c18",
			"temporaire"=>"c19",
			"equipe"=>"c20",
			"mort"=>"c21",
			"paralyse"=>"c22",
			"mj"=>"c23",
			"pi"	=>"c24",
			"quete" =>"c24",
			"etat_quete"=>"c2"
		);

	

	function span($texte,$class){
		global $spans;
		if(isset($spans[$class])){
			return "<span class='".$spans[$class]."'>".$texte."</span>";
		} else {
			return "<span class='".$spans["defaut"]."'>".$texte."</span>";
		}
	}

	$liste_images = array(
			"Force"				=>"../templates/$template_name/images/Icones/IconForce.png",
			"Sagesse"			=>"../templates/$template_name/images/Icones/IconSagesse.png",
			"Constitution"		=>"../templates/$template_name/images/Icones/IconConstitution.png",
			"Dexterite"			=>"../templates/$template_name/images/Icones/IconDexterite.png",
			"Charisme"			=>"../templates/$template_name/images/Icones/IconCharisme.png",
			"Intelligence"		=>"../templates/$template_name/images/Icones/IconIntelligence.png",
			"Air"				=>"../templates/$template_name/images/Icones/IconAir.png",
			"Terre"				=>"../templates/$template_name/images/Icones/IconTerre.png",
			"Feu"				=>"../templates/$template_name/images/Icones/IconFeu.png",
			"Eau"				=>"../templates/$template_name/images/Icones/IconEau.png",
			"Lumiere"			=>"../templates/$template_name/images/Icones/IconLumiere.png",
			"Tenebre"			=>"../templates/$template_name/images/Icones/IconTenebre.png",
			"Illusion"			=>"../templates/$template_name/images/Icones/IconIllusion.png",
			"Psychique"			=>"../templates/$template_name/images/Icones/IconPsychique.png",
			"Lame Courte"		=>"../templates/$template_name/images/Icones/IconLameCourte.png",
			"Lame Longue"		=>"../templates/$template_name/images/Icones/IconLameLongue.png",
			"Vol"				=>"../templates/$template_name/images/Icones/IconVol.png",
			"Masse Legere"		=>"../templates/$template_name/images/Icones/IconMasseLegere.png",
			"Masse Lourde"		=>"../templates/$template_name/images/Icones/IconMasseLourde.png",
			"Crochetage"		=>"../templates/$template_name/images/Icones/IconCrochetage.png",
			"Hache Courte"		=>"../templates/$template_name/images/Icones/IconHacheCourte.png",
			"Hache Longue"		=>"../templates/$template_name/images/Icones/IconHacheLongue.png",
			"Dissimulation"		=>"../templates/$template_name/images/Icones/IconDissimulation.png",
			"Arc Court"			=>"../templates/$template_name/images/Icones/IconArcCourt.png",
			"Arc Long"			=>"../templates/$template_name/images/Icones/IconArcLong.png",
			"Vigilance"			=>"../templates/$template_name/images/Icones/IconVigilance.png",
			"Petite Fronde"		=>"../templates/$template_name/images/Icones/IconPetiteFronde.png",
			"Grande Fronde"		=>"../templates/$template_name/images/Icones/IconGrandeFronde.png",
			"Aura"				=>"../templates/$template_name/images/Icones/IconAura.png",
			"Artefact Mineur"	=>"../templates/$template_name/images/Icones/IconArtefactMineur.png",
			"Artefact Majeur"	=>"../templates/$template_name/images/Icones/IconArtefactMajeur.png",
			"Observation"		=>"../templates/$template_name/images/Icones/IconObservation.png",
			"Clef"				=>"../templates/$template_name/images/Icones/IconClef.png",
			"Passe Partout"		=>"../templates/$template_name/images/Icones/IconCrochetage.png",
			"Nourriture"		=>"../templates/$template_name/images/Icones/IconNourriture.png",
			"Casque"			=>"../templates/$template_name/images/Icones/IconCasque.png",
			"Plastron"			=>"../templates/$template_name/images/Icones/IconPlastron.png",
			"Jambieres"			=>"../templates/$template_name/images/Icones/IconJambieres.png",
			"Gants"				=>"../templates/$template_name/images/Icones/IconGants.png",
			"Bague"				=>"../templates/$template_name/images/Icones/IconBague.png",
			"Amulette"			=>"../templates/$template_name/images/Icones/IconAmulette.png",
			"Argent"			=>"../templates/$template_name/images/Icones/IconArgent.png",
			"Divers"			=>"../templates/$template_name/images/Icones/IconDivers.png",
			"Livre"				=>"../templates/$template_name/images/Icones/IconLivre.png",
			"Parchemin"			=>"../templates/$template_name/images/Icones/IconParchemin.png",
			"Alphabetisation"	=>"../templates/$template_name/images/Icones/IconAlphabetisation.png",
			"Lance Longue"		=>"../templates/$template_name/images/Icones/IconLanceLongue.png",
			"Lance Courte"		=>"../templates/$template_name/images/Icones/IconLanceCourte.png",
			"Bouclier"			=>"../templates/$template_name/images/Icones/IconBouclier.png",
			"Petite Arbalete"	=>"../templates/$template_name/images/Icones/IconPetiteArbalete.png",
			"Grande Arbalete"	=>"../templates/$template_name/images/Icones/IconGrandeArbalete.png",
			"Arts Martiaux"		=>"../templates/$template_name/images/Icones/IconArtsMartiaux.png",
			"Lame Longue à une main"=>"../templates/$template_name/images/Icones/IconLameLongue.png",
			"Lame Longue à 2 mains"	=>"../templates/$template_name/images/Icones/IconLameLongue.png",
			"Soin Naturel"	=>"../templates/$template_name/images/Icones/Iconsoinnaturel.png",
			"Nage"	=>"../templates/$template_name/images/Icones/IconNage.png",
			"Escalade"	=>"../templates/$template_name/images/Icones/IconEscalade.png"			
	);
	
	function GetImage($label){
			global $liste_images;
			global $template_name;
			//$label = str_replace(" ","_",$label);
			if(isset($liste_images[$label]) ){
				if(file_exists(urldecode($liste_images[$label]))){
					return "<img src='".$liste_images[$label]."' alt='".$label."' title='".$label."' style='cursor:help;' border='0' />";
				} else {
					return "<img src='../templates/$template_name/images/Icones/IconNopic.jpg' alt='".$label."' title='".$label."' style='cursor:help;' border='0' />";
				}
			} else {
				return "<img src='../templates/$template_name/images/Icones/IconNopic.jpg' alt='".$label."' title='".$label."' style='cursor:help;' border='0' />";
			}
	}

	function makeTableau($nombre_colonne, $align="", $class, $liste_valeurs,$nowrap="nowrap",$largeur="95%",$border=0,$eclater=false){
		$retour = "\n<table border='".$border."'";
		 if ($class<>"")
		     $retour .= " class='".$class."'";
		//largeur peut etre a "" malgre la valeur par defaut de 95 si border ou eclate sont passes dans l'appel de la fonction
		if ($largeur<>"")
			 $retour .= " width='".$largeur."'";
		//else 	 $retour .= " width='95%'";
		/*  Suppression de align obsolete dans HTML 4.01
		if ($align<>"")
			$retour .= " align='".$align."'>\n";
		else 	
		*/
			$retour .= ">\n";
		$nombre_lignes = ceil(count($liste_valeurs)/$nombre_colonne);
		$colspan = 0;

		if(isset($liste_valeurs)){
			for($i=0; $i< $nombre_lignes; $i++){
				$retour .= "\t<tr>";
				for($j=0;$j<$nombre_colonne;$j++){
					if( (($i*$nombre_colonne)+$j) < count($liste_valeurs) ){
						if( (isset($liste_valeurs[($i*$nombre_colonne)+$j])) && ($liste_valeurs[($i*$nombre_colonne)+$j] !=="") ){
							$tmp = $align;
							if($eclater){
								if($j < floor($nombre_colonne/2) ){
									$tmp="left";
								} else {
									if($j==floor(($nombre_colonne/2))){
											$tmp = "center";
									} else {
										$tmp="right";
									}
								}	
							}
							//$retour .= "<td  ".$nowrap;
							$retour .= "<td ";
							if ($tmp<>"")
								$retour .= "align='".$tmp."' ";
							$retour .= "colspan='".($colspan+1)."'>".$liste_valeurs[($i*$nombre_colonne)+$j]."</td>";
							$colspan=0;
						} else {
							if( (($i*$nombre_colonne)+$j) == count($liste_valeurs) -1){
								//$retour .= "<td ".$nowrap." colspan='".($colspan+1)."'></td>";
								$retour .= "<td colspan='".($colspan+1)."'></td>";
								$colspan=0;
							}
							$colspan++;
						}
					} else {
						//$retour .= "<td ".$nowrap." colspan='".($colspan+1)."'></td>";
						$retour .= "<td colspan='".($colspan+1)."'></td>";
						$colspan=0;
					}
				}
				$retour .= "</tr>\n";
				$colspan=0;
			}
		}
		$retour .= "</table>\n";
		return $retour;
	}

	function DessineBarre($valeur,$max,$taillemax,$couleur,$alt,$modalt){
		global $template_name;
		$pas = $taillemax/$max;
		if ($modalt){$alt .= " (".$valeur."/".$max.")";} 
		$nb		 = Min($valeur,$max);
		$nb		 = Max(0,Min($max,$nb));
		$nb_Vide =  Max(0,$max - $nb);
		$RVB = CouleurHexaToRVB($couleur);
		$nb			= round($nb*$pas);
		$nb_Vide	= round($nb_Vide*$pas);

		$retour = "<img src='../templates/$template_name/images/BarreCote.png' alt=\"".$alt."\" style='cursor:help;' title=\"".$alt."\" align='middle' />";
		if ($nb != 0) {$retour .= "<img src='../templates/$template_name/images/BarrePleine.png' alt=\"".$alt."\" style='cursor:help;' title=\"".$alt."\" width='".($nb)."' height='8' align='middle' />"; }
		if ($nb_Vide != 0){$retour .= "<img src='../templates/$template_name/images/BarreVide.png' alt=\"".$alt."\" style='cursor:help;' title=\"".$alt."\" height='8' width='".($nb_Vide)."' align='middle' />";}
		$retour .= "<img src='../templates/$template_name/images/BarreCote.png' alt=\"".$alt."\" style='cursor:help;' title=\"".$alt."\" align='middle' />";
		return $retour;
	}


	function CouleurHexaToRVB($couleur){
		if ($couleur[0] == '#') {$i=1;} else {$i=0;}
		$rouge = HexDec($couleur[$i].$couleur[$i+1]);
		$vert = HexDec($couleur[$i+2].$couleur[$i+3]);
		$bleu = HexDec($couleur[$i+4].$couleur[$i+5]);
		return array($rouge,$vert,$bleu);
	}

	function faitDate($timestamp,$secondes=false){
		if($timestamp < 0){
			return "Jamais/Infini";
		}
		$corres_jour = array("Mon"=>"Lundi","Tue"=>"Mardi","Wed"=>"Mercredi","Thu"=>"Jeudi","Fri"=>"Vendredi","Sat"=>"Samedi","Sun"=>"Dimanche");
		$corres_mois = array("Jan"=>"Janvier","Feb"=>"Fevrier","Mar"=>"Mars","Apr"=>"Avril","May"=>"Mai","Jun"=>"Juin",
		"Jul"=>"Juillet","Aug"=>"Aout","Sep"=>"Septembre","Oct"=>"Octobre","Nov"=>"Novembre","Dec"=>"Decembre");

		$day = date("D",$timestamp);
		$mois = date("M",$timestamp);
		if($secondes){
			return $corres_jour[$day].date(" d ",$timestamp).$corres_mois[$mois].date(" Y  ",$timestamp)."&agrave; ".date(" G:i:s",$timestamp);
		} else {
			return $corres_jour[$day].date(" d ",$timestamp).$corres_mois[$mois].date(" Y ",$timestamp)."&agrave; " .date(" G:i",$timestamp);
		}
	}

	function faitSelect($nom,$SQL,$disabled="",$idselect=-50,$filtre=array(),$add=array(), $evenement=""){
			$retour=array();			
			$retour[0]= count($add);
			$retour[1] = "<select ".$disabled ." name='".$nom."'".$evenement.">\n";

			foreach($add as $value){
				if ($value=="&nbsp;")
					$retour[1] .= "\t<option value=''>".$value."</option>\n";
				else if ( is_array ( $value)) {					
					$temp = $value;
					$retour[1] .= "\t<option value='".$temp[0]."'";
					if ($temp[0]==$idselect)
						$retour[1] .=" selected='selected'";
					$retour[1] .=">".$temp[1]."</option>\n";
				}	
				else {
					$retour[1] .= "\t<option value='".$value."'";
					if ($value==$idselect)
						$retour[1] .=" selected='selected'";
					$retour[1] .=">".$value."</option>\n";
				}
			}

			if($SQL != ""){
				global $db;
				$requete=$db->sql_query($SQL);
				if($db->sql_numrows($requete) > 0){
					$retour[0] += $db->sql_numrows($requete);
					while($row = $db->sql_fetchrow($requete))  {
						if(!in_array($row["idselect"], $filtre)){
							if($row["idselect"] == $idselect){
								$retour[1] .= "\t<option value='".ConvertAsHTML($row["idselect"])."'  selected='selected'>".ConvertAsHTML($row["labselect"])."</option>\n";
							} else {
								$retour[1] .= "\t<option value='".ConvertAsHTML($row["idselect"])."'>".ConvertAsHTML($row["labselect"])."</option>\n";
							}
						}
						else $retour[0]=$retour[0]-1;
					}
				}
			}
			$retour[1] .= "</select>";
			return $retour;
	}

	function faitOuiNon($nom,$disabled="",$IDSelect=-1, $evenement=""){
			$retour = "<select ".$disabled." name='".$nom."'". $evenement.">\n";
			if($IDSelect == 0){
				$retour .= "\t<option value='0'  selected='selected'>Non</option><option value='1'>Oui</option>\n";
			} else {
				if($IDSelect == 1){
					$retour .= "\t<option value='0'>Non</option><option value='1' selected='selected'>Oui</option>\n";
				} else {
					$retour .= "\t<option value='0'>Non</option><option value='1'>Oui</option>\n";
				}
			}
					
			$retour .= "</select>";
			return $retour;
	}

	function timestampTostring($remise ) {
		$time = time();
		$total = abs($time - $remise); 
		$heure = floor($total / 3600) ;
		//$heure2 = $heure / 24;
		if ($heure >= 24 ) {
			$jour = floor($heure / 24);
			return $jour ."J";
		}	
		else 	{
			$secondes_restantes = $heure % 3600;
	        	$minutes = floor(($total %3600 )/60);
			return $heure ."h ". $minutes ."min";		
		}

        }

	$toto=array_flip ( $liste_langue );
	if (isset($langue))
                $locale = $toto[$langue];
        else $locale= $toto["Francais"];
        if ( strlen( $locale)>2 )
                if ( ! strstr( PHP_OS, 'WIN') )
                        $locale = substr($locale,0,2);
        setlocale(LC_TIME, $locale);
	
	
	
	
	
	        function tailleImageRedimensionnee($nomImage) {
        	         if(!defined("HAUT_MAX_LIEU"))
        	                $hautMax=194;
        	         else   $hautMax=HAUT_MAX_LIEU;     
        	         if(!defined("LARG_MAX_LIEU"))
        	                $largMax=400;
                         else   $largMax=LARG_MAX_LIEU;     

        		//logdate("nomImage ".$nomImage);
        		$imagehw = GetImageSize($nomImage); /* L'index 0 contient la largeur. L'index 1 contient la longueur. L'index 2 contient le type de l'image : 1 = GIF, 2 = JPG, 3 = PNG, 5 = PSD, 6 = BMP. L'index 3 contient la chaîne &agrave; placer dans les balises html : "height=xxx width=xxx".  */
        		//logdate("imagehw ".$imagehw);
        		if ($imagehw==FALSE) {
        			if ($largMax==-1 && $hautMax==-1)
        				return false;
        			else 	
        				return array($largReelle, $hautReelle, $largReelle, $hautReelle);
        			}
        		else {	
                		$largReelle = $imagehw[0]; 
                		$hautReelle = $imagehw[1]; 	
                                //logdate("largReelle ".$largReelle."     hautReelle ".$hautReelle);
	                         if ($largMax==-1 && $hautMax==-1)
	                         	return array($largReelle, $hautReelle, $largReelle, $hautReelle);
                                //logdate("largMax ".$largMax."     hautMax ".$hautMax);
                                $ratioL = 1;
                                if ($largReelle>$largMax && $largMax!=-1)
                                        $ratioL = $largReelle / $largMax;
                                $ratioH = 1;
                                if ($hautReelle>$hautMax && $hautMax!=-1)
                                        $ratioH = $hautReelle / $hautMax;                
                                $ratio = max($ratioL,$ratioH);        
                        }
                        //logdate("ratio ".$ratio."     ratioL ".$ratioL."     ratioH ".$ratioH);
                        $largAffichee = round($largReelle/$ratio);	        
                        $hautAffichee = round($hautReelle/$ratio);
                        //logdate("largAffichee ".$largAffichee."     hautAffichee ".$hautAffichee);
                        return array($largReelle, $hautReelle, $largAffichee, $hautAffichee);
                }	
	
	
		/** \brief fonction retournant le tableau a afficher pour voir l'image du lieu et la description.
		     Cette fonction est utilisée dans game/menu.php et admin/voir_lieu.php.
		     L'image du lieu est diminuee s'il le faut pour conserver une taille définie par l'admin dans la config du jeu
                    \param $id_lieu: l'ID du lieu
		*/
		function afficheImageLieu($id_lieu) {
        		$temp=array();
        		$erreur="";
        		$imageNoView = "../lieux/vues/noview.png";
        	
        		$ext_image="";
        		if(file_exists("../lieux/vues/view".$id_lieu.".jpg")){
        			$ext_image  =".jpg";
        		}
        		else if(file_exists("../lieux/vues/view".$id_lieu.".gif")){
        			$ext_image  =".gif";
        		}
        		else if(file_exists("../lieux/vues/view".$id_lieu.".png")){
        			$ext_image  =".png";
        		} 
        		else if(!file_exists($imageNoView)){
        			$erreur  =1;
        		} 
        	        if ($erreur=="") {
                	        if($ext_image<>""){
                	                $nomImage = "../lieux/vues/view".$id_lieu.$ext_image;
                	                $altImage="image du lieu";
                	        }        
                	        else {
                	                $nomImage = $imageNoView;
                	                $altImage="pas d'image du lieu";
                	        }        
                	        $TailleImage = tailleImageRedimensionnee($nomImage);
                	        if ($TailleImage!=FALSE) {
	                	        $largAffichee= $TailleImage[2];
	                	        $hautAffichee= $TailleImage[3];
	              	                
	                		$temp[0]= "<img src='".$nomImage."' height='".$hautAffichee. "' width='".$largAffichee."' border='0' alt=\"".$altImage."\" />";
                		}
				else $temp[0]= "<img src='".$nomImage."' border='0' alt=\"".$altImage."\" />";
                        }        		
        		else $temp[0] ="";
        		$temp[1] ="";
        		
        		if(file_exists("../lieux/descriptions/desc_".$id_lieu.".txt")){
        			$temp[1]= "../lieux/descriptions/desc_".$id_lieu.".txt";
        		} else {
                		if(file_exists("../lieux/descriptions/nodesc.txt")){
                			$temp[1]= "../lieux/descriptions/nodesc.txt";
                		}                        
                        }
                        if ($temp[1] !="") {
                		$content_array = file($temp[1]);
                		$content = implode("", $content_array);
                		$temp[1]= nl2br($content);
                        }
        		return $temp;		        
		}    


	function verif_EstImage($name) {
		$str="";
		if (isset($name) && $name!=""  ) {
			$ext=strtoupper(substr($name,strlen($name)-3));
			if ($ext!="PNG" && $ext!="JPG" && $ext!="GIF") 			
				$str= $name." n'est pas une image. Seuls sont accept&eacute;s les formats gif, png et jpg.<br />";  
		}		
		return $str;			
	}



	
	function redimImage($nom_image,$nom) {	
		//require("../include/constantes.$phpExtJeu");
		logdate("nom image dans redimImage : ".$nom_image. "" . $nom);
		$new_w=0;
		$new_h=0;		
		
		$ext=strtoupper(substr($nom,strlen($nom)-3));
		switch($ext) {
        		case "JPG": {			
        			if (! function_exists("imagejpeg")) {
        			  	logDate ("aucune fonction jpeg supportee",E_USER_WARNING,1);
        			  	exit();
        			}
      	  		
        			$src_img=ImageCreateFromjpeg($nom_image); 
        			break;
        		}	
        		case "GIF": {        			
        			if (! function_exists("imagegif")) {
        			  	logDate ("aucune fonction gif supportee",E_USER_WARNING,1);
        			  	exit();
        			}
        	  		
        			$src_img=ImageCreateFromgif($nom_image); 
        			break;
        		}	
        		case "PNG": {        			
        			if (! function_exists("imagepng")) {
        			  	logDate ("aucune fonction png supportee",E_USER_WARNING,1);
        			  	exit();
        			}
        	  		
        			$src_img=imagecreatefrompng($nom_image); 
        			break;
        		}
        		default:{
        			  	logDate ("format d'image non supportee",E_USER_WARNING,1);
        			  	exit();
        			}
        		        
        	}	        

        	$TailleImage = tailleImageRedimensionnee($nom_image);
        	if ($TailleImage==FALSE)
        		return false;
        	else {	
	        	$largAffichee= $TailleImage[2];
	        	$hautAffichee= $TailleImage[3];        	 
			
			if (($largAffichee!= $TailleImage[0]) || ($hautAffichee!= $TailleImage[1])) {
				//redimensionnement
				if ($ext=="GIF" || ( ! function_exists("imagecreatetruecolor")))
				        $dst_img= ImageCreate($largAffichee,$hautAffichee); 	
				else $dst_img= imagecreatetruecolor($largAffichee,$hautAffichee); 
				ImageCopyResized($dst_img,$src_img,0,0,0,0,$largAffichee,$hautAffichee,$TailleImage[0],$TailleImage[1] ); 	
				logDate ($dst_img ."/". $nom);	
				if ($ext=="JPG") 
					Imagejpeg($dst_img, $nom,100); 
				elseif ($ext=="GIF") 
					Imagegif($dst_img, $nom,100); 
				elseif ($ext=="PNG") 
					Imagepng($dst_img, $nom,100); 
				return true;
			}
			else {
				return false;
			}
		}  
	}


	function uploadImage($uploadfile,$nom) {
		logDate( "dans upload" .  $uploadfile. "/".$nom);
		//require("../include/constantes.$phpExtJeu");
		
		if (@phpversion() >= '4.0.2')
			// supporte apres 4.02 !!!!
			if (!is_uploaded_file($uploadfile)) {
				echo "fichier non upload&eacute;";
				exit();
			}
		
		$filename = basename($uploadfile);
		logDate( "filename".$filename);
		if (file_exists($uploadfile)) 
			//tmp_chown fonction private de nexen.
			if ((!function_exists('tmp_chown'))||( (function_exists('tmp_chown'))&&(tmp_chown($filename)) ) ) {
				/*if (! file_exists("../".$DIR_ANNONCES))
					if (!mkdir("../".$DIR_ANNONCES,0744)) {
						logDate ("impossible de créer le rep '../".$DIR_ANNONCES."'",E_USER_WARNING,1);
					}			
				*/	
				if (file_exists($nom))
					if (!unlink($nom))
					        logdate( "Impossible de supprimer l'ancien fichier '".$nom."'",E_USER_WARNING,1);
				if (!redimImage($uploadfile,$nom))	
					if (!rename($uploadfile, $nom)) {
						logdate( "Impossible de renommer le fichier '".$nom."'",E_USER_WARNING,1);
						$nom="HS0";				
					}	
			}	
			else $nom="HS1";
		else $nom="HS2";
		return $nom;	
	}


}
?>