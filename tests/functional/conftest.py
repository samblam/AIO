import os, re, sys
import pytest
import time

from pytest_bdd import given, when, then
from splinter import Browser
from selenium import webdriver
import selenium.webdriver.support.ui as ui
from inspect import getsourcefile
from dotenv import load_dotenv, find_dotenv
load_dotenv(find_dotenv())


@pytest.fixture(scope='session')
def pytestbdd_selenium_speed():
    return 0.5

@pytest.fixture
def pytestbdd_feature_base_dir():
    """Feature files base directory."""
    return os.path.abspath(
        os.path.join(
            os.path.dirname(os.path.dirname(getsourcefile(lambda:0))),
            'features'
        )
    )

@pytest.fixture(scope='function')
def browser():
    driver = Browser('chrome')
    return driver

LOGGED_IN = None
AIO = 'aio'
ADMIN = 'admin'
PROFESSOR = 'professor'
PASS = os.getenv('PW')
AIOUSER = os.getenv('AIO_USER')
ADMINUSER = os.getenv('ADMIN_USER')
PROFUSER = os.getenv('PROF_USER')

personas = {
    'aio': {
        'username': AIOUSER,
        'password': PASS
    },
    'admin': {
        'username': ADMINUSER,
        'password': PASS
    },
    'professor': {
        'username': PROFUSER,
        'password': PASS
    }
}

# Used when logging in as different user types
def login_persona(browser, credentials):
    browser.visit(base_url())
    ui.WebDriverWait(browser, 10).until(lambda driver: browser.find_by_name('uname'))
    browser.find_by_name('uname').fill(credentials['username'])
    browser.find_by_name('psw').fill(credentials['password'])
    browser.find_by_name('LoginSubmit').click()
    time.sleep(1)

@pytest.fixture
def credentials():
    """Login credentials."""
    if LOGGED_IN:
        return personas[LOGGED_IN]

@pytest.fixture
def base_url():
    try:
        print os.getenv('AIO_URL')
        return os.getenv('AIO_URL')
    except KeyError:
        sys.exit("Please set the environment variable AIO_URL")

@pytest.fixture
def aio(browser):
    if LOGGED_IN != AIO:
        login_persona(browser, personas[AIO])

@pytest.fixture
def admin(browser):
    if LOGGED_IN != ADMIN:
        login_persona(browser, personas[ADMIN])

@pytest.fixture
def professor(browser):
    if LOGGED_IN != PROFESSOR:
        login_persona(browser, personas[PROFESSOR])
