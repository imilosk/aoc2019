<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("13.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$program[0] = 2;
$segmentDisplay = 0;
$inputInstruction = 1;
$x = $y = $tileId = 0;

$computer = new IntcodeComputer($program);
$computer->execute(
    function () use(&$paddleXPosition, &$ballXPosition) {
        if ($paddleXPosition === $ballXPosition) {
            $input = 0;
        } elseif ($paddleXPosition > $ballXPosition) {
            $input = -1;
        } elseif ($paddleXPosition < $ballXPosition) {
            $input = 1;
        }
        return $input;
    },
    function ($output) use(&$ballXPosition, &$paddleXPosition, &$inputInstruction, &$segmentDisplay, &$x, &$y, &$tileId) {
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
    }, 
);

echo $segmentDisplay . PHP_EOL;

fclose($file);
