#!/usr/bin/env ruby
# Use: gem install json
require 'json'

# Parse the JSON from stdin, and extract the values.
raise "Expects input from stdin" if ($stdin.tty?)
parsed = JSON.parse($stdin.read)

seed = parsed["seed"]
container_width = parsed["width"]
container_height = parsed["height"]
subrects = parsed["subrects"]

container = {}

fit = lambda do |char, width, height|
  can_fit = lambda do |x, y|
    if (x + width > container_width || y + height > container_height)
      return false
    end
    (0..height - 1).each do |offset_y|
      (0..width - 1).each do |offset_x|
        if (container[[x + offset_x, y + offset_y]])
          # Container cell was non-nil; something else has claimed that cell.
          return false
        end
      end
    end
    true
  end
  
  claim = lambda do |x, y|
    (0..height - 1).each do |offset_y|
      (0..width - 1).each do |offset_x|
        container[[x + offset_x, y + offset_y]] = char
      end
    end
  end
  
  xs = (0..container_width - 1).to_a
  ys = (0..container_height - 1).to_a
  ys.each do |y|
    xs.each do |x|
      if (can_fit.call(x, y))
        claim.call(x, y)
        return
      end
    end
  end
end

# sort array by height and width
subrects.sort_by! { |subrect| [-subrect["height"], -subrect["width"]] }.each do |subrect|
  char = subrect["char"]
  width = subrect["width"]
  height = subrect["height"]
  fit.call(char, width, height)
end

# Output data that can be fed back into rects.rb for analysis.
puts "seed: #{seed}"
puts "size: #{container_width}x#{container_height}"
(0..container_height - 1).each do |y|
  (0..container_width - 1).each do |x|
    char = container[[x, y]]
    printf(char ? char : ".")
  end
  puts
end


