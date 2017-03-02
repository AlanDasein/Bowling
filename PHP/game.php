#!/usr/bin/php
<?php
header("Content-Type: text/plain");

require "Bowling.php";

$bowling = new Bowling();

for($i = 0, $j = ($bowling::TURNS * $bowling::TRIES) + 1;$i < $j;$i++) $bowling->play();