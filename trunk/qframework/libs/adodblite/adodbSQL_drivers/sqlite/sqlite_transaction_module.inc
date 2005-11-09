<?php
/**
 * ADOdb Lite Transaction Module for Sqlite
 * 
 */

eval('class sqlite_transaction_EXTENDER extends sqlite_'. $last_module . '_ADOConnection { }');

class sqlite_transaction_ADOConnection extends sqlite_transaction_EXTENDER
{
	var $autoCommit = true;
	var $transOff = 0;
	var $transCnt = 0;
	var $transaction_status = true;

	function StartTrans($errfn = 'ADODB_TransMonitor')
	{
		if ($this->transOff > 0) {
			$this->transOff += 1;
			return;
		}
		$this->transaction_status = true;
		$this->BeginTrans();
		$this->transOff = 1;
	}

	function BeginTrans()
	{
		if ($this->transOff) return true; 
		$ret = $this->Execute("BEGIN TRANSACTION");
		$this->transCnt += 1;
		return true;
	}

	function CompleteTrans($autoComplete = true)
	{
		if ($this->transOff > 1) {
			$this->transOff -= 1;
			return true;
		}
		$this->transOff = 0;
		if ($this->transaction_status && $autoComplete) {
			if (!$this->CommitTrans()) {
				$this->transaction_status = false;
			}
		} else {
			$this->RollbackTrans();
		}
		return $this->transaction_status;
	}

	function CommitTrans($ok=true) 
	{
		if ($this->transOff) return true; 
		if (!$ok) return $this->RollbackTrans();
		$ret = $this->Execute("COMMIT");
		if ($this->transCnt>0)$this->transCnt -= 1;
		return !empty($ret);
	}

	function RollbackTrans()
	{
		if ($this->transOff) return true; 
		$ret = $this->Execute("ROLLBACK");
		if ($this->transCnt>0)$this->transCnt -= 1;
		return !empty($ret);
	}

	function FailTrans()
	{
		$this->transaction_status = false;
	}

	function HasFailedTrans()
	{
		if ($this->transOff > 0) return $this->transaction_status == false;
		return false;
	}

	function RowLock($tables,$where,$flds='1 as ignore') 
	{
		return false;
	}

	function CommitLock($table)
	{
		return $this->CommitTrans();
	}

	function RollbackLock($table)
	{
		return $this->RollbackTrans();
	}

}

eval('class sqlite_transaction_resultset_EXTENDER extends sqlite_'. $last_module . '_ResultSet { }');

class sqlite_transaction_ResultSet extends sqlite_transaction_resultset_EXTENDER
{
}
?>