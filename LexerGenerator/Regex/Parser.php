<?php
/* Driver template for the PHP_PHP_LexerGenerator_Regex_rGenerator parser generator. (PHP port of LEMON)
*/

/**
 * This can be used to store both the string representation of
 * a token, and any useful meta-data associated with the token.
 *
 * meta-data should be stored as an array
 */
class PHP_LexerGenerator_Regex_yyToken implements ArrayAccess
{
    public $string = '';
    public $metadata = array();

    function __construct($s, $m = array())
    {
        if ($s instanceof PHP_LexerGenerator_Regex_yyToken) {
            $this->string = $s->string;
            $this->metadata = $s->metadata;
        } else {
            $this->string = (string) $s;
            if ($m instanceof PHP_LexerGenerator_Regex_yyToken) {
                $this->metadata = $m->metadata;
            } elseif (is_array($m)) {
                $this->metadata = $m;
            }
        }
    }

    function __toString()
    {
        return $this->_string;
    }

    function offsetExists($offset)
    {
        return isset($this->metadata[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value)
    {
        if ($offset === null) {
            if (isset($value[0])) {
                $x = ($value instanceof PHP_LexerGenerator_Regex_yyToken) ?
                    $value->metadata : $value;
                $this->metadata = array_merge($this->metadata, $x);
                return;
            }
            $offset = count($this->metadata);
        }
        if ($value === null) {
            return;
        }
        if ($value instanceof PHP_LexerGenerator_Regex_yyToken) {
            if ($value->metadata) {
                $this->metadata[$offset] = $value->metadata;
            }
        } elseif ($value) {
            $this->metadata[$offset] = $value;
        }
    }

    function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }
}

/** The following structure represents a single element of the
 * parser's stack.  Information stored includes:
 *
 *   +  The state number for the parser at this level of the stack.
 *
 *   +  The value of the token stored at this level of the stack.
 *      (In other words, the "major" token.)
 *
 *   +  The semantic value stored at this level of the stack.  This is
 *      the information used by the action routines in the grammar.
 *      It is sometimes called the "minor" token.
 */
class PHP_LexerGenerator_Regex_yyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
                     ** is the value of the token  */
};

// code external to the class is included here
#line 2 "Parser.y"

require_once 'PHP/LexerGenerator/Exception.php';
#line 102 "Parser.php"

// declare_class is output here
#line 5 "Parser.y"
class PHP_LexerGenerator_Regex_Parser#line 107 "Parser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 21 "Parser.y"

    private $_lex;
    private $_subpatterns;
    private $_updatePattern;
    private $_patternIndex;
    public $result;
    function __construct($lex)
    {
        $this->result = new PHP_LexerGenerator_ParseryyToken('');
        $this->_lex = $lex;
        $this->_subpatterns = 0;
        $this->_patternIndex = 1;
    }

    function reset($patternIndex, $updatePattern = false)
    {
        $this->_updatePattern = $updatePattern;
        $this->_patternIndex = $patternIndex;
        $this->_subpatterns = 0;
        $this->result = new PHP_LexerGenerator_ParseryyToken('');
    }
#line 134 "Parser.php"

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const OPENPAREN                      =  1;
    const OPENASSERTION                  =  2;
    const BAR                            =  3;
    const MULTIPLIER                     =  4;
    const MATCHSTART                     =  5;
    const MATCHEND                       =  6;
    const OPENCHARCLASS                  =  7;
    const CLOSECHARCLASS                 =  8;
    const NEGATE                         =  9;
    const TEXT                           = 10;
    const CONTROLCHAR                    = 11;
    const ESCAPEDBACKSLASH               = 12;
    const HYPHEN                         = 13;
    const BACKREFERENCE                  = 14;
    const COULDBEBACKREF                 = 15;
    const FULLSTOP                       = 16;
    const INTERNALOPTIONS                = 17;
    const CLOSEPAREN                     = 18;
    const COLON                          = 19;
    const POSITIVELOOKAHEAD              = 20;
    const NEGATIVELOOKAHEAD              = 21;
    const POSITIVELOOKBEHIND             = 22;
    const NEGATIVELOOKBEHIND             = 23;
    const PATTERNNAME                    = 24;
    const ONCEONLY                       = 25;
    const COMMENT                        = 26;
    const RECUR                          = 27;
    const YY_NO_ACTION = 252;
    const YY_ACCEPT_ACTION = 251;
    const YY_ERROR_ACTION = 250;

/* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.  
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < self::YYNSTATE                              Shift N.  That is,
**                                                        push the lookahead
**                                                        token onto the stack
**                                                        and goto state N.
**
**   self::YYNSTATE <= N < self::YYNSTATE+self::YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == self::YYNSTATE+self::YYNRULE                    A syntax error has occurred.
**
**   N == self::YYNSTATE+self::YYNRULE+1                  The parser accepts its
**                                                        input. (and concludes parsing)
**
**   N == self::YYNSTATE+self::YYNRULE+2                  No such action.  Denotes unused
**                                                        slots in the yy_action[] table.
**
** The action table is constructed as a single large static array $yy_action.
** Given state S and lookahead X, the action is computed as
**
**      self::$yy_action[self::$yy_shift_ofst[S] + X ]
**
** If the index value self::$yy_shift_ofst[S]+X is out of range or if the value
** self::$yy_lookahead[self::$yy_shift_ofst[S]+X] is not equal to X or if
** self::$yy_shift_ofst[S] is equal to self::YY_SHIFT_USE_DFLT, it means that
** the action is not in the table and that self::$yy_default[S] should be used instead.  
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the static $yy_reduce_ofst array is used in place of
** the static $yy_shift_ofst array and self::YY_REDUCE_USE_DFLT is used in place of
** self::YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  self::$yy_action        A single table containing all actions.
**  self::$yy_lookahead     A table containing the lookahead for each entry in
**                          yy_action.  Used to detect hash collisions.
**  self::$yy_shift_ofst    For each state, the offset into self::$yy_action for
**                          shifting terminals.
**  self::$yy_reduce_ofst   For each state, the offset into self::$yy_action for
**                          shifting non-terminals after a reduce.
**  self::$yy_default       Default action for each state.
*/
    const YY_SZ_ACTTAB = 367;
