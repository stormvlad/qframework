<?php
/**
 * ADOdb Lite Transaction Module for Postgres 6.4
 * 
 */

eval('class postgres64_transaction_EXTENDER extends postgres64_'. $last_module . '_ADOConnection { }');

class postgres64_transaction_ADOConnection extends postgres64_transaction_EXTENDER
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
		$this->transCnt += 1;
		return @pg_Exec($this->connectionId, "begin");
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

		$this->transCnt -= 1;
		return @pg_Exec($this->connectionId, "commit");
	}

	function RollbackTrans()
	{
		if ($this->transOff) return true;
		$this->transCnt -= 1;
		return @pg_Exec($this->connectionId, "rollback");
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
		if (!$this->transCnt) $this->BeginTrans();
		return $this->GetOne("select $flds from $tables where $where for update");
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

eval('class postgres64_transaction_resultset_EXTENDER extends postgres64_'. $last_module . '_ResultSet { }');

class postgres64_transaction_ResultSet extends postgres64_transaction_resultset_EXTENDER
{
}
?>