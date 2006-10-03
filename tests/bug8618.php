<?php
class blah {

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
              3 => 1,
              5 => 10,
              16 => 0,
            );
        if ($this->N >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\000)|^(\x00)|^((hi)\\4)|^((hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)\\15)|^(\000-\007)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->data,
                        $this->N, 5) . '... state 1');
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
        1 => "^(\x00)|^((hi)\\4)|^((hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)\\15)|^(\000-\007)",
        2 => "^((hi)\\4)|^((hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)\\15)|^(\000-\007)",
        3 => "^((hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)(hi)\\15)|^(\000-\007)",
        5 => "^(\000-\007)",
        16 => "",
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
                    } while ($this->{'yy_r1_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->N]);
            }
            break;
        } while (true);
    } // end function

    function yy_r1_1($yy_subpatterns)
    {
$a = 1;    }
    function yy_r1_2($yy_subpatterns)
    {
$a = 2;    }
    function yy_r1_3($yy_subpatterns)
    {
$a = 2;    }
    function yy_r1_5($yy_subpatterns)
    {
$a = 2;    }
    function yy_r1_16($yy_subpatterns)
    {
$a = 2;    }

}
?>