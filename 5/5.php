<?php

function getParameter($program, $i, $parameterMode, $offset) {
    $parameter = null;
    if ($parameterMode == 0) {
        if (isset($program[$program[$i + $offset]]))
            $parameter = $program[$program[$i + $offset]]; 
    } elseif ($parameterMode == 1) {
        if (isset($program[$i + $offset]))
            $parameter = $program[$i + $offset];
    } elseif ($parameterMode == 2) {
        if (isset($program[$i + $offset]))
            $parameter = $program[$i + $offset];
    } else {
        $parameter = null;
    }

    return $parameter;
}

$file = fopen("5.txt","r");
$line = fgets($file);

$program = array_map('intval', explode(',', $line));

for ($i = 0; $i < count($program); $i++) {
    $instruction      = strrev((string)$program[$i]);
    $opcode           = (int) strrev( substr($instruction, 0, 2) );
    $modeOfParameter1 = (int) substr($instruction, 2, 1) ?: 0;
    $modeOfParameter2 = (int) substr($instruction, 3, 1) ?: 0;
    $modeOfParameter3 = (int) substr($instruction, 4, 1) ?: 0;

    switch($modeOfParameter1) {
        case 0:
            if (isset($program[$program[$i + 1]]))
                $parameter1 = $program[$program[$i + 1]]; 
            break;
        case 1:
            if (isset($program[$i + 1]))
                $parameter1 = $program[$i + 1];
            break;
        case 2:
            if (isset($program[$program[$i + 1]]))
                $parameter1 = $program[$program[$i + 1]];
            break;
    }

    switch($modeOfParameter2) {
        case 0:
            if (isset($program[$program[$i + 2]]))
                $parameter2 = $program[$program[$i + 2]]; 
            break;
        case 1:
            if (isset($program[$i + 2]))
                $parameter2 = $program[$i + 2];
            break;
        case 2:
            if (isset($program[$program[$i + 2]]))
                $parameter2 = $program[$program[$i + 2]];
            break;
    }

    switch($modeOfParameter3) {
        case 0:
            if (isset($program[$i + 3]))
                $parameter3 = $program[$i + 3]; 
            break;
        case 1:
            if (isset($program[$program[$i + 3]]))
                $parameter3 = $program[$program[$i + 3]];
            break;
        case 2:
            if (isset($program[$program[$i + 3]]))
                $parameter3 = $program[$program[$i + 3]];
            break;
    }

    if ($opcode === 1) {
        $program[$parameter3] = $parameter1 + $parameter2;
        $i += 3;

    } elseif ($opcode === 2) {
        $program[$parameter3] = $parameter1 * $parameter2;
        $i += 3;

    } elseif ($opcode === 3) {
        $handle = fopen ("php://stdin","r");
        echo 'Enter an integer: ';
        $input = (int) 1;
        $program[$program[$i + 1]] = $input;
        $i += 1;

    } elseif ($opcode === 4) {
        echo $parameter1 . PHP_EOL;
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
        
    } elseif ($opcode === 99) {
        break;
    }
}

fclose($file);
