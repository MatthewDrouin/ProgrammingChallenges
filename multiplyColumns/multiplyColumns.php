<?php

class multiplyColumns {
    
    protected $rows = array(); // [4][][4]int // an array containing a slice which contains an array
    protected $cols = array(); //[4][][4]int
    protected $board = array(); //[5][5]int // an array containing an array
    
    function __construct() {
        $this->board[0] = array(0, 0, 0, 0, 162);
        $this->board[1] = array(0, 0, 0, 0, 200);
        $this->board[2] = array(0, 0, 0, 0, 147);
        $this->board[3] = array(0, 0, 0, 0, 140);
        $this->board[4] = array(140, 150, 441, 72, 0);
    
        for ($i = 0; $i < 4; $i++) {
            $this->rows[$i] = $this->smartPossibilities($this->board[$i][4]);
            $this->cols[$i] = $this->smartPossibilities($this->board[4][$i]);
        }
    }
    
    function printBoard() {
        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++) {
                printf(" %3d", $this->board[$row][$col]);
            }
            print("\n");
        }
    }
    
    // could find a better way to get the possibilities I think
    function possibilities($prod) {
        
        $res = array();
        
        for ($col1 = 1; $col1 < 10; $col1++) {
            for ($col2 = 1; $col2 < 10; $col2++) {
                for ($col3 = 1; $col3 < 10; $col3++) {
                    for ($col4 = 1; $col4 < 10; $col4++) {
                        if ($col1*$col2*$col3*$col4 == $prod) {
                            $res[] = array($col1, $col2, $col3, $col4);
                        }
                    }
                }
            }
        }
        return $res;
    }
    
    // Trying to use only the divisors in the for loop
    // for generating the solutions but it seems to be slower
    // than just doing 1..9 in the for loop
    // This actually cuts down on the runtime by about 0.006s so from 0.077 to 0.071
    function smartPossibilities($prod) {
    
        $res = array();
        
        $divisors[] = 1;
    
        for ($i = 2; $i < 10; $i++) {
            if ($prod%$i == 0) {
                $divisors[] = $i;
            }
        }
    
        foreach ($divisors AS $col1) {
            foreach ($divisors AS $col2) {
                foreach ($divisors AS $col3) {
                    foreach ($divisors AS $col4) {
                        if ($col1*$col2*$col3*$col4 == $prod) {
                            $res[] = array($col1, $col2, $col3, $col4);
                        }
                    }
                }
            }
        }
        return $res;
    }
    
    function shortest($data) {
        $row = 0;
        $length = count($data[0]);
        
        for ($i = 1; $i < 4; $i++) {
            if (count($data[$i]) < $length) {
                $row = $i;
                $length = count($data[$i]);
            }
        }
        return $row;
    }
    
    function insert($axis, $index, $data) {
        if ($axis == "row") {
            for ($col = 0; $col < 4; $col++) {
                $this->board[$index][$col] = $data[$col];
            }
        } else {
            for ($row = 0; $row < 4; $row++) {
                $this->board[$row][$index] = $data[$row];
            }
        }
    }
    
    function fillRow($row) {
    
        // this is kinda hacky
        if ($row == 4) {
            $match = true;
            return $match;
        }
    
        $startValue = array($this->board[$row][0], $this->board[$row][1], $this->board[$row][2], $this->board[$row][3]);
        
        foreach ($this->rows[$row] As $value) {
            $match = true;
            for ($col = 0; $col < 4 && $match; $col++) {
                if ($this->board[$row][$col] != 0 && $this->board[$row][$col] != $value[$col]) {
                    $match = false;
                }
            }
            if ($match) {
                $this->insert("row", $row, $value);
                if ($this->fillCol($row)) {
                    return $match;
                } else {
                    $this->insert("row", $row, $startValue);
                    $match = false;
                }
            }
        }
        return $match;
    }
    
    function fillCol($col) {
        
        $startValue = array($this->board[0][$col], $this->board[1][$col], $this->board[2][$col], $this->board[3][$col]);
        
        foreach ($this->cols[$col] AS $value) {
            $match = true;
            for ($row = 0; $row < 4 && $match; $row++) {
                if ($this->board[$row][$col] != 0 && $this->board[$row][$col] != $value[$row]) {
                    $match = false;
                }
            }
            if ($match) {
                $this->insert("col", $col, $value);
                if ($this->fillRow($col + 1)) {
                    return $match;
                } else {
                    $this->insert("col", $col, $startValue);
                    $match = false;
                }
            }
        }
        return $match;
    }
    
    function main() {
    
        $shortRow = $this->shortest($this->rows);
        $shortCol = $this->shortest($this->cols);
        $found = false;
    
        for ($row = 0; $row < count($this->rows[$shortRow]) && !$found; $row++) {
            for ($col = 0; $col < count($this->cols[$shortCol]) && !$found; $col++) {
                if ($this->rows[$shortRow][$row][$shortCol] == $this->cols[$shortCol][$col][$shortRow]) {
                    $this->insert("row", $shortRow, $this->rows[$shortRow][$row]);
                    $this->insert("col", $shortCol, $this->cols[$shortCol][$col]);
                    if ($this->fillRow(0)) {
                        $this->printBoard();
                        $found = true;
                    }
                }
            }
        }
    }
}

$Obj = new multiplyColumns();
$Obj->main();
