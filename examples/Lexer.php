<?php
//require_once 'File/ChessPGN/Parser.php';
require_once dirname(__FILE__) . '/Parser.php';

class File_ChessPGN_Lexer
{
    const TAGOPEN = File_ChessPGN_Parser::TAGOPEN; // [
    const TAGNAME = File_ChessPGN_Parser::TAGNAME; // ]
    const TAGCLOSE = File_ChessPGN_Parser::TAGCLOSE; // ]
    const STRING = File_ChessPGN_Parser::STRING; // ]
    const NAG = File_ChessPGN_Parser::NAG; // $1 $2, etc.
    const GAMEEND = File_ChessPGN_Parser::GAMEEND; // * 1-0 0-1
    const PAWNMOVE = File_ChessPGN_Parser::PAWNMOVE; // e4, e8=Q, exd8=R
    const PIECEMOVE = File_ChessPGN_Parser::PIECEMOVE; // Nf2, Nab2, N3d5, Qxe4, Qexe4, Q3xe4
    const PLACEMENTMOVE = File_ChessPGN_Parser::PLACEMENTMOVE; // N@f2, P@d5
    const CHECK = File_ChessPGN_Parser::CHECK; // +
    const MATE = File_ChessPGN_Parser::MATE; // #
    const DIGIT = File_ChessPGN_Parser::DIGIT; // 0-9
    const MOVEANNOT = File_ChessPGN_Parser::MOVEANNOT; // ! ? !! ?? !? ?!
    const RAVOPEN = File_ChessPGN_Parser::RAVOPEN; // (
    const RAVCLOSE = File_ChessPGN_Parser::RAVCLOSE; // )
    const PERIOD = File_ChessPGN_Parser::PERIOD; // .
    const COMMENTOPEN = File_ChessPGN_Parser::COMMENTOPEN; // {
    const COMMENTCLOSE = File_ChessPGN_Parser::COMMENTCLOSE; // }
    const COMMENT = File_ChessPGN_Parser::COMMENT; // anything
    const CASTLE = File_ChessPGN_Parser::CASTLE; // O-O O-O-O

    private $input;
    private $N;
    public $token;
    public $value;
    public $line;
    private $_string;
    private $debug = 0;
    
