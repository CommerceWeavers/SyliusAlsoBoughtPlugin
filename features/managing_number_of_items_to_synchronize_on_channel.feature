@managing_channels
Feature: Managing number of items to synchronize on channel
    In order to change the number of items that are synchronised as bought together products
    As an Administrator
    I want to be able to edit their number on channel configuration

    Background:
        Given the store operates on a channel named "Web Channel"
        And I am logged in as an administrator

    @ui
    Scenario: Seeing the default number of synchronised products
        When I want to modify a channel "Web Channel"
        Then the number of synchronised products should be 10

    @ui
    Scenario: Modifying the number of synchronised products
        When I want to modify a channel "Web Channel"
        And I change the number of synchronised products to 5
        And I save my changes
        Then I should be notified that it has been successfully edited
        And the number of synchronised products should be 5
