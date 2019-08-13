<?php
class WebTest extends PHPUnit_Extensions_Selenium2TestCase {

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
     * The following test checks the following things:
     * - Check if the field is available, if so enter a value
     * - Check if the submit button is available, if yes click it
     * - Check if the response is the same as our entered values
     *  If any of these fail, the test fails and it would mean the form is not working properly
     */
    public function testHasWorkingForm() {
        // Go to the index
        $this->url('/index.php');

        // Create an array with our test values, so we can enter them in all the fields and verify it later
        // [ [ field => field type, name => name of field, value => value inside field ], ...]
        $values = [
                ['field'=>'input', 'name'=>'name', 'value'=>'Frans'],
                ['field'=>'radio', 'name'=>'gender', 'value'=>'female'],
                ['field'=>'checkbox', 'name'=>'over18', 'value'=>'on'],
                ['field'=>'select', 'name'=>'car', 'value'=>'Saab'],
                ['field'=>'input', 'name'=>'message', 'value'=>'Just testing!']
            ];

        // Now enter each value in the corresponding field
        foreach($values as $input) {
            // We are skipping the field types 'checkbox' and 'radiobutton', they require a different approach
            switch ($input['field']) {
                case 'input':
                case 'select':
                    // The select box and input box works the same
                    $this->byName($input['name'])->value($input['value']);
                break;
                case 'checkbox':
                    // A checkbox requires a click if the value is 'on'
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
        sleep(1);

        // Let's see if all the values carried over correctly on the new page
        // Originally I had this foreach, but apparently phpunit doesn't like asserts inside a foreach
        $i = 0;
        foreach($values as $input) {
            // Get the returned values from each part of the content, for each field
            $text = $this->byId($input['name'])->text();
            // We need to change 'on' to Yes/No for the checkbox (just like in the template)
//            if ($input['field'] === 'checkbox')
//                ($input['value'] == 'on') ? $text = 'Yes' : $text = 'No';

            // Make sure the values are the same
            $this->assertEquals($text, $input['value']);
            $i++;
        }

        // Make sure we have done all the tests
        $this->assertEquals(count($values), $i);

    }

}