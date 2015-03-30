<!doctype html>
<html lang="en">
<head>
    <title>{docTitle}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="generator" content="phpAPI {VERSION} (https://github.com/Greenray/phpAPI/)">
    <link rel="stylesheet" type="text/css" href="{path}style.css">
    <link rel="start" href="{path}index.html">
</head>
[if=headerNav]
    <body id="{id}" onload="parent.document.title=document.title;">
        {headerNav}
[/if.headerNav]
{page}
[if=footerNav]
        {footerNav}
        <hr>
    </body>
[/if.footerNav]
</html>
