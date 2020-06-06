Feature: Wildcards in Hooks

  Background:
    Given I have dredd-hooks-php installed
    Given I have Dredd installed
    And a file "server.js" with a server responding on "http://localhost:4567" to wildcard hooks
    And a file named "wildcards.apib" with:
      """
      FORMAT: 1A

      # Categories API

      ## Categories [/categories]

      ### Create a category [POST]
      + Response 201

      ## Categories [/categories/{id}]
      + Parameters
        + id (required, `42`)

      ### Delete a category [DELETE]
      + Response 204

      ### Show a category [GET]
      + Response 200
      """

  @announce
  Scenario:
    Given a file named "wildcards.php" with:
      """
      <?php

      use Dredd\Hooks;

      global $a;
      $a = 1;

      Hooks::before("Categories > *", function(&$transaction) {

          global $a;
          echo "Wildcards are fun! For the {$a} time!";
          $a++;
      });
      """
    When I run `dredd ./wildcards.apib http://localhost:4567 --server "node server.js" --language php --hookfiles wildcards.php --loglevel debug`
    And the output should contain:
      """
      Wildcards are fun! For the 1 time!
      """
    And the output should contain:
      """
      Wildcards are fun! For the 2 time!
      """
    And the output should contain:
      """
      Wildcards are fun! For the 3 time!
      """
