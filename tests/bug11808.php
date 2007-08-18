<?php

define('UNDEFINED',0);
define('INTEGER',1);
define('FLOAT',2);
define('NUMBER',3);

class lexer
{
	private $counter;
	public $token;
	public $value;
	private $line;
	private $input;
	public $state = 1;
	
	private $prevTokenType = UNDEFINED;
	
	function __construct($input)
	{
		$this->input = $input;
		$this->N = 0;
		$this->line = 1;
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
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 0,
              4 => 1,
              6 => 0,
              7 => 0,
            );
        if ($this->counter >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^([ \t\n])|^([a-zA-Z]+)|^([\^*\/%+-])|^([+-]?([0-9]+\\.[0-9]+|\\.[0-9]+|[0-9]+\\.))|^([+-]?[0-9]+)|^(.)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->counter), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->counter, 5) . '... state START');
                }
                next($yymatches); // skip global match
                $this->token = key($yymatches); // token number
                if ($tokenMap[$this->token]) {
                    // extract sub-patterns for passing to lex function
                    $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                        $tokenMap[$this->token]);
                } else {
                    $yysubmatches = array();
                }
                $this->value = current($yymatches); // token value
                $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    if ($this->counter >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => array(0, "^([a-zA-Z]+)|^([\^*\/%+-])|^([+-]?([0-9]+\\.[0-9]+|\\.[0-9]+|[0-9]+\\.))|^([+-]?[0-9]+)|^(.)"),
        2 => array(0, "^([\^*\/%+-])|^([+-]?([0-9]+\\.[0-9]+|\\.[0-9]+|[0-9]+\\.))|^([+-]?[0-9]+)|^(.)"),
        3 => array(0, "^([+-]?([0-9]+\\.[0-9]+|\\.[0-9]+|[0-9]+\\.))|^([+-]?[0-9]+)|^(.)"),
        4 => array(1, "^([+-]?[0-9]+)|^(.)"),
        6 => array(1, "^(.)"),
        7 => array(1, ""),
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token][1])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        $yysubmatches = array();
                        if (preg_match('/' . $yy_yymore_patterns[$this->token][1] . '/',
                              substr($this->input, $this->counter), $yymatches)) {
                            $yysubmatches = $yymatches;
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token += key($yymatches) + $yy_yymore_patterns[$this->token][0]; // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count($this->value, "\n");
                            if ($tokenMap[$this->token]) {
                                // extract sub-patterns for passing to lex function
                                $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                                    $tokenMap[$this->token]);
                            } else {
                                $yysubmatches = array();
                            }
                        }
                    	$r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                    } while ($r !== null && !is_bool($r));
			        if ($r === true) {
			            // we have changed state
			            // process this token in the new state
			            return $this->yylex();
                    } elseif ($r === false) {
                        $this->counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        if ($this->counter >= strlen($this->input)) {
                            return false; // end of input
                        }
                        // skip this token
                        continue;
			        } else {
	                    // accept
	                    $this->counter += strlen($this->value);
	                    $this->line += substr_count($this->value, "\n");
	                    return true;
			        }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->counter]);
            }
            break;
        } while (true);

    } // end function


    const START = 1;
    function yy_r1_1($yy_subpatterns)
    {

	return false; //ignore this token
    }
    function yy_r1_2($yy_subpatterns)
    {

	echo 'word: '.$this->value.'<br>';
    }
    function yy_r1_3($yy_subpatterns)
    {

	if($this->prevTokenType != NUMBER)
	{
		return 'more'; //cycle to the next matching rule
	}
		echo 'operator: '.$this->value.'<br>';
    }
    function yy_r1_4($yy_subpatterns)
    {

	echo 'float: '.$this->value.'<br>'; // Important FLOAT must have higher precedence than INTEGER
	$this->prevTokenType = NUMBER;
    }
    function yy_r1_6($yy_subpatterns)
    {

	echo 'Integer :'.$this->value.'<br>';
	$this->prevTokenType = NUMBER;
    }
    function yy_r1_7($yy_subpatterns)
    {

	echo 'Unknown : '.$this->value.'<br>';

    }


}

$lex = new lexer('-2+1');

while($lex->yylex() != false)
{
}
?>