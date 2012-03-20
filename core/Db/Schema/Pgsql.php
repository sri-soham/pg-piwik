<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Myisam.php 5176 2011-09-18 07:25:19Z matt $
 *
 * @category Piwik
 * @package Piwik
 */

/**
 * MySQL schema
 *
 * @package Piwik
 * @subpackage Piwik_Db
 */
class Piwik_Db_Schema_Pgsql implements Piwik_Db_Schema_Interface
{

	/**
	 * Is this schema available?
	 *
	 * @return bool True if schema is available; false otherwise
	 */
	static public function isAvailable()
	{
        return true;
	}

	/**
	 * Get the SQL to create Piwik tables
	 *
	 * @return array of strings containing SQL
	 */
	public function getTablesCreateSql()
	{
		$config = Zend_Registry::get('config');
		$prefixTables = $config->database->tables_prefix;
		$tables = array(
			'user' => "CREATE TABLE {$prefixTables}user (
						  login VARCHAR(100) NOT NULL,
						  password CHAR(32) NOT NULL,
						  alias VARCHAR(45) NOT NULL,
						  email VARCHAR(100) NOT NULL,
						  token_auth CHAR(32) NOT NULL,
						  date_registered TIMESTAMP NULL,
						  PRIMARY KEY(login),
						  CONSTRAINT uniq_keytoken UNIQUE (token_auth)
						)
			",

			'access' => "CREATE TABLE {$prefixTables}access (
						  login VARCHAR(100) NOT NULL,
						  idsite INTEGER NOT NULL,
						  access VARCHAR(10) NULL,
						  PRIMARY KEY(login, idsite)
						)
			",

