<?php require_once('controller.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
<head>
   <title>Distance between two places on earth</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style type="text/css">
   html,body{font-family: helvetica,arial,sans-serif,verdana;color: #000;background:#999;color:#000;}
   #doc{background:#fff;border:1em solid #fff;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;}
   h1{background:none repeat scroll 0 0 #447E40;color:#FFFFFF;padding:14px;text-align:center;font-size: 40px;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;}
   img{display:block;margin-bottom:1em;margin-top: 5px}
   .result{font-size:160%;text-align:center;background:#9c9;padding:10px;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;}
   .result span{color:#060}
   strong{font-weight: bold}
   ul{margin:1em 0;list-style:none;}
   ul ul {margin-left: 10px}
   ul li {margin: 5px}
   form{margin-top: 15px}
   input[type="submit"] {-moz-border-radius:10px 10px 10px 10px;background:none repeat scroll 0 0 #447E40;border:1px solid #447E40;color:#fff;cursor:pointer;padding:0.3em;margin-left: 10px;}
   input[type="submit"]:hover{background: #fff;color: #447E40}
   .warning{border: 10px solid #FFE1E1;-moz-border-radius: 8px;-webkit-border-radius: 8px;  border-radius: 8px;  background: #FFE1E1;  width: 717px;  text-align: center;  font-size: 130%;  color: #c00;  padding: 5px;  margin: 10px auto;}
   .intro {margin-top: 10px;margin-bottom:10px;background: #e3fde9;padding: 10px;}
   .intro a{color: #393}
   #ft{font-size:80%;color:#888;text-align:right;margin:2em 0;font-size: 12px}
   #ft p a{color:#93C37D;}
   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>Distance between two places on earth</h1></div>
   <div id="bd" role="main">

   <?php
   if(!isset($_GET['place1']) && !isset($_GET['place2'])) {
      echo'<div class="yui-g">';
      echo'<p class="intro">This is a simple tool to show you the distance between two places on earth. Simply enter the locations in the form below and submit it. (You can try the following: <a href="index.php?place1=bucharest&place2=toronto">Bucharest to Toronto</a>, <a href="index.php?place1=Amsterdam&place2=Ibiza">Amasterdam to Ibiza</a>, <a href="index.php?place1=constanta&place2=cote+d\'ivoire">constanta to Cote d\'ivoire</a>)</p>'; 
      echo'</div>';
   }
   ?>

   <?php if($wehaveplaces) { ?>  
      <div class="yui-gb">
         <div class="yui-u first">
             <?php echo$map1;echo showInfo($out['place1']);?>
	</div>
        <div class="yui-u">
             <?php echo$map2;echo showInfo($out['place2']);?>
        </div>
        <div class="yui-u">
             <?php echo$mapdirection;?>
        </div>
      </div><!-- end yui-gb -->

      <div class="yui-g">
         <p class="result"><?php echo$result;?></p>	
      </div>
      <?php } else {?>
      <div class="yui-g">
           <?php echo$warning; ?> 
      </div>
      <?php } ?>
      <div class="yui-g">
         <form name="f" id="f" action="<?php echo$_SERVER['PHP_SELF'];?>" method="get">
         <div class="yui-u first">
            <label for="placeone">First Place </label><input type="text" id="place1" name="place1" value="<?php echo isset($_GET['place1']) ? stripslashes($_GET['place1']) : '';?>"/>
         </div>
         <div class="yui-u">
            <label for="placeone">Second Place </label><input type="text" id="place2" name="place2" value="<?php echo isset($_GET['place2']) ? stripslashes($_GET['place2']) : '';?>"/>
            <input type="submit" value="get the distance"/>
         </div>
         </form>
      </div><!-- end yui-g -->
     </div>
   <div id="ft" role="contentinfo"><p>written by @<a href="http://twitter.com/thinkphp">thinkphp</a> using <a href="vincenty.distance.xml">open data table</a> | <a href="YQL.txt">YQL</a> | <a href="index.phps">source</a> | <a href="controller.phps">controller</a>| <a href="../">version 1</a></p></div>
</div>
</body>
</html>
