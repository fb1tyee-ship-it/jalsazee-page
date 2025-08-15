<?php
$videosDir = __DIR__ . '/videos';
$thumbsDir = __DIR__ . '/thumbs';

// সব ভিডিও লিস্ট
$videos = glob($videosDir.'/*.{mp4,mkv,avi,webm}', GLOB_BRACE);
usort($videos,function($a,$b){ return filemtime($b)-filemtime($a); });

// প্লে করার ভিডিও
$videoFile = isset($_GET['file']) ? $_GET['file'] : basename($videos[0]);
$videoPath = $videosDir.'/'.$videoFile;
if(!file_exists($videoPath)) die("ভিডিও পাওয়া যায়নি।");

$videoUrl="//".$_SERVER['HTTP_HOST']."/videos/".rawurlencode($videoFile);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($videoFile); ?></title>
<style>
body{margin:0;font-family:Arial;background:#111;color:#fff;display:flex;flex-direction:column;}
a.back{color:#0af;text-decoration:none;display:block; margin:10px;}
.container{display:flex;flex-wrap:wrap;max-width:1200px;margin:auto;padding:10px;}
.main-video{flex:3;min-width:300px;}
.playlist{flex:1;min-width:200px;max-height:80vh;overflow-y:auto;margin-left:15px;}
.playlist-item{display:flex;margin-bottom:10px;background:#222;border-radius:6px;cursor:pointer;}
.playlist-item img{width:80px;height:50px;object-fit:cover;border-radius:6px 0 0 6px;}
.playlist-item div{padding:5px;font-size:14px;}
.playlist-item:hover{background:#333;}
video{width:100%;max-height:80vh;border-radius:8px;}
@media(max-width:768px){.container{flex-direction:column;}.playlist{margin-left:0;margin-top:15px;max-height:none;}}
</style>
<script>
function playVideo(file){
    window.location.href="?file="+encodeURIComponent(file);
}
</script>
</head>
<body>
<a class="back" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php">⬅ Back to Gallery</a>
<div class="container">
    <div class="main-video">
        <video controls autoplay>
            <source src="<?php echo $videoUrl; ?>" type="video/mp4">
            আপনার ব্রাউজার ভিডিও প্লে করতে পারছে না।
        </video>
        <h3><?php echo htmlspecialchars($videoFile); ?></h3>
    </div>
    <div class="playlist">
        <?php foreach($videos as $v):
            $vFile = basename($v);
            $thumbUrl="//".$_SERVER['HTTP_HOST']."/thumbs/".urlencode(pathinfo($vFile,PATHINFO_FILENAME).'.jpg');
        ?>
        <div class="playlist-item" onclick="playVideo('<?php echo $vFile; ?>')">
            <img src="<?php echo $thumbUrl; ?>" alt="<?php echo htmlspecialchars($vFile); ?>">
            <div><?php echo htmlspecialchars($vFile); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
