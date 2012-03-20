<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 * 
 * @category Piwik_Plugins
 * @package Piwik_DBStats
 */

/**
 * DBStats API is used to request the overall status of the Mysql tables in use by Piwik.
 *
 * FIXME PostgreSQL / needs to be ported to return PostgreSQL-related stats (system and table).
 * 
 * @package Piwik_DBStats
 */
class Piwik_DBStats_API
{
	static private $instance = null;
	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

 	public function getDBStatus()
	{
		Piwik::checkUserIsSuperUser();

		if(function_exists('mysql_connect'))
		{
			$configDb = Piwik_Config::getInstance()->database;
			$link   = mysql_connect($configDb['host'], $configDb['username'], $configDb['password']);
			$status = mysql_stat($link);
			mysql_close($link);
			$status = explode("  ", $status);
		}
		else
		{
			$db = Zend_Registry::get('db');

			$fullStatus = $db->fetchAssoc('SHOW STATUS;');
			if(empty($fullStatus)) {
				throw new Exception('Error, SHOW STATUS failed');
			}

			$status = array(
				'Uptime' => $fullStatus['Uptime']['Value'],
				'Threads' => $fullStatus['Threads_running']['Value'],
				'Questions' => $fullStatus['Questions']['Value'],
				'Slow queries' => $fullStatus['Slow_queries']['Value'],
				'Flush tables' => $fullStatus['Flush_commands']['Value'],
				'Open tables' => $fullStatus['Open_tables']['Value'],
//				'Opens: ', // not available via SHOW STATUS
//				'Queries per second avg' =/ // not available via SHOW STATUS
			);
		}

		return $status;
	}
	
	public function getTableStatus($table, $field = '') 
	{
		Piwik::checkUserIsSuperUser();
		$db = Zend_Registry::get('db');
		// http://dev.mysql.com/doc/refman/5.1/en/show-table-status.html
		$tables = $db->fetchAll("SELECT c.relname, relpages, reltuples, seq_scan, seq_tup_read, idx_scan, idx_tup_fetch, n_tup_ins, n_tup_upd, n_tup_del, n_tup_hot_upd, " . 
								"n_live_tup, n_dead_tup FROM pg_stat_user_tables t join pg_class c on (t.relid = c.oid) WHERE c.relname LIKE ". $db->quote($table));

		if(!isset($tables[0])) {
			throw new Exception('Error, table or field not found');
		}
		if ($field == '')
		{
			return $tables[0];
		}
		else
		{
			return $tables[0][$field];
		}
	}

	public function getAllTablesStatus() 
	{
		Piwik::checkUserIsSuperUser();
		$db = Zend_Registry::get('db');
		// http://dev.mysql.com/doc/refman/5.1/en/show-table-status.html
		$tablesPiwik =  Piwik::getTablesInstalled();
		$total = array('relname' => 'Total', 'relpages' => 0, 'reltuples' => 0, 'seq_scan' => 0, 'seq_tup_read' => 0, 'idx_scan' => 0,
						'idx_tup_fetch' => 0, 'n_tup_ins' => 0, 'n_tup_upd' => 0, 'n_tup_del' => 0, 'n_tup_hot_upd' => 0, 'n_live_tup' => 0,
						'n_dead_tup' => 0);
		$table = array();
		foreach($tablesPiwik as $tableName) 
		{
			$t = $this->getTableStatus($tableName);
			$total['relpages'] += $t['relpages'];
			$total['reltuples'] += $t['reltuples'];
			$total['seq_scan'] += $t['seq_scan'];
			$total['seq_tup_read'] += $t['seq_tup_read'];
			$total['idx_scan'] += $t['idx_scan'];
			$total['idx_tup_fetch'] += $t['idx_tup_fetch'];
			$total['n_tup_ins'] += $t['n_tup_ins'];
			$total['n_tup_upd'] += $t['n_tup_upd'];
			$total['n_tup_del'] += $t['n_tup_del'];
			$total['n_tup_hot_upd'] += $t['n_tup_hot_upd'];
			$total['n_live_tup'] += $t['n_live_tup'];
			$total['n_dead_tup'] += $t['n_dead_tup'];
			
			$table[] = $t;
		}
		$table['Total'] = $total;
		
		return $table;
	}
}
