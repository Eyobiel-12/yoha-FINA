<?php
// Create a 200x100 image with green background
$im = imagecreatetruecolor(200, 100);
$green = imagecolorallocate($im, 107, 170, 100); // #6baa64
$white = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $green);

// Add text
imagestring($im, 5, 40, 40, "YOHANNES", $white);

// Make sure the storage directory exists
$dir = __DIR__ . '/storage/app/public';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Save the image
imagepng($im, $dir . '/logo.png');
imagedestroy($im);

echo "Logo created successfully!\n"; 