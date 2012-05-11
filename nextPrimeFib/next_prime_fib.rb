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
  return n.prime_division.flatten.uniq.sort.sum - 1
end

if ARGV.count == 1
  next_prime = next_prime_fib(ARGV[0].to_i)
  sum_of = sum_of_prime_divisors(next_prime + 1)
  puts "You gave: #{ARGV[0].to_i}"
  puts "Next prime fibonacci number is: #{next_prime}"
  puts "Sum of prime divisors for #{next_prime + 1} is: #{sum_of}"
else 
  puts "USAGE: next_prime_fib.rb number"
end