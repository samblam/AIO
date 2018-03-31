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
TODO: add standards, naming

## Documentations of the libraries used/other resources
- [Splinter] (https://splinter.readthedocs.io/en/latest/)
- [Pytest-bdd] (https://pypi.python.org/pypi/pytest-bdd)
- [Resource on Gherkin syntax and how to write it] (http://docs.behat.org/en/v2.5/guides/1.gherkin.html)