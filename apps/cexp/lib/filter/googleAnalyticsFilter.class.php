<?php

class google_analyticsFilter extends sfFilter
{
  public function execute($filterChain)
  {

    // Nothing to do before the action
    $filterChain->execute();

		$environment = sfConfig::get('sf_environment'); 		
		$key = sfConfig::get('app_google_analytics_key');

		$module_name = sfContext::getInstance()->getModuleName();
		$no_google_analytics_mods = array('client', 'partner', 'admin');
		if (!preg_match('/dev/i', $environment) && !in_array($module_name, $no_google_analytics_mods))
		{
    	// Decorate the response with the tracker code
    	$googleCode = '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("'.$key.'");
pageTracker._setDomainName("courierexpress.ca");
pageTracker._trackPageview();
} catch(err) {}</script>
			';
    $response = $this->getContext()->getResponse();
    $response->setContent(str_ireplace('</body>', $googleCode.'</body>',$response->getContent()));
   	}

	}
}
