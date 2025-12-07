
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- RTL CSS -->
@if( \Config::get('app.locale') == 'en')
@php
$lang_side ='ltr';
$web_lang = 'english';
@endphp
@elseif(\Config::get('app.locale') == 'ar')
@php
$lang_side ='rtl';
$web_lang = 'arabic';
@endphp
@endif
<style type="text/css">
body, body *:not(html):not(style):not(br):not(tr):not(code) {
font-family: Avenir, Helvetica, sans-serif;
box-sizing: border-box;
}

body {
background-color: #fff;
color: #000;
height: 100%;
line-height: 1.4;
margin: 0;
width: 100% !important;
-webkit-text-size-adjust: none;
}

p,
ul,
ol,
blockquote {
line-height: 1.4;
text-align: left;
}

a {
color: #3869D4;
}

a img {
border: none;
}

/* Typography */

h1 {
color: #2F3133;
font-size: 19px;
font-weight: bold;
margin-top: 0;
text-align: left;
}
td.header {
    width: 100%;
    height: auto;
}
td.header a img {
    width: 270px;
}
h2 {
color: #2F3133;
font-size: 16px;
font-weight: bold;
margin-top: 0;
text-align: left;
}

h3 {
color: #2F3133;
font-size: 14px;
font-weight: bold;
margin-top: 0;
text-align: left;
}

p {
color: #74787E;
font-size: 16px;
line-height: 1.5em;
margin-top: 0;
text-align: left;
}

p.sub {
font-size: 12px;
}

img {
max-width: 100%;
}

/* Layout */

.wrapper {
background-color: #000;
margin: 0;
padding: 0;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

.content {
margin: 0;
padding: 0;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

/* Header */

.header {
padding: 25px 0;
text-align: center;
}

.header a {
color: #fff;
font-size: 19px;
font-weight: bold;
text-decoration: none;
text-shadow: 0 1px 0 white;
}

/* Body */

.body {
background-color: #FFFFFF;
margin: 0;
padding: 0;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

.inner-body {
background-color: #FFFFFF;
margin: 0 auto;
padding: 0;
width: 570px;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 570px;
}

/* Subcopy */

.subcopy {
border-top: 1px solid #EDEFF2;
margin-top: 25px;
padding-top: 25px;
}

.subcopy p {
font-size: 12px;
}

/* Footer */

.footer {
margin: 0 auto;
padding: 0;
text-align: center;
width: 570px;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 570px;
}

.footer p {
color: #AEAEAE;
font-size: 12px;
text-align: center;
}

/* Tables */

.table table {
margin: 30px auto;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

.table th {
border-bottom: 1px solid #EDEFF2;
padding-bottom: 8px;
}

.table td {
color: #74787E;
font-size: 15px;
line-height: 18px;
padding: 10px 0;
}

.content-cell {
padding:0px;
}

/* Buttons */

.action {
margin: 30px auto;
padding: 0;
text-align: center;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

.button {
border-radius: 3px;
box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
color: #FFF;
display: inline-block;
text-decoration: none;
-webkit-text-size-adjust: none;
}

.button-blue {
background-color: #000;
border-top: 10px solid #000;
border-right: 18px solid #000;
border-bottom: 10px solid #000;
border-left: 18px solid #000;
}

.button-green {
background-color: #2ab27b;
border-top: 10px solid #2ab27b;
border-right: 18px solid #2ab27b;
border-bottom: 10px solid #2ab27b;
border-left: 18px solid #2ab27b;
}

.button-red {
background-color: #bf5329;
border-top: 10px solid #bf5329;
border-right: 18px solid #bf5329;
border-bottom: 10px solid #bf5329;
border-left: 18px solid #bf5329;
}

/* Panels */

.panel {
margin: 0 0 0px;
}

.panel-content {
background-color: #fff;
padding:0 16px;
}

.panel-item {
padding: 0;
}

.panel-item p:last-of-type {
margin-bottom: 0;
padding-bottom: 0;
}

/* Promotions */

.promotion {
background-color: #FFFFFF;
border: 2px dashed #9BA2AB;
margin: 0;
margin-bottom: 25px;
margin-top: 25px;
padding: 24px;
width: 100%;
-premailer-cellpadding: 0;
-premailer-cellspacing: 0;
-premailer-width: 100%;
}

.promotion h1 {
text-align: center;
}

.promotion p {
font-size: 15px;
text-align: center;
}
.panel-content


</style>
</head>
<body>
<style>

@media  only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media  only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>

<table style="border: 1px solid #231f20;" class="wrapper1" width="570" cellpadding="0" cellspacing="0" align="center">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0">
<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body1" align="center" width="570" cellpadding="0" cellspacing="0">

<tr>
<td class="header" style="background: #fecc35;text-align: center;border-bottom: 1px solid #29375c;padding-bottom: 16px;padding-top: 16px;">
<a href="{{url('/')}}">{{Html::image('https://www.vehicleand.com/frontend/images/logo.png','',array("width"=>"120px"))}}</a>
</td>
</tr>

<!-- Body content -->
<tr>
<td class="content-cell">
<table class="panel" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" style="padding-left: 25px;padding-right: 25px;">
<tr>
<td style="padding: 30px 0px 30px; line-height: 30px; font-size: 14px; color: #000;">
 @yield('content')
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="content-cell" style="padding:10px 0px; font-size:14px; color:#231f20; background:#fecc35;" align="center">
&copy; {{ date('Y') }} MADAREXPRESS. all rights reserved
</td>
</tr>

</table>
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>
