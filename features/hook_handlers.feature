Feature: Hook handlers

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
    Given a file named "hookfile.php" with:
      """
      <?php

      $key = '';
      use Dredd\Hooks;

      Hooks::before('/message > GET', function(&$transaction) use ($key) {

          fprintf(STDERR, "before hook handled");
          flush();
      });

      Hooks::after('/message > GET', function(&$transaction) use ($key) {

          fprintf(STDERR, "after hook handled");
          flush();
      });

      Hooks::beforeValidation('/message > GET', function(&$transaction) use ($key) {

          echo 'before validation hook handled';
      });

      Hooks::beforeAll(function(&$transaction) use ($key) {

          echo 'before all hook handled';
      });

      Hooks::afterAll(function(&$transaction) use ($key) {

          echo 'after all hook handled';
      });

      Hooks::beforeEach(function(&$transaction) use ($key) {

          echo 'before each hook handled';
      });

      Hooks::beforeEachValidation(function(&$transaction) use ($key) {

          echo 'before each validation hook handled';
      });

      Hooks::afterEach(function(&$transaction) use ($key) {

          echo 'after each hook handled';
      });
      """

    When I run `dredd ./apiary.apib http://localhost:4567 --server "node server.js" --language php --hookfiles ./hookfile.php --loglevel debug`
    Then the exit status should be 0
    Then the output should contain:
      """
      before hook handled
      """
    And the output should contain:
      """
      before validation hook handled
      """
    And the output should contain:
      """
      after hook handled
      """
    And the output should contain:
      """
      before each hook handled
      """
    And the output should contain:
      """
      before each validation hook handled
      """
    And the output should contain:
      """
      after each hook handled
      """
    And the output should contain:
      """
      before all hook handled
      """
    And the output should contain:
      """
      after all hook handled
      """
