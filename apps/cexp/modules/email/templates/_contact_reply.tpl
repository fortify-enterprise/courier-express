<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$subject}</title>
<meta name="keywords" content="contact reply" />
<meta name="description" content="contact reply" />
<link href="http://www.courierexpress.ca/css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="posts">

	<div class="post">
		<h2 class="title">{$subject}</h2>
		<h3 class="posted">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

			<div class="story">
				<p>
					
					Thank you {$contact_info.name|default:'customer'} for contacting Courier Express,
					we value your input and will contact you<br />
					as soon as our support team reviews the request.<br />

					Thank you and have a wonderful day!<br /><br />

					Courier Express Team<br /><br /> 

				<a href="{url_for name='landing_page/index' default='true'}">{url_for name='landing_page/index' default='true'}</a>
				</p>
			</div>

			<div class="meta">
				<p>
				</p>
			</div>

		</div>

</div>

</body>
</html>
