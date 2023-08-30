Feature: User authentication through API

  As an existing user
  I want to authenticate using the API
  So that I can access member-only features through API clients

  Scenario: Successful user authentication
    Given I have previously registered with valid credentials
    When I send a POST request to "/login" with those credentials
    Then the response status code should be 200
    And I should receive an access token
    And I should receive a refresh token
