<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$account.subject}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="{url_for name='main_page/index' default='true'}/css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>


<div id="posts">

  <div class="post">
    <h2 class="title">{$subject}</h2>
    <h3 class="posted">Sent on: {$attempted_on|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

      <div class="story">
        <p>
			<pre>
     Package data: <span> {$package_data|@print_r} </span><br />
			</pre>
			<br />

        Courier Express
        <a href="{url_for name='main_page/index' default='true'}">{url_for name='main_page/index' default='true'}</a>
        </p>
      </div>

      <div class="meta">
        <p></p>
      </div>

    </div>

</div>


</body>
</html>


<div>

</div>
