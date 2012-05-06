# Programming Challenge: Place smaller rectangles inside of a larger rectangle

##The input and analysis script
This challenge uses a Ruby script to generate data for the challenge, and to analyze your solution's output (you're not required to write your own code in Ruby, though). The [*rects.rb*](http://lafarren.com/rects/rects.rb) script does two things.

When run without passing anything from stdin, the script will write JSON to stdout. The JSON contains data that your program should parse and use for solving. Included in the output is the random seed, the container's width and height, and the list of subrects. Each subrect has a width and height, and an alphanumeric associated with that subrect.

After your program has finished placing sub-rectangles, it should output the result in a format that can be fed back into the rects.rb script. To see the format, run:

	$ ./rects.rb -h

The rects.rb will read the solution via stdin, validate it (a subrect, if present, must be of the expected size, and appear only once), and analyze the efficiency of the solution.

The example [*driver.rb*](http://lafarren.com/rects/driver.rb) script can be used to try out the analysis part of the script. It solves the problem with poor efficiency (only occupying about 55-60% of the default 64x64 container) by randomly placing the sub-rectangles. The driver reads the problem data from stdin, but your program can just read from a file if that's easier.


##Tips
By default, the seed is chosen based on the current system time, but a fixed seed can be passed in via the command line. This is useful while writing your solution so that you're working with a fixed set of subrects. Once your solution is working, it's better to not specify the fixed seed, and run multiple times to get a sense of the average efficiency.

The default container size is 64x64, but a smaller size can be specified on the command line, in case that's easier to develop with.