<?php

$file = fopen("11.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$outputCount = 0;
$direction = 90;
$robotPosition = [0, 0];
$paintedPoints = array();

$relative_base = 0;
$c = count($program);
for ($i = 0; $i < $c; $i++) {
    $orig_instruction = (string)$program[$i];
    $opcode           = (int) substr($orig_instruction, strlen($orig_instruction) - 2);
    $instruction      = strrev((string)$program[$i]);
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
            $parameter1 = $program[$i + 1]; 
            break;
        case 1:
            if (!isset($program[$i + 1]))
                $program[$i + 1] = 0;
            $parameter1 = $i + 1;
            break;
        case 2:
            if (!isset($program[$program[$i + 1]]))
                $program[$program[$i + 1]] = 0;
            $parameter1 = $program[$i + 1] + $relative_base;
            break;
    }

    switch($modeOfParameter2) {
        case 0:
            if (!isset($program[$program[$i + 2]]))
                $program[$program[$i + 2]] = 0;
            $parameter2 = $program[$i + 2]; 
            break;
        case 1:
            if (!isset($program[$i + 2]))
                $program[$i + 2] = 0;
            $parameter2 = $i + 2;
            break;
        case 2:
            if (!isset($program[$program[$i + 2]]))
                $program[$program[$i + 2]] = 0;
            $parameter2 = $program[$i + 2] + $relative_base;
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
            $parameter3 = $program[$i + 3] + $relative_base;
            break;
        case 2:
            if (!isset($program[$program[$i + 3]]))
                $program[$program[$i + 3]] = 0;
            $parameter3 = $program[$i + 3] + $relative_base;
            break;
    }

    if ($opcode === 1) {
        $program[$parameter3] = $program[$parameter1] + $program[$parameter2];
        $i += 3;

    } elseif ($opcode === 2) {
        $program[$parameter3] = $program[$parameter1] * $program[$parameter2];
        $i += 3;

    } elseif ($opcode === 3) {
        $robotPositionString = implode(',', $robotPosition);
        if (array_key_exists($robotPositionString, $paintedPoints)) {
            $input = $paintedPoints[$robotPositionString];
        } else {
            $input = 0;
        }
        $program[$parameter1] = $input;
        $i += 1;

    } elseif ($opcode === 4) {
        // echo $parameter1 . PHP_EOL;
        $outputCount++;
        if ($outputCount === 1) {
            $colorToPaint = (int) $program[$parameter1];
            $robotPositionString = implode(',', $robotPosition);
            $paintedPoints[$robotPositionString] = $colorToPaint;
        } elseif ($outputCount === 2) {
            $outputCount = 0;
            $turnDirection = (int) $program[$parameter1];

            // $direction %= 360;
            // if ($direction === 0) {
            //     $robotPosition[0] += 1;
            // } elseif ($direction === 90 || $direction === -270) {
            //     $robotPosition[1] += 1;
            // } elseif ($direction === 180 || $direction === -180) {
            //     $robotPosition[0] -= 1;
            // } elseif ($direction === 270 || $direction === -90) {
            //     $robotPosition[0] += 0;
            //     $robotPosition[1] -= 1; 
            // }

            $direction += $turnDirection === 0 ? 90 : -90;
            $orientationX = (int) (cos(deg2rad($direction)));
            $orientationY = (int) (sin(deg2rad($direction)));
            $robotPosition[0] += $orientationX;
            $robotPosition[1] += $orientationY;
        }
        $i += 1;

    } elseif ($opcode === 5) {
        if ($program[$parameter1] !== 0)
            $i = $program[$parameter2] - 1;
        else
            $i += 2;

    } elseif ($opcode === 6) {
        if ($program[$parameter1] === 0)
            $i = $program[$parameter2] - 1;
        else
            $i += 2;

    } elseif ($opcode === 7) {
        if ($program[$parameter1] < $program[$parameter2])
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;

    } elseif ($opcode === 8) {
        if ($program[$parameter1] === $program[$parameter2])
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;
        
    } elseif ($opcode === 9) {
        $relative_base += $program[$parameter1];
        $i += 1;

    } 
}

echo count($paintedPoints) . PHP_EOL;

fclose($file);
