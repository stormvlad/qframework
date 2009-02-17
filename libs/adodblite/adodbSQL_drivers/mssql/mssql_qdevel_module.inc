<?php

eval('class mssql_qdevel_EXTENDER extends '. $last_module . '_ADOConnection { }');

class mssql_qdevel_ADOConnection extends mssql_qdevel_EXTENDER
{
    function SetFetchMode($mode)
    {   
        global $ADODB_FETCH_MODE;
        $old = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = $mode;
        
        return $old;
    }
}

eval('class mssql_qdevel_resultset_EXTENDER extends '. $last_module . '_ResultSet { }');

class mssql_qdevel_ResultSet extends mssql_qdevel_resultset_EXTENDER
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