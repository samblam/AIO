# AIO Web Portal Project
The following project is with regards to automating the process of resolving a student academic integrity offence.
It is a web portal designed to ease the amount of emails being sent back and forth between AIOs and Professors.

## Setting Up Development Environment
Follow the steps outlined below to set up your local development environment for this project. Thank you to Noah Attwood for writing these instructions.

Download MAMP FREE here: https://www.mamp.info/en/ 
MAMP is a PHP and MySQL server that can replicate the server environment in which the AIO project will be hosted.

### Get a Local Copy of the Database
Go to https://myadmin.cs.dal.ca/ and use the following login information:
Username: aio
Password: ge7ochooCae7

Once logged in to the AIO PhpMyAdmin page, navigate to the “Export” tab. Leave the export settings as default and press the “Go” button. This will save a local copy of the AIO database on your computer. The file should be called “db_cs_dal_ca.sql”.

Once the MAMP installation is complete, launch it to start the MySQL and PHP servers. In a web browser, navigate to http://localhost/MAMP/ to access PhpMyAdmin and the running website. Click on the blue “phpMyAdmin” link on the left side under the “MySQL” header to access PhpMyAdmin. 

In phpMyAdmin, click on the “Import” tab. Click on the “Choose File” button and browse your computer for the “db_cs_dal_ca.sql” file downloaded from the production database. Leave the other settings as default and press the “Go” button at the bottom.

The database has now been setup and is ready to run locally through MAMP as if it were the production website database.

### Running the Website
It is recommended that you clone the repository into the ../MAMP/htdocs folder. This will automatically locally host whatever is present in your local git repository
and files don’t need to be copied back to the git repo folder in order to commit and push development changes.
Preferences can be set for MAMP to change the directory location of the apache server if desired.

### Turn on PHP Error Reporting
Open MAMP and click on “Preferences”. Go to the PHP tab and note the PHP version you are using (EX: 7.2.1). 
Go to ../MAMP/conf/<php version> (directory location may be different for Macs) and edit the php.ini file in that folder. 
Look for the display_errors variable and change its value from off to on. Also ensure that the error_reporting variable 
has a value of E_ALL. Errors should now be reported and you will bang your head on the wall a lot less frequently.

### Additional php.ini Changes
The CS help desk was contacted to modify the AIO project server’s php.ini file to have the following values:
 
post_max_size = 100M
memory_limit = 128M
upload_max_filesize = 100M
max_file_uploads = 50

This modification was made at the request of the client. The server will allow users to upload a maximum of 50 files at once 
totalling 100 MB in size. These restrictions are enforced during parsing in fileFunctions.php (for server-side validation) 
and in forma.php (for client-side validation). 

### Additional Project Information
The AIO project is hosted on peso.cs.dal.ca/aio. If you need to make modifications to the hosted project files, you can use 
the following credentials in FileZilla or PuTTY:

Server: peso.cs.dal.ca
Username: aio
Password: ge7ochooCae7


## Functional Testing
Contains all the automated functional test scripts for the AIO-automate.
It uses splinter and pytest-bdd libraries in python.

### Requirements
**Make sure you have python 2.7, virtualenv and pip-tools before proceeding to the next step**
- python 2.7
- virtualenv and virtualenvwrapper
- pip-tools (this will install all the requirements for you)
- All other requirements needed are installed via pip-tools

### How to set up dev environment for functional testing
**Note: make sure you're in virtualenv when working on python**

```
$source /usr/local/bin/virtualenvwrapper.sh
$mkvirtualenv aio-automate
$workon aio-automate
$pip install pip-tools
$pip-compile //will install all requirements in your virtualenv
$pip-sync //must be in folder with requirements files
```
- Don't forget to add corresponding info to the `.env` file before running the tests. Scripts won't work unless you fill the appropriate information.

### Setting up virtualenv:
**Purpose of virtualenv**: creates an isolated environment when developing. Rather than installing libraries globally (ie. on your machine), you create these virtual environments and install them in here.
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

