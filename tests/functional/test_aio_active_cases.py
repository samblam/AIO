from pytest_bdd import (
    given,
    scenario,
    then,
    when,
)

# As of April 5, 2018: EVERYTHING in this test script is failing because AIO login doesn't work
@scenario('aio_active_cases.feature', 'AIO_ACTIVE_CASES-0 AIO can view "Active Cases" page')
def test_aio_can_view_active_cases_page(aio):
    """AIO_ACTIVE_CASES-0 AIO can view "Active Cases" page."""

@scenario('aio_active_cases.feature', 'AIO_ACTIVE_CASES-1 AIO can view case')
def test_aio_can_view_case(aio):
    """AIO_ACTIVE_CASES-1 AIO can view case."""

@given('I am in the login page')
def i_am_in_the_login_page(browser):
    """I am in the login page."""

@given('I am in "Active Cases" page')
def i_am_in_active_cases_page(browser):
    """I am in "Active Cases" page."""
    assert browser.find_by_text('Active Cases')

@when('I sign in')
def i_sign_in(browser):
    """I sign in."""

@when('I click "View case"')
def i_click_view_case(browser):
    """I click "View case"."""
    browser.find_by_xpath('//tr/td[text()="Moe"]/following-sibling::td/button[text()="View Case"]').click()

@then('I see the "Active Cases" page')
def i_see_the_active_cases_page(browser):
    """I see the 'Active Cases' page."""
    assert browser.find_by_text('Active Cases')
