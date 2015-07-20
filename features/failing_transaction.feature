Feature: Failing a transaction

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
    Given a file named "hooks/failedhook.php" with:
      """
      <?php

      use Dredd\Hooks;

      Hooks::before("/message > GET", function(&$transaction) {

          $transaction->fail = true;
          echo "Yay! Failed!";
      });
      """
    When I run `dredd ./apiary.apib http://localhost:4567 --server "ruby server.rb" --language "dredd-hooks-php" --hookfiles hooks/failedhook.php`
    Then the exit status should be 1
    And the output should contain:
      """
      Yay! Failed!
      """
