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

@scenario('login.feature', 'AIO-0 AIO can login to web portal')
def test_aio_can_login_to_web_portal():
    """AIO-0 AIO can login to web portal."""

@scenario('login.feature', 'AIO-1 AIO can login to web portal')
def test_aio_can_login_to_web_portal_1():
    """AIO-1 AIO can login to web portal."""

@given('I am in main page')
def i_am_in_main_page(browser):
    """I am in main page."""
    time.sleep(1)
    browser.visit('http://www.google.co.th')

@when('I click on the "About" button')
def i_click_on_the_about_button(browser):
    """I click on the "About" button."""
    time.sleep(2)
    browser.find_by_text('About').click()
    time.sleep(2)


@then('I see "Our latest"')
def i_see_our_latest(browser):
    """I see "Our latest"."""
    time.sleep(2)
    assert browser.find_by_xpath('//a[@title="Our latest"]')
