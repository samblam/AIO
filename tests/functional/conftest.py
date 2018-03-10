import os, re, sys
import pytest
import time

from pytest_bdd import given, when, then
from splinter import Browser
from selenium import webdriver
import selenium.webdriver.support.ui as ui
from inspect import getsourcefile

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
# PASS = os.environ['PW]
PASS = 'pass1234'

personas = {
    'aio': {
        'username':'aio@email.com',
        'password': PASS
    },
    'admin': {
        'username':'admin@email.com',
        'password': PASS
    },
    'professor': {
        'username':'prof@email.com',
        'password': PASS
    }
}

# Used when logging in as different user types
def login_persona(browser, credentials):
    browser.visit(base_url())

@pytest.fixture
def credentials():
    """Login credentials."""
    if LOGGED_IN:
        return personas[LOGGED_IN]

@pytest.fixture
def base_url():
    try:
        return os.environ['AIO_URL']
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
