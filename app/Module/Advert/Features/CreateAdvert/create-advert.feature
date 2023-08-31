Feature: Create an advert through API

  As an authenticated user
  I want to create an advert using the API
  So that I can list my items or services for sale

  Scenario: Successful advert creation by an authenticated user
    Given I am already authenticated with valid credentials
    And I have a valid access token
    When I send a POST request to "/adverts" with my advert details and the access token in the Bearer header
    Then the response status code should be 201
