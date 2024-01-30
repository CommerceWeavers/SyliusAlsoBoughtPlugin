@also_bought
Feature: Creating bought together product association type
    In order to setup plugin
    As a Developer
    I want to create bought together product association type

    @cli
    Scenario: Creating bought together association type when there is no bought together product association type
        Given the store operates on a single channel in "United States"
        When I create a bought together product association type
        Then I should be informed that the bought together product association type is created

    @cli
    Scenario: Creating bought together association type when there is already bought together product association type
        Given the store operates on a single channel in "United States"
        And the store has a product association type "Bought together" with a code "bought_together"
        When I create a bought together product association type
        Then I should be informed that the bought together product association type already exists
