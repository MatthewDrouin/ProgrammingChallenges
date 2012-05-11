#!/usr/bin/env ruby

require 'prime'

class Array
    def sum
        self.inject{|sum,x| sum + x }
    end
end

def next_prime_fib(n)
  curr = 0
  succ = 1
  
  until succ > n and succ.prime?
    curr, succ = succ, curr + succ
  end
  
  return succ
end

def sum_of_prime_divisors(n)
  array = n.prime_division.flatten.uniq.sort
  return array.sum - 1
end

if ARGV.count == 1
  puts sum_of_prime_divisors(next_prime_fib(ARGV[0].to_i)+1)
else 
  puts "USAGE: next_prime_fib.rb number"
end