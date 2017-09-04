<?php

class smsReadTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cexp'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'sms-messages';
    $this->name             = 'read';
    $this->briefDescription = 'Read sms messages from inbox to database';
    $this->detailedDescription = <<<EOF
The [smsReadMessages|INFO] task does things.
Call it with:

  [php symfony smsReadMessages|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    if ($handle = opendir(sfConfig::get('app_sms_incoming')))
    {
      while (false !== ($file = readdir($handle)))
      {
        if ($file == '.' || $file == '..')
          continue;

        $this->process_message_file($file);

        // remove file
        unlink(sfConfig::get('app_sms_incoming') . '/' . $file);
      }
    }
  }


  protected function process_message_file ($file)
  {
    $res = preg_split('/From:|From_TOA:|From_SMSC:|Sent:|Received:|Subject:|Modem:|IMSI:|Report:|Alphabet:|Length:/', file_get_contents(sfConfig::get('app_sms_incoming') . '/' . $file));
    $message = preg_split('/\n/', $res[11]);

    $message_clean = '';
    $length = '';
    for ($i = 0; $i < sizeof($message); $i++)
    {
      if ($i == 0)
       $length = $message[0];

      if ($i < 2)
        continue;

      $message_clean .= $message[$i] . "\n";
    }

    print 'Reading sms message ' . $file . "\r\n";


    $sms = new SmsIncoming();
    $sms['carrier'] = trim($res[1]);
    $sms['toa'] =  trim($res[2]);
    $sms['smsc'] = trim($res[3]);
    $sms['sent_on'] = trim($res[4]);
    $sms['received_on'] = trim($res[5]);
    $sms['imsi'] = trim($res[8]);
    $sms['length'] = trim($length);
    $sms['text'] = trim($message_clean);
    $sms['reply_required'] = ($this->is_reply_required($message_clean)) ? 1 : 0;
  
    $sms->save();
  }


  protected function is_reply_required ($text)
  {
    return preg_match('/^track/', $text);
  }
}
