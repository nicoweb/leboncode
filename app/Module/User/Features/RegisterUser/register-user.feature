Feature: User registration through API

  As a new user
  I want to register an account using the API
  So that I can access member-only features through API clients

  Scenario: Successful user registration
    Given I send a POST request to "/users" with valid registration data
    Then the response status code should be 204
