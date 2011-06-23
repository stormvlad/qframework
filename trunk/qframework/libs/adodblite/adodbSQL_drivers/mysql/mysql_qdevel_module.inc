<?php

eval('class mysql_qdevel_EXTENDER extends '. $last_module . '_ADOConnection { }');

class mysql_qdevel_ADOConnection extends mysql_qdevel_EXTENDER
{
    var $metaTablesSQL = "SHOW TABLES";	
    var $metaColumnsSQL = "SHOW COLUMNS FROM %s";
	
    function SetFetchMode($mode)
    {   
        global $ADODB_FETCH_MODE;
        $old = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = $mode;
        
        return $old;
    }
    
    /**
    * @param ttype can either be 'VIEW' or 'TABLE' or false. 
    * 		If false, both views and tables are returned.
    *		"VIEW" returns only views
    *		"TABLE" returns only tables
    * @param showSchema returns the schema/user with the table name, eg. USER.TABLE
    * @param mask  is the input mask - only supported by oci8 and postgresql
    *
    * @return  array of tables for current database.
    */ 
    function &MetaTables($ttype = false, $showSchema = false, $mask = false) 
    {	
            $save = $this->metaTablesSQL;
            
            if ($showSchema && is_string($showSchema))
            {
                $this->metaTablesSQL .= " FROM " . $showSchema;
            }
            
            if ($mask)
            {
                $mask = $this->qstr($mask);
                $this->metaTablesSQL .= " LIKE " . $mask;
            }
            
            $ret = &$this->_MetaTables($ttype, $showSchema);
            
            $this->metaTablesSQL = $save;
            return $ret;
    }

    /**
    * Add function info here
    */
    function &_MetaTables($ttype = false, $showSchema = false, $mask = false) 
    {
            global $ADODB_FETCH_MODE;
            $false = false;
            
            if ($mask)
            {
                return $false;
            }
            
            if ($this->metaTablesSQL)
            {
                $save = $ADODB_FETCH_MODE; 
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM; 
                
                $rs = $this->Execute($this->metaTablesSQL);
                
                if (isset($savem))
                {
                    $this->SetFetchMode($savem);
                }
                $ADODB_FETCH_MODE = $save; 
                
                if ($rs === false)
                {
                    return $false;
                }
                $arr  = &$rs->GetArray();
                $arr2 = array();
                
                if ($hast = ($ttype && isset($arr[0][1])))
                { 
                    $showt = strncmp($ttype, "T", 1);
                }
                
                for ($i = 0; $i < sizeof($arr); $i++)
                {
                    if ($hast)
                    {
                        if ($showt == 0)
                        {
                            if (strncmp($arr[$i][1], "T", 1) == 0)
                            {
                                $arr2[] = trim($arr[$i][0]);
                            }
                        }
                        else
                        {
                            if (strncmp($arr[$i][1], "V", 1) == 0)
                            {
                                $arr2[] = trim($arr[$i][0]);
                            }
                        }
                    }
                    else
                    {
                        $arr2[] = trim($arr[$i][0]);
                    }
                }
                
                $rs->Close();
                return $arr2;
            }
            
            return $false;
    }

    /**
    * Add function info here
    */
    function _findschema(&$table, &$schema)
    {
        if (!$schema && ($at = strpos($table, ".")) !== false)
        {
            $schema = substr($table, 0, $at);
            $table  = substr($table, $at + 1);
        }
    }	
}

eval('class mysql_qdevel_resultset_EXTENDER extends '. $last_module . '_ResultSet { }');

class mysql_qdevel_ResultSet extends mysql_qdevel_resultset_EXTENDER
{
    function &FetchRow()
    {
        if ($this->EOF)
        {
            return false;
        }
        
        $arr = $this->fields;
        $this->MoveNext();
        
        return $arr;
    }
}

?>