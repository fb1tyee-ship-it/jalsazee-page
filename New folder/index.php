<?php
$videosDir = __DIR__ . '/videos';
$thumbsDir = __DIR__ . '/thumbs';

// thumbs ফোল্ডার তৈরি
if (!is_dir($thumbsDir)) mkdir($thumbsDir, 0777, true);

// সব ভিডিও নাও
$videos = glob($videosDir . '/*.{mp4,mkv,avi,webm}', GLOB_BRACE);

// নতুন ভিডিও আগে দেখানো
usort($videos, function($a,$b){ return filemtime($b)-filemtime($a); });
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jalsazee ভিডিও গ্যালারি</title>
<style>
body { font-family: Arial; background:#f9f9f9; margin:0; padding:0; }
h1{text-align:center;padding:20px;}
.video-list{display:flex; flex-wrap:wrap; justify-content:center; gap:15px; padding:0 10px;}
.video-item{width:200px; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); text-align:center;}
.video-item img{width:100%; height:120px; object-fit:cover; cursor:pointer;}
.video-item div{padding:5px;font-size:14px;}
a{text-decoration:none;color:inherit;}
</style>
</head>
<body>
<h1>🎥 Jalsazee ভিডিও গ্যালারি</h1>
<div class="video-list">
<?php foreach($videos as $videoPath):
    $videoFile = basename($videoPath);
    $thumbPath = $thumbsDir.'/'.pathinfo($videoFile, PATHINFO_FILENAME).'.jpg';

    // pre-generated thumbnails assumption
    $thumbUrl="//".$_SERVER['HTTP_HOST']."/thumbs/".urlencode(pathinfo($videoFile, PATHINFO_FILENAME).'.jpg');

    $playUrl="//".$_SERVER['HTTP_HOST']."/play.php?file=".urlencode($videoFile);
?>
    <div class="video-item">
        <a href="<?php echo $playUrl; ?>">
            <img src="<?php echo $thumbUrl; ?>" alt="<?php echo htmlspecialchars($videoFile); ?>">
            <div><?php echo htmlspecialchars($videoFile); ?></div>
        </a>
    </div>
<?php endforeach; ?>
</div>
</body>
</html>
