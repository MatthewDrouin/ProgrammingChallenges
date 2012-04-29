class MultiplyColumns:
	rows = [0, 0, 0, 0]
	cols = [0, 0, 0, 0]
	board = []
	
	def __init__(self):
		self.board = [
			[0, 0, 0, 0, 162],
			[0, 0, 0, 0, 200],
			[0, 0, 0, 0, 147],
			[0, 0, 0, 0, 140],
			[140, 150, 441, 72, 0]
		]
		
		for i in range(0, 4):
			self.rows[i] = self.smartPossibilities(self.board[i][4])
			self.cols[i] = self.smartPossibilities(self.board[4][i])
	
	def printBoard(self):
		for row in range(0, 5):
			for col in range(0, 5):
				print(" %3d" % self.board[row][col]),
			print("")
	
	def possibilities(self, prod):
		res = []
		
		for col1 in xrange(1, 10):
			for col2 in xrange(1, 10):
				for col3 in xrange(1, 10):
					for col4 in xrange(1, 10):
						if (col1 * col2 * col3 * col4) == prod:
							res.append([col1, col2, col3, col4])
		
		return res
	
	def smartPossibilities(self, prod):
		res = []
		divisors = [1]
		
		for i in xrange(2, 10):
			if (prod % i) == 0:
				divisors.append(i)
		
		for col1 in divisors:
			for col2 in divisors:
				for col3 in divisors:
					for col4 in divisors:
						if (col1 * col2 * col3 * col4) == prod:
							res.append([col1, col2, col3, col4])
		
		return res
	
	def shortest(self, data):
		row = 0
		length = len(data[0])
		
		for i in xrange(1, 4):
			if len(data[i]) < length:
				row = i
				length = len(data[i])
		
		return row
	
	def insert(self, axis, index, data):
		if axis == 'row':
			for col in xrange(0, 4):
				self.board[index][col] = data[col]
		else:
			for row in xrange(0, 4):
				self.board[row][index] = data[row]
	
	def fillRow(self, row):
		# this is kinda hacky
		if row == 4:
			return True
		
		startValue = [self.board[row][0], self.board[row][1], self.board[row][2], self.board[row][3]]
		
		for value in self.rows[row]:
			match = True
			
			for col in xrange(0, 4):
				if self.board[row][col] != 0 and self.board[row][col] != value[col]:
					match = False
					break
			
			if match:
				self.insert('row', row, value)
				if self.fillCol(row):
					return match
				else:
					self.insert('row', row, startValue)
					match = False
		
		return match
	
	def fillCol(self, col):
		startValue = [self.board[0][col], self.board[1][col], self.board[2][col], self.board[3][col]]
		
		for value in self.cols[col]:
			match = True
			
			for row in xrange(0, 4):
				if self.board[row][col] != 0 and self.board[row][col] != value[row]:
					match = False
					break
			
			if match:
				self.insert('col', col, value)
				if self.fillRow(col + 1):
					return match
				else:
					self.insert('col', col, startValue)
					match = False
		
		return match
	
	def main(self):
		shortRow = self.shortest(self.rows)
		shortCol = self.shortest(self.cols)
		
		for row in self.rows[shortRow]:
			for col in self.cols[shortCol]:
				if row[shortCol] == col[shortRow]:
					self.insert('row', shortRow, row)
					self.insert('col', shortCol, col)
					if self.fillRow(0):
						self.printBoard()
						return


obj = MultiplyColumns()
obj.main()