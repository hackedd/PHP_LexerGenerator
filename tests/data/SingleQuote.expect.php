<?php
class UnitTestSingleQuoteParser
{
    private $_counter;
    private $_data;
    private $line;
    private $column;
    private $state = 1;
    public $token;
    public $value;

    function __construct($data) {
        $this -> _data = $data;
        $this -> _counter = 0;
        $this -> line = 1;
    }

    function getState() {
        return $this -> state;
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
              4 => 0,
            );
        if ($this->_counter >= strlen($this->_data)) {
            return false; // end of input
        }
        $yy_global_pattern = '/\G([ \t\n])|\G([tT][eE][sS][tT])|\G([a-zA-Z]+)|\G(.)/';

        do {
            if (preg_match($yy_global_pattern,$this->_data, $yymatches, null, $this->_counter)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        ' an empty string.  Input "' . substr($this->_data,
                        $this->_counter, 5) . '... state START');
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
                    $this->_counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    $newline = strrpos(substr($this->_data, 0, $this->_counter), "\n");
                    if ($newline === FALSE) {
                        $this->column = $this->_counter;
                    } else {
                        $this->column = $this->_counter - $newline - 1;
                    }
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->_counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    $newline = strrpos(substr($this->_data, 0, $this->_counter), "\n");
                    if ($newline === FALSE) {
                        $this->column = $this->_counter;
                    } else {
                        $this->column = $this->_counter - $newline - 1;
                    }
                    if ($this->_counter >= strlen($this->_data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {
                    $yy_yymore_patterns = array(
        1 => array(0, "\G([tT][eE][sS][tT])|\G([a-zA-Z]+)|\G(.)"),
        2 => array(0, "\G([a-zA-Z]+)|\G(.)"),
        3 => array(0, "\G(.)"),
        4 => array(0, ""),
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token][1])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        $yysubmatches = array();
                        if (preg_match('/' . $yy_yymore_patterns[$this->token][1] . '/',
                              $this->_data, $yymatches, null, $this->_counter)) {
                            $yysubmatches = $yymatches;
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token += key($yymatches) + $yy_yymore_patterns[$this->token][0]; // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count($this->value, "\n");
                            $newline = strrpos(substr($this->_data, 0, $this->_counter), "\n");
                            if ($newline === FALSE) {
                                $this->column = $this->_counter;
                            } else {
                                $this->column = $this->_counter - $newline - 1;
                            }
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
                        $this->_counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        $newline = strrpos(substr($this->_data, 0, $this->_counter), "\n");
                        if ($newline === FALSE) {
                            $this->column = $this->_counter;
                        } else {
                            $this->column = $this->_counter - $newline - 1;
                        }
                        if ($this->_counter >= strlen($this->_data)) {
                            return false; // end of input
                        }
                        // skip this token
                        continue;
                    } else {
                        // accept
                        $this->_counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        $newline = strrpos(substr($this->_data, 0, $this->_counter), "\n");
                        if ($newline === FALSE) {
                            $this->column = $this->_counter;
                        } else {
                            $this->column = $this->_counter - $newline - 1;
                        }
                        return true;
                    }
                }
            } else {
                throw new Exception('Unexpected input "' . $this->_data[$this->_counter] . '" at line ' .
                    $this->line . ', column ' . ($this->column + 1));
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

    echo 'test: ' . $this -> value . '<br>';
    }
    function yy_r1_3($yy_subpatterns)
    {

    echo 'word: ' . $this -> value . '<br>';
    }
    function yy_r1_4($yy_subpatterns)
    {

    echo 'Unknown : ' . $this -> value . '<br>';
    }


}

?>
