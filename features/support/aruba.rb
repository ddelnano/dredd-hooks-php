require 'aruba/api'

Aruba.configure do |config|
  config.io_wait_timeout = 15
  config.exit_timeout = 15
  $stderr.puts 'Hello world'
end
