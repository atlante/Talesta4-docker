<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: oracle.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.10 $
$Date: 2010/01/24 19:33:34 $

*/

/***************************************************************************
 *                                oracle.$phpExtJeu
 *                            -------------------
 *   begin                : Thrusday Feb 15, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: oracle.php,v 1.10 2010/01/24 19:33:34 vincent Exp $phpExtJeu,v 1.18.2.1 2002/11/26 11:42:12 psotfx Exp $
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


//if(!defined("SQL_LAYER")),"oracle");

class sql_dbJeu
{

	var $db_connect_id;
	var $query_result;
	var $in_transaction = 0;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $last_query_text = "";
	var $delimiter = "/";
	var $comment="--";
	//
	// Constructor
	//
	function sql_dbJeu($sqlserver, $sqluser, $sqlpassword, $database="", $persistency = true)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;
		//if (isset($HTTP_ENV_VARS))
		//	$os = $HTTP_ENV_VARS["OS"];
		//else $os =  $_ENV["OS"]	;
		//if (strtoupper(substr(trim($os),0,3))=="WIN") 
		//	$this->delimiter="\r\n".$this->delimiter;	
		//else
		 $this->delimiter="\n".$this->delimiter;	
		if($this->persistency)
		{
			$this->db_connect_id = OCIPLogon($this->user, $this->password, $this->server);
		}
		else
		{
			$this->db_connect_id = OCINLogon($this->user, $this->password, $this->server);
		}
		if($this->db_connect_id)
		{
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
		if($this->db_connect_id)
		{
			// Commit outstanding transactions
			if($this->in_transaction)
			{
				OCICommit($this->db_connect_id);
			}

			if($this->query_result)
			{
				OCIFreeStatement($this->query_result);
			}
			$result = OCILogoff($this->db_connect_id);
			return $result;
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
		// Remove any pre-existing queries
		unset($this->query_result);

		// Put us in transaction mode because with Oracle as soon as you make a query you're in a transaction
		$this->in_transaction = TRUE;

		if($query != "")
		{
			$this->last_query = $query;
			$this->num_queries++;

			if(eregi("LIMIT ", $query))
			{
				preg_match("/^(.*)LIMIT ([0-9]+)[, ]*([0-9]+)*/s", $query, $limits);

				$query = $limits[1];
				if($limits[3])
				{
					$row_offset = $limits[2];
					$num_rows = $limits[3];
				}
				else
				{
					$row_offset = 0;
					$num_rows = $limits[2];
				}
			}
			
			/* ne sert plus remplacement des if en case when qui est compatible sql92 et qui fonctionne donc sous oracle,mysql et postgres
			//ajout Hixcks transforme IF en decode
			while ( ($pos1=stripos($query, " if ("))!==false){
				if (($pos_egal=stripos($query, "=", $pos1))!==false) {
					$query = substr ( $query, 0,$pos1). " decode(".substr ( $query, $pos1+5,$pos_egal-($pos1+5)).",".substr ( $query, $pos_egal+1);
				}	
			}	

			while ( ($pos1=stripos($query, " if("))!==false){
				if (($pos_egal=stripos($query, "=", $pos1))!==false) {
					$query = substr ( $query, 0,$pos1). " decode(".substr ( $query, $pos1+4,$pos_egal-($pos1+4)).",".substr ( $query, $pos_egal+1);
				}	
				
			}	

			while ( ($pos1=stripos($query, ",if ("))!==false){
				if (($pos_egal=stripos($query, "=", $pos1))!==false) {
					$query = substr ( $query, 0,$pos1). " ,decode(".substr ( $query, $pos1+5,$pos_egal-($pos1+5)).",".substr ( $query, $pos_egal+1);
				}	
				
			}	

			while ( ($pos1=stripos($query, ",if("))!==false){
				if (($pos_egal=stripos($query, "=", $pos1))!==false) {
					$query = substr ( $query, 0,$pos1). " ,decode(".substr ( $query, $pos1+4,$pos_egal-($pos1+4)).",".substr ( $query, $pos_egal+1);
				}	
				
			}	
			*/
			if(eregi("^(INSERT|UPDATE) ", $query))
			{	
				$query = preg_replace("/\\\'/s", "''", $query);
			}
			$this->query_result = OCIParse($this->db_connect_id, $query);
			$success = OCIExecute($this->query_result, OCI_DEFAULT);
		}
		if($success)
		{
			if($transaction == END_TRANSACTION_JEU)
			{
				OCICommit($this->db_connect_id);
				$this->in_transaction = FALSE;
			}

			unset($this->row[(integer)$this->query_result]);
			unset($this->rowset[(integer)$this->query_result]);
			$this->last_query_text[(integer)$this->query_result] = $query;

			return $this->query_result;
		}
		else
		{
			if($this->in_transaction)
			{
				OCIRollback($this->db_connect_id);
			}
			return false;
		}
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = OCIFetchStatement($query_id, $this->rowset);
			// OCIFetchStatment kills our query result so we have to execute the statment again
			// if we ever want to use the query_id again.
			OCIExecute($query_id, OCI_DEFAULT);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_affectedrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = OCIRowCount($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if (function_exists('oci_num_fields'))
				$result = oci_num_fields($query_id);
			else 
				$result = OCINumCols($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldname($offset, $query_id = 0)
	{
		// OCIColumnName uses a 1 based array so we have to up the offset by 1 in here to maintain
		// full abstraction compatibitly
		$offset += 1;
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = strtolower(OCIColumnName($query_id, $offset));
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldtype($offset, $query_id = 0)
	{
		// This situation is the same as fieldname
		$offset += 1;
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = OCIColumntype($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrow($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result_row = "";
			$result = OCIFetchInto($query_id, $result_row, OCI_ASSOC+OCI_RETURN_NULLS);
			if($result_row == "")
			{
				return false;
			}

			for($i = 0; $i < count($result_row); $i++)
			{
				list($key, $val) = each($result_row);
				$return_arr[strtolower($key)] = $val;
			}
			$this->row[(integer)$query_id] = $return_arr;

			return $this->row[(integer)$query_id];
		}
		else
		{
			return false;
		}
	}
	// This function probably isn't as efficant is it could be but any other way I do it
	// I end up losing 1 row...
	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$rows = OCIFetchStatement($query_id, $results);
			OCIExecute($query_id, OCI_DEFAULT);
			for($i = 0; $i < $rows; $i++)
			{
				OCIFetchInto($query_id, $tmp_result, OCI_ASSOC+OCI_RETURN_NULLS);

				for($j = 0; $j < count($tmp_result); $j++)
				{
					list($key, $val) = each($tmp_result);
					$return_arr[strtolower($key)] = $val;
				}
				$result[] = $return_arr;
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
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				// Reset the internal rownum pointer.
				OCIExecute($query_id, OCI_DEFAULT);
				for($i = 0; $i < $rownum; $i++)
				  {
						// Move the interal pointer to the row we want
						OCIFetch($query_id);
				  }
				// Get the field data.
				$result = OCIResult($query_id, strtoupper($field));
			}
			else
			{
				// The internal pointer should be where we want it
				// so we just grab the field out of the current row.
				$result = OCIResult($query_id, strtoupper($field));
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
		if(!$query_id)
		{
				$query_id = $this->query_result;
		}
		if($query_id)
		{
				OCIExecute($query_id, OCI_DEFAULT);
			for($i = 0; $i < $rownum; $i++)
				{
					OCIFetch($query_id);
				}
			$result = OCIFetch($query_id);
			return $result;
		}
		else
		{
				return false;
		}
	}


	function sql_nextid($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id && $this->last_query_text[(integer)$query_id] != "")
		{
			if( eregi("^(INSERT{1}|^INSERT INTO{1})[[:space:]][\"]?([a-zA-Z0-9\_\-]+)[\"]?", $this->last_query_text[$query_id], $tablename))
			{
				$query = "SELECT seq_".$tablename[2].".CURRVAL FROM DUAL";
				$temp_q_id =  OCIParse($this->db_connect_id, $query);
				OCIExecute($temp_q_id, OCI_DEFAULT);
				OCIFetchInto($temp_q_id, $temp_result, OCI_ASSOC+OCI_RETURN_NULLS);

				if($temp_result)
				{
					return $temp_result['CURRVAL'];
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}



	function sql_freeresult($query_id = 0)
	{
		if(!$query_id)
		{
				$query_id = $this->query_result;
		}
		if($query_id)
		{
				$result = OCIFreeStatement($query_id);
				return $result;
		}
		else
		{
				return false;
		}
	}
	function sql_error($query_id  = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		$result  = OCIError($query_id);
		return $result;
	}

	function sql_export($repertoireExport) {
		return TRUE;		
	}	

	function versionServer() {
		return ociserverversion(); 
	}	

 	function versionServerDiscriminante() {
 		$vers=$this->versionServer();
 		$DetailVersion = explode(".", $vers); 		 		
 		return $DetailVersion[0].".".$DetailVersion[1];
 	}
/*
	function prefixe_table($prefixe) {
	    if ($prefixe!="tlt_") {
		    $result_archive=$this->sql_query("select lower(table_name) from user_tables where upper(table_name) like 'TLT_%'");	    
		    if ($result_archive) {
			while ($row = $this->sql_fetchrow($result_archive) ) {				
				if (isset($row['table_name'])) {
					$query =  "alter table ".$row['table_name']." rename to ".  str_replace ($row['table_name'],'tlt_',$prefixe);
					$result_annonce = $this->sql_query($query); 		    		
					if (!$result_annonce) 
						logDate("probleme pour renommer ".$row['table_name'],E_USER_WARNING,1);
				}
			}
		   }  else logDate ("Echec de show tables",E_USER_WARNING,1);
	   }
	}
*/
} // class sql_dbJeu

//} // if ... define

?>