static public $yy_action = array(
 /*     0 */   251,   50,   16,   21,  128,  129,  141,  140,  139,  142,
 /*    10 */   143,  145,  144,  138,   41,   16,   21,  128,  129,  141,
 /*    20 */   140,  139,  142,  143,  145,  144,  138,   43,   16,   21,
 /*    30 */   128,  129,  141,  140,  139,  142,  143,  145,  144,  138,
 /*    40 */    98,   16,   21,  128,  129,  141,  140,  139,  142,  143,
 /*    50 */   145,  144,  138,   32,   16,   21,  128,  129,  141,  140,
 /*    60 */   139,  142,  143,  145,  144,  138,   39,   16,   21,  128,
 /*    70 */   129,  141,  140,  139,  142,  143,  145,  144,  138,   31,
 /*    80 */    16,   21,  128,  129,  141,  140,  139,  142,  143,  145,
 /*    90 */   144,  138,   42,   16,   21,  128,  129,  141,  140,  139,
 /*   100 */   142,  143,  145,  144,  138,   29,   16,   21,  128,  129,
 /*   110 */   141,  140,  139,  142,  143,  145,  144,  138,   35,   16,
 /*   120 */    21,  128,  129,  141,  140,  139,  142,  143,  145,  144,
 /*   130 */   138,   40,   16,   21,  128,  129,  141,  140,  139,  142,
 /*   140 */   143,  145,  144,  138,   37,   16,   21,  128,  129,  141,
 /*   150 */   140,  139,  142,  143,  145,  144,  138,   38,   16,   21,
 /*   160 */   128,  129,  141,  140,  139,  142,  143,  145,  144,  138,
 /*   170 */    36,   16,   21,  128,  129,  141,  140,  139,  142,  143,
 /*   180 */   145,  144,  138,   15,   21,  128,  129,  141,  140,  139,
 /*   190 */   142,  143,  145,  144,  138,   54,   24,   23,   78,   75,
 /*   200 */    76,   82,   83,   89,   88,   87,   84,   86,  112,  115,
 /*   210 */   114,   34,   11,    1,    7,    8,    4,    2,    3,   13,
 /*   220 */    52,   60,   10,   17,  102,  108,   14,   55,   18,   96,
 /*   230 */    11,   47,   61,   48,  124,   46,   49,   51,   10,   17,
 /*   240 */   106,   11,   11,   97,   18,   44,  103,   47,   61,   48,
 /*   250 */    56,   46,   49,   51,   10,   17,   58,  132,  117,  111,
 /*   260 */    18,  119,   12,   47,   61,   48,  122,   46,   49,   51,
 /*   270 */    10,   17,    7,    8,    4,    2,   18,   28,   11,   47,
 /*   280 */    61,   48,  135,   46,   49,   51,  116,  137,   65,  127,
 /*   290 */    64,   63,   73,   79,  113,  120,   68,   70,   71,   11,
 /*   300 */    67,   66,   69,   62,   26,   64,   63,   73,   99,  113,
 /*   310 */   120,   19,   72,   74,   59,  126,   92,   80,   72,   74,
 /*   320 */    59,   11,   92,   80,  123,  121,  118,  131,   93,  100,
 /*   330 */   130,  136,  133,   11,   11,   11,   53,   11,   11,   11,
 /*   340 */     6,    9,  107,  125,  110,  146,   33,   81,   57,   91,
 /*   350 */   104,   85,  105,   94,   45,   27,   95,  101,   25,  109,
 /*   360 */   134,   30,    5,   77,   20,   22,   90,
    );
    static public $yy_lookahead = array(
 /*     0 */    29,   30,   31,   32,   33,   34,   35,   36,   37,   38,
 /*    10 */    39,   40,   41,   42,   30,   31,   32,   33,   34,   35,
 /*    20 */    36,   37,   38,   39,   40,   41,   42,   30,   31,   32,
 /*    30 */    33,   34,   35,   36,   37,   38,   39,   40,   41,   42,
 /*    40 */    30,   31,   32,   33,   34,   35,   36,   37,   38,   39,
 /*    50 */    40,   41,   42,   30,   31,   32,   33,   34,   35,   36,
 /*    60 */    37,   38,   39,   40,   41,   42,   30,   31,   32,   33,
 /*    70 */    34,   35,   36,   37,   38,   39,   40,   41,   42,   30,
 /*    80 */    31,   32,   33,   34,   35,   36,   37,   38,   39,   40,
 /*    90 */    41,   42,   30,   31,   32,   33,   34,   35,   36,   37,
 /*   100 */    38,   39,   40,   41,   42,   30,   31,   32,   33,   34,
 /*   110 */    35,   36,   37,   38,   39,   40,   41,   42,   30,   31,
 /*   120 */    32,   33,   34,   35,   36,   37,   38,   39,   40,   41,
 /*   130 */    42,   30,   31,   32,   33,   34,   35,   36,   37,   38,
 /*   140 */    39,   40,   41,   42,   30,   31,   32,   33,   34,   35,
 /*   150 */    36,   37,   38,   39,   40,   41,   42,   30,   31,   32,
 /*   160 */    33,   34,   35,   36,   37,   38,   39,   40,   41,   42,
 /*   170 */    30,   31,   32,   33,   34,   35,   36,   37,   38,   39,
 /*   180 */    40,   41,   42,   31,   32,   33,   34,   35,   36,   37,
 /*   190 */    38,   39,   40,   41,   42,    1,    2,   32,   33,   34,
 /*   200 */    35,   36,   37,   38,   39,   40,   41,   42,   10,   11,
 /*   210 */    12,   17,    3,   19,   20,   21,   22,   23,   24,   25,
 /*   220 */    26,   27,    1,    2,   10,   11,    5,   18,    7,   18,
 /*   230 */     3,   10,   11,   12,    4,   14,   15,   16,    1,    2,
 /*   240 */     4,    3,    3,    6,    7,   18,    4,   10,   11,   12,
 /*   250 */    10,   14,   15,   16,    1,    2,   18,   18,    4,    6,
 /*   260 */     7,   18,   19,   10,   11,   12,    4,   14,   15,   16,
 /*   270 */     1,    2,   20,   21,   22,   23,    7,   13,    3,   10,
 /*   280 */    11,   12,    4,   14,   15,   16,   10,   11,    8,    4,
 /*   290 */    10,   11,   12,   18,   14,   15,   10,   11,   12,    3,
 /*   300 */    14,   15,   16,    8,   13,   10,   11,   12,   18,   14,
 /*   310 */    15,    9,   10,   11,   12,    4,   14,   15,   10,   11,
 /*   320 */    12,    3,   14,   15,   10,   11,   12,   10,   11,   12,
 /*   330 */    10,   11,   12,    3,    3,    3,   18,    3,    3,    3,
 /*   340 */    36,   37,    4,    4,    4,    4,   13,    4,   18,   18,
 /*   350 */    18,    4,   18,   18,   18,   13,    4,    4,   13,    4,
 /*   360 */     4,   13,   18,    4,   43,   43,    4,
);
    const YY_SHIFT_USE_DFLT = -1;
    const YY_SHIFT_MAX = 74;
    static public $yy_shift_ofst = array(
 /*     0 */   221,  221,  221,  221,  221,  221,  221,  221,  221,  221,
 /*    10 */   221,  221,  221,  221,  269,  237,  253,  194,  302,  308,
 /*    20 */   280,  286,  295,  286,  252,  320,  317,  198,  314,  336,
 /*    30 */   276,  239,  335,  214,  243,  332,  330,  318,  334,  275,
 /*    40 */   209,  227,  331,  238,  362,  359,  230,  278,  262,  285,
 /*    50 */   296,  311,  211,  254,  240,  356,  344,  353,  347,  333,
 /*    60 */   290,  341,  340,  345,  342,  339,  343,  355,  352,  338,
 /*    70 */   236,  242,  264,  348,  291,
);
    const YY_REDUCE_USE_DFLT = -30;
    const YY_REDUCE_MAX = 19;
    static public $yy_reduce_ofst = array(
 /*     0 */   -29,  101,  127,  140,   88,   -3,  -16,   36,   62,   75,
 /*    10 */   114,   10,   49,   23,  152,  165,  165,  304,  322,  321,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 1 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 2 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 3 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 4 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 5 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 6 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 7 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 8 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 9 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 10 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 11 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 12 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 13 */ array(1, 2, 5, 7, 10, 11, 12, 14, 15, 16, ),
        /* 14 */ array(1, 2, 7, 10, 11, 12, 14, 15, 16, ),
        /* 15 */ array(1, 2, 6, 7, 10, 11, 12, 14, 15, 16, ),
        /* 16 */ array(1, 2, 6, 7, 10, 11, 12, 14, 15, 16, ),
        /* 17 */ array(1, 2, 17, 19, 20, 21, 22, 23, 24, 25, 26, 27, ),
        /* 18 */ array(9, 10, 11, 12, 14, 15, ),
        /* 19 */ array(10, 11, 12, 14, 15, ),
        /* 20 */ array(8, 10, 11, 12, 14, 15, ),
        /* 21 */ array(10, 11, 12, 14, 15, 16, ),
        /* 22 */ array(8, 10, 11, 12, 14, 15, ),
        /* 23 */ array(10, 11, 12, 14, 15, 16, ),
        /* 24 */ array(20, 21, 22, 23, ),
        /* 25 */ array(10, 11, 12, ),
        /* 26 */ array(10, 11, 12, ),
        /* 27 */ array(10, 11, 12, ),
        /* 28 */ array(10, 11, 12, ),
        /* 29 */ array(3, 18, ),
        /* 30 */ array(10, 11, ),
        /* 31 */ array(3, 18, ),
        /* 32 */ array(3, 18, ),
        /* 33 */ array(10, 11, ),
        /* 34 */ array(18, 19, ),
        /* 35 */ array(3, 18, ),
        /* 36 */ array(3, 18, ),
        /* 37 */ array(3, 18, ),
        /* 38 */ array(3, 18, ),
        /* 39 */ array(3, 18, ),
        /* 40 */ array(3, 18, ),
        /* 41 */ array(3, 18, ),
        /* 42 */ array(3, 18, ),
        /* 43 */ array(3, 18, ),
        /* 44 */ array(4, ),
        /* 45 */ array(4, ),
        /* 46 */ array(4, ),
        /* 47 */ array(4, ),
        /* 48 */ array(4, ),
        /* 49 */ array(4, ),
        /* 50 */ array(3, ),
        /* 51 */ array(4, ),
        /* 52 */ array(18, ),
        /* 53 */ array(4, ),
        /* 54 */ array(10, ),
        /* 55 */ array(4, ),
        /* 56 */ array(18, ),
        /* 57 */ array(4, ),
        /* 58 */ array(4, ),
        /* 59 */ array(13, ),
        /* 60 */ array(18, ),
        /* 61 */ array(4, ),
        /* 62 */ array(4, ),
        /* 63 */ array(13, ),
        /* 64 */ array(13, ),
        /* 65 */ array(4, ),
        /* 66 */ array(4, ),
        /* 67 */ array(4, ),
        /* 68 */ array(4, ),
        /* 69 */ array(4, ),
        /* 70 */ array(4, ),
        /* 71 */ array(4, ),
        /* 72 */ array(13, ),
        /* 73 */ array(13, ),
        /* 74 */ array(13, ),
        /* 75 */ array(),
        /* 76 */ array(),
        /* 77 */ array(),
        /* 78 */ array(),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(),
        /* 83 */ array(),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(),
        /* 87 */ array(),
        /* 88 */ array(),
        /* 89 */ array(),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(),
        /* 99 */ array(),
        /* 100 */ array(),
        /* 101 */ array(),
        /* 102 */ array(),
        /* 103 */ array(),
        /* 104 */ array(),
        /* 105 */ array(),
        /* 106 */ array(),
        /* 107 */ array(),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(),
        /* 112 */ array(),
        /* 113 */ array(),
        /* 114 */ array(),
        /* 115 */ array(),
        /* 116 */ array(),
        /* 117 */ array(),
        /* 118 */ array(),
        /* 119 */ array(),
        /* 120 */ array(),
        /* 121 */ array(),
        /* 122 */ array(),
        /* 123 */ array(),
        /* 124 */ array(),
        /* 125 */ array(),
        /* 126 */ array(),
        /* 127 */ array(),
        /* 128 */ array(),
        /* 129 */ array(),
        /* 130 */ array(),
        /* 131 */ array(),
        /* 132 */ array(),
        /* 133 */ array(),
        /* 134 */ array(),
        /* 135 */ array(),
        /* 136 */ array(),
        /* 137 */ array(),
        /* 138 */ array(),
        /* 139 */ array(),
        /* 140 */ array(),
        /* 141 */ array(),
        /* 142 */ array(),
        /* 143 */ array(),
        /* 144 */ array(),
        /* 145 */ array(),
        /* 146 */ array(),
);
    static public $yy_default = array(
 /*     0 */   250,  250,  250,  250,  250,  250,  250,  250,  250,  250,
 /*    10 */   250,  250,  250,  250,  250,  149,  151,  250,  250,  250,
 /*    20 */   250,  153,  250,  164,  250,  250,  250,  250,  250,  250,
 /*    30 */   250,  250,  250,  250,  250,  250,  250,  250,  250,  250,
 /*    40 */   250,  250,  250,  250,  235,  237,  213,  205,  211,  215,
 /*    50 */   147,  207,  250,  245,  250,  231,  250,  243,  234,  181,
 /*    60 */   250,  209,  175,  192,  194,  176,  227,  225,  217,  219,
 /*    70 */   221,  223,  179,  193,  180,  166,  167,  238,  165,  239,
 /*    80 */   191,  228,  168,  169,  173,  233,  174,  172,  171,  170,
 /*    90 */   236,  240,  190,  184,  247,  218,  248,  148,  152,  249,
 /*   100 */   183,  244,  185,  224,  241,  242,  222,  220,  182,  226,
 /*   110 */   177,  150,  202,  203,  201,  200,  199,  246,  189,  229,
 /*   120 */   204,  188,  212,  187,  214,  178,  208,  216,  154,  155,
 /*   130 */   198,  186,  230,  197,  232,  206,  195,  196,  163,  158,
 /*   140 */   157,  156,  159,  160,  162,  161,  210,
);
/* The next thing included is series of defines which control
** various aspects of the generated parser.
**    self::YYNOCODE      is a number which corresponds
**                        to no legal terminal or nonterminal number.  This
**                        number is used to fill in empty slots of the hash 
**                        table.
**    self::YYFALLBACK    If defined, this indicates that one or more tokens
**                        have fall-back values which should be used if the
**                        original value of the token will not parse.
**    self::YYSTACKDEPTH  is the maximum depth of the parser's stack.
**    self::YYNSTATE      the combined number of states.
**    self::YYNRULE       the number of rules in the grammar
**    self::YYERRORSYMBOL is the code number of the error symbol.  If not
**                        defined, then do no error processing.
*/
    const YYNOCODE = 45;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 147;
    const YYNRULE = 103;
    const YYERRORSYMBOL = 28;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 0;
    /** The next table maps tokens into fallback tokens.  If a construct
     * like the following:
     * 
     *      %fallback ID X Y Z.
     *
     * appears in the grammer, then ID becomes a fallback token for X, Y,
     * and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
     * but it does not parse, the type of the token is changed to ID and
     * the parse is retried before an error is thrown.
     */
    static public $yyFallback = array(
    );
    /**
     * Turn parser tracing on by giving a stream to which to write the trace
     * and a prompt to preface each trace message.  Tracing is turned off
     * by making either argument NULL 
     *
     * Inputs:
     * 
     * - A stream resource to which trace output should be written.
     *   If NULL, then tracing is turned off.
     * - A prefix string written at the beginning of every
     *   line of trace output.  If NULL, then tracing is
     *   turned off.
     *
     * Outputs:
     * 
     * - None.
     * @param resource
     * @param string
     */
    static function Trace($TraceFILE, $zTracePrompt)
    {
        if (!$TraceFILE) {
            $zTracePrompt = 0;
        } elseif (!$zTracePrompt) {
            $TraceFILE = 0;
        }
        self::$yyTraceFILE = $TraceFILE;
        self::$yyTracePrompt = $zTracePrompt;
    }

    /**
     * Output debug information to output (php://output stream)
     */
    static function PrintTrace()
    {
        self::$yyTraceFILE = fopen('php://output', 'w');
        self::$yyTracePrompt = '';
    }

    /**
     * @var resource|0
     */
    static public $yyTraceFILE;
    /**
     * String to prepend to debug output
     * @var string|0
     */
    static public $yyTracePrompt;
    /**
     * @var int
     */
    public $yyidx;                    /* Index of top element in stack */
    /**
     * @var int
     */
    public $yyerrcnt;                 /* Shifts left before out of the error */
    /**
     * @var array
     */
    public $yystack = array();  /* The parser's stack */

    /**
     * For tracing shifts, the names of all terminals and nonterminals
     * are required.  The following table supplies these names
     * @var array
     */
    static public $yyTokenName = array( 
  '$',             'OPENPAREN',     'OPENASSERTION',  'BAR',         
  'MULTIPLIER',    'MATCHSTART',    'MATCHEND',      'OPENCHARCLASS',
  'CLOSECHARCLASS',  'NEGATE',        'TEXT',          'CONTROLCHAR', 
  'ESCAPEDBACKSLASH',  'HYPHEN',        'BACKREFERENCE',  'COULDBEBACKREF',
  'FULLSTOP',      'INTERNALOPTIONS',  'CLOSEPAREN',    'COLON',       
  'POSITIVELOOKAHEAD',  'NEGATIVELOOKAHEAD',  'POSITIVELOOKBEHIND',  'NEGATIVELOOKBEHIND',
  'PATTERNNAME',   'ONCEONLY',      'COMMENT',       'RECUR',       
  'error',         'start',         'pattern',       'basic_pattern',
  'basic_text',    'character_class',  'assertion',     'grouping',    
  'lookahead',     'lookbehind',    'subpattern',    'onceonly',    
  'comment',       'recur',         'conditional',   'character_class_contents',
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "start ::= pattern",
 /*   1 */ "pattern ::= MATCHSTART basic_pattern MATCHEND",
 /*   2 */ "pattern ::= MATCHSTART basic_pattern",
 /*   3 */ "pattern ::= basic_pattern MATCHEND",
 /*   4 */ "pattern ::= basic_pattern",
 /*   5 */ "pattern ::= pattern BAR pattern",
 /*   6 */ "basic_pattern ::= basic_text",
 /*   7 */ "basic_pattern ::= character_class",
 /*   8 */ "basic_pattern ::= assertion",
 /*   9 */ "basic_pattern ::= grouping",
 /*  10 */ "basic_pattern ::= lookahead",
 /*  11 */ "basic_pattern ::= lookbehind",
 /*  12 */ "basic_pattern ::= subpattern",
 /*  13 */ "basic_pattern ::= onceonly",
 /*  14 */ "basic_pattern ::= comment",
 /*  15 */ "basic_pattern ::= recur",
 /*  16 */ "basic_pattern ::= conditional",
 /*  17 */ "basic_pattern ::= basic_pattern basic_text",
 /*  18 */ "basic_pattern ::= basic_pattern character_class",
 /*  19 */ "basic_pattern ::= basic_pattern assertion",
 /*  20 */ "basic_pattern ::= basic_pattern grouping",
 /*  21 */ "basic_pattern ::= basic_pattern lookahead",
 /*  22 */ "basic_pattern ::= basic_pattern lookbehind",
 /*  23 */ "basic_pattern ::= basic_pattern subpattern",
 /*  24 */ "basic_pattern ::= basic_pattern onceonly",
 /*  25 */ "basic_pattern ::= basic_pattern comment",
 /*  26 */ "basic_pattern ::= basic_pattern recur",
 /*  27 */ "basic_pattern ::= basic_pattern conditional",
 /*  28 */ "character_class ::= OPENCHARCLASS character_class_contents CLOSECHARCLASS",
 /*  29 */ "character_class ::= OPENCHARCLASS NEGATE character_class_contents CLOSECHARCLASS",
 /*  30 */ "character_class ::= OPENCHARCLASS character_class_contents CLOSECHARCLASS MULTIPLIER",
 /*  31 */ "character_class ::= OPENCHARCLASS NEGATE character_class_contents CLOSECHARCLASS MULTIPLIER",
 /*  32 */ "character_class_contents ::= TEXT",
 /*  33 */ "character_class_contents ::= CONTROLCHAR",
 /*  34 */ "character_class_contents ::= ESCAPEDBACKSLASH",
 /*  35 */ "character_class_contents ::= ESCAPEDBACKSLASH HYPHEN CONTROLCHAR",
 /*  36 */ "character_class_contents ::= CONTROLCHAR HYPHEN ESCAPEDBACKSLASH",
 /*  37 */ "character_class_contents ::= CONTROLCHAR HYPHEN CONTROLCHAR",
 /*  38 */ "character_class_contents ::= ESCAPEDBACKSLASH HYPHEN TEXT",
 /*  39 */ "character_class_contents ::= CONTROLCHAR HYPHEN TEXT",
 /*  40 */ "character_class_contents ::= TEXT HYPHEN TEXT",
 /*  41 */ "character_class_contents ::= TEXT HYPHEN CONTROLCHAR",
 /*  42 */ "character_class_contents ::= TEXT HYPHEN ESCAPEDBACKSLASH",
 /*  43 */ "character_class_contents ::= BACKREFERENCE",
 /*  44 */ "character_class_contents ::= COULDBEBACKREF",
 /*  45 */ "character_class_contents ::= character_class_contents CONTROLCHAR",
 /*  46 */ "character_class_contents ::= character_class_contents ESCAPEDBACKSLASH",
 /*  47 */ "character_class_contents ::= character_class_contents TEXT",
 /*  48 */ "character_class_contents ::= character_class_contents CONTROLCHAR HYPHEN CONTROLCHAR",
 /*  49 */ "character_class_contents ::= character_class_contents ESCAPEDBACKSLASH HYPHEN CONTROLCHAR",
 /*  50 */ "character_class_contents ::= character_class_contents CONTROLCHAR HYPHEN ESCAPEDBACKSLASH",
 /*  51 */ "character_class_contents ::= character_class_contents CONTROLCHAR HYPHEN TEXT",
 /*  52 */ "character_class_contents ::= character_class_contents ESCAPEDBACKSLASH HYPHEN TEXT",
 /*  53 */ "character_class_contents ::= character_class_contents TEXT HYPHEN CONTROLCHAR",
 /*  54 */ "character_class_contents ::= character_class_contents TEXT HYPHEN ESCAPEDBACKSLASH",
 /*  55 */ "character_class_contents ::= character_class_contents TEXT HYPHEN TEXT",
 /*  56 */ "character_class_contents ::= character_class_contents BACKREFERENCE",
 /*  57 */ "character_class_contents ::= character_class_contents COULDBEBACKREF",
 /*  58 */ "basic_text ::= TEXT",
 /*  59 */ "basic_text ::= TEXT MULTIPLIER",
 /*  60 */ "basic_text ::= FULLSTOP",
 /*  61 */ "basic_text ::= FULLSTOP MULTIPLIER",
 /*  62 */ "basic_text ::= CONTROLCHAR",
 /*  63 */ "basic_text ::= CONTROLCHAR MULTIPLIER",
 /*  64 */ "basic_text ::= ESCAPEDBACKSLASH",
 /*  65 */ "basic_text ::= ESCAPEDBACKSLASH MULTIPLIER",
 /*  66 */ "basic_text ::= BACKREFERENCE",
 /*  67 */ "basic_text ::= BACKREFERENCE MULTIPLIER",
 /*  68 */ "basic_text ::= COULDBEBACKREF",
 /*  69 */ "basic_text ::= COULDBEBACKREF MULTIPLIER",
 /*  70 */ "basic_text ::= basic_text TEXT",
 /*  71 */ "basic_text ::= basic_text TEXT MULTIPLIER",
 /*  72 */ "basic_text ::= basic_text FULLSTOP",
 /*  73 */ "basic_text ::= basic_text FULLSTOP MULTIPLIER",
 /*  74 */ "basic_text ::= basic_text CONTROLCHAR",
 /*  75 */ "basic_text ::= basic_text CONTROLCHAR MULTIPLIER",
 /*  76 */ "basic_text ::= basic_text ESCAPEDBACKSLASH",
 /*  77 */ "basic_text ::= basic_text ESCAPEDBACKSLASH MULTIPLIER",
 /*  78 */ "basic_text ::= basic_text BACKREFERENCE",
 /*  79 */ "basic_text ::= basic_text BACKREFERENCE MULTIPLIER",
 /*  80 */ "basic_text ::= basic_text COULDBEBACKREF",
 /*  81 */ "basic_text ::= basic_text COULDBEBACKREF MULTIPLIER",
 /*  82 */ "assertion ::= OPENASSERTION INTERNALOPTIONS CLOSEPAREN",
 /*  83 */ "assertion ::= OPENASSERTION INTERNALOPTIONS COLON pattern CLOSEPAREN",
 /*  84 */ "grouping ::= OPENASSERTION COLON pattern CLOSEPAREN",
 /*  85 */ "grouping ::= OPENASSERTION COLON pattern CLOSEPAREN MULTIPLIER",
 /*  86 */ "conditional ::= OPENASSERTION OPENPAREN TEXT CLOSEPAREN pattern CLOSEPAREN MULTIPLIER",
 /*  87 */ "conditional ::= OPENASSERTION OPENPAREN TEXT CLOSEPAREN pattern CLOSEPAREN",
 /*  88 */ "conditional ::= OPENASSERTION lookahead pattern CLOSEPAREN",
 /*  89 */ "conditional ::= OPENASSERTION lookahead pattern CLOSEPAREN MULTIPLIER",
 /*  90 */ "conditional ::= OPENASSERTION lookbehind pattern CLOSEPAREN",
 /*  91 */ "conditional ::= OPENASSERTION lookbehind pattern CLOSEPAREN MULTIPLIER",
 /*  92 */ "lookahead ::= OPENASSERTION POSITIVELOOKAHEAD pattern CLOSEPAREN",
 /*  93 */ "lookahead ::= OPENASSERTION NEGATIVELOOKAHEAD pattern CLOSEPAREN",
 /*  94 */ "lookbehind ::= OPENASSERTION POSITIVELOOKBEHIND pattern CLOSEPAREN",
 /*  95 */ "lookbehind ::= OPENASSERTION NEGATIVELOOKBEHIND pattern CLOSEPAREN",
 /*  96 */ "subpattern ::= OPENASSERTION PATTERNNAME pattern CLOSEPAREN",
 /*  97 */ "subpattern ::= OPENASSERTION PATTERNNAME pattern CLOSEPAREN MULTIPLIER",
 /*  98 */ "subpattern ::= OPENPAREN pattern CLOSEPAREN",
 /*  99 */ "subpattern ::= OPENPAREN pattern CLOSEPAREN MULTIPLIER",
 /* 100 */ "onceonly ::= OPENASSERTION ONCEONLY pattern CLOSEPAREN",
 /* 101 */ "comment ::= OPENASSERTION COMMENT CLOSEPAREN",
 /* 102 */ "recur ::= OPENASSERTION RECUR CLOSEPAREN",
    );

    /**
     * This function returns the symbolic name associated with a token
     * value.
     * @param int
     * @return string
     */
    function tokenName($tokenType)
    {
        if ($tokenType === 0) {
            return 'End of Input';
        }
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
        } else {
            return "Unknown";
        }
    }

    /**
     * The following function deletes the value associated with a
     * symbol.  The symbol can be either a terminal or nonterminal.
     * @param int the symbol code
     * @param mixed the symbol's value
     */
    static function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
        /* Here is inserted the actions which take place when a
        ** terminal or non-terminal is destroyed.  This can happen
        ** when the symbol is popped from the stack during a
        ** reduce or during error processing or when a parser is 
        ** being destroyed before it is finished parsing.
        **
        ** Note: during a reduce, the only symbols destroyed are those
        ** which appear on the RHS of the rule, but which are not used
        ** inside the C code.
        */
            default:  break;   /* If no destructor action specified: do nothing */
        }
    }

    /**
     * Pop the parser's stack once.
     *
     * If there is a destructor routine associated with the token which
     * is popped from the stack, then call it.
     *
     * Return the major token number for the symbol popped.
     * @param PHP_LexerGenerator_Regex_yyParser
     * @return int
     */
    function yy_pop_parser_stack()
    {
        if (!count($this->yystack)) {
            return;
        }
        $yytos = array_pop($this->yystack);
        if (self::$yyTraceFILE && $this->yyidx >= 0) {
            fwrite(self::$yyTraceFILE,
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
                    "\n");
        }
        $yymajor = $yytos->major;
        self::yy_destructor($yymajor, $yytos->minor);
        $this->yyidx--;
        return $yymajor;
    }

    /**
     * Deallocate and destroy a parser.  Destructors are all called for
     * all stack elements before shutting the parser down.
     */
    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        if (is_resource(self::$yyTraceFILE)) {
            fclose(self::$yyTraceFILE);
        }
    }

    /**
     * Based on the current state and parser stack, get a list of all
     * possible lookahead tokens
     * @param int
     * @return array
     */
    function yy_get_expected_tokens($token)
    {
        $state = $this->yystack[$this->yyidx]->stateno;
        $expected = self::$yyExpectedTokens[$state];
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return $expected;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return array_unique($expected);
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate])) {
                        $expected += self::$yyExpectedTokens[$nextstate];
                            if (in_array($token,
                                  self::$yyExpectedTokens[$nextstate], true)) {
                            $this->yyidx = $yyidx;
                            $this->yystack = $stack;
                            return array_unique($expected);
                        }
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new PHP_LexerGenerator_Regex_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return array_unique($expected);
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return $expected;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return array_unique($expected);
    }

    /**
     * Based on the parser state and current parser stack, determine whether
     * the lookahead token is possible.
     * 
     * The parser will convert the token value to an error token if not.  This
     * catches some unusual edge cases where the parser would fail.
     * @param int
     * @return bool
     */
    function yy_is_expected_token($token)
    {
        if ($token === 0) {
            return true; // 0 is not part of this
        }
        $state = $this->yystack[$this->yyidx]->stateno;
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return true;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return true;
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate]) &&
                          in_array($token, self::$yyExpectedTokens[$nextstate], true)) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        return true;
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new PHP_LexerGenerator_Regex_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        if (!$token) {
                            // end of input: this is valid
                            return true;
                        }
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return false;
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return true;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return true;
    }

    /**
     * Find the appropriate action for a parser given the terminal
     * look-ahead token iLookAhead.
     *
     * If the look-ahead token is YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return YY_NO_ACTION.
     * @param int The look-ahead token
     */
    function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;
     
        /* if ($this->yyidx < 0) return self::YY_NO_ACTION;  */
        if (!isset(self::$yy_shift_ofst[$stateno])) {
            // no shift actions
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_shift_ofst[$stateno];
        if ($i === self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            if (count(self::$yyFallback) && $iLookAhead < count(self::$yyFallback)
                   && ($iFallback = self::$yyFallback[$iLookAhead]) != 0) {
                if (self::$yyTraceFILE) {
                    fwrite(self::$yyTraceFILE, self::$yyTracePrompt . "FALLBACK " .
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
                }
                return $this->yy_find_shift_action($iFallback);
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Find the appropriate action for a parser given the non-terminal
     * look-ahead token $iLookAhead.
     *
     * If the look-ahead token is self::YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return self::YY_NO_ACTION.
     * @param int Current state number
     * @param int The look-ahead token
     */
    function yy_find_reduce_action($stateno, $iLookAhead)
    {
        /* $stateno = $this->yystack[$this->yyidx]->stateno; */

        if (!isset(self::$yy_reduce_ofst[$stateno])) {
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_reduce_ofst[$stateno];
        if ($i == self::YY_REDUCE_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Perform a shift action.
     * @param int The new state to shift in
     * @param int The major token to shift in
     * @param mixed the minor token to shift in
     */
    function yy_shift($yyNewState, $yyMajor, $yypMinor)
    {
        $this->yyidx++;
        if ($this->yyidx >= self::YYSTACKDEPTH) {
            $this->yyidx--;
            if (self::$yyTraceFILE) {
                fprintf(self::$yyTraceFILE, "%sStack Overflow!\n", self::$yyTracePrompt);
            }
            while ($this->yyidx >= 0) {
                $this->yy_pop_parser_stack();
            }
            /* Here code is inserted which will execute if the parser
            ** stack ever overflows */
            return;
        }
        $yytos = new PHP_LexerGenerator_Regex_yyStackEntry;
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        array_push($this->yystack, $yytos);
        if (self::$yyTraceFILE && $this->yyidx > 0) {
            fprintf(self::$yyTraceFILE, "%sShift %d\n", self::$yyTracePrompt,
                $yyNewState);
            fprintf(self::$yyTraceFILE, "%sStack:", self::$yyTracePrompt);
            for($i = 1; $i <= $this->yyidx; $i++) {
                fprintf(self::$yyTraceFILE, " %s",
                    self::$yyTokenName[$this->yystack[$i]->major]);
            }
            fwrite(self::$yyTraceFILE,"\n");
        }
    }

    /**
     * The following table contains information about every rule that
     * is used during the reduce.
     *
     * <pre>
     * array(
     *  array(
     *   int $lhs;         Symbol on the left-hand side of the rule
     *   int $nrhs;     Number of right-hand side symbols in the rule
     *  ),...
     * );
     * </pre>
     */
    static public $yyRuleInfo = array(
  array( 'lhs' => 29, 'rhs' => 1 ),
  array( 'lhs' => 30, 'rhs' => 3 ),
  array( 'lhs' => 30, 'rhs' => 2 ),
  array( 'lhs' => 30, 'rhs' => 2 ),
  array( 'lhs' => 30, 'rhs' => 1 ),
  array( 'lhs' => 30, 'rhs' => 3 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 33, 'rhs' => 3 ),
  array( 'lhs' => 33, 'rhs' => 4 ),
  array( 'lhs' => 33, 'rhs' => 4 ),
  array( 'lhs' => 33, 'rhs' => 5 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 4 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 32, 'rhs' => 2 ),
  array( 'lhs' => 32, 'rhs' => 3 ),
  array( 'lhs' => 34, 'rhs' => 3 ),
  array( 'lhs' => 34, 'rhs' => 5 ),
  array( 'lhs' => 35, 'rhs' => 4 ),
  array( 'lhs' => 35, 'rhs' => 5 ),
  array( 'lhs' => 42, 'rhs' => 7 ),
  array( 'lhs' => 42, 'rhs' => 6 ),
  array( 'lhs' => 42, 'rhs' => 4 ),
  array( 'lhs' => 42, 'rhs' => 5 ),
  array( 'lhs' => 42, 'rhs' => 4 ),
  array( 'lhs' => 42, 'rhs' => 5 ),
  array( 'lhs' => 36, 'rhs' => 4 ),
  array( 'lhs' => 36, 'rhs' => 4 ),
  array( 'lhs' => 37, 'rhs' => 4 ),
  array( 'lhs' => 37, 'rhs' => 4 ),
  array( 'lhs' => 38, 'rhs' => 4 ),
  array( 'lhs' => 38, 'rhs' => 5 ),
  array( 'lhs' => 38, 'rhs' => 3 ),
  array( 'lhs' => 38, 'rhs' => 4 ),
  array( 'lhs' => 39, 'rhs' => 4 ),
  array( 'lhs' => 40, 'rhs' => 3 ),
  array( 'lhs' => 41, 'rhs' => 3 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        6 => 4,
        7 => 4,
        9 => 4,
        10 => 4,
        12 => 4,
        13 => 4,
        14 => 4,
        15 => 4,
        16 => 4,
        5 => 5,
        17 => 17,
        18 => 17,
        20 => 17,
        21 => 17,
        23 => 17,
        24 => 17,
        25 => 17,
        26 => 17,
        27 => 17,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32,
        58 => 32,
        60 => 32,
        33 => 33,
        62 => 33,
        34 => 34,
        64 => 34,
        35 => 35,
        36 => 36,
        37 => 37,
        38 => 38,
        39 => 39,
        40 => 40,
        41 => 41,
        42 => 42,
        43 => 43,
        66 => 43,
        44 => 44,
        68 => 44,
        45 => 45,
        74 => 45,
        46 => 46,
        76 => 46,
        47 => 47,
        70 => 47,
        72 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        55 => 55,
        56 => 56,
        78 => 56,
        57 => 57,
        80 => 57,
        59 => 59,
        61 => 59,
        63 => 63,
        65 => 65,
        67 => 67,
        69 => 69,
        71 => 71,
        73 => 71,
        75 => 75,
        77 => 77,
        79 => 79,
        81 => 81,
        82 => 82,
        83 => 83,
        84 => 84,
        85 => 85,
        86 => 86,
        87 => 87,
        88 => 88,
        89 => 89,
        90 => 90,
        94 => 90,
        91 => 91,
        92 => 92,
        93 => 93,
        95 => 95,
        96 => 96,
        97 => 97,
        98 => 98,
        99 => 99,
        100 => 100,
        101 => 101,
        102 => 102,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 47 "Parser.y"
    function yy_r0(){
    $this->yystack[$this->yyidx + 0]->minor->string = str_replace('"', '\\"', $this->yystack[$this->yyidx + 0]->minor->string);
    $x = $this->yystack[$this->yyidx + 0]->minor->metadata;
    $x['subpatterns'] = $this->_subpatterns;
    $this->yystack[$this->yyidx + 0]->minor->metadata = $x;
    $this->_subpatterns = 0;
    $this->result = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1302 "Parser.php"
#line 56 "Parser.y"
    function yy_r1(){
    throw new PHP_LexerGenerator_Exception('Cannot include start match "' .
        $this->yystack[$this->yyidx + -2]->minor . '" or end match "' . $this->yystack[$this->yyidx + 0]->minor . '"');
    }
#line 1308 "Parser.php"
#line 60 "Parser.y"
    function yy_r2(){
    throw new PHP_LexerGenerator_Exception('Cannot include start match "' .
        B . '"');
    }
#line 1314 "Parser.php"
#line 64 "Parser.y"
    function yy_r3(){
    throw new PHP_LexerGenerator_Exception('Cannot include end match "' . $this->yystack[$this->yyidx + 0]->minor . '"');
    }
#line 1319 "Parser.php"
#line 67 "Parser.y"
    function yy_r4(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1322 "Parser.php"
#line 68 "Parser.y"
    function yy_r5(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . '|' . $this->yystack[$this->yyidx + 0]->minor->string, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . '|' . $this->yystack[$this->yyidx + 0]->minor['pattern']));
    }
#line 1328 "Parser.php"
#line 84 "Parser.y"
    function yy_r17(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . $this->yystack[$this->yyidx + 0]->minor->string, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . $this->yystack[$this->yyidx + 0]->minor['pattern']));
    }
#line 1334 "Parser.php"
#line 123 "Parser.y"
    function yy_r28(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('[' . $this->yystack[$this->yyidx + -1]->minor->string . ']', array(
        'pattern' => '[' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ']'));
    }
#line 1340 "Parser.php"
#line 127 "Parser.y"
    function yy_r29(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('[^' . $this->yystack[$this->yyidx + -1]->minor->string . ']', array(
        'pattern' => '[^' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ']'));
    }
#line 1346 "Parser.php"
#line 131 "Parser.y"
    function yy_r30(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('[' . $this->yystack[$this->yyidx + -2]->minor->string . ']' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '[' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ']' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1352 "Parser.php"
#line 135 "Parser.y"
    function yy_r31(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('[^' . $this->yystack[$this->yyidx + -2]->minor->string . ']' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '[^' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ']' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1358 "Parser.php"
#line 140 "Parser.y"
    function yy_r32(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1364 "Parser.php"
#line 144 "Parser.y"
    function yy_r33(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1370 "Parser.php"
#line 148 "Parser.y"
    function yy_r34(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1376 "Parser.php"
#line 152 "Parser.y"
    function yy_r35(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1382 "Parser.php"
#line 156 "Parser.y"
    function yy_r36(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1388 "Parser.php"
#line 160 "Parser.y"
    function yy_r37(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1394 "Parser.php"
#line 164 "Parser.y"
    function yy_r38(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1400 "Parser.php"
#line 168 "Parser.y"
    function yy_r39(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\' . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1406 "Parser.php"
#line 172 "Parser.y"
    function yy_r40(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1412 "Parser.php"
#line 176 "Parser.y"
    function yy_r41(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1418 "Parser.php"
#line 180 "Parser.y"
    function yy_r42(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor . '-\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1424 "Parser.php"
#line 184 "Parser.y"
    function yy_r43(){
    if (((int) substr($this->yystack[$this->yyidx + 0]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr($this->yystack[$this->yyidx + 0]->minor, 1));
    }
    $this->yystack[$this->yyidx + 0]->minor = substr($this->yystack[$this->yyidx + 0]->minor, 1);
    // adjust back-reference for containing ()
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + 0]->minor)));
    }
#line 1436 "Parser.php"
#line 194 "Parser.y"
    function yy_r44(){
    if (((int) substr($this->yystack[$this->yyidx + 0]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception($this->yystack[$this->yyidx + 0]->minor . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr($this->yystack[$this->yyidx + 0]->minor, 1) . ' for octal');
    }
    $this->yystack[$this->yyidx + 0]->minor = substr($this->yystack[$this->yyidx + 0]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + 0]->minor)));
    }
#line 1447 "Parser.php"
#line 203 "Parser.y"
    function yy_r45(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . '\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1453 "Parser.php"
#line 207 "Parser.y"
    function yy_r46(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . '\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1459 "Parser.php"
#line 211 "Parser.y"
    function yy_r47(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1465 "Parser.php"
#line 215 "Parser.y"
    function yy_r48(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . '\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1471 "Parser.php"
#line 219 "Parser.y"
    function yy_r49(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . '\\\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1477 "Parser.php"
#line 223 "Parser.y"
    function yy_r50(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . '\\' . $this->yystack[$this->yyidx + -2]->minor . '-\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1483 "Parser.php"
#line 227 "Parser.y"
    function yy_r51(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . '\\' . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1489 "Parser.php"
#line 231 "Parser.y"
    function yy_r52(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . '\\\\' . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1495 "Parser.php"
#line 235 "Parser.y"
    function yy_r53(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . $this->yystack[$this->yyidx + -2]->minor . '-\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1501 "Parser.php"
#line 239 "Parser.y"
    function yy_r54(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . $this->yystack[$this->yyidx + -2]->minor . '-\\\\' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1507 "Parser.php"
#line 243 "Parser.y"
    function yy_r55(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -3]->minor->string . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor . '-' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1513 "Parser.php"
#line 247 "Parser.y"
    function yy_r56(){
    if (((int) substr($this->yystack[$this->yyidx + 0]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr($this->yystack[$this->yyidx + 0]->minor, 1));
    }
    $this->yystack[$this->yyidx + 0]->minor = substr($this->yystack[$this->yyidx + 0]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . '\\\\' . ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex), array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + 0]->minor)));
    }
#line 1524 "Parser.php"
#line 256 "Parser.y"
    function yy_r57(){
    if (((int) substr($this->yystack[$this->yyidx + 0]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception($this->yystack[$this->yyidx + 0]->minor . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr($this->yystack[$this->yyidx + 0]->minor, 1) . ' for octal');
    }
    $this->yystack[$this->yyidx + 0]->minor = substr($this->yystack[$this->yyidx + 0]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor->string . '\\\\' . ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex), array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor['pattern'] . '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + 0]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + 0]->minor)));
    }
#line 1535 "Parser.php"
#line 270 "Parser.y"
    function yy_r59(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1541 "Parser.php"
#line 286 "Parser.y"
    function yy_r63(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\' . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1547 "Parser.php"
#line 294 "Parser.y"
    function yy_r65(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1553 "Parser.php"
#line 308 "Parser.y"
    function yy_r67(){
    if (((int) substr($this->yystack[$this->yyidx + -1]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr($this->yystack[$this->yyidx + -1]->minor, 1));
    }
    $this->yystack[$this->yyidx + -1]->minor = substr($this->yystack[$this->yyidx + -1]->minor, 1);
    // adjust back-reference for containing ()
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + -1]->minor) . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1565 "Parser.php"
#line 327 "Parser.y"
    function yy_r69(){
    if (((int) substr($this->yystack[$this->yyidx + -1]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception($this->yystack[$this->yyidx + -1]->minor . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr($this->yystack[$this->yyidx + -1]->minor, 1) . ' for octal');
    }
    $this->yystack[$this->yyidx + -1]->minor = substr($this->yystack[$this->yyidx + -1]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('\\\\' . ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + -1]->minor) . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1576 "Parser.php"
#line 340 "Parser.y"
    function yy_r71(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1582 "Parser.php"
#line 356 "Parser.y"
    function yy_r75(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . '\\' . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1588 "Parser.php"
#line 364 "Parser.y"
    function yy_r77(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . '\\\\' . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1594 "Parser.php"
#line 377 "Parser.y"
    function yy_r79(){
    if (((int) substr($this->yystack[$this->yyidx + -1]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr($this->yystack[$this->yyidx + -1]->minor, 1));
    }
    $this->yystack[$this->yyidx + -1]->minor = substr($this->yystack[$this->yyidx + -1]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . '\\\\' . ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + -1]->minor) . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1605 "Parser.php"
#line 395 "Parser.y"
    function yy_r81(){
    if (((int) substr($this->yystack[$this->yyidx + -1]->minor, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception($this->yystack[$this->yyidx + -1]->minor . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr($this->yystack[$this->yyidx + -1]->minor, 1) . ' for octal');
    }
    $this->yystack[$this->yyidx + -1]->minor = substr($this->yystack[$this->yyidx + -1]->minor, 1);
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken($this->yystack[$this->yyidx + -2]->minor->string . '\\\\' . ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => $this->yystack[$this->yyidx + -2]->minor['pattern'] . '\\' . ($this->_updatePattern ? ($this->yystack[$this->yyidx + -1]->minor + $this->_patternIndex) : $this->yystack[$this->yyidx + -1]->minor) . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1616 "Parser.php"
#line 405 "Parser.y"
    function yy_r82(){
    throw new PHP_LexerGenerator_Exception('Error: cannot set preg options directly with "' .
        $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor . '"');
    }
#line 1622 "Parser.php"
#line 409 "Parser.y"
    function yy_r83(){
    throw new PHP_LexerGenerator_Exception('Error: cannot set preg options directly with "' .
        $this->yystack[$this->yyidx + -4]->minor . $this->yystack[$this->yyidx + -3]->minor . $this->yystack[$this->yyidx + -2]->minor . $this->yystack[$this->yyidx + -1]->minor['pattern'] . $this->yystack[$this->yyidx + 0]->minor . '"');
    }
#line 1628 "Parser.php"
#line 414 "Parser.y"
    function yy_r84(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?:' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(?:' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1634 "Parser.php"
#line 418 "Parser.y"
    function yy_r85(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?:' . $this->yystack[$this->yyidx + -2]->minor->string . ')' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '(?:' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1640 "Parser.php"
#line 423 "Parser.y"
    function yy_r86(){
    if ($this->yystack[$this->yyidx + -4]->minor != 'R') {
        if (!preg_match('/[1-9][0-9]*/', $this->yystack[$this->yyidx + -4]->minor)) {
            throw new PHP_LexerGenerator_Exception('Invalid sub-pattern conditional: "(?(' . $this->yystack[$this->yyidx + -4]->minor . ')"');
        }
        if ($this->yystack[$this->yyidx + -4]->minor > $this->_subpatterns) {
            throw new PHP_LexerGenerator_Exception('sub-pattern conditional . "' . $this->yystack[$this->yyidx + -4]->minor . '" refers to non-existent sub-pattern');
        }
    } else {
        throw new PHP_LexerGenerator_Exception('Recursive conditional (?(' . $this->yystack[$this->yyidx + -4]->minor . ')" cannot work in this lexer');
    }
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?(' . $this->yystack[$this->yyidx + -4]->minor . ')' . $this->yystack[$this->yyidx + -2]->minor->string . ')' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '(?(' . $this->yystack[$this->yyidx + -4]->minor . ')' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1656 "Parser.php"
#line 437 "Parser.y"
    function yy_r87(){
    if ($this->yystack[$this->yyidx + -3]->minor != 'R') {
        if (!preg_match('/[1-9][0-9]*/', $this->yystack[$this->yyidx + -3]->minor)) {
            throw new PHP_LexerGenerator_Exception('Invalid sub-pattern conditional: "(?(' . $this->yystack[$this->yyidx + -3]->minor . ')"');
        }
        if ($this->yystack[$this->yyidx + -3]->minor > $this->_subpatterns) {
            throw new PHP_LexerGenerator_Exception('sub-pattern conditional . "' . $this->yystack[$this->yyidx + -3]->minor . '" refers to non-existent sub-pattern');
        }
    } else {
        throw new PHP_LexerGenerator_Exception('Recursive conditional (?(' . $this->yystack[$this->yyidx + -3]->minor . ')" cannot work in this lexer');
    }
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?(' . $this->yystack[$this->yyidx + -3]->minor . ')' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(?(' . $this->yystack[$this->yyidx + -3]->minor . ')' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1672 "Parser.php"
#line 451 "Parser.y"
    function yy_r88(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?' . $this->yystack[$this->yyidx + -2]->minor->string . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(?' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1678 "Parser.php"
#line 455 "Parser.y"
    function yy_r89(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?' . $this->yystack[$this->yyidx + -3]->minor->string . $this->yystack[$this->yyidx + -2]->minor->string . ')' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '(?' . $this->yystack[$this->yyidx + -3]->minor['pattern'] . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1684 "Parser.php"
#line 459 "Parser.y"
    function yy_r90(){
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<=' .
        $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')');
    }
#line 1690 "Parser.php"
#line 463 "Parser.y"
    function yy_r91(){
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<=' .
        $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')');
    }
#line 1696 "Parser.php"
#line 468 "Parser.y"
    function yy_r92(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?=' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern '=> '(?=' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1702 "Parser.php"
#line 472 "Parser.y"
    function yy_r93(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?!' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(?!' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1708 "Parser.php"
#line 481 "Parser.y"
    function yy_r95(){
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<!' .
        $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')');
    }
#line 1714 "Parser.php"
#line 486 "Parser.y"
    function yy_r96(){
    throw new PHP_LexerGenerator_Exception('Cannot use named sub-patterns: "(' .
        $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')');
    }
#line 1720 "Parser.php"
#line 490 "Parser.y"
    function yy_r97(){
    throw new PHP_LexerGenerator_Exception('Cannot use named sub-patterns: "(' .
        $this->yystack[$this->yyidx + -3]->minor['pattern'] . ')');
    }
#line 1726 "Parser.php"
#line 494 "Parser.y"
    function yy_r98(){
    $this->_subpatterns++;
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1733 "Parser.php"
#line 499 "Parser.y"
    function yy_r99(){
    $this->_subpatterns++;
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(' . $this->yystack[$this->yyidx + -2]->minor->string . ')' . $this->yystack[$this->yyidx + 0]->minor, array(
        'pattern' => '(' . $this->yystack[$this->yyidx + -2]->minor['pattern'] . ')' . $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1740 "Parser.php"
#line 505 "Parser.y"
    function yy_r100(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(?>' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(?>' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1746 "Parser.php"
#line 510 "Parser.y"
    function yy_r101(){
    $this->_retvalue = new PHP_LexerGenerator_ParseryyToken('(' . $this->yystack[$this->yyidx + -1]->minor->string . ')', array(
        'pattern' => '(' . $this->yystack[$this->yyidx + -1]->minor['pattern'] . ')'));
    }
#line 1752 "Parser.php"
#line 515 "Parser.y"
    function yy_r102(){
    throw new Exception('(?R) cannot work in this lexer');
    }
#line 1757 "Parser.php"

    /**
     * placeholder for the left hand side in a reduce operation.
     * 
     * For a parser with a rule like this:
     * <pre>
     * rule(A) ::= B. { A = 1; }
     * </pre>
     * 
     * The parser will translate to something like:
     * 
     * <code>
     * function yy_r0(){$this->_retvalue = 1;}
     * </code>
     */
    private $_retvalue;

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     * 
     * For a rule such as:
     * 
     * <pre>
     * A ::= B blah C. { dosomething(); }
     * </pre>
     * 
     * This function will first call the action, if any, ("dosomething();" in our
     * example), and then it will pop three states from the stack,
     * one for each entry on the right-hand side of the expression
     * (B, blah, and C in our example rule), and then push the result of the action
     * back on to the stack with the resulting state reduced to (as described in the .out
     * file)
     * @param int Number of the rule by which to reduce
     */
    function yy_reduce($yyruleno)
    {
        //int $yygoto;                     /* The next state */
        //int $yyact;                      /* The next action */
        //mixed $yygotominor;        /* The LHS of the rule reduced */
        //PHP_LexerGenerator_Regex_yyStackEntry $yymsp;            /* The top of the parser's stack */
        //int $yysize;                     /* Amount to pop the stack */
        $yymsp = $this->yystack[$this->yyidx];
        if (self::$yyTraceFILE && $yyruleno >= 0 
              && $yyruleno < count(self::$yyRuleName)) {
            fprintf(self::$yyTraceFILE, "%sReduce (%d) [%s].\n",
                self::$yyTracePrompt, $yyruleno,
                self::$yyRuleName[$yyruleno]);
        }

        $this->_retvalue = $yy_lefthand_side = null;
        if (array_key_exists($yyruleno, self::$yyReduceMap)) {
            // call the action
            $this->_retvalue = null;
            $this->{'yy_r' . self::$yyReduceMap[$yyruleno]}();
            $yy_lefthand_side = $this->_retvalue;
        }
        $yygoto = self::$yyRuleInfo[$yyruleno]['lhs'];
        $yysize = self::$yyRuleInfo[$yyruleno]['rhs'];
        $this->yyidx -= $yysize;
        for($i = $yysize; $i; $i--) {
            // pop all of the right-hand side parameters
            array_pop($this->yystack);
        }
        $yyact = $this->yy_find_reduce_action($this->yystack[$this->yyidx]->stateno, $yygoto);
        if ($yyact < self::YYNSTATE) {
            /* If we are not debugging and the reduce action popped at least
            ** one element off the stack, then we can push the new element back
            ** onto the stack here, and skip the stack overflow test in yy_shift().
            ** That gives a significant speed improvement. */
            if (!self::$yyTraceFILE && $yysize) {
                $this->yyidx++;
                $x = new PHP_LexerGenerator_Regex_yyStackEntry;
                $x->stateno = $yyact;
                $x->major = $yygoto;
                $x->minor = $yy_lefthand_side;
                $this->yystack[$this->yyidx] = $x;
            } else {
                $this->yy_shift($yyact, $yygoto, $yy_lefthand_side);
            }
        } elseif ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
            $this->yy_accept();
        }
    }

    /**
     * The following code executes when the parse fails
     * 
     * Code from %parse_fail is inserted here
     */
    function yy_parse_failed()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sFail!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser fails */
    }

    /**
     * The following code executes when a syntax error first occurs.
     * 
     * %syntax_error code is inserted here
     * @param int The major type of the error token
     * @param mixed The minor type of the error token
     */
    function yy_syntax_error($yymajor, $TOKEN)
    {
#line 6 "Parser.y"

/* ?><?php */
    // we need to add auto-escaping of all stuff that needs it for result.
    // and then validate the original regex only
    echo "Syntax Error on line " . $this->_lex->line . ": token '" . 
        $this->_lex->value . "' while parsing rule:";
    foreach ($this->yystack as $entry) {
        echo $this->tokenName($entry->major) . ' ';
    }
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
    throw new Exception('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN
        . '), expected one of: ' . implode(',', $expect));
#line 1885 "Parser.php"
    }

    /**
     * The following is executed when the parser accepts
     * 
     * %parse_accept code is inserted here
     */
    function yy_accept()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sAccept!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $stack = $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser accepts */
    }

    /**
     * The main parser program.
     * 
     * The first argument is the major token number.  The second is
     * the token value string as scanned from the input.
     *
     * @param int the token number
     * @param mixed the token value
     * @param mixed any extra arguments that should be passed to handlers
     */
    function doParse($yymajor, $yytokenvalue)
    {
//        $yyact;            /* The parser action. */
//        $yyendofinput;     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */
        
        /* (re)initialize the parser, if necessary */
        if ($this->yyidx === null || $this->yyidx < 0) {
            /* if ($yymajor == 0) return; // not sure why this was here... */
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $x = new PHP_LexerGenerator_Regex_yyStackEntry;
            $x->stateno = 0;
            $x->major = 0;
            $this->yystack = array();
            array_push($this->yystack, $x);
        }
        $yyendofinput = ($yymajor==0);
        
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sInput %s\n",
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL &&
                  !$this->yy_is_expected_token($yymajor)) {
                // force a syntax error
                $yyact = self::YY_ERROR_ACTION;
            }
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yytokenvalue);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } elseif ($yyact < self::YYNSTATE + self::YYNRULE) {
                $this->yy_reduce($yyact - self::YYNSTATE);
            } elseif ($yyact == self::YY_ERROR_ACTION) {
                if (self::$yyTraceFILE) {
                    fprintf(self::$yyTraceFILE, "%sSyntax Error!\n",
                        self::$yyTracePrompt);
                }
                if (self::YYERRORSYMBOL) {
                    /* A syntax error has occurred.
                    ** The response to an error depends upon whether or not the
                    ** grammar defines an error token "ERROR".  
                    **
                    ** This is what we do if the grammar does define ERROR:
                    **
                    **  * Call the %syntax_error function.
                    **
                    **  * Begin popping the stack until we enter a state where
                    **    it is legal to shift the error symbol, then shift
                    **    the error symbol.
                    **
                    **  * Set the error count to three.
                    **
                    **  * Begin accepting and shifting new tokens.  No new error
                    **    processing will occur until three tokens have been
                    **    shifted successfully.
                    **
                    */
                    if ($this->yyerrcnt < 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $yymx = $this->yystack[$this->yyidx]->major;
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ){
                        if (self::$yyTraceFILE) {
                            fprintf(self::$yyTraceFILE, "%sDiscard input token %s\n",
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0 &&
                                 $yymx != self::YYERRORSYMBOL &&
        ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                              ){
                            $this->yy_pop_parser_stack();
                        }
                        if ($this->yyidx < 0 || $yymajor==0) {
                            $this->yy_destructor($yymajor, $yytokenvalue);
                            $this->yy_parse_failed();
                            $yymajor = self::YYNOCODE;
                        } elseif ($yymx != self::YYERRORSYMBOL) {
                            $u2 = 0;
                            $this->yy_shift($yyact, self::YYERRORSYMBOL, $u2);
                        }
                    }
                    $this->yyerrcnt = 3;
                    $yyerrorhit = 1;
                } else {
                    /* YYERRORSYMBOL is not defined */
                    /* This is what we do if the grammar does not define ERROR:
                    **
                    **  * Report an error message, and throw away the input token.
                    **
                    **  * If the input token is $, then fail the parse.
                    **
                    ** As before, subsequent error messages are suppressed until
                    ** three input tokens have been successfully shifted.
                    */
                    if ($this->yyerrcnt <= 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $this->yyerrcnt = 3;
                    $this->yy_destructor($yymajor, $yytokenvalue);
                    if ($yyendofinput) {
                        $this->yy_parse_failed();
                    }
                    $yymajor = self::YYNOCODE;
                }
            } else {
                $this->yy_accept();
                $yymajor = self::YYNOCODE;
            }            
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }
}