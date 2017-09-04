<?php

class layoutComponents extends sfComponents
{
  public function executeHeader()
  {
    $this->initContents();

		// include session check, on inactivity logout
		$response = sfContext::getInstance()->getResponse();
		$response->addJavascript('common/session_check.js');

		$this->fb_appid = sfConfig::get('app_facebook_id');
		$this->fb_uid   = $this->getUser()->getAttribute('fb_uid');
  }


  public function executeFooter()
  {
    $this->initContents();
    $menu_file = 'main';
    if ($this->login_type)
      $menu_file = $this->login_type;

    $this->menu = sfYaml::load(sfConfig::get('sf_data_dir') . "/menus/footer/$menu_file.yml");
  }


  public function executeLeft()
  {
    $this->initContents();
  }


  public function executeMenu()
  {
    $this->initContents();
    $menu_file = 'main';
    if ($this->login_type)
      $menu_file = $this->login_type;

    $this->menu = sfYaml::load(sfConfig::get('sf_data_dir') . "/menus/navigation/$menu_file.yml");
  }


  public function initContents()
  {
		$this->login_type = $this->getUser()->getAttribute('login_type');
		$this->client     = $this->getUser()->getAttribute('client');
		$this->client_id  = $this->getUser()->getAttribute('client_id');
		$this->module_name = $this->getRequest()->getParameter('module');
  }
}
?>
