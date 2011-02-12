<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../tests/config_test.php";
}

class Test_Piwik_SegmentExpression extends UnitTestCase
{
    public function test_SegmentSql_simpleNoOperation()
    {
        $expressionToSql = array(
            // classic expressions
            'A' => " A ",
            'A,B' => " (A OR B )",
            'A;B' => " A AND B ",
            'A;B;C' => " A AND B AND C ",
            'A,B;C,D;E,F,G' => " (A OR B) AND (C OR D) AND (E OR F OR G )",
        
            // unescape the backslash 
            'A\,B\,C,D' => " (A,B,C OR D )",
            '\,A' => ' ,A ',
            // unescape only when it was escaping a known delimiter
            '\\\A' => ' \\\A ',
            // unescape at the end
            '\,\;\A\B,\,C,D\;E\,' => ' (,;\A\B OR ,C OR D;E, )',
        
            // only replace when a following expression is detected
            'A,' => ' A, ',
            'A;' => ' A; ',
            'A;B;' => ' A AND B; ',
            'A,B,' => ' (A OR B, )',
        );
        foreach($expressionToSql as $expression => $expectedSql)
        {
            $segment = new Piwik_SegmentExpression($expression);
            $expected = array('sql' => $expectedSql, 'bind' => array());
            $processed = $segment->getSql();
            $this->assertEqual($processed, $expected);
        }
    }
    
    public function test_SegmentSql_withOperations()
    {
        // Filter expression => SQL string + Bind values
        $expressionToSql = array(
            'A==B' => array('sql' => " A = ? ", 'bind' => array('B')),
            'ABCDEF====B===' => array('sql' => " ABCDEF = ? ", 'bind' => array('==B===')),
            'A===B;CDEF!=C!=' => array('sql' => " A = ? AND CDEF <> ? ", 'bind' => array('=B', 'C!=' )),
            'A==B,C==D' => array('sql' => " (A = ? OR C = ? )", 'bind' => array('B', 'D')),
            'A!=B;C==D' => array('sql' => " A <> ? AND C = ? ", 'bind' => array('B', 'D')),
            'A!=B;C==D,E!=Hello World!=' => array('sql' => " A <> ? AND (C = ? OR E <> ? )", 'bind' => array('B', 'D', 'Hello World!=')),
        );
        foreach($expressionToSql as $expression => $expectedSql)
        {
            $segment = new Piwik_SegmentExpression($expression);
            $segment->parseSubExpressions();
            $segment->parseSubExpressionsIntoSqlExpressions();
            $processed = $segment->getSql();
            $this->assertEqual($processed, $expectedSql, '<br/>'.var_export($processed, true) . "\n *DIFFERENT FROM*   ".var_export($expectedSql, true));
        }
    }
    
    public function test_bogusFilters_expectExceptionThrown()
    {
        $boguses = array(
            'A=B',
            'C!D',
            '',
            '      ',
            ',;,',
            ',',
            ',,',
            '===',
            '!='
        );
        foreach($boguses as $bogus) 
        {
            $segment = new Piwik_SegmentExpression($bogus);
            try {
                $segment->parseSubExpressions();
                $processed = $segment->getSql();
                $this->fail('expecting exception '.$bogus);
            } catch(Exception $e) {
                $this->pass();
            } 
        }
    }
}

