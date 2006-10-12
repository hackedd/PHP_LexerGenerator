<?php
class CodeGen_PECL_Tools_ProtoParser
{
    const PAR_OPEN = 1;
    const PAR_CLOSE = 2;
    const EQ = 3;
    const COMMA = 4;
    const SEMICOLON = 5;
    const VOID = 6;
    const BOOL = 7;
    const NUMVAL = 8;
    const STRVAL = 9;
}

class CodeGen_PECL_Tools_ProtoLexer
{
    private $data;
    public $token;
    public $value;
    private $line;
    private $count;

    function __construct($data)
    {
        $this->data  = $data;
        $this->count = 0;
        $this->line  = 1;
    }


    private $_yy_state = 1;
    private $_yy_stack = array();

    function yylex()
    {
        return $this->{'yylex' . $this->_yy_state}();
    }

    function yypushstate($state)
    {
        array_push($this->_yy_stack, $this->_yy_state);
        $this->_yy_state = $state;
    }

    function yypopstate()
    {
        $this->_yy_state = array_pop($this->_yy_stack);
    }

    function yybegin($state)
    {
        $this->_yy_state = $state;
    }




    function yylex1()
    {
    	do {
	    	$rules = array(
    			'/^[ \t\n]+/',
    			'/^\\(/',
    			'/^\\)/',
    			'/^=/',
    			'/^,/',
    			'/^;/',
    			'/^void/',
    			'/^bool(ean)?/',
    			'/^[0-9]+|0x[0-9a-fA-F]+/',
    			'/^[_a-zA-Z][_a-zA-Z0-9]+/',
	    	);
	    	$match = false;
	    	foreach ($rules as $index => $rule) {
	    		if (preg_match($rule, substr($this->data, $this->count), $yymatches)) {
	            	if ($match) {
	            	    if (strlen($yymatches[0]) > strlen($match[0][0])) {
	            	    	$match = array($yymatches, $index); // matches, token
	            	    }
	            	} else {
	            		$match = array($yymatches, $index);
	            	}
	            }
	    	}
	    	if (!$match) {
	            throw new Exception('Unexpected input at line' . $this->line .
	                ': ' . $this->data[$this->count]);
	    	}
	    	$this->token = $match[1];
	    	$this->value = $match[0][0];
	    	$yysubmatches = $match[0];
	    	array_shift($yysubmatches);
	    	if (!$yysubmatches) {
	    		$yysubmatches = array();
	    	}
	        $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
	        if ($r === null) {
	            $this->count += strlen($this->value);
	            $this->line += substr_count("\n", $this->value);
	            // accept this token
	            return true;
	        } elseif ($r === true) {
	            // we have changed state
	            // process this token in the new state
	            return $this->yylex();
	        } elseif ($r === false) {
	            $this->count += strlen($this->value);
	            $this->line += substr_count("\n", $this->value);
	            if ($this->count >= strlen($this->data)) {
	                return false; // end of input
	            }
	            // skip this token
	            continue;
	        } else {
	            $yy_yymore_patterns = array_slice($rules, $this->token, true);
	            // yymore is needed
	            do {
	                if (!isset($yy_yymore_patterns[$this->token])) {
	                    throw new Exception('cannot do yymore for the last token');
	                }
			    	$match = false;
	                foreach ($yy_yymore_patterns[$this->token] as $index => $rule) {
	                	if (preg_match($rule,
	                      	  substr($this->data, $this->count), $yymatches)) {
	                    	$yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
			            	if ($match) {
			            	    if (strlen($yymatches[0]) > strlen($match[0][0])) {
			            	    	$match = array($yymatches, $index); // matches, token
			            	    }
			            	} else {
			            		$match = array($yymatches, $index);
			            	}
			            }
			    	}
			    	if (!$match) {
			            throw new Exception('Unexpected input at line' . $this->line .
			                ': ' . $this->data[$this->count]);
			    	}
			    	$this->token = $match[1];
			    	$this->value = $match[0][0];
			    	$yysubmatches = $match[0];
			    	array_shift($yysubmatches);
			    	if (!$yysubmatches) {
			    		$yysubmatches = array();
			    	}
	                $this->line = substr_count("\n", $this->value);
	                $r = $this->{'yy_r1_' . $this->token}();
	            } while ($r !== null || !$r);
		        if ($r === true) {
		            // we have changed state
		            // process this token in the new state
		            return $this->yylex();
		        } else {
	                // accept
	                $this->count += strlen($this->value);
	                $this->line += substr_count("\n", $this->value);
	                return true;
		        }
	        }
        } while (true);

    } // end function

    function yy_r1_0($yy_subpatterns)
    {

	return false;
    }
    function yy_r1_1($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::PAR_OPEN;
    }
    function yy_r1_2($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::PAR_CLOSE;
    }
    function yy_r1_3($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::EQ;
    }
    function yy_r1_4($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::COMMA;
    }
    function yy_r1_5($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::SEMICOLON;
    }
    function yy_r1_6($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::VOID;
    }
    function yy_r1_7($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::BOOL;
    }
    function yy_r1_8($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::NUMVAL;
    }
    function yy_r1_9($yy_subpatterns)
    {

	$this->token = CodeGen_PECL_Tools_ProtoParser::STRVAL;
    }


}
$a = new CodeGen_PECL_Tools_ProtoLexer('booler boolboolean boolean bool');
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
