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
    Given a file named "failedhook.php" with:
      """
      <?php

      use Dredd\Hooks;

      Hooks::before("/message > GET", function(&$transaction) {

          $transaction->fail = true;
          echo "Yay! Failed!";
          flush();
      });
      """
    When I run `dredd ./apiary.apib http://localhost:4567 --server "node server.js" --language php --hookfiles failedhook.php --loglevel debug`
    Then the exit status should be 1
    And the output should contain:
      """
      Yay! Failed!
      """
