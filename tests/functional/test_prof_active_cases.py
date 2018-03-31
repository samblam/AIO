import time
from pytest_bdd import (
    given,
    scenario,
    then,
    when,
)

prof_name = 'prof' + str(time.time())
email = 'email@' + str(time.time())
phone_num = '0009991234'
student_name = 'student' + str(time.time())
student_b00 = 'B00999123'
date = '01/01/2018'
comment = 'Test comment'

@scenario('prof_active_cases.feature', 'PROF_ACTIVE_CASE-0 Professor can view "Active Cases" page')
def test_professor_can_view_active_cases_page(professor):
    """PROF_ACTIVE_CASE-0 Professor can view "Active Cases" page."""

@scenario('prof_active_cases.feature', 'PROF_ACTIVE_CASE-1 Professor can submit new case')
def test_professor_can_submit_new_case(professor):
    """PROF_ACTIVE_CASE-1 Professor can submit new case."""

@scenario('prof_active_cases.feature', 'PROF_ACTIVE_CASE-2 Professor can view case')
def test_professor_can_view_case(professor):
    """PROF_ACTIVE_CASE-2 Professor can view case."""

@given('I am in the login page')
def i_am_in_the_login_page(browser):
    """I am in the login page."""

@given('I am in the "Active Cases" page')
def i_am_in_the_active_cases_page(browser):
    """I am in the "Active Cases" page."""
    assert browser.find_by_text('Active Cases')

@when('I sign in')
def i_sign_in(browser):
    """I sign in."""

@when('I click "Submit new case"')
def i_click_submit_new_case(browser):
    """I click "Submit new case"."""
    browser.find_by_xpath('//button[text()="Submit new case"]').click()

@when('I fill in information')
def i_fill_in_information(browser):
    """I fill in information."""
    browser.find_by_id('ProfessorName').fill(prof_name)
    browser.find_by_id('email').fill(email)
    browser.find_by_id('phoneNum').fill(phone_num)
    browser.find_by_xpath('//div[@name="students"]/input[@aria-label="Name"]').fill(student_name)
    browser.find_by_xpath('//div[@name="students"]/input[@aria-label="B00"]').fill(student_b00)
    browser.find_by_id('date').fill(date)
    browser.find_by_id('additionalComments').fill(comment)

@when('I click the "Submit" button')
def i_click_the_submit_button(browser):
    """I click the "Submit" button."""
    browser.find_by_name('SubmitFormA').click()

@when('I click "View case"')
def i_click_view_case(browser):
    """I click "View case"."""
    browser.find_by_xpath('//tr/td[text()="Mark Otto"]/following-sibling::td/button[text()="View Case"]').click()

@then('I see the "Active Cases" page')
def i_see_the_active_cases_page(browser):
    """I see the 'Active Cases' page."""
    assert browser.find_by_text('Active Cases')
