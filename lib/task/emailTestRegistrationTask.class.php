<?php

class emailTestRegistrationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('email', sfCommandArgument::OPTIONAL, 'Email parameter'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cexp'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'email';
    $this->name             = 'test-registration';
    $this->briefDescription = 'Test email registration email';
    $this->detailedDescription = <<<EOF
The [email|INFO] task does things.
Call it with:

  [php symfony email|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    include(dirname(__FILE__).'/../../test/bootstrap/Doctrine.php');

    // Initialize the action object
    $email = 'aktush@gmail.com';

    if ($arguments['email'])
      $email = $arguments['email'];

    //$email = $this->askAndValidate('What is your email address?', new sfValidatorEmail());

    // add your code here
    $res = Doctrine_Query::create()
      ->select('c.*')
      ->from('Client c')
      ->leftJoin('ClientLogin cl')
      ->where('c.login_id = cl.id')
      ->andWhere('cl.email = ?', $email)
      ->fetchArray();

    if ($res[0]['id'])
    {
      $this->logSection('email', 'sending test registration email to:' . $res[0]['id']);
      $accounts_db = new Accounts_Db();
      $accounts_db->send_account_details($this, $res[0]['id']);
    }
    else
    {
      $this->logSection('email', 'Email address does not exist');
    }
  }
}
