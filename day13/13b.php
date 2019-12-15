<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("13.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$program[0] = 2;
$segmentDisplay = 0;
$inputInstruction = 1;

$computer = new IntcodeComputer($program);
$generator = $computer->execute();

while (!$computer->halt) {
    $output = $generator->current();

    // if - input, else - output
    if ($output === 'Input') {
        if ($paddleXPosition === $ballXPosition) {
            $input = 0;
        } elseif ($paddleXPosition > $ballXPosition) {
            $input = -1;
        } elseif ($paddleXPosition < $ballXPosition) {
            $input = 1;
        }
        $generator->send($input);

    } elseif ($output !== null) {
        if ($inputInstruction === 1) {
            $x = $output;
        } elseif ($inputInstruction === 2) {
            $y = $output;
        } elseif ($inputInstruction === 3) {
            $tileId = $output;
            $inputInstruction = 0;

            if ($x === -1 && $y === 0) 
                $segmentDisplay = $output;
            if ($tileId === 4) 
                $ballXPosition = $x;
            if ($tileId === 3) 
                $paddleXPosition = $x;
            
        }
        $inputInstruction++;
        $generator->next();
    }
    
}

echo $segmentDisplay . PHP_EOL;

fclose($file);
