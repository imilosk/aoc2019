<?php

$file = fopen("13.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$instructions = [];
// $program[0] = 2;

$relative_base = 0;
$num_instr = count($program);
for ($i = 0; $i < $num_instr; $i++) {
    $instruction      = strrev((string)$program[$i]);
    $opcode           = (int) strrev( substr($instruction, 0, 2) );
    $modeOfParameter1 = (int) substr($instruction, 2, 1) ?: 0;
    $modeOfParameter2 = (int) substr($instruction, 3, 1) ?: 0;
    $modeOfParameter3 = (int) substr($instruction, 4, 1) ?: 0;

    if ($opcode === 99) {
        break;
    }

    switch($modeOfParameter1) {
        case 0:
            if (!isset($program[$program[$i + 1]]))
                $program[$program[$i + 1]] = 0;
            $parameter1 = $program[$program[$i + 1]]; 
            break;
        case 1:
            if (!isset($program[$i + 1]))
                $program[$i + 1] = 0;
            $parameter1 = $program[$i + 1];
            break;
        case 2:
            if (!isset($program[$program[$i + 1]]))
                $program[$program[$i + 1]] = 0;
            $parameter1 = $program[$program[$i + 1] + $relative_base];
            break;
    }

    switch($modeOfParameter2) {
        case 0:
            if (!isset($program[$program[$i + 2]]))
                $program[$program[$i + 2]] = 0;
            $parameter2 = $program[$program[$i + 2]]; 
            break;
        case 1:
            if (!isset($program[$i + 2]))
                $program[$i + 2] = 0;
            $parameter2 = $program[$i + 2];
            break;
        case 2:
            if (!isset($program[$program[$i + 2]]))
                $program[$program[$i + 2]] = 0;
            $parameter2 = $program[$program[$i + 2] + $relative_base];
            break;
    }

    switch($modeOfParameter3) {
        case 0:
            if (!isset($program[$i + 3]))
                $program[$i + 3] = 0;
            $parameter3 = $program[$i + 3]; 
            break;
        case 1:
            if (!isset($program[$program[$i + 3]]))
                $program[$program[$i + 3]] = 0;
            $parameter3 = $program[$program[$i + 3]];
            break;
        case 2:
            if (!isset($program[$program[$i + 3]]))
                $program[$program[$i + 3]] = 0;
            $parameter3 = $program[$i + 3] + $relative_base;
            break;
    }

    if ($opcode === 1) {
        if ($program[$i] == '21101')
            $temp = 123;
        $program[$parameter3] = $parameter1 + $parameter2;
        $i += 3;

    } elseif ($opcode === 2) {
        $program[$parameter3] = $parameter1 * $parameter2;
        $i += 3;

    } elseif ($opcode === 3) {
        $handle = fopen ("php://stdin","r");
        echo 'Enter an integer: ';
        $input = (int) fgetc($handle);
        echo PHP_EOL;
        $program[$parameter3] = $input;
        $i += 1;

    } elseif ($opcode === 4) {
        // echo $parameter1 . PHP_EOL;
        $instructions[] = $parameter1;
        $i += 1;

    } elseif ($opcode === 5) {
        if ($parameter1 !== 0)
            $i = $parameter2 - 1;
        else
            $i += 2;

    } elseif ($opcode === 6) {
        if ($parameter1 === 0)
            $i = $parameter2 - 1;
        else
            $i += 2;

    } elseif ($opcode === 7) {
        if ($parameter1 < $parameter2)
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;

    } elseif ($opcode === 8) {
        if ($parameter1 === $parameter2)
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;
        
    } elseif ($opcode === 9) {
        $relative_base += $parameter1;
        $i += 1;
        
    } 
}

$blockTilesNumber = 0;
for ($i = 0; $i < count($instructions); $i += 3) {
    $x = $instructions[$i];
    $y = $instructions[$i + 1];
    $tileId = $instructions[$i + 2];
    if ($tileId === 2)
        $blockTilesNumber++;
}

echo $blockTilesNumber . PHP_EOL;

fclose($file);
