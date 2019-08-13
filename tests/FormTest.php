<?php

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
     *      name => name of field,
     *      value => value inside field  ],
     *  ...]
     * @return array
     */
    public function inputsProvider(): array {
        // It looks a bit ridiculous but a data provider has to be an array with an array...
        return [[[
            ['field'=>'input',      'name'=>'name',     'value'=>'Frans'],
            ['field'=>'radio',      'name'=>'gender',   'value'=>'female'],
            ['field'=>'checkbox',   'name'=>'over18',   'value'=>'on'],
            ['field'=>'select',     'name'=>'car',      'value'=>'saab'],
            ['field'=>'input',      'name'=>'message',  'value'=>'Just testing!']
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
     * First test will simply check if the server is running and if its showing the
     * correct page (index should have a specific title)
     */
    public function testPageOnline() {
        // Go to the index
        $this->url('/index.php');
        // Check if the title is "Home page"
        $this->assertEquals('Home page', $this->title());
        // The 'assert' is essentially the test,
        // without an assert the test is incomplete (phpunit will give a warning)
    }

    /**
     * With this test we simply verify the post form actually works
     * by clicking the post button (with an empty form)
     * @dataProvider inputsProvider
     * @param array $inputs
     */
    public function testPostEmptyForm(array $inputs) {
        // Go to the index
        $this->url('/index.php');
        // Test submit
        $this->byId('post')->submit();
        // Give the browser time to post
        $this->waitForBrowser();
        // On the new page, we should have an empty Name: field
        $this->assertEquals('', $this->byId($inputs[0]['name'])->text());
        // ^ You can use any random field here as they should all be empty because
        // we didn't enter anything into the form (except car which has a default)
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
            $this->assertTrue($this->byName($input['name'])->enabled());
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
                    $this->byName($input['name'])->value($input['value']);
                break;
                case 'checkbox':
                    // A checkbox requires a click if the value is supposed to be 'on'
                    if ($input['value'] === 'on') $this->byName($input['name'])->click();
                break;
                case 'radio':
                    // For a radio button it's a little more complicated
                    // There's no easy way to get ALL elements of a certain type in PHPUnit (when extending from selenium...)
                    // The only way I found to work:
                    $radio = $this->element($this->using('css selector')->value('*[value="'.$input['value'].'"]'));
                    $radio->click();
                break;
            }
        }

        // Now we're ready to submit
        $this->byId('post')->submit();
        $this->waitForBrowser();

        // Let's see if all the values carried over correctly on the new page
        foreach($inputs as $input) {
            // Get the returned values from each part of the content, for each field
            $text = $this->byId($input['name'])->text();
            // Make sure the values are the same
            $this->assertEquals($text, $input['value']);
        }
    }

}