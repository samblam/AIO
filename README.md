The following project is with regards to automating the process of resolving a student academic integrity offence.
It is a web portal designed to ease the amount of emails being sent back and forth between AIOs and Professors.

# Functional Testing
Contains all the automated functional test scripts for the AIO-automate.
It uses splinter and pytest-bdd libraries in python.
## Requirements
- python 2.7
- virtualenv and virtualenvwrapper
- pip-tools (this will install all the requirements for you)
- All other requirements needed are installed via pip-tools

## How to set up dev environment for functional testing
**Note: make sure you're in virtualenv when working on python**

```
$source /usr/local/bin/virtualenvwrapper.sh
$mkvirtualenv aio-automate
$workon aio-automate
$pip install pip-tools
$pip-compile //will install all requirements in your virtualenv
$pip-sync //must be in folder with requirements files
```

### Setting up virtualenv:
```
$source /usr/local/bin/virtualenvwrapper.sh
$mkvirtualenv aio-automate
```

To work on virtualenv:
```
$workon aio-automate
```

To quit virtualenv:
```
$deactivate
```

To show list of virtualenv (must be outside virtualenv):
```
$lsvirtualenv
```

To resume project (assuming your virtualenv is gone, otherwise can continue at `workon` line):
```
$source /usr/local/bin/virtualenvwrapper.sh
$mkvirtualenv aio-automate
$workon aio-automate
$pip-sync
```

## Running functional tests in python
**Note: Make sure you're in virtualenv**
```
pytest tests/functional/test_filename.py
```

## Working on test scripts
### Notes:
- `.feature` files uses Gherkin syntax (refer to resources section on how to write them)
- `.py` files uses Splinter library and python
- Everything related to fixtures, how .env files/webdriver are loaded, etc. are in `conftest.py`
- To login to as aio/professor/admin, simply pass in the corresponding persona into parameters in `@scenario`
- To unstick pages, use `ui.WebDriverWait(browser, 10).until(lambda driver: browser.find_by_etc('[etc]'))` -- otherwise use `time.sleep(1)`

### Code standards:
- Refer to `test_login.py` and `login.feature` on how feature files and scripts should be written and follow its function naming, etc.
- In `.py` scripts, put `@given`, `@when`, `@then` together (easier to read)
- If two or more `@when` (or `@then`) function does the same thing (ie. clicks the same button but has different statement in feature file, it can be written as:

```
@when('I click on submit')
@when('I select the submit button')
def function(browser):
    <do some stuff>
```

## Documentations of the libraries used/other resources
- [Splinter] (https://splinter.readthedocs.io/en/latest/)
- [Pytest-bdd (there are examples on how splinter is used along with feature files using Gherkin)] (https://pypi.python.org/pypi/pytest-bdd)
- [Resource on Gherkin syntax/how to write it] (http://docs.behat.org/en/v2.5/guides/1.gherkin.html)