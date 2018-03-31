Scenario: LOGIN-0 Professor can login to web portal
    Given I am in the login page

    When I sign in

    Then I see the Active Cases page

Scenario: LOGIN-1 AIO can login to web portal
    Given I am in the login page

    When I sign in

    Then I see the Active Cases page

Scenario: LOGIN-3 Admin can login to web portal
    Given I am in the login page

    When I sign in

    Then I see the Active Cases page
