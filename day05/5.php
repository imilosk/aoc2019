<?php

include '../Intcode/IntcodeComputer.php';

$file = fopen("5.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$computer = new IntcodeComputer($program);
$computer->execute(
    function () {
        // part one
        // return 1;
        // part two
        return 5;
    },
    function ($output) {
        echo $output . PHP_EOL;
    }
);

fclose($file);
