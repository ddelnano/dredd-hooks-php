Feature: Failing a transaction

  Background:
    Given I have dredd-hooks-php installed
    Given I have Dredd installed
    And a file named "apiary.apib" with:
      """
      # My Api
      ## GET /message
      + Response 200 (text/html)
      """
    And a file "server.js" with a server responding on "http://localhost:4567/message" with "Hello World!"

  @announce
  Scenario:
    Given a file named "hook_transaction_object.php" with:
      """
      <?php

      use Dredd\DataObjects\Transaction;
      use Dredd\Hooks;

      Hooks::beforeEach(function(Transaction &$transaction) {

          echo 'Transaction object';
      });
      """
    When I run `dredd ./apiary.apib http://localhost:4567 --server "node server.js" --language php --hookfiles hook_transaction_object.php --loglevel debug`
    Then the output should contain:
      """
      Transaction object
      """