    function __construct($data)
    {
        $this->input = str_replace("\r\n", "\n", $data);
        $this->N = 0;
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
              5 => 0,
              6 => 0,
              7 => 0,
              8 => 0,
              9 => 0,
              10 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\\[)|^(\\()|^((?:1-0|0-1|1\2-1\2))|^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state YYINITIAL');
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^(\\()|^((?:1-0|0-1|1\2-1\2))|^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        2 => "^((?:1-0|0-1|1\2-1\2))|^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        3 => "^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        4 => "^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        5 => "^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        6 => "^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        7 => "^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)",
        8 => "^(O-O-O|O-O)|^([ \n\t]+)",
        9 => "^([ \n\t]+)",
        10 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
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
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const YYINITIAL = 1;
    function yy_r1_1($yy_subpatterns)
    {

    if ($this->debug) echo 'new tag ['.$this->value."]\n";
    $this->token = self::TAGOPEN;
    $this->yybegin(self::INTAG);
    }
    function yy_r1_2($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo '->found rav ['.$this->value."]\n";
    $this->token = self::RAVOPEN;
    }
    function yy_r1_3($yy_subpatterns)
    {

    // end of game
    if ($this->debug) echo 'found game end ['.$this->value."]\n";
    $this->token = self::GAMEEND;
    }
    function yy_r1_4($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo '->found digit ['.$this->value."]\n";
    $this->token = self::DIGIT;
    }
    function yy_r1_5($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo '->found pawn move ['.$this->value."]\n";
    $this->token = self::PAWNMOVE;
    }
    function yy_r1_6($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo '->found piece move ['.$this->value."]\n";
    $this->token = self::PIECEMOVE;
    }
    function yy_r1_7($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo '->found placement move ['.$this->value."]\n";
    $this->token = self::PLACEMENTMOVE;
    }
    function yy_r1_8($yy_subpatterns)
    {

    if ($this->debug) echo 'new comment ['.$this->value."]\n";
    $this->yypushstate(self::INCOMMENT);
    $this->token = self::COMMENTOPEN;
    }
    function yy_r1_9($yy_subpatterns)
    {

    $this->yybegin(self::INMOVES);
    if ($this->debug) echo 'found castle move ['.$this->value."]\n";
    $this->token = self::CASTLE;
    }
    function yy_r1_10($yy_subpatterns)
    {

    // cycle to next token
    return false;
    }


    function yylex2()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 0,
              4 => 0,
              5 => 0,
              6 => 0,
              7 => 0,
              8 => 0,
              9 => 0,
              10 => 0,
              11 => 0,
              12 => 0,
              13 => 0,
              14 => 0,
              15 => 0,
              16 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\\()|^((?:1-0|0-1|1\2-1\2))|^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state INMOVES');
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^((?:1-0|0-1|1\2-1\2))|^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        2 => "^([0-9]{1,3})|^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        3 => "^(P?[a-h](?:[2-7]|[18]=(?:Q|R|B|N))|P?[a-h]x[a-h](?:[2-7]|[18]=(?:Q|R|B|N)))|^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        4 => "^((?:Q|K|R|B|N)(?:[a-h]|[1-8])?[a-h][1-8]|(?:Q|K|R|B|N)(?:[a-h]|[1-8])?x[a-h][1-8])|^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        5 => "^((?:P|K|Q|R|B|N)@[a-h][1-8])|^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        6 => "^(\\{)|^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        7 => "^(O-O-O|O-O)|^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        8 => "^([ \n\t]+)|^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        9 => "^(\\$[0-9]+)|^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        10 => "^(\\*)|^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        11 => "^(\\.)|^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        12 => "^(\\+)|^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        13 => "^(#)|^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        14 => "^(!|\\?|!!|\\?\\?|!\\?|\\?!)|^(\\))",
        15 => "^(\\))",
        16 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r2_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const INMOVES = 2;
    function yy_r2_1($yy_subpatterns)
    {

    if ($this->debug) echo '->found rav ['.$this->value."]\n";
    $this->token = self::RAVOPEN;
    }
    function yy_r2_2($yy_subpatterns)
    {

    // end of game
    $this->yybegin(self::YYINITIAL);
    if ($this->debug) echo 'found game end ['.$this->value."]\n";
    $this->token = self::GAMEEND;
    }
    function yy_r2_3($yy_subpatterns)
    {

    if ($this->debug) echo '->found digit ['.$this->value."]\n";
    $this->token = self::DIGIT;
    }
    function yy_r2_4($yy_subpatterns)
    {

    if ($this->debug) echo '->found pawn move ['.$this->value."]\n";
    $this->token = self::PAWNMOVE;
    }
    function yy_r2_5($yy_subpatterns)
    {

    if ($this->debug) echo '->found piece move ['.$this->value."]\n";
    $this->token = self::PIECEMOVE;
    }
    function yy_r2_6($yy_subpatterns)
    {

    if ($this->debug) echo '->found placement move ['.$this->value."]\n";
    $this->token = self::PLACEMENTMOVE;
    }
    function yy_r2_7($yy_subpatterns)
    {

    if ($this->debug) echo 'new comment ['.$this->value."]\n";
    $this->yypushstate(self::INCOMMENT);
    $this->token = self::COMMENTOPEN;
    }
    function yy_r2_8($yy_subpatterns)
    {

    if ($this->debug) echo 'found castle move ['.$this->value."]\n";
    $this->token = self::CASTLE;
    }
    function yy_r2_9($yy_subpatterns)
    {

    // cycle to next token
    return false;
    }
    function yy_r2_10($yy_subpatterns)
    {

    if ($this->debug) echo 'found numeric annotation glyph ['.$this->value."]\n";
    $this->token = self::NAG;
    }
    function yy_r2_11($yy_subpatterns)
    {

    // end of game
    $this->yybegin(self::YYINITIAL);
    if ($this->debug) echo 'found unfinished game indicator ['.$this->value."]\n";
    $this->token = self::GAMEEND;
    }
    function yy_r2_12($yy_subpatterns)
    {

    if ($this->debug) echo 'found period ['.$this->value."]\n";
    $this->token = self::PERIOD;
    }
    function yy_r2_13($yy_subpatterns)
    {

    if ($this->debug) echo 'found check ['.$this->value."]\n";
    $this->token = self::CHECK;
    }
    function yy_r2_14($yy_subpatterns)
    {

    if ($this->debug) echo 'found mate ['.$this->value."]\n";
    $this->token = self::MATE;
    }
    function yy_r2_15($yy_subpatterns)
    {

    if ($this->debug) echo 'found move annotation ['.$this->value."]\n";
    $this->token = self::MOVEANNOT;
    }
    function yy_r2_16($yy_subpatterns)
    {

    if ($this->debug) echo 'found recursive annotation variation close ['.$this->value."]\n";
    $this->token = self::RAVCLOSE;
    }


    function yylex3()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 0,
              4 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\")|^(\\])|^([^\\]\" ]+)|^( )/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state INTAG');
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^(\\])|^([^\\]\" ]+)|^( )",
        2 => "^([^\\]\" ]+)|^( )",
        3 => "^( )",
        4 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r3_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const INTAG = 3;
    function yy_r3_1($yy_subpatterns)
    {

    if ($this->debug) echo "starting string\n";
    $this->yybegin(self::INSTRING);
    $this->_string = '';
    $this->N++;
    return true; // cycle to next state
    }
    function yy_r3_2($yy_subpatterns)
    {

    if ($this->debug) echo "ending tag [".$this->value."]\n";
    $this->yybegin(self::YYINITIAL);
    $this->token = self::TAGCLOSE;
    }
    function yy_r3_3($yy_subpatterns)
    {

    if ($this->debug) echo 'tag contents ['.$this->value."]\n";
    $this->token = self::TAGNAME;
    }
    function yy_r3_4($yy_subpatterns)
    {

    // skip token
    return false;
    }


    function yylex4()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\\\\)|^(\")|^([^[\"\\\\]+)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state INSTRING');
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
                $r = $this->{'yy_r4_' . $this->token}($yysubmatches);
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^(\")|^([^[\"\\\\]+)",
        2 => "^([^[\"\\\\]+)",
        3 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r4_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const INSTRING = 4;
    function yy_r4_1($yy_subpatterns)
    {

    if ($this->debug) echo "string escape [\\]\n";
    $this->yybegin(self::INESCAPE);
    return true;
    }
    function yy_r4_2($yy_subpatterns)
    {

    if ($this->debug) echo "returning string [$this->_string]\n";
    $this->yybegin(self::INTAG);
    $this->value = $this->_string;
    $this->token = self::STRING;
    $this->N -= strlen($this->_string) - 1; // make sure the counter is right
    $this->_string = '';
    }
    function yy_r4_3($yy_subpatterns)
    {

    if ($this->debug) echo "added to string [".$this->value."]\n";
    $this->_string .= $this->value;
    return false;
    }


    function yylex5()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\"|\\\\)|^(.)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state INESCAPE');
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
                $r = $this->{'yy_r5_' . $this->token}($yysubmatches);
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^(.)",
        2 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r5_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const INESCAPE = 5;
    function yy_r5_1($yy_subpatterns)
    {

    if ($this->debug) echo "escape [".$this->value."]\n";
    $this->yybegin(self::INSTRING);
    $this->_string .= $this->value;
    break;
    }
    function yy_r5_2($yy_subpatterns)
    {

    if ($this->debug) echo "non-escape [".$this->value."]\n";
    $this->yybegin(self::INSTRING);
    $this->_string .= $this->value;
    return false;
    }


    function yylex6()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
            );
        if ($this->N >= strlen($this->input)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^(\\})|^([^}]+)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->input, $this->N), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr($this->input,
                        $this->N, 5) . '... state INCOMMENT');
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
                $r = $this->{'yy_r6_' . $this->token}($yysubmatches);
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
                    if ($this->N >= strlen($this->input)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {                    $yy_yymore_patterns = array(
        1 => "^([^}]+)",
        2 => "",
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match($yy_yymore_patterns[$this->token],
                              substr($this->input, $this->N), $yymatches)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r6_' . $this->token}() !== null);
                    // accept
                    $this->N += strlen($this->value);
                    $this->line += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->input[$this->N]);
            }
            break;
        } while (true);
    } // end function


    const INCOMMENT = 6;
    function yy_r6_1($yy_subpatterns)
    {

    if ($this->debug) echo 'close comment ['.$this->value."]\n";
    $this->yypopstate();
    $this->token = self::COMMENTCLOSE;
    }
    function yy_r6_2($yy_subpatterns)
    {

    if ($this->debug) echo 'comment contents ['.$this->value."]\n";
    $this->token = self::COMMENT;
    }

    /**
     * return something useful, when a parse error occurs.
     *
     * used to build error messages if the parser fails, and needs to know the line number..
     *
     * @return   string 
     * @access   public 
     */
    function parseError() 
    {
        return "Error at line {$this->yyline}";
        
    }
}