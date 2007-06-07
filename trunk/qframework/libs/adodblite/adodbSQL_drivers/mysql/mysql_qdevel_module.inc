<?php

eval('class mysql_qdevel_EXTENDER extends '. $last_module . '_ADOConnection { }');

class mysql_qdevel_ADOConnection extends mysql_qdevel_EXTENDER
{
    function SetFetchMode($mode)
    {   
        global $ADODB_FETCH_MODE;
        $old = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = $mode;
        
        return $old;
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