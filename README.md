# mkltpl
PHP Template System

有名な所ではsmartyやsigmaなどPHPで使えるテンプレートシステムはいくつかあるのだけど
HTML側には出来るだけコードを書かずに使えるモノが良かったので簡単に使えるモノを作ってみた

ただそれだけ（笑）

使い方
■ sample html

FILENAME:template.html
<html>
  <head>
    <title>%TITLE%</title>
  </head>
  <body>
    &lt;h1&gt;%HEADER%&lt;/h1&gt;
 <!-- Begin BODY -->
    <li>%TEXT%</li>
 <!-- End BODY -->
  </body>
</html>


■ sample php

<?php
  require_once "mkltpl.php";
  
  $TPL  = new template();
  
  $TEMPLATE = "template.html";
  $HTMLDATA = $TPL->set($TEMPLATE,'index');
  $HTML     = $TPL->htmlset($HTMLDATA['index'],'%TITLE%','MEDIAKISSLB','0');
  $HTML     = $TPL->htmlset($HTML,'%HEADER%','テンプレートサンプル','0');
  
  $BODY     = $TPL->htmlset($HTMLDATA['BODY'],'%TEXT%','Hello','0');
  $HTML     = $TPL->htmlset($HTML,'%BODY%',$BODY,'1');
  
  $BODY     = $TPL->htmlset($HTMLDATA['BODY'],'%TEXT%','World','0');
  $HTML     = $TPL->htmlset($HTML,'%BODY%',$BODY,'1');
  
  $TPL->htmlout($HTML);  // HTML出力
  $OUTPUT   = $TPL->htmlout($HTML,'1');  // 変数に代入
?>
