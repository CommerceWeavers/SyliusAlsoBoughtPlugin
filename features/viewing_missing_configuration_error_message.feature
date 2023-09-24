@viewing_error_message
Feature: Viewing missing configuration error message
    In order to see that the plugin is not working properly
    As an Administrator
    I want to see an error message about the problem

    Background:
        Given the store operates on a single channel in the "United States" named "Web"
        And I am logged in as an administrator

    @ui
    Scenario: Viewing error message when there is no bought together product association type
        When I open administration dashboard for "Web" channel
        Then I should see the error message about missing configuration

    @ui
    Scenario: Not viewing error message when there is bought together product association type
        Given the store has a product association type "Bought together" with a code "bought_together"
        When I open administration dashboard for "Web" channel
        Then I should not see the error message about missing configuration
