--TEST--
bug #11808: incorrect yymore regexes
--FILE--
<?php
include dirname(__FILE__) . '/bug11808.php';
?>
--EXPECT--
Integer :-2<br>operator: +<br>Integer :1<br>