Feature: Multiple hook files with a glob

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
    Given a file named "hooks/hookfile1.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDOUT, "It's me, File1");
            flush();
        });
      """
    And a file named "hooks/hookfile2.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDOUT, "It's me, File2");
            flush();
        });
      """
    And a file named "hooks/hookfile_to_be_globed.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDOUT, "It's me, File3");
            flush();
        });
      """
    When I run `dredd ./apiary.apib http://localhost:4567 --server "ruby server.rb" --language dredd-hooks-php --hookfiles hooks/hookfile1.php --hookfiles hooks/hookfile2.php --hookfiles hooks/hookfile_*.php`
    Then the exit status should be 0
    And the output should contain:
      """
      It's me, File1
      """
    And the output should contain:
      """
      It's me, File2
      """
    And the output should contain:
      """
      It's me, File3
      """