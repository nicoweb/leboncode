Feature: Retrieve an advert through API

  As an authenticated user
  I want to retrieve an advert using the API
  So that I can view details of items or services for sale

  Scenario: Successful retrieval of an advert by an authenticated user
    Given I am already authenticated with valid credentials
    And I have a valid access token
    And an advert with ID "b6d4090a-aa0f-481e-ba57-3dbc18c82450" exists
    When I send a GET request to "/adverts/b6d4090a-aa0f-481e-ba57-3dbc18c82450" with the access token in the Bearer header
    Then the response status code should be 200
    And the response should contain the advert details
