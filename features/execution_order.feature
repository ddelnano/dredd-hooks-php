Feature: Execution order

  Background:
    Given I have dredd-hooks-php installed
    And I have Dredd installed
    And a file "server.js" with a server responding on "http://localhost:4567/message" with "Hello World!"
    And a file named "apiary.apib" with:
      """
      # My Api
      ## GET /message
      + Response 200 (text/html)
      """

  @announce
  Scenario:
    Given a file named "execution_order_hookfile.php" with:
      """
      <?php

      use Dredd\Hooks;

      $key = "hooks_modifications";

      Hooks::before('/message > GET', function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "before modification";
      });

      Hooks::after('/message > GET', function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "after modification";
      });

      Hooks::beforeValidation('/message > GET', function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "before validation modification";
      });

      Hooks::beforeAll(function(&$transactions) use ($key) {

          if ( ! property_exists($transactions[0], $key)) $transactions[0]->{$key} = [];

          $transactions[0]->{$key}[] = "before all modification";
      });

      Hooks::afterAll(function(&$transactions) use ($key) {

          if ( ! property_exists($transactions[0], $key)) $transactions[0]->{$key} = [];

          $transactions[0]->{$key}[] = "after all modification";
      });

      Hooks::beforeEach(function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "before each modification";
      });

      Hooks::beforeEachValidation(function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "before each validation modification";
      });

      Hooks::afterEach(function(&$transaction) use ($key) {

          if ( ! property_exists($transaction, $key)) $transaction->{$key} = [];

          $transaction->{$key}[] = "after each modification";
      });
      """

    Given I set the environment variables to:
      | variable                       | value      |
      | TEST_DREDD_HOOKS_HANDLER_ORDER | true       |

    When I run `dredd ./apiary.apib http://localhost:4567 --server "node server.js" --language php --hookfiles=./execution_order_hookfile.php`
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
