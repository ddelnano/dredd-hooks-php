Feature: Configurable port

  Background:
    Given I have "dredd-hooks-php" command installed
    And I have "dredd" command installed
    And a file named "server.rb" with:
      """
      require 'sinatra'
      get '/message' do
        "Hello World!\n\n"
      end
      """

    And a file named "apiary.apib" with:
      """
      # My Api
      ## GET /message
      + Response 200 (text/html;charset=utf-8)
          Hello World!
      """

  @announce
  Scenario:
    Given a file named "hooks/execution_order_hookfile.php" with:
      """
      <?php

      use Dredd\Hooks;

      $key = "hooks_modifications";

      Hooks::before('/message > GET', function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "listening on different port";
      });
      """

    Given I set the environment variables to:
      | variable                       | value      |
      | TEST_DREDD_HOOKS_HANDLER_ORDER | true       |

    When I run `dredd ./apiary.apib http://localhost:4567 --hooks-worker-handler-port 61325 --server "ruby server.rb" --language dredd-hooks-php --hookfiles hooks/execution_order_hookfile.php`
    Then the exit status should be 0
    Then the output should contain:
      """
      0 listening on different port
      """
