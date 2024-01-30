@viewing_products
Feature: Viewing bought together product's associations
    In order to see which products are frequently bought together with the one I'm currently viewing
    As a Visitor
    I want to see frequently bought together products when viewing product details

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product association type "Bought together" with a code "bought_together"
        And the store has products "Weaver", "Swan", "Swift" and "Bird bath"
        And the store ships everywhere for free
        And the store allows paying with "Cash on delivery"
        And there is a customer "john.doe@example.com" that placed an order
        And the customer bought a single "Weaver", "Swift" and "Bird bath"
        And the customer "John Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        And bought together products are synchronized

    @api @ui
    Scenario: Viewing a detailed page with bought together products
        When I view product "Bird bath"
        Then I should see the product association "Bought together" with products "Weaver" and "Swift"
        And I should not see the product association "Bought together" with product "Swan"

    @api @ui
    Scenario: Viewing a detailed page without bought together products
        When I view product "Swan"
        Then I should not see the product association "Bought together"
