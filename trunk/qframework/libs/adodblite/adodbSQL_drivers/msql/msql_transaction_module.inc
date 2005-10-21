<?php
/**
 * ADOdb Lite Transaction Module for Mini Sql
 * 
 */

eval('class msql_transaction_EXTENDER extends msql_'. $last_module . '_ADOConnection { }');

class msql_transaction_ADOConnection extends msql_transaction_EXTENDER
{
}

eval('class msql_transaction_resultset_EXTENDER extends msql_'. $last_module . '_ResultSet { }');

class msql_transaction_ResultSet extends msql_transaction_resultset_EXTENDER
{
}
?>