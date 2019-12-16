<?php

include '../Intcode/IntcodeComputer.php';

$file = fopen("2.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$program[1] = 12;
$program[2] = 2;

$computer = new IntcodeComputer($program);
$computer->execute(
    null,
    function ($output) {
        echo $output;
    }
);

echo $computer->program[0] . PHP_EOL;

fclose($file);
