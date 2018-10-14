<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: http_get_post.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.33 $
$Date: 2010/05/15 08:48:35 $

*/

require_once("../include/extension.inc");
if(!defined("__HTTPGETPOST.PHP")) {
	define("__HTTPGETPOST.PHP",0);


	if(file_exists ("../include/config.".$phpExtJeu)) {
		include_once('../include/config.'.$phpExtJeu);
	}
	if( ! isset($template_name)) 
		{$template_name = 'Original';}


	$template_main = "";
	$template_name = rawurlencode($template_name);

	if(!defined("__CONST.PHP")){include('../include/const.'.$phpExtJeu);}
	if(!defined("__MISEENPAGE.PHP")){include('../include/miseenpage.'.$phpExtJeu);}
	if(!defined("__MESSAGES.PHP")){
	        include('../include/messages.'.$phpExtJeu);
	        if (file_exists("../include/messages_perso.".$phpExtJeu))
	                include('../include/messages_perso.'.$phpExtJeu);
	                                
	}
	function gettime() {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		return $mtime;	
	}
	
	function logDate ($str, $type=E_USER_NOTICE, $faitecho=false) {
		global $template_main;
		if (defined("DEBUG_MODE") && DEBUG_MODE>=1) {
			// timestamp for the error entry
			$dt = date("d/m/Y H:i:s ");
			trigger_error($dt." ".$str, $type);
		}

		if ($faitecho)
			$template_main.= $str. "<br />";
	}



	function post_it($datastream, $url) { 
		global $HTTP_SESSION_VARS;
		$url = preg_replace("@^http://@i", "", $url); 
		$host2 = substr($url, 0, strpos($url, ":")); 
		$pos_2point=strpos($url, ":");
		if ($pos_2point>0) {
			$port = substr($url, $pos_2point+1,strpos($url, "/")-$pos_2point-1); 
			$host = substr($url, 0, $pos_2point); 		
		}	
		else {
			$port=80;	
			$host = substr($url, 0, strpos($url, "/")); 
		}
	
		$uri = strstr($url, "/"); 
	/*	echo "url = $url <br>";
		echo "port = $port<br>";	
		echo "host = $host<br>";
		echo "uri = $uri<br>";
	*/	
	      $reqbody = ""; 
	      logdate("isset(HTTP_SESSION_VARS['idsessMJ'])".isset($HTTP_SESSION_VARS['idsessMJ']));
	      logdate("isset(SESSION['idsessMJ'])".isset($_SESSION['idsessMJ']));
	      foreach($datastream as $key=>$val) { 
	          if (!empty($reqbody)) 
	          	$reqbody.= "&"; 
		  $reqbody.= $key."=".urlencode($val); 
	      } 
		$contentlength = strlen($reqbody); 
		//logdate("reqbody".$reqbody);
		$reqheader =  "POST $uri HTTP/1.0\r\n". 
		                   "Host: $host\n". "User-Agent: PostIt\r\n". 
		     "Content-Type: application/x-www-form-urlencoded\r\n". 
		     "Content-Length: $contentlength\r\n\r\n". 
		     "$reqbody\r\n"; 
	
		$socket = fsockopen($host, $port, $errno, $errstr); 
	
		if (!$socket) { 
		   $result["errno"] = $errno; 
		   $result["errstr"] = $errstr; 
		   return $result; 
		} 
		
		fputs($socket, $reqheader); 
		
		while (!feof($socket)) { 
		   $result[] = fgets($socket, 4096); 
		} 
		
		fclose($socket); 
		return $result; 
	} 

	
	function envoi ($data, $url) {
		global $phpExtJeu;
		$result = post_it($data, $url); 	  
	   	$nomSite = preg_replace("@^http://@i", "", $url);
	   	$nomSite = substr($nomSite, 0, strpos($nomSite, "/")); 
		
	  	if (isset($result["errno"])) { 
	    		$errno = $result["errno"]; 
	    		$errstr = $result["errstr"]; 
	    		logdate ("<b>Impossible de se connecter &agrave; $nomSite : Error $errno</b> $errstr",E_USER_WARNING,1);
	    		return 0; 
	  	} else {
	  		//filtre les premiers elements du retour (HTTP/1.1 200 OK Date: Tue, 17 Jan 2006 13:03:12 GMT Server: Apache/2.0.48 (Win32) PHP/5.1.1 mod_ssl/2.0.48 OpenSSL/0.9.7c X-Powered-By: PHP/5.1.1 Connection: close Content-Type: text/html; charset=ISO-8859-1)
	    		$i=0;
	    		$affiche=false;	    		
	    		while ($i< count($result) && $affiche==false) {	    			
	    			if ($result[$i]=="\r\n") {
	    				$affiche=true;	    			
	    			}	
				else $i++;
			}	
			while ($i< count($result)){
    				echo $result[$i];
	    			$i++;
	    		}	
	    		return 1;
	    	}
	    	
	}
	
	function makeUrl($chemin) {
		global $HTTP_SERVER_VARS;
		
		$port = $HTTP_SERVER_VARS['SERVER_PORT'];
		if ($port==443)
			$url = "https://";
		else $url = "http://";	
		$url = $url.$HTTP_SERVER_VARS['HTTP_HOST'].dirname($HTTP_SERVER_VARS['PHP_SELF']).$chemin;
		return $url;
	}




	function verifDroits($repertoire) {
	    logDate( "Peut ecrire dans ".$repertoire .":".  is_writable ( $repertoire),E_WARNING,1);
	    $groupe = filegroup ( $repertoire);
	    if ($groupe!==FALSE) {   	
	    	$tab_groupe = posix_getgrgid($groupe);
	    	logDate( "Groupe du repertoire : ID :". $groupe,E_WARNING,1);
	    	$tmp="";
		foreach($tab_groupe as $nom => $value) 
			$tmp .= $nom .": " .$value.",";
		logDate	("infos groupe :" . $tmp);
	    }	
	    else 	
	    	logDate( "Echec Lecture de filegroup ",E_WARNING,1);
	    $owner = fileowner ( $repertoire);
	    if ($owner!==FALSE)  {  	
	    	$tab_owner = posix_getpwuid($owner);
	    	logDate( "owner du repertoire : ID :". $owner . " nom : ".$tab_owner["name"] . " groupe : ".$tab_owner["gid"],E_WARNING,1);
	    }	
	    else 	
	    	logDate( "Echec Lecture de fileowner ",E_WARNING,1);
		
	}	


	/*
	function formReset() {
		$clef = array_keys($HTTP_POST_VARS); 
		for($i=0;$i<count($clef);$i++){ 
			unset($HTTP_POST_VARS[$clef[$i]]); 
		}
	}
	*/

	function affiche ($TEXTE) {
		global  ${$TEXTE};
		if (isset(${$TEXTE}))
			return ${$TEXTE};
	}		


	function afficheErreurClient($TEXTE) {
		$avant="<br /><span class='centerSimple'><span class='c0'>";
		$apres="</span></span>";
		return afficheClient($TEXTE, $avant,$apres);
	}	


	function afficheClient($TEXTE, $AVANT,$APRES) {
		global  ${$TEXTE};
		if (isset(${$TEXTE})) {
			$str=$AVANT." ". ${$TEXTE}." ". $APRES;
			return $str;
		}	
	}	


	function teste ($NOM_VARIABLE, $VALEUR,$COMP="EQ") {
		global  ${$NOM_VARIABLE};
		if ($COMP=="EQ") {
			if (isset(${$NOM_VARIABLE}) && (${$NOM_VARIABLE}==$VALEUR)) {
				return true;
			}
			else {
				return false;	
			}
		}
		elseif	($COMP=="NEQ") {
			if (isset(${$NOM_VARIABLE})) {
				if (${$NOM_VARIABLE}!=$VALEUR)
					return true;
			}
			else return false;	
		}
	}	

	function testeEtAffiche ($NOM_VARIABLE, $VALEUR,$TEXTE_A_AFFICHER,$COMP="EQ") {
		if (teste ($NOM_VARIABLE, $VALEUR,$COMP))	    
			return $TEXTE_A_AFFICHER;
		else return "";	
	}	



	function testeOPTIONS_SELECT ($NOM_VARIABLE, $VALEUR,$COMP="EQ") {
		return testeEtAffiche ($NOM_VARIABLE, $VALEUR," selected='selected'",$COMP);
	}	


	function testeCHECKBOX ($NOM_VARIABLE, $VALEUR,$COMP="EQ") {
		return testeEtAffiche ($NOM_VARIABLE, $VALEUR," checked='checked'",$COMP);
	}	


     function verif_email($email) {
     	// filtre mini X@X.XX
     	if (strlen($email)==0) return false;
        $arobase = strpos($email,"@");
        $point = strrpos($email,".");
        //logdate ('verif mail '.$point . "  /" . $arobase . "/ ".strlen($email));
        if(($arobase < 1)||($point + 2 > strlen($email))
           ||($point < $arobase+2)) return false;
        return true;
     }


	// Fonction spciale de gestion des erreurs
	function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
	{
	    // Date et heure de l'erreur
	
	    // Dfinit un tableau associatif avec les chanes d'erreur
	    // En fait, les seuls niveaux qui nous interessent
	    // sont E_WARNING, E_NOTICE, E_USER_ERROR,
	    // E_USER_WARNING et E_USER_NOTICE
	    if (@phpversion() >= '5.0.0') {
		    $errortype = array (
		                E_ERROR           => "Erreur",
		                E_WARNING         => "Alerte",
		                E_PARSE           => "Erreur d'analyse",
		                E_NOTICE          => "Note",
		                E_CORE_ERROR      => "Core Error",
		                E_CORE_WARNING    => "Core Warning",
		                E_COMPILE_ERROR   => "Compile Error",
		                E_COMPILE_WARNING => "Compile Warning",
		                E_USER_ERROR      => "Erreur spcifique",
		                E_USER_WARNING    => "Alerte spcifique",
		                E_USER_NOTICE     => "Note spcifique",
		                E_ALL => "erreur",
		                E_STRICT          => "Runtime Notice",
		                E_DEPRECATED   => "deprecated"
		                );
		    // Les niveaux qui seront enregistrs
		    $user_errors = array(E_ALL,E_USER_NOTICE, E_USER_WARNING,E_USER_ERROR, E_COMPILE_WARNING,E_COMPILE_ERROR, E_CORE_WARNING, E_CORE_ERROR,E_NOTICE,E_PARSE, E_WARNING, E_ERROR , 	 E_STRICT  , E_DEPRECATED   );
	    }
	    else {
	    		    $errortype = array (
		                1           => "Erreur",
		                2         => "Alerte",
		                4          => "Erreur d'analyse",
		                8          => "Note",
		                16      => "Core Error",
		                32   => "Core Warning",
		                64   => "Compile Error",
		                128  => "Compile Warning",
		                256  => "Erreur spcifique",
		                512  => "Alerte spcifique",
		                1024 => "Note spcifique",
		                2047 => "erreur",
		                2048 => "Notice",
		                8192 => "deprecated"
		                );
		    // Les niveaux qui seront enregistrs
		    $user_errors = array(1,2,4,8,16,32,64,128,256,512,1024,2047  );
	    }
	    global $HTTP_SERVER_VARS;
	    		 //echo "<br> errno " .$errno;   
	    if (in_array($errno, $user_errors)) {
	       //echo "DEBUG_JEU_ONLY" .DEBUG_JEU_ONLY."in_array " . $errmsg;
	    	// supprime les warning causes par pg_fetch_array
	    	if ( strstr ( $errmsg, "pg-fetch-array")===FALSE) {
	    		$err="";
		    	if (isset($HTTP_SERVER_VARS['PHP_SELF'])) 
		    	   $self = $HTTP_SERVER_VARS['PHP_SELF'];
          else  $self   = $_SERVER['PHP_SELF'];
          if (isset($self)) {
		    	        if(defined("DEBUG_JEU_ONLY") && DEBUG_JEU_ONLY==1) {		    		
        		    		//filtre les erreurs pour ne prendre que celles qui viennent du moteur et pas du forum ou autre...		    		
        		    		$rep_filename = explode("/", str_replace($HTTP_SERVER_VARS["DOCUMENT_ROOT"] ,"",str_replace("\\", "/", dirname($filename))));
        		    		
        		    		if ($rep_filename!==FALSE && isset($rep_filename[0]))  {
        					     $rep = explode("/", dirname($self));					
                      if ($rep!==FALSE && isset($rep[1])) {
        					        if ($rep[1] == $rep_filename[0])
        		      					$err = $errortype[$errno] . "\t" . $errmsg . " dans " . $filename . " ligne " . $linenum . " referer " . basename($self) ."\n";
        		      					else $err="";
        		    			}		
        			   	}
        			}	
        			else $err = $errortype[$errno] . "\t" . $errmsg . " dans " . $filename . " ligne " . $linenum . " referer " . basename($self) ."\n";
		    	}	
		    	else $err = $errortype[$errno] . "\t" . $errmsg . " dans " . $filename . " ligne " . $linenum . " \n";
      
		    if ($err<>"" && defined("DEBUG_MODE") && (DEBUG_MODE==3|| DEBUG_MODE==4)) {
			    if (function_exists('debug_backtrace')) {
				    $debug = debug_backtrace();
				    foreach($debug as $trace=>$val) { 
			    		if ($trace>0) {
				    		if (is_array($val)) {
					    		$err= $err . " \t BackTrace $trace => " .$val['function'];
							if (isset($val['args']) && is_array($val['args'])) {
						    		$err.="(";
								foreach($val['args'] as $key2=>$val2) { 
									$err.= $val2.", ";
								}
								$err=substr($err,0,-2).")";
						    	}
							if ((isset($val['file']) && $val['file']))
						    		$err= $err ." dans le fichier  ".$val['file'];
						    	if ((isset($val['line']) && $val['line']))
						    		$err= $err ." ligne ".$val['line'];
						    	if ((isset($val['class']) && $val['class']))
						    		$err= $err ." class ".$val['class'];
						    	if ((isset($val['type']) && $val['type']))
						    		$err= $err ." type ".$val['type'];
							$err.="\n";
						}	
					}
				   }
			    }
			}
			if ($err<>"") {
  			if(! file_exists ( DIRNAME(FICHIER_LOG)))
  				if (! mkdir( DIRNAME(FICHIER_LOG),0700)){
  					//logDate ("impossible de crer le rep " . DIRNAME(FICHIER_LOG),E_USER_WARNING,1);
		//			echo ("impossible de crer le rep " . DIRNAME(FICHIER_LOG));
  				}	
  			$ecritureDirecte=false;
  			if (function_exists('error_log')) {				
  				if (!error_log($err, 3, realpath (FICHIER_LOG))) {
  					$ecritureDirecte=true;
  					//logDate("retour de error_log == false ");
  				}
  			}
  			else {
  				//logDate( "error_log n'existe pas",E_USER_WARNING,1);
  				$ecritureDirecte=true;
  			}
  			if ($ecritureDirecte) {				
  				if (($f = fopen(FICHIER_LOG,"a+b"))===false) 	{
  						//logDate ("impossible d'ouvrir ou crer le fichier '" . FICHIER_LOG."'",E_USER_WARNING,1);
  				}		
  				else {	
  					if (fwrite($f,$err)===false) {
  						//logDate( "Probleme  l'criture de '".FICHIER_LOG."'",E_USER_WARNING,1);
  					}
  					if (fclose($f)===false){
  						//logDate( "Probleme  la fermeture de '".FICHIER_LOG."'",E_USER_WARNING,1);
  					}
  				}
  			}	
			} 
		}
	    	//logDate("retour de error_log : " . error_log($err, 3, FICHIER_LOG));
	    } //else logDate ($errortype[$errno] . "\t" . $errmsg . " dans " . $filename . " ligne " . $linenum . " referer " . basename($HTTP_SERVER_VARS['PHP_SELF']) ."\n" );
	   // else echo $errortype[$errno] . "\t" . $errmsg . " dans " . $filename . " ligne " . $linenum . " referer " . basename($HTTP_SERVER_VARS['PHP_SELF']) ."\n"; 
	    
	}

	function erreurFatale($msgErreur="") {
	    	global $MJ;	
	    	global $PERSO;	
	    	global $phpExtJeu;
	    	global $db;
	    	global $template_name;
	    	global $template_head;
	    	global $template_main;
	    	global $barre;
	    	global $starttime;
	    	/* DEFINITION DU MENU... */
	    	if(defined("PAGE_ADMIN") && (isset($MJ)) && get_class($MJ)=="MJ" )
	    		{include('../admin/menu_admin.'.$phpExtJeu);}
	    	else
	    	if(defined("PAGE_EN_JEU") && (isset($PERSO)) && get_class($PERSO)=="Joueur" ) {
			include('../game/menu_jeu.'.$phpExtJeu);
	    	}		
	    	else{include('../main/menu_site.'.$phpExtJeu);}
		/* FIN MENU */		
		if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
			die($msgErreur);
	}

	function instancieForum ($typeforum, $lien_forum) {
		global $phpExtJeu;
		global $HTTP_GET_VARS;
		global $HTTP_POST_VARS;
		global $HTTP_COOKIE_VARS;
		global $HTTP_SERVER_VARS;
		global $HTTP_SESSION_VARS;
		global $HTTP_ENV_VARS;
		global $HTTP_POST_FILES;		
		$forum=null;
		switch($typeforum)
		{
			case 'phpBB': 
				global $board_config;
				include_once( '../include/forum/forum_phpBB2.'.$phpExtJeu);
				$forum = new forumPHPBB($lien_forum);
				break;

			case 'phorum':
				global $PHORUM;
				include_once( '../include/forum/forum_phorum5.'.$phpExtJeu);
				$forum = new forumPHORUM($lien_forum);
				break;

		/*
			case 'pnpBB':
				include_once( '../include/forum/forum_pnpBB.'.$phpExtJeu);
				break;
		*/
			
			default:
				logdate("Aucune valeur attendue pour forum");
				die("Probleme de parametre : Aucune valeur attendue pour forum");
		}
		return $forum;
	}


        /**
        /param $id_cible id_perso du bestiaire 
        Rem: Ici on ne recopie pas les objets et sorts venants des etats temps, car on les recopie deja du bestiaire
        */
        function creationMonstre( $id_cible, $id_lieu, $typeCreation="MJ") {
                global $db;
                global $template_main;
                global $liste_type_objetSecret;
                $temp="";
		$erreur="";
		$erreurLog="";
		if ($typeCreation=="MJ")
		        global $MJ;
                $SQL= "select * from ".NOM_TABLE_REGISTRE." where pnj=2 and id_perso = ".$id_cible;			
                if($resultBestiaire=$db->sql_query($SQL)) {
                        if ($rowBestiaire = $db->sql_fetchrow($resultBestiaire)) {
                            $rowBestiaire['id_lieu']=$id_lieu;
                            $rowBestiaire['pnj']=3;
                            //le max tout court ne fonctionne pas car il prend la chaine au lieu du nombre
                            $SQL="select max(0+REPLACE(nom,'".$rowBestiaire['nom']."','')) as derniernom from ".NOM_TABLE_REGISTRE." where pnj=3 and nom like ('".$rowBestiaire['nom']."%')";
                            if($resultMax=$db->sql_query($SQL)) {        
                                    $rowMax = $db->sql_fetchrow($resultMax);
                                    if ($rowMax['derniernom'] == "")
                                            $rowBestiaire['nom'].="1";
                                    else    {
                                            $seq=$rowMax['derniernom']+1;
                                            $rowBestiaire['nom']=$rowBestiaire['nom'].$seq;     
                                    }        
                            }        
                        }
                        else   $erreur=1;                                              
                }        
                else $erreur=1;
                       
		if ($erreur=="") {
			$SQL = "INSERT INTO ".NOM_TABLE_REGISTRE." (";
			$SQL2 = " values (";
			foreach($rowBestiaire as $cle => $value) {
			        if ($cle<>"id_perso" &&  (! is_int ( $cle)) && $value!="") {
			                $SQL .= $cle.",";
			                $SQL2 .= "'".ConvertAsHTML($value)."',";
			        }        			
			} 
			$SQL=substr($SQL,0, strlen($SQL)-1).")";
			$SQL2=substr($SQL2,0, strlen($SQL2)-1).")";
			$SQL = $SQL . $SQL2;
			//nom,pass,pa,pv,po,pi,id_lieu,email,interval_remisepa,derniere_remisepa,interval_remisepi , derniere_remisepi ,lastaction,fanonlu, background) VALUES ('".ConvertAsHTML($nom)."','".ConvertAsHTML($pass)."','$nbPAs','$nbPVs','$nbPOs','$nbPIs','1','".ConvertAsHTML($email)."','".INTERVAL_REMISEPA."','".(time()-(INTERVAL_REMISEPA*3600))."','".INTERVAL_REMISEPI."','".(time()-(INTERVAL_REMISEPI*3600))."','".time()."','0', '".ConvertAsHTML($Back)."')";
			if($db->sql_query($SQL, "",BEGIN_TRANSACTION_JEU)) {
				$result_id =$db->sql_nextid();
				$id_perso = $result_id;
				$SQL = "INSERT INTO ".NOM_TABLE_PERSOETATTEMP." (id_perso,id_etattemp,fin) select ".$id_perso.",id_etattemp,fin from ".NOM_TABLE_PERSOETATTEMP."  where id_perso= ".$id_cible;
				$result=$db->sql_query($SQL);
				if (!$result) 
					$erreur.=$db->erreur;
				if ($erreur=="") {	
					if ($rowBestiaire['dissimule']) {	
						$toto = array_keys($liste_type_objetSecret);		
						$SQL = "INSERT INTO ".NOM_TABLE_ENTITECACHEE." (ID_entite,id_lieu,type, nom) VALUES (".$id_perso.",'".ConvertAsHTML($id_lieu)."',". $toto[2].",'".ConvertAsHTML($rowBestiaire['nom'])."')";
						$result=$db->sql_query($SQL);
						if (!$result) 
							$erreur.=$db->erreur;
					}
				}
	
				if($erreur=="") {		
					$SQL = "INSERT INTO ".NOM_TABLE_PERSOOBJET." (id_perso,id_objet,durabilite,munitions, equipe) select ".$id_perso.",id_objet,durabilite,munitions, equipe from ".NOM_TABLE_PERSOOBJET."  where id_perso= ".$id_cible;
					if($db->sql_query($SQL)) {
						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) select ".$id_perso.",id_magie,charges from ".NOM_TABLE_PERSOMAGIE." where  id_perso= ".$id_cible;
						if($db->sql_query($SQL)) {
							$SQL = "INSERT INTO ".NOM_TABLE_COMP." (id_perso,id_comp,xp) select ".$id_perso.",id_comp,xp from ".NOM_TABLE_COMP." where  id_perso= ".$id_cible;
							if($result=$db->sql_query($SQL)) {
                        					$SQL = "INSERT INTO ".NOM_TABLE_PERSOSPEC." (id_perso,id_spec) select ".$id_perso.",id_spec from ".NOM_TABLE_PERSOSPEC."  where  id_perso= ".$id_cible;
                        					$result=$db->sql_query($SQL);
								/* pas de creation de membre pour les monstres
								if(defined("IN_FORUM")&& IN_FORUM==1) {
									$result=$forum->CreationMembre($nom,$pass,$email);
								}
								*/
                                        			if ($result) {
									//efface l'ancien FA au cas ou il existerait
									if(file_exists("../fas/pj_".$result_id.".fa"))
										if ((unlink ("../fas/pj_".$result_id.".fa"))===false)
											$temp .= "Impossible d'effacer le fichier '../fas/pj_".$result_id.".fa'";												
                                        			        
                                        				$nom_fichierBest="../pjs/descriptions/desc_".$id_cible.".txt";			
                                        				if (($f = fopen($nom_fichierBest,"r+b"))!==false) {
                                        					if (($description = fread($f,filesize ($nom_fichierBest)))===false) {
                                        						$temp .= "Probleme  la lecture de '".$nom_fichierBest."'";
                                        					}
                                        					else 
                                        					if (fclose ($f)===false)
                                        						$temp .= "Probleme  la fermeture de '".$nom_fichierBest."'";
                                        				}	
                                        				else die ("impossible d'ouvrir le fichier '".$nom_fichier."' en ecriture");
                                        				                                        			        
                                        				$nom_fichier="../pjs/descriptions/desc_".$id_perso.".txt";			
                                        				if (($f = fopen($nom_fichier,"w+b"))!==false) {
                                        					if (fwrite($f,$description)===false) {
                                        						$temp .= "Probleme  l'criture de '".$nom_fichier."'";
                                        					}
                                        					else 
                                        					if (fclose ($f)===false)
                                        						$temp .= "Probleme  la fermeture de '".$nom_fichier."'";
                                        				}	
                                        				else die ("impossible d'ouvrir le fichier '".$nom_fichier."' en ecriture");
                                        				if ($typeCreation=="MJ")
                                        				        $MJ->OutPut("Monstre ".span(ConvertAsHTML($rowBestiaire['nom']),"pj")." correctement cree",true);
                                        				$etape=0;
                                        			}
                                        			else {
                                        			        if ($typeCreation=="MJ")
                                        				        $temp .= $MJ->OutPut($db->erreur);
                                        				$etape=0;
                                        			}

							}
						}
					}
				}
	                }
		}
		else {
		        if ($typeCreation=="MJ")
			        $MJ->OutPut($erreur,true);
			$etape=0;
		}    
		
		if ($typeCreation=="MJ")
		        $template_main.=$temp;
		else logdate($temp);        
         }       




	session_start();
	if (set_error_handler("userErrorHandler")===FALSE)
		logDate ("erreur sur set_error_handler ",E_USER_WARNING,1);
		

