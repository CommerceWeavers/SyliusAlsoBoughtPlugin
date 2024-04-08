@also_bought
Feature: Synchronizing bought together products
    In order to have bought together products up to date
    As a Developer
    I want to synchronize bought together products with orders

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store allows paying with "Cash on delivery"

    @cli
    Scenario: Synchronizing bought together products
        Given the store has a product association type "Bought together" with a code "bought_together"
        And the store has products "Weaver", "Swan", "Swift", "Grains", "Twigs", "Bird feeder", "Bird bath" and "Bird netting"
        And there is a customer "john.doe@example.com" that placed an order
        And the customer bought a single "Weaver", "Bird bath" and "Bird netting"
        And the customer bought 100 "Grains" products
        And the customer bought 500 "Twigs" products
        And the customer "John Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        And there is another customer "john.galt@example.com" that placed an order
        And the customer bought a single "Swan"
        And the customer bought 200 "Grains" products
        And the customer bought 20 "Twigs" products
        And the customer "John Galt" addressed it to "Atlas Way", "385" "Libertyville" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        And there is another customer "jane.doe@example.com" that placed an order
        And the customer bought a single "Swift", "Bird feeder", "Bird bath" and "Bird netting"
        And the customer bought 100 "Grains" products
        And the customer bought 100 "Twigs" products
        And the customer "Jane Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        When I synchronize bought together products by running command
        Then I should be informed that the bought together products are synchronized
        And the "Weaver" product should have usually been bought with "Grains", "Twigs", "Bird bath" and "Bird netting"
        And the "Swan" product should have usually been bought with "Grains" and "Twigs"
        And the "Grains" product should have usually been bought with "Twigs", "Bird bath", "Bird netting", "Weaver" and "Swan"
        And the "Bird feeder" product should have usually been bought with "Grains", "Twigs", "Bird bath", "Bird netting" and "Swift"

    @cli
    Scenario: Synchronizing maximum number of bought together products
        Given the channel "United States" has 2 configured as number of synchronizable bought together products
        And the store has a product association type "Bought together" with a code "bought_together"
        And the store has products "Weaver", "Swan", "Swift", "Grains", "Twigs", "Bird feeder", "Bird bath" and "Bird netting"
        And there is a customer "john.doe@example.com" that placed an order
        And the customer bought a single "Weaver", "Bird bath" and "Bird netting"
        And the customer bought 100 "Grains" products
        And the customer bought 500 "Twigs" products
        And the customer "John Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        And there is another customer "john.galt@example.com" that placed an order
        And the customer bought a single "Swan"
        And the customer bought 200 "Grains" products
        And the customer bought 20 "Twigs" products
        And the customer "John Galt" addressed it to "Atlas Way", "385" "Libertyville" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        And there is another customer "jane.doe@example.com" that placed an order
        And the customer bought a single "Swift", "Bird feeder", "Bird bath" and "Bird netting"
        And the customer bought 100 "Grains" products
        And the customer bought 100 "Twigs" products
        And the customer "Jane Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        When I synchronize bought together products by running command
        Then I should be informed that the bought together products are synchronized
        And the "Weaver" product should have usually been bought with "Grains" and "Twigs"
        And the "Swan" product should have usually been bought with "Grains" and "Twigs"
        And the "Grains" product should have usually been bought with "Twigs" and "Bird bath"
        And the "Bird feeder" product should have usually been bought with "Grains" and "Twigs"

    @cli
    Scenario: Being notified about specific association type requirement
        Given the store has a product "Weaver"
        And there is a customer "john.doe@example.com" that placed an order
        And the customer bought a single "Weaver"
        And the customer "John Doe" addressed it to "Elm Street", "90802" "Anytown" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on delivery" payment
        And this order is already paid
        When I synchronize bought together products by running command
        Then I should be informed that bought together association type not found
