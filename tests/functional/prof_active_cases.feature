Scenario: PROF_ACTIVE_CASE-0 Professor can view "Active Cases" page
    Given I am in the login page

    When I sign in

    Then I see the "Active Cases" page

Scenario: PROF_ACTIVE_CASE-1 Professor can submit new case
    Given I am in the "Active Cases" page

    When I click "Submit new case"
    And I fill in information
    And I click the "Submit" button

    # TODO: Needs to be done after form processing works
    # Then I see a form submit success page

Scenario: PROF_ACTIVE_CASE-2 Professor can view case
    Given I am in the "Active Cases" page

    When I click "View case"

    # TODO: Needs to be done after button works
    # Then I can view the case form
