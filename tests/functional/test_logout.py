import time
from pytest_bdd import (
    given,
    scenario,
    then,
    when,
)

@scenario('logout.feature', 'LOGOUT-0 Professor can logout from web portal')
def test_professor_can_logout_from_web_portal(professor):
    """LOGOUT-0 Professor can logout from web portal."""

# As of April 5, 2018: This is failing because login for AIO doesn't work, therefore logout button doesn't exists -> error thrown
@scenario('logout.feature', 'LOGOUT-1 AIO can logout from web portal')
def test_aio_can_logout_from_web_portal(aio):
    """LOGOUT-1 AIO can logout from web portal."""

# As of April 5, 2018: This is failing because logout button doesn't work
@scenario('logout.feature', 'LOGOUT-2 Admin can logout from web portal')
def test_admin_can_logout_from_web_portal(admin):
    """LOGOUT-2 Admin can logout from web portal."""

@given('I am logged in')
def i_am_logged_in(browser):
    """I am logged in."""

@when('I click the logout button')
def i_click_the_logout_button(browser):
    """I click the logout button."""
    time.sleep(1)
    browser.find_by_xpath('//button[@type="button" and text()="Logout"]').click()

@then('I am back at the login page')
def i_am_back_at_the_login_page(browser):
    """I am back at the login page."""
    assert browser.find_by_text('Login')
