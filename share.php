<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31097297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    <?php
    $nct_id = "";
    $title = "Wowzic, Ứng dụng nghe nhạc cho windows 8";
    if (isset($_GET["M"])) {
        $nct_id = $_GET["M"];
        $url = 'http://www.nhaccuatui.com/bai-hat/a.' . $_GET["M"].'.html';
        if (!isset($_GET['title'])) {
            $str = file_get_contents($url);
            $str = preg_replace('/\s+/', ' ', $str);
            preg_match('/<title>(.+?)<\/title>/', $str, $match);
            $title = $match[1];
        } else {
            $title = $_GET['title'] . ' - ' . $_GET['artist'];
        }
    } else {
        header('location:http://www.facebook.com/wowzic');
    }
    ?>
    <head>
        <title><?php echo $title; ?></title>
        <meta content="Ứng dụng nghe nhạc Wowzic dành cho windows 8" name="description" />
        <meta content="<?php echo $title; ?>" name="keywords" /> 
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link rel="shortcut icon" href="http://www.kenstore.biz/wowzic/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="http://stc.nct.nixcdn.com/static_v8/styles/screen_121017.css" />
        <link rel="alternate" media="handheld" href="http://m.nhaccuatui.com/nghe?M=<?php echo $nct_id ?>" />
        <link rel="canonical" href="http://www.kenstore.biz/wowzic/share.php?M=<?php echo $nct_id ?>" />
        <meta content="index, follow" name="robots" />
        <link rel="image_src" href="http://i1207.photobucket.com/albums/bb465/chunghd126/logo_small.png" />    
        <link rel="video_src" href="http://www.nhaccuatui.com/m/<?php echo $nct_id; ?>" />
        <meta name="video_width" content="300" />
        <meta name="video_height" content="300" />
        <meta name="video_type" content="application/x-shockwave-flash" />

    </head>

    <body> 
<a href="https://plus.google.com/114234053300932221357" rel="author">Kenstore</a>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<div class="container2332">
		<div class="left-banner" style="position:absolute; left:10px; top:10px;" > <a href="http://www.000webhost.com/" onClick="this.href='http://www.000webhost.com/652943.html'" target="_blank"><img src="http://www.000webhost.com/images/banners/160x600/banner1.gif" alt="Web hosting" width="160" height="600" border="0" /></a></div>
<div align="center">
        Bài hát được chia sẻ bằng ứng dụng nghe nhạc Wowzic dành cho windows 8.
        <br/> Link tải ứng dụng cho windows 8: <a href="http://apps.microsoft.com/webpdp/vi-VN/app/wowzic/5c6b1417-ef1d-409a-a20f-ab7c6fe94d57">Link Windows Store</a><br>

<b><?php echo $title;?></b><div class="fb-like" data-href="http://www.kenstore.biz/wowzic/share.php?M=<?php echo $nct_id ?>" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true"></div><br/>
<object width="300" height="300">  <param name="movie" value="http://www.nhaccuatui.com/m/<?php echo $nct_id ?>" />  <param name="quality" value="high" />  <param name="wmode" value="transparent" />  <param name="allowscriptaccess" value="always" />  <param name="flashvars" value="&autostart=false" />  <embed src="http://www.nhaccuatui.com/m/<?php echo $nct_id ?>" flashvars="target=blank&autostart=false" allowscriptaccess="always" quality="high" wmode="transparent" type="application/x-shockwave-flash" width="300" height="300"></embed></object>
<br>
<br>
<br><script language="JavaScript" src="http://www.counter160.com/js.js?img=8"></script><br><a href="http://www.000webhost.com"><img src="http://www.counter160.com/images/8/left.png" alt="Free web hosting" border="0" align="texttop"></a><a href="http://www.hosting24.com"><img alt="Web hosting" src="http://www.counter160.com/images/8/right.png" border="0" align="texttop"></a>
<!-- END OF HIT COUNTER CODE -->
</div>
        <!-- START OF HIT COUNTER CODE -->
		<div class="fb-like-box" data-href="http://www.facebook.com/Wowzic" data-width="292" data-show-faces="true" data-stream="true" data-border-color="green" data-header="true" style="position:absolute; right:10px; top:10px;" ></div>
		</div>


    </body>

</html>
