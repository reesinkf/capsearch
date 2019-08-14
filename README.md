# PHPUnit + Selenium Demo
A demo of how to use PHPUnit and Selenium with a Symfony 4 framework on Ubuntu.

## Relevant files

- [Functional tests](https://github.com/reesinkf/capsearch/blob/master/tests/FormTest.php)
- [Controller with form](https://github.com/reesinkf/capsearch/blob/master/src/Controller/HomeController.php)

## Installation
- GeckoDriver: This is the firefox driver for selenium
- Selenium: Browser automation
- Java: To run Selenium

### Geckodriver
To use firefox with Selenium you need [GeckoDriver](https://github.com/mozilla/geckodriver/releases):
```
wget https://github.com/mozilla/geckodriver/releases/download/v0.24.0/geckodriver-v0.24.0-linux64.tar.gz
tar -xvzf geckodriver*
chmod +x geckodriver
sudo mv geckodriver /usr/bin/geckodriver
```
Alternatively you can use [ChromeDriver](https://chromedriver.chromium.org/) if you prefer to use Chrome.

### Selenium
Selenium 3.9 has an annoying bug, please download [Selenium 3.8.1](https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar).

### Java
Check if you have java installed:
```
java --version
```
If not:
```
sudo apt install default-jre
```
### Symfony
After cloning the repo, install all the packages:
```
git clone https://github.com/reesinkf/capsearch.git && cd capsearch;composer install
```

## Usage 

#### Start Selenium
```
java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```
Make sure to start Selenium inside a window system (not a remote terminal) so it can spawn a browser window.

#### Start Symfony web listener
```
php bin/console server:run
```

#### Run the tests
```
vendor/bin/phpunit
```
By default it will go through all available tests in the tests file, so no need to specify a certain file.

![image](https://user-images.githubusercontent.com/3394637/62924993-9ea53900-bdb1-11e9-9495-b699d9a08e88.png)

### Versions 
I used to following versions while developing with [PhpStorm 2019.1](https://www.jetbrains.com/phpstorm/):
- Selenium 3.8.1
- Geckodriver 0.24
- Firefox 68.0.1
- PHP 7.3.8
- PHPUnit 7.5.14
- Symfony 4.3.3
- Ubuntu 18


