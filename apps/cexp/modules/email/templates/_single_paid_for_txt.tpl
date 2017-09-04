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
    <h3 class="posted">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

      <div class="story">
        <p>

     Package code: <span style='font-weight: bold'> {$package.package_code} </span><br />
     Courier name: <span style='font-weight: bold'> {$package.name} </span><br />
     From address: <span style='font-weight: bold'> {$package.from_text_address|default:"None"} </span><br />
     To address:   <span style='font-weight: bold'> {$package.to_text_address|default:'None'} </span><br />
     Reference:    <span>{$package.reference|default:'None'}</span> <br />
     Package type: <span> {$package.package_type|default:'None'}</span> <br />
     Round trip:   <span> {if $package.round_trip} Yes {else} No {/if}</span> <br />
     Instructions: <span> {$package.instructions|default:'None'}</span><br /><br />

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
