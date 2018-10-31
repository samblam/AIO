from pytest_bdd import (
    given,
    scenario,
    then,
    when,
)

@scenario('admin_active_cases.feature', 'ADMIN_ACTIVE_CASE-0 Admin can view "Active Cases" page')
def test_admin_can_view_active_cases_page(admin):
    """ADMIN_ACTIVE_CASE-0 Admin can view "Active Cases" page."""

@scenario('admin_active_cases.feature', 'ADMIN_ACTIVE_CASE-1 Admin can view Case Information')
def test_admin_can_view_case_information(admin):
    """ADMIN_ACTIVE_CASE-1 Admin can view Case Information."""

@scenario('admin_active_cases.feature', 'ADMIN_ACTIVE_CASE-2 Admin can change AIO for a case')
def test_admin_can_change_aio_for_a_case(admin):
    """ADMIN_ACTIVE_CASE-2 Admin can change AIO for a case."""

@given('I am in the login page')
def i_am_in_the_login_page(browser):
    """I am in the login page."""

@given('I am in the "Active Cases" page')
def i_am_in_the_active_cases_page(browser):
    """I am in the "Active Cases" page."""
    assert browser.find_by_text('Active Cases')

@when('I click "View"')
def i_click_view(browser):
    """I click "View"."""
    browser.find_by_xpath('//div[@class="dropdown"]/button[normalize-space()="Actions"]').click()
    browser.find_by_xpath('//tr/td[text()="Mr. Incredible"]/following-sibling::td//a[text()="View"]').click()

@when('I sign in')
def i_sign_in(browser):
    """I sign in."""

@when('I click "Change AIO"')
def i_click_change_AIO(browser):
    """I click "Change AIO"."""
    browser.find_by_xpath('//div[@class="dropdown"]/button[normalize-space()="Actions"]').click()
    browser.find_by_xpath('//tr/td[text()="Mr. Incredible"]/following-sibling::td//a[text()="Change AIO"]').click()

@when('I select a different AIO')
def i_select_a_different_aio(browser):
    """I select a different AIO."""
    browser.find_by_xpath('//select/option[text()="AIO 1"]').click()

@when('I click "Submit"')
def i_click_submit(browser):
    """I click "Submit"."""
    browser.find_by_xpath('//button[text()="Submit"]').click()

@then('I see the "Active Cases" page')
def i_see_the_active_cases_page(browser):
    """I see the 'Active Cases' page."""
    assert browser.find_by_text('Active Cases')

@then('I should see the "Case Information" page')
def i_see_the_case_information_page(browser):
    """I should see the "Case Information" page."""
    assert browser.find_by_text('Case Information')
