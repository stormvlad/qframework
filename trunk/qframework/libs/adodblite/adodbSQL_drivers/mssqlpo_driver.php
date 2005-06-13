<?php

/**
 * ADOdb Lite is a PHP class to encapsulate multiple database APIs and is compatible with 
 * a subset of the ADODB Command Syntax. 
 * Currently supports Frontbase, MaxDB, miniSQL, MSSQL, MSSQL Pro, MySQLi, MySQLt, MySQL, PostgresSQL,
 * PostgresSQL64, PostgresSQL7, SqLite and Sybase.
 * 
 * @version 0.01
 */

class mssqlpoStatement
{
	var $connection;
	var $parameters;
	var $sql;

	/**
	 * mysqlStatement Constructor
	 * 
	 * @access private 
	 * @param string $connection 
	 * @param string $parameters 
	 * @param string $sql 
	 */

	function mssqlpoStatement( $sql, &$connection )
	{
		$this->connection = &$connection;
		$this->parameters = array();
		$this->sql = $sql;
	} 

	/**
	 * Executes SQL query and instantiates resultset methods
	 * 
	 * @access private 
	 * @return mixed Resultset methods
	 */

	function &do_query()
	{
		global $ADODB_FETCH_MODE;

		$resultId = @mssql_query( $this->sql );

		if ($resultId === false) { // error handling if query fails
			return false;
		} 

		if ($resultId === true) { // return simplified recordset for inserts/updates/deletes with lower overhead
			$rs =& new ADORecordSet_empty();
			return $rs;
		}

		$recordset = new mssqlpoResultSet( $resultId, $this->connection );

		$recordset->_currentRow = 0;
		$recordset->sql = $this->sql;

		switch ($ADODB_FETCH_MODE)
		{
			case ADODB_FETCH_NUM: $recordset->fetchMode = MSSQL_NUM; break;
			case ADODB_FETCH_ASSOC:$recordset->fetchMode = MSSQL_ASSOC; break;
			default:
			case ADODB_FETCH_DEFAULT:
			case ADODB_FETCH_BOTH:$recordset->fetchMode = MSSQL_BOTH; break;
		}

		$recordset->_fetch();

		return $recordset;
	} 
} 

/**
 * Empty result record set for updates, inserts, ect
 * 
 * @access private 
 */

class ADORecordSet_empty
{
	var $connectionId;
	var $fields;
	var $resultId;
	var $_currentRow;
	var $_numOfRows;
	var $fetchMode;
	var $EOF = true;
	function MoveNext() {return;}
	function RecordCount() {return 0;}
	function EOF(){return TRUE;}
	function Close(){return true;}
	function fields() {return false;}
	function GetArray(){ 
		$results = array();
		return $results;
	}
	function Affected_Rows() { return 0;}
}

class mssqlpoResultSet
{
	var $connectionId;
	var $fields;
	var $resultId;
	var $_currentRow;
	var $_numOfRows = -1;
	var $fetchMode;
	var $EOF;
	var $sql;

	/**
	 * mssqlResultSet Constructor
	 * 
	 * @access private 
	 * @param string $record 
	 * @param string $resultId 
	 */

	function mssqlpoResultSet( $resultId, $connectionId )
	{
		$this->fields = array();
		$this->connectionId = $connectionId;
		$this->record = array();
		$this->resultId = $resultId;
		$this->EOF = false;
	} 

	/**
	 * Returns # of affected rows from insert/delete/update query
	 * 
	 * @access public 
	 * @return integer Affected rows
	 */

	function Affected_Rows()
	{
		return mssql_rows_affected( $this->connectionId );
	} 

	/**
	 * Frees resultset
	 * 
	 * @access public 
	 */

	function close()
	{
		mssql_free_result( $this->resultId );
		$this->fields = array();
		$this->resultId = false;
	} 

	/**
	 * Returns field name from select query
	 * 
	 * @access public 
	 * @param string $field
	 * @return string Field name
	 */

	function fields( $field )
	{
		return $this->fields[$field];
	} 

	/**
	 * Returns numrows from select query
	 * 
	 * @access public 
	 * @return integer Numrows
	 */

	function RecordCount()
	{
		$this->_numOfRows = mssql_num_rows( $this->resultId );
		return $this->_numOfRows;
	} 

	/**
	 * Returns next record
	 * 
	 * @access public 
	 */

	function MoveNext()
	{
		if (@$this->fields =& mssql_fetch_array($this->resultId,$this->fetchMode)) {
			$this->_currentRow += 1;
			return true;
		}
		if (!$this->EOF) {
			$this->_currentRow += 1;
			$this->EOF = true;
		}
		return false;
	} 

	/**
	 * Move to the first row in the recordset. Many databases do NOT support this.
	 *
	 * @return true or false
	 */

	function MoveFirst() 
	{
		if ($this->_currentRow == 0) return true;
		return $this->Move(0);			
	}			

	/**
	 * Returns the Last Record
	 * 
	 * @access public 
	 */

	function MoveLast()
	{
		if ($this->EOF) return false;
		while (!$this->EOF) {
			$fields = $this->fields;
			$this->MoveNext();
		}
		$this->fields = $fields;
		return true;
	} 

	/**
	 * Random access to a specific row in the recordset. Some databases do not support
	 * access to previous rows in the databases (no scrolling backwards).
	 *
	 * @param rowNumber is the row to move to (0-based)
	 *
	 * @return true if there still rows available, or false if there are no more rows (EOF).
	 */

	function Move($rowNumber = 0) 
	{
		$this->EOF = false;
		if ($rowNumber == $this->_currentRow) return true;
		if ($rowNumber >= $this->_numOfRows)
	   		if ($this->_numOfRows != -1) $rowNumber = $this->_numOfRows-2;
  				
		if ($this->_seek($rowNumber)) {
			$this->_currentRow = $rowNumber;
			if ($this->_fetch()) {
				return true;
			}
		} else {
			$this->EOF = true;
			return false;
		}
	}

	/**
	 * Perform Seek to specific row
	 * 
	 * @access private 
	 */

	function _seek($row)
	{
		if ($this->_numOfRows == 0) return false;
		return @mssql_data_seek($this->resultId,$row);
	}

	/**
	 * Fills field array with first database element when query initially executed
	 * 
	 * @access private 
	 */

	function _fetch()
	{
		$this->fields = @mssql_fetch_array($this->resultId,$this->fetchMode);
		if( $this->RecordCount() == 0)
		{
			$this->EOF = true;
		}
		return is_array($this->fields);
	}

	/**
	 * Check to see if last record reached
	 * 
	 * @access public 
	 */

	function EOF()
	{
		if( $this->_currentRow < $this->_numOfRows)
		{
			return false;
		}
		else
		{
			$this->EOF = true;
			return true;
		}
	} 

	/**
	 * Returns All Records in an array
	 * 
	 * @access public 
	 * @param [nRows]  is the number of rows to return. -1 means every row.
	 */

	function GetArray($nRows = -1)
	{
		$results = array();
		$cnt = 0;
		while (!$this->EOF && $nRows != $cnt) {
			$results[] = $this->fields;
			$this->MoveNext();
			$cnt++;
		}
		return $results;
	} 

	function &GetRows($nRows = -1) 
	{
		$arr =& $this->GetArray($nRows);
		return $arr;
	}

	function &GetAll($nRows = -1)
	{
		$arr =& $this->GetArray($nRows);
		return $arr;
	}

} 

?>