<?php

namespace GlpiPlugin\Formcreator\Tests;
use GlpiPlugin\Formcreator\Tests\CommonBrowsing;
use GlpiPlugin\Formcreator\Tests\CommonTestCase;

class CommonFunctionalTestCase extends CommonTestCase {
   public $crawler;
   public $client;
   protected $browsing;
   protected $screenshotPath;
   private $currentTestMethod;

   public function setUp() {
   }

   public function beforeTestMethod($method) {
      parent::beforeTestMethod($method);

      // Check the plugin is active
      $this->boolean(\Plugin::isPluginActive('formcreator'))->isTrue();

      // set path for screenshots
      $classname = explode('\\', static::class);
      $classname = array_pop($classname);
      $this->screenshotPath = TEST_SCREENSHOTS_DIR . '/' . $classname . '/' .  $method;
      @mkdir($this->screenshotPath, 0777, true);

      // create client
      $this->client = \Symfony\Component\Panther\Client::createChromeClient(null, null, [], 'http://localhost:8000');
      //$this->client = \Symfony\Component\Panther\Client::createFirefoxClient(null, null, [], 'http://localhost:8000');

      $this->browsing = new CommonBrowsing($this);

      // Browse to login page
      $this->crawler = $this->client->request('GET', '/');

      // screenshot
      $this->currentTestMethod = $method;
      $this->client->waitForVisibility('#boxlogin > form');
      $this->takeScreenshot();
      $form = $this->crawler->filter('#boxlogin > form')->form();

      // Login as glpi
      $login = $this->crawler->filter('input#login_name')->attr('name');
      $passwd = $this->crawler->filter('input#login_password')->attr('name');
      $form[$login] = 'glpi';
      $form[$passwd] = 'glpi';
      $this->crawler = $this->client->submit($form);

      $this->client->waitFor('#footer');
   }

   public function takeScreenshot() {
      static $counter = 0;

      $counter++;
      $number = sprintf("%'.04d", $counter);
      $name = $this->currentTestMethod;
      $this->client->takeScreenshot($this->screenshotPath . "/$name-$number.png");
   }

   public function tearDown() {
      if ($this->client === null) {
         return;
      }
      $this->client->quit();
   }
}
