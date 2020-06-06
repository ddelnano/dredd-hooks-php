Feature: Configuring the server

  @announce
  Scenario: Starting the server with --force flag if port is blocked
    Given I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
    Then I run "dredd-hooks-php --force" interactively, I wait for output to contain "Starting"

  @announce
  Scenario: Starting the server on non standard port
    Given I run "dredd-hooks-php --port 1123" interactively, I wait for output to contain "Starting"
    Then It should start listening on localhost port 1123

