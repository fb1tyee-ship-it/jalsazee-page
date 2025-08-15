<?php
header('Content-Type: application/json; charset=utf-8');

// ভিডিও ও থাম্বনেইল ফোল্ডার (একই ফোল্ডার)
$videosDir = __DIR__ . '/videos';

// সার্ভারে ffmpeg এর path (Windows এ)
$ffmpegPath = __DIR__ . '/ffmpeg.exe';

// ভিডিও ফাইল এক্সটেনশন লিস্ট (যেগুলো দেখবে)
$videoExtensions = ['mp4', 'mkv', 'avi', 'mov', 'flv', 'wmv'];

// ভিডিও লিস্ট সংগ্রহের জন্য
$videos = [];

// Helper function: ফাইলের এক্সটেনশন নেয়ার জন্য
function getExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Helper function: ভিডিও থেকে থাম্বনেইল তৈরি
function generateThumbnail($ffmpegPath, $videoPath, $thumbnailPath) {
    // ২০ সেকেন্ড পজিশন থেকে ছবি নেবে
    $cmd = "\"$ffmpegPath\" -ss 20 -i \"$videoPath\" -frames:v 1 -q:v 2 \"$thumbnailPath\" -y";
    exec($cmd, $output, $return_var);
    return $return_var === 0;
}

if (is_dir($videosDir)) {
    $files = scandir($videosDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $ext = getExtension($file);
        if (in_array($ext, $videoExtensions)) {
            $videoPath = $videosDir . '/' . $file;

            // থাম্বনেইল পাথ (সেই ভিডিও নাম + .jpg)
            $thumbnailFileName = pathinfo($file, PATHINFO_FILENAME) . '.jpg';
            $thumbnailPath = $videosDir . '/' . $thumbnailFileName;

            // যদি থাম্বনেইল না থাকে, তাহলে তৈরি কর
            if (!file_exists($thumbnailPath)) {
                generateThumbnail($ffmpegPath, $videoPath, $thumbnailPath);
            }

            // URL base (তুমি তোমার সার্ভার আইপি / ডোমেইন অনুযায়ী ঠিক করবে)
            $baseUrl = "http://160.25.7.148/videos";

            // JSON আউটপুটের জন্য ভিডিও ও থাম্বনেইলের URL
            $videoUrl = $baseUrl . '/' . rawurlencode($file);
            $thumbnailUrl = $baseUrl . '/' . rawurlencode($thumbnailFileName);

            $title = pathinfo($file, PATHINFO_FILENAME);

            // আপলোড/মডিফাই ডেট + সময় (AM/PM সহ)
            $uploadDate = date("Y-m-d h:i A", filemtime($videoPath));

            $videos[] = [
                'title' => $title,
                'description' => $title,
                'videoUrl' => $videoUrl,
                'thumbnailUrl' => $thumbnailUrl,
                'uploadDate' => $uploadDate
            ];
        }
    }

    // নতুন ভিডিও উপরে রাখতে তারিখ অনুসারে sort করো
    usort($videos, function ($a, $b) {
        return strtotime($b['uploadDate']) - strtotime($a['uploadDate']);
    });
}

echo json_encode($videos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
