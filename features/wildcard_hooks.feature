Feature: Wildcards in Hooks

  Background:
    Given I have "dredd-hooks-php" command installed
    And I have "dredd" command installed
    And a file named "wildcards.rb" with:
      """
      require 'sinatra'

      post '/categories' do
          [201, '']
      end

      get '/categories/:id' do
          [200, '']
      end

      delete '/categories/:id' do
          [204, '']
      end
      """

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
    Given a file named "hooks/wildcards.php" with:
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
    When I run `dredd ./wildcards.apib http://localhost:4567 --server "ruby wildcards.rb" --language "dredd-hooks-php" --hookfiles hooks/wildcards.php`
#    Thend the exit status should be 1
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