			'site' => "CREATE TABLE {$prefixTables}site (
						  idsite SERIAL,
						  name VARCHAR(90) NOT NULL,
						  main_url VARCHAR(255) NOT NULL,
  						  ts_created TIMESTAMP NULL,
  						  ecommerce BOOLEAN NOT NULL DEFAULT false,
  						  timezone VARCHAR( 50 ) NOT NULL,
  						  currency CHAR( 3 ) NOT NULL,
  						  excluded_ips TEXT NOT NULL,
  						  excluded_parameters VARCHAR ( 255 ) NOT NULL,
  						  \"group\" VARCHAR(250) NOT NULL, 
						  PRIMARY KEY(idsite)
						)
			",

			'site_url' => "CREATE TABLE {$prefixTables}site_url (
							  idsite INTEGER NOT NULL,
							  url VARCHAR(255) NOT NULL,
							  PRIMARY KEY(idsite, url)
						)
			",

			'goal' => "	CREATE TABLE {$prefixTables}goal (
							  idsite int NOT NULL,
							  idgoal int NOT NULL,
							  name varchar(50) NOT NULL,
							  match_attribute varchar(20) NOT NULL,
							  pattern varchar(255) NOT NULL,
							  pattern_type varchar(10) NOT NULL,
							  case_sensitive INT NOT NULL,
							  allow_multiple INT NOT NULL,
							  revenue float NOT NULL,
							  deleted INT NOT NULL default 0,
							  PRIMARY KEY  (idsite,idgoal)
							)
			",

			'logger_message' => "CREATE TABLE {$prefixTables}logger_message (
									  idlogger_message SERIAL,
									  timestamp TIMESTAMP NULL,
									  message TEXT NULL,
									  PRIMARY KEY(idlogger_message)
									)
			",

			'logger_api_call' => "CREATE TABLE {$prefixTables}logger_api_call (
									  idlogger_api_call SERIAL,
									  class_name VARCHAR(255) NULL,
									  method_name VARCHAR(255) NULL,
									  parameter_names_default_values TEXT NULL,
									  parameter_values TEXT NULL,
									  execution_time FLOAT NULL,
									  caller_ip INET NOT NULL,
									  timestamp TIMESTAMP NULL,
									  returned_value TEXT NULL,
									  PRIMARY KEY(idlogger_api_call)
									)
			",

			'logger_error' => "CREATE TABLE {$prefixTables}logger_error (
									  idlogger_error SERIAL,
									  timestamp TIMESTAMP NULL,
									  message TEXT NULL,
									  errno INTEGER NULL,
									  errline INTEGER NULL,
									  errfile VARCHAR(255) NULL,
									  backtrace TEXT NULL,
									  PRIMARY KEY(idlogger_error)
									)
			",

			'logger_exception' => "CREATE TABLE {$prefixTables}logger_exception (
									  idlogger_exception SERIAL,
									  timestamp TIMESTAMP NULL,
									  message TEXT NULL,
									  errno INTEGER NULL,
									  errline INTEGER NULL,
									  errfile VARCHAR(255) NULL,
									  backtrace TEXT NULL,
									  PRIMARY KEY(idlogger_exception)
									)
			",

			'log_action' => "CREATE TABLE {$prefixTables}log_action (
									  idaction SERIAL,
									  name TEXT,
									  hash INTEGER NOT NULL,
  									  type INTEGER NULL,
									  PRIMARY KEY(idaction)
						)
			",

			'log_visit' => "CREATE TABLE {$prefixTables}log_visit (
							  idvisit SERIAL,
							  idsite INTEGER NOT NULL,
							  idvisitor BYTEA NOT NULL,
							  visitor_localtime TIME NOT NULL,
							  visitor_returning INT NOT NULL,
							  visitor_count_visits INT NOT NULL,
							  visitor_days_since_last INT NOT NULL,
							  visitor_days_since_order INT NOT NULL,
							  visitor_days_since_first INT NOT NULL,
							  visit_first_action_time TIMESTAMP NOT NULL,
							  visit_last_action_time TIMESTAMP NOT NULL,
							  visit_exit_idaction_url INT NOT NULL,
							  visit_exit_idaction_name INT NOT NULL,
							  visit_entry_idaction_url INT NOT NULL,
							  visit_entry_idaction_name INT NOT NULL,
							  visit_total_actions INT NOT NULL,
							  visit_total_time INT NOT NULL,
							  visit_goal_converted BOOLEAN NOT NULL DEFAULT false,
							  visit_goal_buyer INT NOT NULL,
							  referer_type INT NULL,
							  referer_name VARCHAR(70) NULL,
							  referer_url TEXT NOT NULL,
							  referer_keyword VARCHAR(255) NULL,
							  config_id BYTEA NOT NULL,
							  config_os CHAR(3) NOT NULL,
							  config_browser_name VARCHAR(10) NOT NULL,
							  config_browser_version VARCHAR(20) NOT NULL,
							  config_resolution VARCHAR(9) NOT NULL,
							  config_pdf BOOLEAN NOT NULL DEFAULT false,
							  config_flash BOOLEAN NOT NULL DEFAULT false,
							  config_java BOOLEAN NOT NULL DEFAULT false,
							  config_director BOOLEAN NOT NULL DEFAULT false,
							  config_quicktime BOOLEAN NOT NULL DEFAULT false,
							  config_realplayer BOOLEAN NOT NULL DEFAULT false,
							  config_windowsmedia BOOLEAN NOT NULL DEFAULT false,
							  config_gears BOOLEAN NOT NULL DEFAULT false,
							  config_silverlight BOOLEAN NOT NULL DEFAULT false,
							  config_cookie BOOLEAN NOT NULL DEFAULT false,
							  location_ip INET NOT NULL,
							  location_browser_lang VARCHAR(20) NOT NULL,
							  location_country CHAR(3) NOT NULL,
							  location_continent CHAR(3) NOT NULL,
							  custom_var_k1 VARCHAR(200) DEFAULT NULL,
							  custom_var_v1 VARCHAR(200) DEFAULT NULL,
							  custom_var_k2 VARCHAR(200) DEFAULT NULL,
							  custom_var_v2 VARCHAR(200) DEFAULT NULL,
							  custom_var_k3 VARCHAR(200) DEFAULT NULL,
							  custom_var_v3 VARCHAR(200) DEFAULT NULL,
							  custom_var_k4 VARCHAR(200) DEFAULT NULL,
							  custom_var_v4 VARCHAR(200) DEFAULT NULL,
							  custom_var_k5 VARCHAR(200) DEFAULT NULL,
							  custom_var_v5 VARCHAR(200) DEFAULT NULL,
							  PRIMARY KEY(idvisit)
							)
			",

			'log_conversion_item' => "CREATE TABLE {$prefixTables}log_conversion_item (
												  idsite INT NOT NULL,
										  		  idvisitor BYTEA NOT NULL,
										          server_time TIMESTAMP NOT NULL,
												  idvisit INTEGER NOT NULL,
												  idorder varchar(100) NOT NULL,
												  
												  idaction_sku INTEGER NOT NULL,
												  idaction_name INTEGER NOT NULL,
												  idaction_category INTEGER NOT NULL,
												  idaction_category2 INTEGER NOT NULL,
												  idaction_category3 INTEGER NOT NULL,
												  idaction_category4 INTEGER NOT NULL,
												  idaction_category5 INTEGER NOT NULL,
												  price FLOAT NOT NULL,
												  quantity INTEGER NOT NULL,
												  deleted INT NOT NULL,
												  PRIMARY KEY(idvisit, idorder, idaction_sku)
												)
			",

			'log_conversion' => "CREATE TABLE {$prefixTables}log_conversion (
									  idvisit INT NOT NULL,
									  idsite INT NOT NULL,
									  idvisitor BYTEA NOT NULL,
									  server_time timestamp NOT NULL,
									  idaction_url INT default NULL,
									  idlink_va INT default NULL,
									  referer_visit_server_date date default NULL,
									  referer_type INT default NULL,
									  referer_name varchar(70) default NULL,
									  referer_keyword varchar(255) default NULL,
									  visitor_returning INT NOT NULL,
        							  visitor_count_visits INT NOT NULL,
        							  visitor_days_since_first INT NOT NULL,
							  		  visitor_days_since_order INT NOT NULL,
									  location_country char(3) NOT NULL,
									  location_continent char(3) NOT NULL,
									  url text NOT NULL,
									  idgoal INT NOT NULL,
									  buster BIGINT NOT NULL,
									  
									  idorder varchar(100) default NULL,
									  items SMALLINT DEFAULT NULL,
									  revenue numeric default NULL,
									  revenue_subtotal numeric default NULL,
									  revenue_tax numeric default NULL,
									  revenue_shipping numeric default NULL,
									  revenue_discount numeric default NULL,
        							  
									  custom_var_k1 VARCHAR(200) DEFAULT NULL,
        							  custom_var_v1 VARCHAR(200) DEFAULT NULL,
        							  custom_var_k2 VARCHAR(200) DEFAULT NULL,
        							  custom_var_v2 VARCHAR(200) DEFAULT NULL,
        							  custom_var_k3 VARCHAR(200) DEFAULT NULL,
        							  custom_var_v3 VARCHAR(200) DEFAULT NULL,
        							  custom_var_k4 VARCHAR(200) DEFAULT NULL,
        							  custom_var_v4 VARCHAR(200) DEFAULT NULL,
        							  custom_var_k5 VARCHAR(200) DEFAULT NULL,
        							  custom_var_v5 VARCHAR(200) DEFAULT NULL,
									  PRIMARY KEY (idvisit, idgoal, buster),
									  CONSTRAINT unique_idsite_idorder UNIQUE (idsite, idorder)
									)
			",

			'log_link_visit_action' => "CREATE TABLE {$prefixTables}log_link_visit_action (
											  idlink_va SERIAL,
									          idsite INT NOT NULL,
									  		  idvisitor BYTEA NOT NULL,
									          server_time TIMESTAMP NOT NULL,
											  idvisit INTEGER NOT NULL,
											  idaction_url INTEGER NOT NULL,
											  idaction_url_ref INTEGER NOT NULL,
											  idaction_name INTEGER,
											  idaction_name_ref INTEGER NOT NULL,
											  time_spent_ref_action INTEGER NOT NULL,
											  custom_var_k1 VARCHAR(200) DEFAULT NULL,
											  custom_var_v1 VARCHAR(200) DEFAULT NULL,
											  custom_var_k2 VARCHAR(200) DEFAULT NULL,
											  custom_var_v2 VARCHAR(200) DEFAULT NULL,
											  custom_var_k3 VARCHAR(200) DEFAULT NULL,
											  custom_var_v3 VARCHAR(200) DEFAULT NULL,
											  custom_var_k4 VARCHAR(200) DEFAULT NULL,
											  custom_var_v4 VARCHAR(200) DEFAULT NULL,
											  custom_var_k5 VARCHAR(200) DEFAULT NULL,
											  custom_var_v5 VARCHAR(200) DEFAULT NULL,
											  PRIMARY KEY(idlink_va)
											)
			",

			'log_profiling' => "CREATE TABLE {$prefixTables}log_profiling (
								  query TEXT NOT NULL,
								  count INTEGER NULL,
								  sum_time_ms FLOAT NULL,
								  CONSTRAINT unique_query UNIQUE (query)
								)
			",

			'option' => "CREATE TABLE {$prefixTables}option (
								option_name VARCHAR( 255 ) NOT NULL,
								option_value TEXT NOT NULL,
								autoload BOOLEAN NOT NULL DEFAULT false,
								PRIMARY KEY ( option_name )
								)
			",

			'session' => "CREATE TABLE {$prefixTables}session (
								id CHAR(32) NOT NULL,
								modified INTEGER,
								lifetime INTEGER,
								data TEXT,
								PRIMARY KEY ( id )
								)
			",

			'archive_numeric'	=> "CREATE TABLE {$prefixTables}archive_numeric (
									  idarchive INTEGER NOT NULL,
									  name VARCHAR(255) NOT NULL,
									  idsite INTEGER NULL,
									  date1 DATE NULL,
								  	  date2 DATE NULL,
									  period INT NULL,
								  	  ts_archived TIMESTAMP NULL,
								  	  value FLOAT NULL,
									  PRIMARY KEY(idarchive, name)
									)
			",

			'archive_blob'	=> "CREATE TABLE {$prefixTables}archive_blob (
									  idarchive INTEGER NOT NULL,
									  name VARCHAR(255) NOT NULL,
									  idsite INTEGER NULL,
									  date1 DATE NULL,
									  date2 DATE NULL,
									  period INT NULL,
									  ts_archived TIMESTAMP NULL,
									  value BYTEA NULL,
									  PRIMARY KEY(idarchive, name)
									)
			",

		);
		return $tables;
	}

    /**
     * Get the SQL to create Piwik tables
     *
     * @return array of strings containing SQL
     */
    public function getIndexesCreateSql()
    {
        $config = Zend_Registry::get('config');
        $prefixTables = $config->database->tables_prefix;
        $tables = array(

            'index_type_hash' => "CREATE INDEX index_type_hash ON {$prefixTables}log_action (type, hash)",

            'index_idsite_config_datetime' => "CREATE INDEX index_idsite_config_datetime ON {$prefixTables}log_visit (idsite, config_id, visit_last_action_time)",

            'index_idsite_datetime' => "CREATE INDEX index_idsite_datetime ON {$prefixTables}log_visit (idsite, visit_last_action_time)",

            'index_idsite_idvisitor' => "CREATE INDEX index_idsite_idvisitor ON {$prefixTables}log_visit (idsite, idvisitor)",

            'index_idsite_servertime' => "CREATE INDEX index_idsite_servertime ON {$prefixTables}log_conversion_item ( idsite, server_time )",

            'index_idsite_datetime' => "CREATE INDEX index_idsite_datetime ON {$prefixTables}log_conversion ( idsite, server_time )",

            'index_idvisit' => "CREATE INDEX index_idvisit ON {$prefixTables}log_link_visit_action (idvisit)",

            'index_idsite_servertime' => "CREATE INDEX index_idsite_servertime ON {$prefixTables}log_link_visit_action ( idsite, server_time )",

            'index_autoload' => "CREATE INDEX autoload ON {$prefixTables}option ( autoload )",

            // 'index_idsite_dates_period' => "CREATE INDEX index_idsite_dates_period ON {$prefixTables}archive_numeric (idsite, date1, date2, period, ts_archived)",

            // 'index_period_archived' => "CREATE INDEX index_period_archived ON {$prefixTables}archive_numeric (period, ts_archived)",

            // 'index_period_archived' => "CREATE INDEX index_period_archived ON {$prefixTables}archive_blob (period, ts_archived)"

        );
        return $tables;
    }

    /**
     * Get the SQL to create Piwik tables
     *
     * @return array of strings containing SQL
     */
    public function getFKeysCreateSql()
    {
        $config = Zend_Registry::get('config');
        $prefixTables = $config->database->tables_prefix;
        $fkeys = array(

            'access_site' => "ALTER TABLE {$prefixTables}access ADD FOREIGN KEY (idsite) REFERENCES {$prefixTables}site (idsite)",

            'goal_site' => "ALTER TABLE {$prefixTables}goal ADD FOREIGN KEY (idsite) REFERENCES {$prefixTables}site (idsite)",

            'conversion_visit' => "ALTER TABLE {$prefixTables}log_conversion ADD FOREIGN KEY (idvisit) REFERENCES {$prefixTables}log_visit (idvisit)",

            'conversion_site' => "ALTER TABLE {$prefixTables}log_conversion ADD FOREIGN KEY (idsite) REFERENCES {$prefixTables}site (idsite)",

            'conversion_goal' => "ALTER TABLE {$prefixTables}log_conversion ADD FOREIGN KEY (idsite,idgoal) REFERENCES {$prefixTables}goal (idsite,idgoal)",

            'site_url' => "ALTER TABLE {$prefixTables}site_url ADD FOREIGN KEY (idsite) REFERENCES {$prefixTables}site (idsite)",

        );
        return $fkeys;
    }

    public function getTriggersCreateSql()
    {
        $config = Zend_Registry::get('config');
        $prefixTables = $config->database->tables_prefix;
        $triggers = array(

            'option_merge' => "CREATE TRIGGER piwik_option_merge BEFORE INSERT ON {$prefixTables}option FOR EACH ROW EXECUTE PROCEDURE {$prefixTables}option_merge()",

            'session_merge' => "CREATE TRIGGER piwik_session_merge BEFORE INSERT ON {$prefixTables}session FOR EACH ROW EXECUTE PROCEDURE {$prefixTables}session_merge()",

            'profiling_merge' => "CREATE TRIGGER piwik_profiling_merge BEFORE INSERT ON {$prefixTables}log_profiling FOR EACH ROW EXECUTE PROCEDURE {$prefixTables}profiling_merge()",

        );

        return $triggers;

    }

    public function getFunctionsCreateSql()
    {
        $config = Zend_Registry::get('config');
        $prefixTables = $config->database->tables_prefix;
        $functions = array(

            /* FIXME a bit naive merge implementation (vulnerable to a race condition) */
            'option_merge' => "CREATE OR REPLACE FUNCTION {$prefixTables}option_merge() RETURNS trigger AS $$
                                DECLARE
                                    v_cnt INT := 0;
                                BEGIN

                                    SELECT 1 INTO v_cnt FROM {$prefixTables}option WHERE option_name = NEW.option_name;

                                    IF v_cnt = 1 THEN
                                        UPDATE {$prefixTables}option SET option_value = NEW.option_value WHERE option_name = NEW.option_name;
                                        RETURN NULL;
                                    END IF;

                                    RETURN NEW;

                                END;
                                $$ LANGUAGE plpgsql",

            /* FIXME a bit naive merge implementation (vulnerable to a race condition) */
            'session_merge' => "CREATE OR REPLACE FUNCTION {$prefixTables}session_merge() RETURNS trigger AS $$
                                DECLARE
                                    v_cnt INT := 0;
                                BEGIN

                                    SELECT 1 INTO v_cnt FROM {$prefixTables}session WHERE id = NEW.id;

                                    IF v_cnt = 1 THEN
                                        UPDATE {$prefixTables}session SET modified = NEW.modified, lifetime = NEW.lifetime, data = NEW.data WHERE id = NEW.id;
                                        RETURN NULL;
                                    END IF;

                                    RETURN NEW;

                                END;
                                $$ LANGUAGE plpgsql",

            /* FIXME a bit naive merge implementation (vulnerable to a race condition) */
            'profiling_merge' => "CREATE OR REPLACE FUNCTION {$prefixTables}profiling_merge() RETURNS trigger AS $$
                                DECLARE
                                    v_cnt INT := 0;
                                BEGIN

                                    SELECT 1 INTO v_cnt FROM {$prefixTables}log_profiling WHERE query = NEW.query;

                                    IF v_cnt = 1 THEN
                                        UPDATE {$prefixTables}log_profiling SET count = count + NEW.count, sum_time_ms = sum_time_ms + NEW.sum_time_ms WHERE query = NEW.query;
                                        RETURN NULL;
                                    END IF;

                                    RETURN NEW;

                                END;
                                $$ LANGUAGE plpgsql",

			'crc32' => "CREATE OR REPLACE FUNCTION CRC32(p_value TEXT) RETURNS int AS $$
						DECLARE
							v_hash BYTEA;
						BEGIN
							v_hash := DECODE(MD5(p_value),'hex');
							RETURN (get_byte(v_hash,0) << 24) + (get_byte(v_hash,1) << 16) + (get_byte(v_hash,2) << 8) + get_byte(v_hash,3);
						END;
						$$ LANGUAGE plpgsql",

			'hour' => "CREATE OR REPLACE FUNCTION hour(p_value TIME) RETURNS int AS $$
						BEGIN
							RETURN EXTRACT(HOUR FROM p_value);
						END;
						$$ LANGUAGE plpgsql",

			'hour2' => "CREATE OR REPLACE FUNCTION hour(p_value TIMESTAMP) RETURNS int AS $$
						BEGIN
							RETURN EXTRACT(HOUR FROM p_value);
						END;
						$$ LANGUAGE plpgsql"
        );

        return $functions;

    }


	/**
	 * Get the SQL to create a specific Piwik table
	 *
	 * @param string $tableName
	 * @return string SQL
	 */
	public function getTableCreateSql( $tableName )
	{
		$tables = Piwik::getTablesCreateSql();

		if(!isset($tables[$tableName]))
		{
			throw new Exception("The table '$tableName' SQL creation code couldn't be found.");
		}

		return $tables[$tableName];
	}

	/**
	 * Names of all the prefixed tables in piwik
	 * Doesn't use the DB
	 *
	 * @return array Table names
	 */
	public function getTablesNames()
	{
		$aTables = array_keys($this->getTablesCreateSql());
		$config = Zend_Registry::get('config');
		$prefixTables = $config->database->tables_prefix;
		$return = array();
		foreach($aTables as $table)
		{
			$return[] = $prefixTables.$table;
		}
		return $return;
	}

	private $tablesInstalled = null;

	/**
	 * Get list of tables installed
	 *
	 * @param bool $forceReload Invalidate cache
	 * @param string $idSite
	 * @return array Tables installed
	 */
	public function getTablesInstalled($forceReload = true)
	{
		if(is_null($this->tablesInstalled)
			|| $forceReload === true)
		{
			$db = Zend_Registry::get('db');
			$config = Zend_Registry::get('config');
			$prefixTables = $config->database->tables_prefix;

			// '_' matches any character; force it to be literal
			$prefixTables = str_replace('_', '\_', $prefixTables);

			$allTables = $db->fetchCol("SELECT tablename FROM pg_tables WHERE tablename LIKE '".$prefixTables."%'");

			// all the tables to be installed
			$allMyTables = $this->getTablesNames();

			// we get the intersection between all the tables in the DB and the tables to be installed
			$tablesInstalled = array_intersect($allMyTables, $allTables);

			// at this point we have only the piwik tables which is good
			// but we still miss the piwik generated tables (using the class Piwik_TablePartitioning)
			$allArchiveNumeric = $db->fetchCol("SELECT tablename FROM pg_tables WHERE tablename LIKE '".$prefixTables."archive_numeric%'");
			$allArchiveBlob = $db->fetchCol("SELECT tablename FROM pg_tables WHERE tablename LIKE '".$prefixTables."archive_blob%'");

			$allTablesReallyInstalled = array_merge($tablesInstalled, $allArchiveNumeric, $allArchiveBlob);

			$this->tablesInstalled = $allTablesReallyInstalled;
		}
		return 	$this->tablesInstalled;
	}

	/**
	 * Do tables exist?
	 *
	 * @return bool True if tables exist; false otherwise
	 */
	public function hasTables()
	{
		return count($this->getTablesInstalled()) != 0;
	}

	/**
	 * Create database
	 *
	 * @param string $dbName
	 */
	public function createDatabase( $dbName = null )
	{
		if(is_null($dbName))
		{
			$dbName = Zend_Registry::get('config')->database->dbname;
		}
		Piwik_Exec("CREATE DATABASE ".$dbName." ENCODING = utf8");
	}

	/**
	 * Drop database
	 */
	public function dropDatabase()
	{
		$dbName = Zend_Registry::get('config')->database->dbname;
		Piwik_Exec("DROP DATABASE IF EXISTS " . $dbName);

	}

	/**
	 * Create all tables
	 */
	public function createTables()
	{
		$db = Zend_Registry::get('db');
		$config = Zend_Registry::get('config');
		$prefixTables = $config->database->tables_prefix;

		$tablesAlreadyInstalled = $this->getTablesInstalled();
		$tablesToCreate = $this->getTablesCreateSql();
		unset($tablesToCreate['archive_blob']);
		unset($tablesToCreate['archive_numeric']);

		foreach($tablesToCreate as $tableName => $tableSql)
		{
			$tableName = $prefixTables . $tableName;
			if(!in_array($tableName, $tablesAlreadyInstalled))
			{
				$db->query( $tableSql );
			}
		}

        $indexesToCreate = $this->getIndexesCreateSql();
        foreach($indexesToCreate as $indexName => $indexSql)
        {
            $db->query( $indexSql );
        }

        $fkeysToCreate = $this->getFKeysCreateSql();
        foreach($fkeysToCreate as $fkeyName => $fkeySql)
        {
            $db->query( $fkeySql );
        }

        $functionsToCreate = $this->getFunctionsCreateSql();
        foreach($functionsToCreate as $funcName => $funcSql)
        {
            $db->query( $funcSql );
        }

        $triggersToCreate = $this->getTriggersCreateSql();
        foreach($triggersToCreate as $triggName => $triggSql)
        {
            $db->query( $triggSql );
        }

	}

	/**
	 * Creates an entry in the User table for the "anonymous" user.
	 */
	public function createAnonymousUser()
	{
		// The anonymous user is the user that is assigned by default
		// note that the token_auth value is anonymous, which is assigned by default as well in the Login plugin
		$db = Zend_Registry::get('db');
		$db->query("INSERT INTO ". Piwik_Common::prefixTable("user") . "
					VALUES ( 'anonymous', '', 'anonymous', 'anonymous@example.org', 'anonymous', '".Piwik_Date::factory('now')->getDatetime()."' );" );
	}

	/**
	 * Truncate all tables
	 */
	public function truncateAllTables()
	{
		$tablesAlreadyInstalled = $this->getTablesInstalled($forceReload = true);
		foreach($tablesAlreadyInstalled as $table)
		{
			Piwik_Query("TRUNCATE \"$table\"");
		}
	}

	/**
	 * Drop specific tables
	 *
	 * @param array $doNotDelete Names of tables to not delete
	 */
	public function dropTables( $doNotDelete = array() )
	{
		$tablesAlreadyInstalled = $this->getTablesInstalled();
		$db = Zend_Registry::get('db');

		$doNotDeletePattern = '/('.implode('|',$doNotDelete).')/';

		foreach($tablesAlreadyInstalled as $tableName)
		{
			if( count($doNotDelete) == 0
				|| (!in_array($tableName,$doNotDelete)
					&& !preg_match($doNotDeletePattern,$tableName)
					)
				)
			{
				$db->query("DROP TABLE \"$tableName\"");
			}
		}
	}
}
