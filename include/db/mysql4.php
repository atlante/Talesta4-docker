<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: mysql4.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.8 $
$Date: 2010/01/24 19:33:34 $

*/

require_once("../include/extension.inc");
/***************************************************************************
 *                                 mysql4.$phpExtJeu
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : supportphpbb.com
 *
 *   $Id: mysql4.php,v 1.8 2010/01/24 19:33:34 vincent Exp $phpExtJeu,v 1.5 2002/04/02 21:13:47 the_systech Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//if(!defined("SQL_LAYER"))
//{

//
//if(!defined("SQL_LAYER")),"mysql4");

class sql_dbJeu
{

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $in_transaction = 0;
	var $delimiter = ";";
	var $comment="#";
	//
	// Constructor
	//
	function sql_dbJeu($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		$this->db_connect_id = ($this->persistency) ? mysql_pconnect($this->server, $this->user, $this->password) : mysql_connect($this->server, $this->user, $this->password);

		if( $this->db_connect_id )
		{
			if( $database != "" )
			{
				$this->dbname = $database;
				$dbselect = mysql_select_db($this->dbname);

				if( !$dbselect )
				{
					mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			return $this->db_connect_id;
		}
		else
		{
			return false;
		}
	}

	//
	// Other base methods
	//
	function sql_close()
	{
		if( $this->db_connect_id )
		{
			//
			// Commit any remaining transactions
			//
			if( $this->in_transaction )
			{
				mysql_query("COMMIT", $this->db_connect_id);
			}

			return mysql_close($this->db_connect_id);
		}
		else
		{
			return false;
		}
	}

	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE)
	{
		//
		// Remove any pre-existing queries
		//
		unset($this->query_result);

		if( $query != "" )
		{
			$this->num_queries++;
			if( $transaction == BEGIN_TRANSACTION_JEU && !$this->in_transaction )
			{
				$result = mysql_query("BEGIN", $this->db_connect_id);
				if(!$result)
				{
					return false;
				}
				$this->in_transaction = TRUE;
			}

			$this->query_result = mysql_query($query, $this->db_connect_id);
		}
		else
		{
			if( $transaction == END_TRANSACTION_JEU && $this->in_transaction )
			{
				$result = mysql_query("COMMIT", $this->db_connect_id);
			}
		}

		if( $this->query_result )
		{
			unset($this->row[(integer)($this->query_result)]);
			unset($this->rowset[(integer)($this->query_result]));

			if( $transaction == END_TRANSACTION_JEU && $this->in_transaction )
			{
				$this->in_transaction = FALSE;

				if ( !mysql_query("COMMIT", $this->db_connect_id) )
				{
					mysql_query("ROLLBACK", $this->db_connect_id);
					return false;
				}
			}
			
			return $this->query_result;
		}
		else
		{
			if( $this->in_transaction )
			{
				mysql_query("ROLLBACK", $this->db_connect_id);
				$this->in_transaction = FALSE;
			}
			return false;
		}
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_num_rows($query_id) : false;
	}

	function sql_affectedrows()
	{
		return ( $this->db_connect_id ) ? mysql_affected_rows($this->db_connect_id) : false;
	}

	function sql_numfields($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_num_fields($query_id) : false;
	}

	function sql_fieldname($offset, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_field_name($query_id, $offset) : false;
	}

	function sql_fieldtype($offset, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_field_type($query_id, $offset) : false;
	}

	function sql_fetchrow($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			//$this->row[(integer)$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
			$this->row[(integer)$query_id] = mysql_fetch_array($query_id,MYSQL_BOTH);
			return $this->row[(integer)$query_id];
		}
		else
		{
			return false;
		}
	}

	function sql_fetchrowset($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			unset($this->rowset[(integer)$query_id]);
			unset($this->row[(integer)$query_id]);

			while($this->rowset[(integer)$query_id] = mysql_fetch_array($query_id, MYSQL_BOTH))
			{
				$result[] = $this->rowset[(integer)$query_id];
			}

			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			if( $rownum > -1 )
			{
				$result = mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if( empty($this->row[(integer)$query_id]) && empty($this->rowset[$query_id]) )
				{
					if( $this->sql_fetchrow() )
					{
						$result = $this->row[(integer)$query_id][$field];
					}
				}
				else
				{
					if( $this->rowset[(integer)$query_id] )
					{
						$result = $this->rowset[(integer)$query_id][$field];
					}
					else if( $this->row[(integer)$query_id] )
					{
						$result = $this->row[(integer)$query_id][$field];
					}
				}
			}

			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_data_seek($query_id, $rownum) : false;
	}

	function sql_nextid()
	{
		return ( $this->db_connect_id ) ? mysql_insert_id($this->db_connect_id) : false;
	}

	function sql_freeresult($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[(integer)$query_id]);
			unset($this->rowset[(integer)$query_id]);

			mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}

	function sql_error()
	{
		$result['message'] = mysql_error($this->db_connect_id);
		$result['code'] = mysql_errno($this->db_connect_id);

		return $result;
	}


	function sql_export($repertoireExport) {
	    $result_archive=$this->sql_query("show tables");	    
	    if ($result_archive) {
		while ($row = $this->sql_fetchrow($result_archive) ) {				
			$sql =  "select * from ".$row[0]." INTO OUTFILE '".  $repertoireExport.$row[0].".txt' fields terminated by ';' ";  
			$result_annonce = $this->sql_query($sql); 		    		
			if (!$result_annonce) {
				logDate("probleme sur requete ".$sql,E_USER_WARNING,1);
				return FALSE;
			}	
		}
	   }  else {
	   	logDate ("Echec de show tables",E_USER_WARNING,1);
	   	return FALSE;
	   }	
	   return TRUE;
	}

	function versionServer() {
		return mysql_get_server_info(); 
	}	

 	function versionServerDiscriminante() {
 		$vers=$this->versionServer();
 		if ($vers!==FALSE) {
 			$DetailVersion = explode(".", $vers); 		 		
 			$vers=$DetailVersion[0].".".$DetailVersion[1];
 		}	
 		else $vers=4.0;
 		return $vers;
 	}
 	
 	/*
	function prefixe_table($prefixe) {
	    if ($prefixe!="tlt_") {
		    $result_archive=$this->sql_query("show tables like \"tlt_%\"");	    
		    if ($result_archive) {
			while ($row = $this->sql_fetchrow($result_archive) ) {				
				//droppe les tables destinatrices ou cas ou elles existeraient
				$table_destinatrice = str_replace ('tlt_',$prefixe,$row[0]);
				$query =  "drop table IF EXISTS ".  $table_destinatrice;
				$result_annonce = $this->sql_query($query); 		    		
				if ($result_annonce===false) 
					logDate("probleme pour supprimer ".$table_destinatrice."<br />",E_USER_WARNING,1);
				if ($row[0]<>$table_destinatrice) {
					$query =  "rename table ".$row[0]." to ". $table_destinatrice;
					$result_annonce = $this->sql_query($query); 		    		
					if ($result_annonce===false) 
						logDate("probleme pour renommer ".$row[0]." en ".$table_destinatrice ."<br />",E_USER_WARNING,1);
				}
			}
		   }  else logDate ("Echec de show tables",E_USER_WARNING,1);
	   }
	}
*/
} // class sql_dbJeu

//} // if ... define

?>
