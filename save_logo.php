<?php
// This script will save the attached logo to the storage/app/public directory

// First, ensure storage directory exists
$storageDir = __DIR__ . '/storage/app/public';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

// Create the logo from scratch using GD
$width = 200;
$height = 200;
$img = imagecreatetruecolor($width, $height);

// Make the background transparent
imagesavealpha($img, true);
$trans_colour = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $trans_colour);

// Green square with rounded corners
$green = imagecolorallocate($img, 107, 170, 100); // #6baa64
$darkGreen = imagecolorallocate($img, 0, 100, 0);
$white = imagecolorallocate($img, 255, 255, 255);

// Draw the green square base
imagefilledrectangle($img, 40, 40, 160, 160, $green);

// Add a simple leaf design
$points = array(
    100, 40,  // Top of leaf
    140, 80,  // Right curve
    100, 120, // Bottom
    60, 80,   // Left curve
);
imagefilledpolygon($img, $points, 4, $darkGreen);

// Add a stem
imageline($img, 100, 120, 100, 160, $darkGreen);
imagesetthickness($img, 3);

// Save the image
$targetPath = $storageDir . '/yohannes_logo.png';
imagepng($img, $targetPath);
imagedestroy($img);

echo "Logo has been saved to: " . $targetPath . PHP_EOL;
echo "You can now use this logo in your invoices." . PHP_EOL; 