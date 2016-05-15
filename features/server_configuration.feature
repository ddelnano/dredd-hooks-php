Feature: Configuring the server

  @announce
  Scenario: Starting the server with --force flag if port is blocked
    Given I run `dredd-hooks-php` interactively
    Then I run `dredd-hooks-php --force` interactively

  @announce
  Scenario: Starting the server on non standard port
    Given I run `dredd-hooks-php --port 123` interactively
    Then It should start listening on localhost port "123"

