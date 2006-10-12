<?php
class TestLexer
{
    private $data;
    private $N;
    public $token;
    public $value;
    private $line;
    private $state = 1;

    function __construct($data)
    {
        $this->data = $data;
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
              1 => 8,
              10 => 2,
              13 => 1,
              15 => 0,
              16 => 1,
              18 => 0,
              19 => 0,
              20 => 0,
            );
        if ($this->N >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(((@import\\s+[\"'`]([\\w:?=@&\/#._;-]+)[\"'`];)|(:\\s*url\\s*\\([\\s\"'`]*([\\w:?=@&\/#._;-]+)([\\s\"'`]*\\))|<[^>]*\\s+(src|href|url)=[\\s\"'`]*([\\w:?=@&\/#._;-]+)[\\s\"'`]*[^>]*>)))|^(#(test)\\11{1,2}(hi)\\12)|^([a-zA-Z]_[a-zA-Z]+([0-9])+)|^([a-zA-Z]_[a-zA-Z]+)|^([0-9][0-9]\\.([0-9])+)|^([ \t\n]+)|^(\\$)|^(a\\$)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->data,
                        $this->N, 5) . '... state START');
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
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    if ($this->N >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^(#(test)\\11{1,2}(hi)\\12)|^([a-zA-Z]_[a-zA-Z]+([0-9])+)|^([a-zA-Z]_[a-zA-Z]+)|^([0-9][0-9]\\.([0-9])+)|^([ \t\n]+)|^(\\$)|^(a\\$)",
        10 => "^([a-zA-Z]_[a-zA-Z]+([0-9])+)|^([a-zA-Z]_[a-zA-Z]+)|^([0-9][0-9]\\.([0-9])+)|^([ \t\n]+)|^(\\$)|^(a\\$)",
        13 => "^([a-zA-Z]_[a-zA-Z]+)|^([0-9][0-9]\\.([0-9])+)|^([ \t\n]+)|^(\\$)|^(a\\$)",
        15 => "^([0-9][0-9]\\.([0-9])+)|^([ \t\n]+)|^(\\$)|^(a\\$)",
        16 => "^([ \t\n]+)|^(\\$)|^(a\\$)",
        18 => "^(\\$)|^(a\\$)",
        19 => "^(a\\$)",
        20 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->data, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    	$r = $this->{'yy_r1_' . $this->token}();
                    } while ($r !== null || !$r);
			        if ($r === true) {
			            // we have changed state
			            // process this token in the new state
			            return $this->yylex();
			        } else {
	                    // accept
	                    $this->N += strlen($this->value);
	                    $this->line += substr_count("\n", $this->value);
	                    return true;
			        }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->N]);
            }
            break;
        } while (true);

    } // end function


    const START = 1;
    function yy_r1_1($yy_subpatterns)
    {

    echo "complex\n";
    var_dump($this->value);
    echo "    complex subpatterns: \n";
    var_dump($yy_subpatterns);
    }
    function yy_r1_10($yy_subpatterns)
    {

    echo "weirdo\n";
    var_dump($this->value);
    echo "    weirdo subpatterns: \n";
    var_dump($yy_subpatterns);
    }
    function yy_r1_13($yy_subpatterns)
    {

    echo "rule 1\n";
    var_dump($this->value);
    echo "    rule 1 subpatterns: \n";
    var_dump($yy_subpatterns);
    $this->yypushstate(self::TWO);
    }
    function yy_r1_15($yy_subpatterns)
    {

    echo "rule 2\n";
    var_dump($this->value);
    echo "    rule 2 subpatterns: \n";
    var_dump($yy_subpatterns);
    $this->yybegin(self::THREE);
    }
    function yy_r1_16($yy_subpatterns)
    {

    echo "rule 3\n";
    var_dump($this->value);
    echo "    rule 3 subpatterns: \n";
    var_dump($yy_subpatterns);
    }
    function yy_r1_18($yy_subpatterns)
    {

    echo "whitespace\n";
    echo "    whitespace subpatterns: \n";
    var_dump($yy_subpatterns);
    return false; // skip this token (do not return it)
    }
    function yy_r1_19($yy_subpatterns)
    {

    echo "blah\n";
    echo "    blah subpatterns: \n";
    var_dump($yy_subpatterns);
    var_dump($this->value);
    }
    function yy_r1_20($yy_subpatterns)
    {

    echo "blahblah\n";
    echo "    blahblah subpatterns: \n";
    var_dump($yy_subpatterns);
    var_dump($this->value);
    }


    function yylex2()
    {
        $tokenMap = array (
              1 => 0,
            );
        if ($this->N >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^([a-zA-Z][a-zA-Z])/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->data,
                        $this->N, 5) . '... state TWO');
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
                $r = $this->{'yy_r2_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    if ($this->N >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->data, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    	$r = $this->{'yy_r2_' . $this->token}();
                    } while ($r !== null || !$r);
			        if ($r === true) {
			            // we have changed state
			            // process this token in the new state
			            return $this->yylex();
			        } else {
	                    // accept
	                    $this->N += strlen($this->value);
	                    $this->line += substr_count("\n", $this->value);
	                    return true;
			        }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->N]);
            }
            break;
        } while (true);

    } // end function


    const TWO = 2;
    function yy_r2_1($yy_subpatterns)
    {

    echo "alpha alpha (state TWO)\n";
    var_dump($this->value);
    $this->yypopstate();
    }


    function yylex3()
    {
        $tokenMap = array (
              1 => 0,
            );
        if ($this->N >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\\$[0-9])/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->data,
                        $this->N, 5) . '... state THREE');
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
                $r = $this->{'yy_r3_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    if ($this->N >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->data, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    	$r = $this->{'yy_r3_' . $this->token}();
                    } while ($r !== null || !$r);
			        if ($r === true) {
			            // we have changed state
			            // process this token in the new state
			            return $this->yylex();
			        } else {
	                    // accept
	                    $this->N += strlen($this->value);
	                    $this->line += substr_count("\n", $this->value);
	                    return true;
			        }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->N]);
            }
            break;
        } while (true);

    } // end function


    const THREE = 3;
    function yy_r3_1($yy_subpatterns)
    {

    echo "number (state THREE)\n";
    $this->yybegin(self::START);
    }

}

$a = new TestLexer('a_AB1yk $09.1 a$B_b$1 #testtesthihi <a href="http://www.example.com/s/style.css">');
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
$a->yylex();
var_dump('advance: ' . $a->value);
