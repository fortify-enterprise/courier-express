<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    {include_http_metas}
    {include_metas}
    {include_title}
    <link rel="shortcut icon" href="/favicon.ico" />
    {include_stylesheets}
    <meta http-equiv="X-UA-Compatible" content="chrome=1">

  </head>
  <body>
		<div id="container" class="ui-corner-all">
			
			<div id="header" title="sitename">
			  {include_component_slot name='header_component'}
			</div> 

      <div id="mainnav">
			  {include_component_slot name='menu_component'}
			</div>

      <div id="contents">
    		{$sf_content}
			</div>

      <div id="footer">
        {include_component_slot name='footer_component'}
			</div>


		</div>
    {include_javascripts}

    <input type="hidden" name="environment" id="environment" value="%environment%" />
		<input type="hidden" name="session_timeout" id="session_timeout" value="%session_timeout%" />

{* google analytics *}
{*

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9779751-2");
pageTracker._setDomainName("www.courierexpress.ca");
pageTracker._trackPageview();
} catch(err) {}</script>
*}

  </body>

</html>
