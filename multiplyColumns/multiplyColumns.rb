class MultiplyColumns
  def initialize
    @board = [
      [0, 0, 0, 0, 162],
			[0, 0, 0, 0, 200],
			[0, 0, 0, 0, 147],
			[0, 0, 0, 0, 140],
			[140, 150, 441, 72, 0]
    ]
    @rows = []
    @cols = []
    
    4.times do |i|
      @rows[i] = smartPossibilities(@board[i][4])
      @cols[i] = smartPossibilities(@board[4][i])
    end
  end
  
  def printBoard
    5.times do |row|
      5.times do |col|
        print(' %3d' % @board[row][col])
      end
      puts "\n"
    end
  end
  
  def possibilites(prod)
    res = []
    
    for col1 in 1..10
      for col2 in 1..10
        for col3 in 1..10
          for col4 in 1..10
            if (col1 * col2 * col3 * col4) == prod
              res[] = [col1, col2, col3, col4]
            end
          end
        end
      end
    end
    
    res
  end
  
  def smartPossibilities(prod)
    res = []
    divisors = [1]
    
    for i in 2..10
      if (prod % i) == 0
        divisors.push i
      end
    end
    
    divisors.each do |col1|
      divisors.each do |col2|
        divisors.each do |col3|
          divisors.each do |col4|
            if (col1 * col2 * col3 * col4) == prod
              res.push [col1, col2, col3, col4]
            end
          end
        end
      end
    end
    
    res
  end
  
  def shortest(data)
    row = 0
    length = data[0].length
    
    4.times do |i|
      if data[i].length < length
        row = i
        len = data[i].length
      end
    end
    
    row
  end
  
  def insert(axis, index, data)
    if axis == 'row'
      4.times do |col|
        @board[index][col] = data[col]
      end
    else
      4.times do |row|
        @board[row][index] = data[row]
      end
    end
  end
  
  def fillRow(row)
    if row == 4
      return true
    end
    
    startValue = [@board[row][0], @board[row][1], @board[row][2], @board[row][3]]
    match = true
    
    @rows[row].each do |value|
      match = true
      
      4.times do |col|
        if @board[row][col] != 0 && @board[row][col] != value[col]
          match = false
          break
        end
      end
      
      if match
        insert('row', row, value)
        if fillCol(row)
          return match
        else
          insert('row', row, startValue)
          match = false
        end
      end
    end
    
    match
  end
  
  def fillCol(col)
    startValue = [@board[0][col], @board[1][col], @board[2][col], @board[3][col]]
    match = true
    
    @cols[col].each do |value|
      match = true
      4.times do |row|
        if @board[row][col] != 0 && @board[row][col] != value[row]
          match = false
        end
      end
      
      if match
        insert('col', col, value)
        if fillRow(col + 1)
          return match
        else
          insert('col', col, startValue)
          match = false
        end
      end
    end
    
    match
  end
  
  def main
    shortRow = shortest(@rows)
    shortCol = shortest(@cols)
    found = false
    
    @rows[shortRow].each do |row|
      @cols[shortCol].each do |col|
        if row[shortCol] == col[shortRow]
          insert('row', shortRow, row)
          insert('col', shortCol, col)
          
          if fillRow(0)
            printBoard
            return
          end
        end
      end
    end
  end
end

obj = MultiplyColumns.new
obj.main