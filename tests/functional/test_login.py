import time
import pytest
from selenium.webdriver.common.keys import Keys
import selenium.webdriver.support.ui as ui
from pytest_bdd import (
    given,
    scenario,
    then,
    when,
)
import utility
import conftest as var

@scenario('login.feature', 'AIO-0 Professor can login to web portal')
def test_professor_can_login_to_web_portal(professor):
    """AIO-0 Professor can login to web portal."""

@given('I am in main page as Professor')
def i_am_in_main_page_as_professor(browser):
    """I am in main page as Professor."""

@when('I click the submit button')
def i_click_the_submit_button(browser):
    """I click the submit button."""

@then('I see the Active Cases page')
def i_see_the_active_cases_page(browser):
    """I see the Active Cases page."""
    assert browser.find_by_text('Active Cases')
