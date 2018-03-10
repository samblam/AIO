The following project is with regards to automating the process of resolving a student academic integrity offence.
It is a web portal designed to ease the amount of emails being sent back and forth between AIOs and Professors.

# Functional Testing
Contains all the automated functional test scripts for the AIO-automate
## Requirements
- python 2.7
- virtualenv and virtualenvwrapper
- pip-tools

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

To resume project:
```
$workon aio-automate
$pip-sync
```