if ((!defined('IN_FORUM'))|| (IN_FORUM<>1) || $typeforum<>'phpBB' 
	//|| ( !file_exists (CHEMIN_FORUM. 'extension.inc'))
	) {
	if (!defined('BEGIN_TRANSACTION_JEU'))	
		define('BEGIN_TRANSACTION_JEU', 1);
	if (!defined('END_TRANSACTION_JEU'))
		define('END_TRANSACTION_JEU', 2);
	
	function unset_vars(&$var) {
		while (list($var_name, $null) = @each($var))
		{
			unset($GLOBALS[$var_name]);
		}
		return;
	}



	// PHP5 with register_long_arrays off?
	if (!isset($HTTP_POST_VARS) && isset($_POST))	{
		$HTTP_POST_VARS = $_POST;
		$HTTP_GET_VARS = $_GET;
		$HTTP_SERVER_VARS = $_SERVER;
		$HTTP_COOKIE_VARS = $_COOKIE;
		$HTTP_ENV_VARS = $_ENV;
		$HTTP_POST_FILES = $_FILES;    // $_FILES PHP 4.1.0 
		if (isset($_SESSION))
			$HTTP_SESSION_VARS =$_SESSION; // $_SESSION PHP 4.1.0
	}

     //compatibilite IIS		
     if ( !isset($HTTP_SERVER_VARS['REQUEST_URI']) ) {
        $HTTP_SERVER_VARS['REQUEST_URI'] = $HTTP_SERVER_VARS['PHP_SELF'];
        if ( isset($HTTP_SERVER_VARS['QUERY_STRING']) && !empty($HTTP_SERVER_VARS['QUERY_STRING']) ) {
            $HTTP_SERVER_VARS['REQUEST_URI'] .= '?'.$HTTP_SERVER_VARS['QUERY_STRING'];
        }
        $_SERVER['REQUEST_URI'] = $HTTP_SERVER_VARS['REQUEST_URI'];
    }

	
	//
	// addslashes to vars if magic_quotes_gpc is off
	// this is a security precaution to prevent someone
	// trying to break out of a SQL statement.
	//
	if( !get_magic_quotes_gpc() )	{
		if( is_array($HTTP_GET_VARS) )	{
			while( list($k, $v) = each($HTTP_GET_VARS) ) {
				if( is_array($HTTP_GET_VARS[$k]) ) {
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) ){
						$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
					}
					@reset($HTTP_GET_VARS[$k]);
				}
				else {
					$HTTP_GET_VARS[$k] = addslashes($v);
				}
			}
			@reset($HTTP_GET_VARS);
		}
	
		if( is_array($HTTP_POST_VARS) )	{
			while( list($k, $v) = each($HTTP_POST_VARS) ) {
				if( is_array($HTTP_POST_VARS[$k]) ) {
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) ) {
						$HTTP_POST_VARS[$k][$k2] = addslashes($v2);
					}
					@reset($HTTP_POST_VARS[$k]);
				}
				else {
					$HTTP_POST_VARS[$k] = addslashes($v);
				}
			}
			@reset($HTTP_POST_VARS);
		}
	
		if( is_array($HTTP_COOKIE_VARS) ){
			while( list($k, $v) = each($HTTP_COOKIE_VARS) )	{
				if( is_array($HTTP_COOKIE_VARS[$k]) ) {
					while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) ) {
						$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
					}
					@reset($HTTP_COOKIE_VARS[$k]);
				}
				else {
					$HTTP_COOKIE_VARS[$k] = addslashes($v);
				}
			}
			@reset($HTTP_COOKIE_VARS);
		}
	}
}
else {
	if (!defined('BEGIN_TRANSACTION_JEU'))	{
		if (defined('BEGIN_TRANSACTION'))
			define('BEGIN_TRANSACTION_JEU', BEGIN_TRANSACTION);
		else
			define('BEGIN_TRANSACTION_JEU', 1);
	}	
	if (!defined('END_TRANSACTION_JEU')){
		if (defined('END_TRANSACTION'))
			define('END_TRANSACTION_JEU', END_TRANSACTION);
		else
		define('END_TRANSACTION_JEU', 2);
	}	

}
	if (defined('IN_FORUM') && (IN_FORUM==1)) {
		$forum = instancieForum ($typeforum, CHEMIN_FORUM);
	}
	
	if ((defined("DEBUG_MODE") && DEBUG_MODE>=1)||(defined("SHOW_TIME") && SHOW_TIME==1)) 
		$starttime = gettime();

	// Pour PHP5 avec Apache2
	if (!(isset($HTTP_SERVER_VARS["PATH_TRANSLATED"])))
		$HTTP_SERVER_VARS["PATH_TRANSLATED"] = $HTTP_SERVER_VARS["SCRIPT_FILENAME"];
	
	if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "GET") {
		$clef = array_keys($HTTP_GET_VARS); 
		for($i=0;$i<count($clef);$i++){ 
			$$clef[$i] = $HTTP_GET_VARS[$clef[$i]]; 
			//logDate( $clef[$i]."  = " . $$clef[$i]);
		}
	}

	if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST") {
		$clef = array_keys($HTTP_POST_VARS); 
		for($i=0;$i<count($clef);$i++){ 
			$$clef[$i] = $HTTP_POST_VARS[$clef[$i]]; 
			//logDate( $clef[$i]."  = " . $$clef[$i]);
		}	
	}
	

	$nom_script = explode('/',$HTTP_SERVER_VARS['PHP_SELF']);
	Define("NOM_SCRIPT",	$nom_script[count($nom_script)-1]);
	if ( NOM_SCRIPT<>("gethelp.".$phpExtJeu) && NOM_SCRIPT<>("install.".$phpExtJeu) && (!(file_exists ("../include/config.".$phpExtJeu))))
		die("Fichier include/config.".$phpExtJeu. " inexistant");
	if (isset($dbmsJeu)) {
		//logdate("dbmsJeu".$dbmsJeu);
		switch($dbmsJeu)
		{
			case 'mysql':
				include( '../include/db/mysql.'.$phpExtJeu);
				$check_exts = 'mysql';
				$check_other = 'mysql';
				break;
		
			case 'mysql4':
				include( '../include/db/mysql4.'.$phpExtJeu);
				$check_exts = 'mysql';
				$check_other = 'mysql';
				break;
		
			case 'postgres':
				include( '../include/db/postgres7.'.$phpExtJeu);
				$check_exts = 'pgsql';
				$check_other = 'pgsql';
				break;
		
			case 'mssql':
				include( '../include/db/mssql.'.$phpExtJeu);
				$check_exts = 'mssql';
				$check_other = 'sybase';
				break;
		
			case 'oracle':
				include( '../include/db/oracle.'.$phpExtJeu);
				$check_exts = 'oci8';
				$check_other = 'oci8';
				break;
		
			case 'msaccess':
				include( '../include/db/msaccess.'.$phpExtJeu);
				$check_exts = 'odbc';
				$check_other = 'odbc';
				break;
		
			case 'mssql-odbc':
				include( '../include/db/mssql-odbc.'.$phpExtJeu);
				$check_exts = 'odbc';
				$check_other = 'odbc';
				break;
			
			default:
				logdate("Aucune valeur attendue pour dbmsJeu");
				die("Probleme de parametres : Aucune valeur attendue pour dbmsJeu");
		}

		if (!extension_loaded($check_exts) && !extension_loaded($check_other))
		{	
			   logdate("Module de la base choisie non charg. Modifier votre php.ini ou changer de base");
			   die("Module de la base choisie non charg. Modifier votre php.ini ou changer de base");
		}

		include("../include/dbTalesta.".$phpExtJeu);
		// Make the database connection.
		
		if (NOM_SCRIPT!=("install.".$phpExtJeu)) {
			$db = new sql_dbTalesta($hostbd, $userbd, $passbd, $bdd, false);
			$db =$db;
			if($db===false || !$db->db_connect_id)
			{
			   logdate("Impossible de se connecter  la base");
			   die("Impossible de se connecter  la base");
			}
		}			
	}

	//if((defined("DEBUG_HTML")  && DEBUG_HTML==1) || basename($HTTP_SERVER_VARS['PHP_SELF'])=="fa.".$phpExtJeu) {
	if((defined("DEBUG_HTML")  && DEBUG_HTML==1) ||NOM_SCRIPT=="fa.".$phpExtJeu) {
		ini_set("output_buffering","On");
		
		ob_start();
	}		


}	

?>