### Running functional tests in python
**Note: Make sure you're in virtualenv**

This will only execute 1 test script of that file name:

```
pytest tests/functional/test_filename.py
```

In order to execute ALL test scripts:
- Go into the folder with shell script, `run_functional_tests.sh`:

```
$sh run_functional_tests.sh
```
- All scripts should now run automatically

When running scripts, you may use `-s` to see `print` statements as the script is being ran, example:

```
pytest tests/functional/test_filename.py -s
```

### Working on test scripts
#### Notes:
- `.feature` files uses Gherkin syntax (refer to resources section on how to write them)
- `.py` files uses Splinter library and python
- Everything related to fixtures, how .env files/webdriver are loaded, etc. are in `conftest.py`
- To login to as aio/professor/admin, simply pass in the corresponding persona into parameters in `@scenario`
- To unstick pages, use `ui.WebDriverWait(browser, 10).until(lambda driver: browser.find_by_etc('[etc]'))` -- otherwise use `time.sleep(1)`
- **DO NOT** use absolute paths when using `find_by_xpath`:

```
DO NOT USE SOMETHING LIKE: /html/body/div[3]/table/tbody/tr[1]/td[5]/button
Rather use something like: //tr/td[text()="Moe"]/following-sibling::td/button[text()="View Case"] instead
```
- This essentially selects the View Case button on the row that contains "Moe"
- Using absolute paths to select a button, etc. is not the way to go. Once a new div, button, text, etc. is added to the page, your script will break because that absolute path is now something different
- Dev tool in your browser is useful when trying to test if that xpath is correct or not
- Simply inspect -> find div -> right click div -> copy -> copy xpath. Then do CTRL+F inside the browser dev tool and paste the xpath/your own xpath


#### Code standards:
- Refer to `test_login.py` and `login.feature` on how feature files and scripts should be written and follow its function naming, etc.
- In `.py` scripts, put `@given`, `@when`, `@then` together (easier to read)
- If two or more `@when` (or `@then`) function does the same thing (ie. clicks the same button but has different statement in feature file, it can be written as:

```
@when('I click on submit')
@when('I select the submit button')
def function(browser):
    <do some stuff>
```

#### Additional information on conftest file:
This `conftest.py` file contains all the setup for the webdriver. It also contains all the fixtures for different types of users that'll be using the site (ie. AIO/Admin/Professor).

Essentially, these user fixtures are passed into the function parameters and allows scripts to quickly go to project's website, fill in user/pass and click enter. Rather than constantly repeating this visiting website/filling in user and password process in every script, just pass in the fixtures into function parameters.

In addition, `conftest.py` also uses environmental variables and these are loaded from the `.env` file. If the file isn't filled in locally, then errors will occur as soon as you try running a script.

#### As of April 5, 2018:
- There are 14 test scripts, 4 of them fails in the following files:
- Note: Failures are from functionalities in the web portal not working (ie. buttons not working, etc.)

```
Fails:
test_aio_can_view_active_cases_page
test_aio_can_view_case
test_aio_can_login_to_web_portal
test_aio_can_logout_from_web_portal
```

#### As of July 29, 2018:
- There are 14 test scripts, 7 of them fail in the following files:

```
Fails:
test_admin_can_view_case_information
test_admin_can_change_aio_for_a_case
test_aio_can_view_case
test_professor_can_logout_from_web_portal
test_aio_can_logout_from_web_portal
test_admin_can_logout_from_web_portal
test_professor_can_view_case
```

### Documentations of the libraries used/other resources
- [Splinter] (https://splinter.readthedocs.io/en/latest/)
- [Pytest-bdd (there are examples on how splinter is used along with feature files using Gherkin)] (https://pypi.python.org/pypi/pytest-bdd)
- [Resource on Gherkin syntax/how to write it] (http://docs.behat.org/en/v2.5/guides/1.gherkin.html)
- [Information about environmental variables/.env] (https://github.com/theskumar/python-dotenv)
