<?php
/**
 * Testing functions:
 * testPageIsAvailable()            Checks if the server is running and the correct page is showing
 * testFormPost()                   Tests if we the form submit works properly
 * testVerifyFieldsAreAvailable()   Tests if all fields are available in the form
 * testHasWorkingForm()             Actually tests the form and if the printed values are correct
 * Other:
 * setUp()                          Basic set up config
 * inputsProvider()                 Returns an array with our test data, field id's and field types
 * waitForBrowser()                 A function we can call when the browser needs some time
 */

/**
 *  Usually in PHPUnit you would extend from PHPUnit_Framework_TestCase but in this case we want to use Selenium
 */
class FormTest extends PHPUnit_Extensions_Selenium2TestCase {

    /**
     * Function to set up testing configuration
     */
    protected function setUp() {
        // Browser of choice is firefox, but you can use Chrome as well with "chromedriver" instead of "geckodriver"
        $this->setBrowser('firefox');
        // Configure the url of our website to test
        $this->setBrowserUrl('http://localhost:8000');
    }

    /**
     * This is our data provider for all our form tests
     * It returns an array with arrays with everything we need to enter values into the correct fields
     * and to verify them later
     *  [ [ field => field type,
     *      id => id of field,
     *      value => value inside field  ],
     *  ...]
     * @return array
     */
    public function inputsProvider(): array {
        // It looks a bit ridiculous but a data provider has to be an array with an array...
        return [[[
            ['field'=>'input',      'id'=>'form_name',     'value'=>'Frans'],
            ['field'=>'radio',      'id'=>'form_gender',   'value'=>'Male'],
            ['field'=>'checkbox',   'id'=>'form_over18',   'value'=>'1'],
            ['field'=>'select',     'id'=>'form_car',      'value'=>'Saab'],
            ['field'=>'input',      'id'=>'form_message',  'value'=>'Just testing!']
        ]]];
    }

    /**
     *  A function we can use when we want to give the browser time to load
     *  It only continues when the page header is visible
     */
    public function waitForBrowser() {
        $this->waitUntil(function () {
            // Continue if we find the h3 element
            // The h3 element is on both pages so this is safe to use
            if ($this->byCssSelector('h3')) return true;
            // Return nothing and continue waiting if we didn't find the header yet
            return null;
            // Wait a maximum amount of 3 seconds
        }, 3000);
    }

    /**
     * The first test will simply check if the page is available by
     * checking what the header (h3) says
     */
    public function testPageIsAvailable() {
        // Go to the index
        $this->url('/index.php');
        // Check if the header says "Capsearch Selenium/PHPUnit Test"
        $header = $this->byCssSelector('h3')->text();
        $this->assertEquals($header, 'Capsearch Selenium/PHPUnit Test');
        // The 'assert' is essentially the test,
        // without an assert the test is incomplete (phpunit will give a warning)
    }

    /**
     * With this test we make sure the form works by clicking the Post button
     * We're ignoring all the input boxes for now
     * @dataProvider inputsProvider
     * @param array $inputs
     */
    public function testFormPost(array $inputs) {
        // Go to the index
        $this->url('/index.php');
        // Test submit
        $this->byId('form_save')->submit();
        // Give the browser time to post
        $this->waitForBrowser();
        // Now we should see "Data posted!"
        $page = $this->byCssSelector('h6')->text();
        $this->assertEquals($page, "Data posted!");
    }

    /**
     * This test will check if all fields are available inside the form
     * Using our data provider we know exactly what fields to look for
     * @dataProvider inputsProvider
     * @param array $inputs
     */
    public function testVerifyFieldsAreAvailable(array $inputs) {
        $this->url('/index.php');
        // Just loop through all fields
        foreach($inputs as $input) {
            // Check if the field is enabled (to verify it exists)
            $this->assertTrue($this->byId($input['id'])->enabled());
        }
    }

    /**
     * The following function posts actual data and verifies if the returned data is the same
     * @dataProvider inputsProvider
     * @param array $inputs
     */
    public function testHasWorkingForm(array $inputs) {
        // Go to the index
        $this->url('/index.php');

        // Now enter each value in the corresponding field
        foreach($inputs as $input) {
            // Check what field we're currently on..
            switch ($input['field']) {
                case 'input':
                case 'select':
                    // The select box and input box works the same so we can enter the value for them both
                    $this->byId($input['id'])->value($input['value']);
                break;
                case 'checkbox':
                    // A checkbox requires a click if the value is '1'
                    if ($input['value'] === '1') $this->byId($input['id'])->click();
                break;
            }
        }

        // Now we're ready to submit
        $this->byId('form_save')->submit();
        $this->waitForBrowser();

        // Let's see if all the values carried over correctly on the new page
        foreach($inputs as $input) {
            // Get the returned values from each part of the content, for each field
            $text = $this->byId($input['id'])->text();
            // Make sure the values are the same
            $this->assertEquals($text, $input['value']);
        }
    }

}
