Scenario: AIO_ACTIVE_CASES-0 AIO can view "Active Cases" page
    Given I am in the login page

    When I sign in

    Then I see the "Active Cases" page

Scenario: AIO_ACTIVE_CASES-1 AIO can view case
    Given I am in "Active Cases" page

    When I click "View case"

    # TODO: Needs to be done after button works
    # Then I can view the case
