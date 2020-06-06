Feature: Multiple hook files with a glob

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
    Given a file named "hookfile1.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDERR, "It's me, File1");
            flush();
        });
      """
    And a file named "hookfile2.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDERR, "It's me, File2");
            flush();
        });
      """
    And a file named "hookfile_to_be_globed.php" with:
      """
        <?php

        use Dredd\Hooks;

        Hooks::before("/message > GET", function($transaction) {

            fprintf(STDERR, "It's me, File3");
            flush();
        });
      """
    When I run `dredd ./apiary.apib http://localhost:4567 --server "node server.js" --language php --hookfiles hookfile1.php --hookfiles hookfile2.php --hookfiles hookfile_*.php --loglevel debug`
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
