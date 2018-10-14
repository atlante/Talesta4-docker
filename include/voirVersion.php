<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: voirVersion.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/06 06:12:39 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $detail_version;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

function recupereRevisionCVS($fichier) {
        $handle = fopen($fichier, "r");
        $tmp=array();
        $trouve=false;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $trouve==false) {
            $num = count($data);
            $c=0;
            while ( $c < $num && $trouve==false) {
                if (! isset($tmp[0])) {
                        $pos = strpos($data[$c], "Revision");
                        if ($pos !== false) {
                                $tmp[0]=$data[$c];
                                if (isset($tmp[1]))
                                        $trouve=true;
                        }
                }
                if (! isset($tmp[1])) {
                        $pos = strpos($data[$c], "Date");
                        if ($pos !== false) {
                                $tmp[1]=$data[$c];
                                if (isset($tmp[0]))
                                        $trouve=true;
                        }
                }
                $c++;
            }
        }
        
        fclose($handle);        
        return $tmp;
}        

function parcoursRepertoire($rep) {
        $dir = opendir($rep);
        global $template_main; 
        $fichierTrouve=false;
        $continue=0;
        if ($dir != FALSE) {
        	  while(FALSE !==($file = readdir($dir))) {
        	  	if (is_file ($rep."/".$file)) {
        		  	$ext = strtoupper(substr($file,strrpos($file, ".")+1 ));
        		   	if ( $ext=="PHP" || $ext=="SQL"  )  {
        				$template_main .= "<tr><td>".$rep."/".$file ."</td><td>". md5_file ($rep."/".$file)."</td><td>";
        				$tmp=recupereRevisionCVS($rep."/".$file);
        				if (isset($tmp[0])) $template_main .= $tmp[0];
        				$template_main .="</td><td>";
        				if (isset($tmp[1])) $template_main .= $tmp[1];
        				$template_main .= "</td></tr>";
        			}	
       			}
        		else {
        			if ($file!="." && $file!="..") 
        			    	parcoursRepertoire($rep."/".$file);
        		}	
        	  }
        }	  
}	

        $rep = "..";
        
        $template_main .= "<div class='centerSimple'> Version utilise : " .VERSION . "<br /><br />";
        $template_main .= "Checksum des fichiers <br /></div> ";
        $template_main .= "<table class='detailscenter'>";
        $template_main .= "<tr><td>Nom fichier</td><td>MD5</td><td>Rvision</td><td>Date</td></tr>";
        parcoursRepertoire($rep);
        $template_main .= "</table>";
	
if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
