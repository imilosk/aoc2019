<?php

include '../Intcode/IntcodeComputer.php';

$file = fopen("5.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$computer = new IntcodeComputer($program);
$generator = $computer->execute();

while (!$computer->halt) {
    $output = $generator->current();

    // if - input, else - output
    if ($output === 'Input') {
        // part one
        // $input = 1;

        // part two
        $input = 5;

        $generator->send($input);

    } elseif ($output !== null) {
        echo $output . PHP_EOL;

        $generator->next();
    }
    
}

fclose($file);
