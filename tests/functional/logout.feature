Scenario: LOGOUT-0 Professor can logout from web portal
    Given I am logged in

    When I click the logout button

    Then I am back at the login page

Scenario: LOGOUT-1 AIO can logout from web portal
    Given I am logged in

    When I click the logout button

    Then I am back at the login page

Scenario: LOGOUT-2 Admin can logout from web portal
    Given I am logged in

    When I click the logout button

    Then I am back at the login page
