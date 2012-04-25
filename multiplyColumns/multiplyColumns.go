package main

import (
	"flag"
	"fmt"
	"os"
	"log"
	"runtime/pprof"
)

// profiling
var cpuprofile = flag.String("cpuprofile", "", "write cpu profile to file")

var rows [4][][4]int // an array containing a slice which contains an array
var cols [4][][4]int
var board [5][5]int // an array containing an array

func start() {
	board[0][4] = 162
	board[1][4] = 200
	board[2][4] = 147
	board[3][4] = 140
	board[4][0] = 140
	board[4][1] = 150
	board[4][2] = 441
	board[4][3] = 72

	for i := 0; i < 4; i++ {
		rows[i] = possibilities(board[i][4])
		cols[i] = possibilities(board[4][i])
	}
}

func printBoard() {
	for row := 0; row < 5; row++ {
		for col := 0; col < 5; col++ {
			fmt.Printf(" %3d", board[row][col])
		}
		fmt.Println()
	}
}

// could find a better way to get the possibilities I think
func possibilities(prod int) (res [][4]int) {
	for col1 := 1; col1 < 10; col1++ {
		for col2 := 1; col2 < 10; col2++ {
			for col3 := 1; col3 < 10; col3++ {
				for col4 := 1; col4 < 10; col4++ {
					if col1*col2*col3*col4 == prod {
						res = append(res, [4]int{col1, col2, col3, col4})
					}
				}
			}
		}
	}
	return
}

func smartPossibilities(prod int) (res [][4]int) {
	var divisors []int = make([]int, 9)

	divisors = append(divisors, 1)

	for i := 2; i < 10; i++ {
		if prod%i == 0 {
			divisors = append(divisors, i)
		}
	}

	for _, col1 := range divisors {
		for _, col2 := range divisors {
			for _, col3 := range divisors {
				for _, col4 := range divisors {
					if col1*col2*col3*col4 == prod {
						//fmt.Printf("%3d %3d %3d %3d %3d\n", col1, col2, col3, col4, prod)
						res = append(res, [4]int{col1, col2, col3, col4})
					}
				}
			}
		}
	}
	return
}

func shortest(array [4][][4]int) (row int) {
	length := len(array[0])
	for key, value := range array {
		if len(value) < length {
			row = key
			length = len(value)
		}
	}
	return
}

func insert(axis string, index int, array [4]int) {
	if axis == "row" {
		for col := 0; col < 4; col++ {
			board[index][col] = array[col]
		}
	} else {
		for row := 0; row < 4; row++ {
			board[row][index] = array[row]
		}
	}
}

func fillRow(row int) (match bool) {

	// this is kinda hacky
	if row == 4 {
		match = true
		return
	}

	startValue := [4]int{board[row][0], board[row][1], board[row][2], board[row][3]}
	for _, value := range rows[row] {
		match = true
		for col := 0; col < 4 && match; col++ {
			if board[row][col] != 0 && board[row][col] != value[col] {
				match = false
			}
		}
		if match {
			insert("row", row, value)
			if fillCol(row) {
				return
			} else {
				insert("row", row, startValue)
				match = false
			}
		}
	}
	return
}

func fillCol(col int) (match bool) {
	startValue := [4]int{board[0][col], board[1][col], board[2][col], board[3][col]}
	for _, value := range cols[col] {
		match = true
		for row := 0; row < 4 && match; row++ {
			if board[row][col] != 0 && board[row][col] != value[row] {
				match = false
			}
		}
		if match {
			insert("col", col, value)
			if fillRow(col + 1) {
				return
			} else {
				insert("col", col, startValue)
				match = false
			}
		}
	}
	return
}

func main() {

	flag.Parse()
	if *cpuprofile != "" {
		f, err := os.Create(*cpuprofile)
		if err != nil {
			log.Fatal(err)
		}
		pprof.StartCPUProfile(f)
		defer pprof.StopCPUProfile()
	}

	start()
	shortRow := shortest(rows)
	shortCol := shortest(cols)
	found := false

	for row := 0; row < len(rows[shortRow]) && !found; row++ {
		for col := 0; col < len(cols[shortCol]) && !found; col++ {
			if rows[shortRow][row][shortCol] == cols[shortCol][col][shortRow] {
				insert("row", shortRow, rows[shortRow][row])
				insert("col", shortCol, cols[shortCol][col])
				if fillRow(0) {
					printBoard()
					found = true
				}
			}
		}
	}
}