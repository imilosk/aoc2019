<?php

include '../Intcode/IntcodeComputer.php';

$file = fopen("2.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$program[1] = 12;
$program[2] = 2;

$computer = new IntcodeComputer($program);
$generator = $computer->execute();

while (!$computer->halt) {
    $output = $generator->current();

    // if - input, else - output
    if ($output === 'Input') {
        $generator->send($input);

    } elseif ($output !== null) {
        echo $output . PHP_EOL;

        $generator->next();
    } else {
        $generator->next();
    }
    
}

echo $computer->program[0] . PHP_EOL;

fclose($file);
