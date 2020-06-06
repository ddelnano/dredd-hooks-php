@skip
Feature: TCP server and messages

Scenario: TCP server
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  Then it should start listening on localhost port 61321

Scenario: Message exchange for event beforeEach
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  And I connect to the server
  And I send a JSON message to the socket:
    """
    {"event": "beforeEach", "uuid": "1234-abcd", "data": {"key":"value"}}
    """
  And I send a newline character as a message delimiter to the socket
  Then I should receive the same response
  And I should be able to gracefully disconnect

Scenario: Message exchange for event beforeEachValidation
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  And I connect to the server
  And I send a JSON message to the socket:
    """
    {"event": "beforeEachValidation", "uuid": "2234-abcd", "data": {"key":"value"}}
    """
  And I send a newline character as a message delimiter to the socket
  Then I should receive the same response
  And I should be able to gracefully disconnect

Scenario: Message exchange for event afterEach
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  And I connect to the server
  And I send a JSON message to the socket:
    """
    {"event": "afterEach", "uuid": "3234-abcd", "data": {"key":"value"}}
    """
  And I send a newline character as a message delimiter to the socket
  Then I should receive the same response
  And I should be able to gracefully disconnect

Scenario: Message exchange for event beforeAll
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  And I connect to the server
  And I send a JSON message to the socket:
    """
    {"event": "beforeAll", "uuid": "4234-abcd", "data": {"key":"value"}}
    """
  And I send a newline character as a message delimiter to the socket
  Then I should receive the same response
  And I should be able to gracefully disconnect

Scenario: Message exchange for event afterAll
  Given I have dredd-hooks-php installed
  When I run "dredd-hooks-php" interactively, I wait for output to contain "Starting"
  And I connect to the server
  And I send a JSON message to the socket:
    """
    {"event": "afterAll", "uuid": "5234-abcd", "data": {"key":"value"}}
    """
  And I send a newline character as a message delimiter to the socket
  Then I should receive the same response
  And I should be able to gracefully disconnect
