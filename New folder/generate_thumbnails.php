<?php
$dir = __DIR__ . DIRECTORY_SEPARATOR . "videos";
$ffmpegPath = __DIR__ . DIRECTORY_SEPARATOR . "ffmpeg.exe";

$files = scandir($dir);
$files = array_diff($files, array('.', '..'));
$files = array_values($files);

foreach ($files as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext === "mp4") {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $videoFile = $dir . DIRECTORY_SEPARATOR . $file;
        $thumbFile = $dir . DIRECTORY_SEPARATOR . $filename . ".jpg";

        if (!file_exists($thumbFile)) {
            // ffmpeg command
            $cmd = "\"$ffmpegPath\" -ss 00:00:20 -i \"$videoFile\" -vframes 1 -q:v 2 \"$thumbFile\" 2>&1";

            echo "Running command:<br>$cmd<br><br>";

            exec($cmd, $output, $return_var);

            echo "Return code: $return_var<br>";
            echo "Output:<br><pre>" . implode("\n", $output) . "</pre><br>";

            if ($return_var === 0) {
                echo "✅ Thumbnail created for $file<br><br>";
            } else {
                echo "❌ Error creating thumbnail for $file<br><br>";
            }
        } else {
            echo "✅ Thumbnail already exists for $file<br><br>";
        }
    }
}
?>
