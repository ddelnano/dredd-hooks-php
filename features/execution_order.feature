Feature: Execution order

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

  @debug
  Scenario:
    Given a file named "hooks/execution_order_hookfile.php" with:
      """
      <?php

      use Dredd\Hooks;

      $key = "hook_modifications";

      Hooks::before('/message > GET', function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "before modification";
      });

      Hooks::after('/message > GET', function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "after modification";
      });

      Hooks::beforeValidation('/message > GET', function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "before validation modification";
      });

      Hooks::beforeAll(function(&$transactions) use ($key) {
          $transactions[0]->key = [];
          $transactions[0]->key[] = "before all modification";
      });

      Hooks::afterAll(function(&$transactions) use ($key) {
          $transactions[0]->key = [];
          $transactions[0]->key[] = "after all modification";
      });

      Hooks::beforeEach(function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "before each modification";
      });

      Hooks::beforeEachValidation(function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "before each validation modification";
      });

      Hooks::afterEach(function(&$transaction) use ($key) {

          $transaction->key = [];
          $transaction->key[] = "after each modification";
      });
      """

    Given I set the environment variables to:
      | variable                       | value      |
      | TEST_DREDD_HOOKS_HANDLER_ORDER | true       |

    When I run `dredd ./apiary.apib http://localhost:4567 --server "ruby server.rb" --language dredd-hooks-php --hookfiles hooks/execution_order_hookfile.php`
    Then the exit status should be 0
    Then the output should contain:
      """
      0 before all modification
      1 before each modification
      2 before modification
      3 before each validation modification
      4 before validation modification
      5 after modification
      6 after each modification
      7 after all modification
      """
