#!/usr/bin/env ruby

require 'prime'

def nextPrimeFib(n)
  curr = 0
  succ = 1
  
  until succ > n and succ.prime?
    curr, succ = succ, curr + succ
  end
  
  return succ
end

if ARGV.count == 1
  puts nextPrimeFib(ARGV[0].to_i)
else 
  puts "USAGE: next_prime_fib.rb number"
end