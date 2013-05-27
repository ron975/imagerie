<?php

$files = scandir('uploads');
$filetypes = array(
    'png'  => 'png',
    'jpg' => 'jpg',
    'bmp' => 'bmp',
    'gif' => 'gif',
);

# Please set a password
$password = "password";
 
##### HERE LIES DRAGONS #####
session_set_cookie_params(60 * 60 * 24);
session_start();
 
if(empty($password))            die('<strong>Change the default password at gallery.php</strong>');
if($_SESSION['attempts'] >= 10) die('<strong>Please try logging in again in a day</strong>');
 
if ($_POST['key'] == $password) {
    $_SESSION['login']    = true;
    $_SESSION['attempts'] = 0;
} elseif(isset($_POST['key'])) {
    $_SESSION['attempts']++;
}
 
if(isset($_GET['logout'])){
    session_destroy();
    header('Location: gallery.php');
}
 
if($_SESSION['login'] == true) {
    $content = <<<EOF
<body>
    <header>
        <span class="align-right">Imagerie - Gallery</span>
        <span class="align-left"><a class="logout" href="?logout">Logout</a></span>
    </header>
EOF;
 
    $count  = 0;
    $length = 10;
    $start  = $_GET['start'] or $start = 0;
 
    foreach($files as $filename) {
    	if ($filename == '..' || $filename == '.') continue;	
	$filetype = pathinfo("uploads/".$filename, PATHINFO_EXTENSION);
	if (!in_array($filetype, $filetypes)) continue;
       if($count >= ($start + $length)) {
            $content .= '<p><a href="?start=' . ($start - $length) . '">Previous</a> -  - <a href="?start=' . ($start + $length) . '">Next</a></p>';
            break;
        }
        $content .= <<<EOF
            <a class="fancybox" title="{$filename}" caption="<a class='title' href='#' onclick='$.fancybox.prev();'>Previous</a> - <a class='title' target='_blank' href='uploads/{$filename}'>{$filename}</a> - <a class='title' href='#' onclick='$.fancybox.next();'>Next</a> - <a class='title' href='#' onclick='$.fancybox.close();'>Close</a> - <a class='title' href='#' onclick='deleteFile(&quot;{$filename}&quot;)'>Delete</a>" href="uploads/{$filename}?raw"><img class="base gallery" src="uploads/{$filename}?raw" alt="{$filename}"></a>
EOF;
        $count++;
    }
} else {
    $content = <<<EOF
<body class="display">
    <header>
        <span class="align-right">Imagerie - Gallery</span>
    </header>
 
    <div class="loginwrapper">
        <form class="login" name="login" action="#" method="post">
            <input type="password" id="keyinput" name="key">
            <a class="submit" href="#" onclick="parentNode.submit();">login</a>
        </form>
    </div>
EOF;
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gallery - Imagerie</title>
    <link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:300" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
 
    <!--fancybox js-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/fancybox.css?v=2.1.4" type="text/css" media="screen" />
    <script type="text/javascript" src="/js/fancybox.js?v=2.1.4"></script>
    <!--/fancybox js-->
 
    <script type="text/javascript" src="/js/gallery.js"></script>
</head>
 
<?php echo $content; ?>
 
    <footer>
        <span class="align-left">Created by <a href="http://github.com/ron975/">Ronny Chan</a></span>
        <span class="align-right">Powered by <a href="http://gyazo.com/">Gyazo</a></span>
    </footer>
</body>
</html>