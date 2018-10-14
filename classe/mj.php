<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: mj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/01/24 16:36:42 $

*/

require_once("../include/extension.inc");

if(!defined("__MJ.PHP") ) {
	Define("__MJ.PHP",	0);

	class MJ{
		
		var $ID;
		var $nom;			///< Nom du MJ
		var $titre;			///< titre du MJ
		var $flags;			///< Droits du MJ (suite de 1 et 0 cf. $liste_flags_mj dans include/const.php) 
		var $email;			///< EMail du MJ (sert pour prevenir que le FA a evolu si $wantmail =1)
		var $FANonLu;
		var $lastaction;
		var $wantmail;			///< Flag qui indique si le MJ dsire recevoir un mail quand son fa est modifi
		var $pass;			///< Mot de passe du MJ	(non crypt puisque c'est admin qui cre les MJs)
		var $imageforum="";
		var $wantmusic;			///< Flag qui indique si le MJ dsire entendre les sons des lieux dans voir_lieu
		var $dispo_pour_ppa;		///< Flag qui indique si le MJ apparait dans la liste des MJs pour PPAs
				
		function UpdateFromBD(){
			global $db;
			global $forum;
			if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )  
				$SQL = $forum->requeteMJ($this->ID);
			else $SQL = "SELECT * FROM ".NOM_TABLE_MJ." WHERE ID_MJ =".$this->ID;
			$requete=$db->sql_query($SQL);
			$row = $db->sql_fetchrow($requete);
			if(defined("IN_FORUM")&& IN_FORUM==1 && $forum->champimage<>null )  
				$this->imageforum = $row[$forum->champimage];			
			$this->nom = ConvertAsHTML($row["nom"]);
			$this->titre = ConvertAsHTML($row["titre"]);
			$this->flags = $row["flags"];
			$this->FANonLu= $row["fanonlu"];
			$this->lastaction= $row["lastaction"];
			$this->email= $row["email"];
			$this->wantmail= $row["wantmail"];
			$this->pass = $row["pass"];
			$this->wantmusic = $row["wantmusic"];
			$this->dispo_pour_ppa = $row["dispo_pour_ppa"];
		}
	
		function MJ($ID_MJ){
			$this->ID = $ID_MJ;
			$this->UpdateFromBD();
			return $this;
		}
	
		function aDroit($FLAG){
			return $this->flags[$FLAG] == 1;
		}
	
		function GetCheminFA(){
			return "../fas/mj_".$this->ID.".fa";
		}
		

		
		function ArchiveFA($force=FALSE,$contenu='') {
			$fagz = $this->GetCheminFA();
			if(file_exists($fagz)){	
			        //$str=ob_get_contents ();
			        if ($contenu=='')
				        $contenu= $this->LireFA(0);
				EnvoyerMail("",$this->email,"[".NOM_JEU." - Votre FA a &eacute;t&eacute; archiv&eacute;]","Bonjour ".$this->nom."; voici votre FA: <br />" . $contenu);
				if (defined("MAIL_FA_ARCHIVES") &&  MAIL_FA_ARCHIVES!="")
				        EnvoyerMail("",MAIL_FA_ARCHIVES,"[".NOM_JEU." - FA de ".$this->nom . " archiv]","Bonjour , voici le FA de ".$this->nom . ": <br />" .  $contenu );
				if ((unlink($fagz))===false)
					logDate( "Impossible d'effacer le fichier '".$fagz."'",E_USER_WARNING,1);
				else {	
					$msg = "Fa ";
					global $MJ;
					if ($this->ID<> $MJ->ID)
						$msg .= " de ". span($this->nom,"mj");
					if ($force)
						$msg .=" trop important => automatiquement ";
					$msg.= " effac&eacute; et envoy&eacute; par mail.";
					$MJ->OutPut($msg,true,true);
				}
			}	
		}
/*		function LireFA() {
			$fagz = $this->GetCheminFA();
			
			if(file_exists($fagz)){	
				if (!extension_loaded('zlib')) {
					$f = fopen($fagz,"rb");
					//le fois 2 en cas de debordement avant archivage
					$contenu = fgets($f, TAILLE_MAX_FA*1024*2);
					fclose($f);
				} 
				else {
						$zp = gzopen($fagz, "rb");
						$contenu = gzread($zp, TAILLE_MAX_FA*1024*2);
						gzclose($zp);	
				}
				$contenu = 	"<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>
							<input type='hidden' name='del' value='1' />
							<input type='submit' value='Reseter' /></form></div>" . $contenu;

				//archivage auto si trop gros
				logDate("taille" . filesize ($fagz));
				if (filesize ($fagz)> TAILLE_MAX_FA*1024)
					$this->ArchiveFA(TRUE);
			} 
			else 
				$contenu ="FA vide ou inexistant";
			return $contenu;
		}
*/	
	
		function LireFA($boutoninclus=1) {
			$fagz = $this->GetCheminFA();
			
			if(file_exists($fagz)){	
				if (!extension_loaded('zlib')) {
					if (($f = fopen($fagz,"rb"))!==false) {
						//le fois 2 en cas de debordement avant archivage
						//$contenu_tmp = fgets($f, TAILLE_MAX_FA*1024*2);
						//$contenu_tmp = fread($f, TAILLE_MAX_FA*1024*2);
						$contenu_tmp = '';
                                                while (!feof($f) && $contenu_tmp!==FALSE) {
                                                  $contenu_tmp .= fread($f, 8192);
                                                }	
						if (fclose($f)===false)
							logDate( "Probleme  la fermeture de '".$fagz."'",E_USER_WARNING,1);
					}
					else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
				} 
				else {
						$zp = gzopen($fagz, "rb");
						//$contenu_tmp = gzread($zp, TAILLE_MAX_FA*1024*2);
						$contenu_tmp = '';
                                                while (!gzeof($zp) && $contenu_tmp!==FALSE) {
                                                  $contenu_tmp .= gzread($zp, 8192);
                                                }	
						if (gzclose($zp)===false)
							logDate( "Probleme  la fermeture de '".$fagz."'",E_USER_WARNING,1);	
				}
				
				$tailleFA = filesize ($fagz);
				//logDate("taille" . $tailleFA);								
				global $MJ;
				if ($boutoninclus && ! ($this->ID == $MJ->ID && ($tailleFA> TAILLE_MAX_FA*1024))) 
					$contenu = 	"<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>
								<input type='hidden' name='del' value='1' />
								<input type='hidden' name='etape' value='1' />
								<input type='hidden' name='id_cible' value='".$this->ID."' />
								<input type='submit' value='Reseter' /></form></div>";
                                else $contenu="";								
				$contenu .= $contenu_tmp;						
				//si ce n'est pas un MJ qui lit le fa d'un autre mj => archivage auto si trop gros
				if($this->ID == $MJ->ID){					
					if ($tailleFA> TAILLE_MAX_FA*1024) {
						$this->ArchiveFA(TRUE, $contenu_tmp);
					}	
				}
			} 
			else 
				$contenu ="FA vide ou inexistant";
			return $contenu;
		}
	
		function EcrireFA($msg,$date=true){
				global $db;
				global $MJ;

				$fagz=$this->GetCheminFA();
				if(! file_exists ( dirname($fagz)))
						if (! mkdir(dirname($fagz),0700))
							logDate ("impossible de crer le rep " .dirname($fagz),E_USER_WARNING,1);
					
				if (!extension_loaded('zlib')) {
					if(file_exists($fagz)){
						if (($f = fopen($fagz,"rb"))!==false) {
        						//$contenu = fread($f, TAILLE_MAX_FA*1024*2);
        						$contenu = '';
                                                        while (!feof($f)) {
                                                          $contenu .= fread($f, 8192);
                                                        }
							if (fclose($f)===false)
								logDate( "Probleme  la fermeture de '".$fagz."'",E_USER_WARNING,1);
						}
						else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
	
					} 
					else 	$contenu="";
					if (($f = fopen($fagz,"r+b"))!==false) {
						if($date){
							fwrite($f,span(faitDate(time(),true),"date")."<br />");
						}					
						if (fwrite($f,stripslashes(nl2br($msg))."<br />&nbsp;<br />")===false) {
							logDate( "Probleme  l'criture de '".$fagz."'",E_USER_WARNING,1);
						}
						else
						if (fwrite($f,$contenu)===false) {
							logDate( "Probleme  l'criture de '".$fagz."'",E_USER_WARNING,1);
						}
						if (fclose($f)===false)
							logDate( "Probleme  la fermeture de '".$fagz."'",E_USER_WARNING,1);
					}
					else logDate ("impossible d'ouvrir le fichier '".$fagz."'",E_USER_WARNING,1);
				} 
				else {
					if(file_exists($fagz)){
						$zp = gzopen($fagz, "rb");
						if ($zp!=FALSE) {
        						//$contenu = gzread($zp, TAILLE_MAX_FA*1024*2);
        						$contenu = '';
                                                        while (!gzeof($zp)) {
                                                          $contenu .= gzread($zp, 8192);
                                                        }	
							if (gzclose($zp)==FALSE)
								logDate("Probleme &agrave; la fermeture de ".$fagz);
						}		    
						else logDate("Probleme &agrave; l'ouverture de ".$fagz);			
					}	else $contenu="";
					$contenu=stripslashes(nl2br($msg))."<br />&nbsp;<br />".$contenu;
					if($date)
						$contenu=span(faitDate(time(),true),"date")."<br />" . $contenu;

					$zp = gzopen($fagz, "w9");
					if ($zp!=FALSE) {
						    gzwrite($zp, $contenu);
							if (gzclose($zp)==FALSE)
								logDate("Probleme &agrave; la fermeture de ".$fagz);			
					}	    
					else 	logDate("Probleme &agrave; l'ouverture de ".$fagz);			
				
				}
				if($this->wantmail==1 && $MJ->ID<>$this->ID){
					if($this->FANonLu == 0){
						$this->FANonLu = 1;
					}
					if( ($this->lastaction < (time()-10*60)) && ($this->FANonLu == 1) ){
							$this->FANonLu = 2;
							 EnvoyerMail("",$this->email,"[".NOM_JEU." - Votre FA a &eacute;t&eacute; modifi&eacute;]","Bonjour ".$this->nom."\n Une action impliquant votre personnage a eu lieu alros que vosu n'etiez pas connect&eacute;.\n Vous pouvez en lire un compte rendu dans votre Fichier d'Action.");
					}
					
					$SQL = "UPDATE ".NOM_TABLE_REGISTRE." SET LASTACTION = '".time()."', FANonLu = '".$this->FANonLu."' WHERE id_perso = ".$this->ID;
					$db->sql_query($SQL);
				}
		}

		function OutPut($msg,$echo=true,$date=true){
			global $template_main;
			if ($msg<>"") {
				$this->EcrireFA($msg,$date);
				if($echo){$template_main.= stripslashes($msg);}
			}
		}
	
		
	}

}
?>