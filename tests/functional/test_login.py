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

@scenario('login.feature', 'LOGIN-0 Professor can login to web portal')
def test_professor_can_login_to_web_portal(professor):
    """LOGIN-0 Professor can login to web portal."""

@scenario('login.feature', 'LOGIN-1 AIO can login to web portal')
def test_aio_can_login_to_web_portal(aio):
    """LOGIN-1 AIO can login to web portal."""

@scenario('login.feature', 'LOGIN-3 Admin can login to web portal')
def test_admin_can_login_to_web_portal(admin):
    """LOGIN-3 Admin can login to web portal."""

@given('I am in the login page')
def i_am_in_the_login_page(browser):
    """I am in the login page."""

@when('I sign in')
def i_sign_in(browser):
    """I sign in."""

@then('I see the Active Cases page')
def i_see_the_active_cases_page(browser):
    """I see the Active Cases page."""
    assert browser.find_by_text('Active Cases')
