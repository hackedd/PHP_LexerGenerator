<?php
/**
 * Lexer output capture utility.
 *
 * @author Alan Langford <jal@ambitonline.com>
 * @copyright 2007 Alan Langford
 * @license http://www.php.net/license/3_01.txt  PHP License 3.01
 * @package PHP_LexerGenerator
 * @version $Id$
 */

require_once 'PHP/LexerGenerator/Lexer.php';

/**
 * Lexer output capture.
 *
 * Captures the operation of the lexer on an input file for comparison purposes.
 * @version @package_version@
 */
class LexerOutputRecorder
{

    /**
     * Generate an array of lexer states.
     *
     * @param string Mode selector, one of "data" or "file".
     * @param string Plex data if mode is "data", path to the plex file if mode
     * is "file".
     * @return array Array containing snapshot of lexer state after each call to
     * advance(), error count and any output.
     */
    function process($mode, $plex)
    {
        $result = array();
        switch ($mode) {
            case 'data': {
                $lex = new PHP_LexerGenerator_Lexer($plex);
            }
            break;

            case 'file': {
                $lex = new PHP_LexerGenerator_Lexer(file_get_contents($plex));
                $result['source'] = str_replace($plex, '\\', '/');
            }
            break;

            default:
                return false;
        }
        $states = array();
        ob_start();
        try {
            while ($lex->advance(null)) {
                $states[] = array(
                    'line' => $lex->line,
                    'token' => $lex->token,
                    'value' => $lex->value
                );
            }
        } catch (Exception $e) {
            $result['exception'] = (string) $e;
        }
        $result['output'] = ob_get_clean();
        $result['states'] = $states;
        $result['errors'] = $lex -> errors;
        return $result;
    }
    
}
?>
