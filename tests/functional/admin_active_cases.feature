Scenario: ADMIN_ACTIVE_CASE-0 Admin can view "Active Cases" page
    Given I am in the login page

    When I sign in

    Then I see the "Active Cases" page

Scenario: ADMIN_ACTIVE_CASE-1 Admin can view Case Information
    Given I am in the "Active Cases" page

    When I click "View"

    Then I should see the "Case Information" page

Scenario: ADMIN_ACTIVE_CASE-2 Admin can change AIO for a case
    Given I am in the "Active Cases" page

    When I click "Change AIO"
    And I select a different AIO
    And I click "Submit"

    # TODO: Needs to be done after changing AIO actually does something/submit button works
    # Then I should see a success message

Scenario: ADMIN_ACTIVE_CASE- Admin can delete a case
