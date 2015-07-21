Feature: Hook handlers

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
  @announce-output
  Scenario:
    Given a file named "hookfile.php" with:
      """
      <?php

      $key = '';
      use Dredd\Hooks;

      Hooks::before('/message > GET', function(&$transaction) use ($key) {

          var_dump("before hook handled");
      });

      Hooks::after('/message > GET', function(&$transaction) use ($key) {

          echo "after hook handled";
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

    When I run `dredd ./apiary.apib http://localhost:4567 --server "ruby server.rb" --language dredd-hooks-php --hookfiles ./hookfile.php`
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
