<?php
// This script will create a logo similar to the one shared by the user

// First, ensure storage directory exists
$storageDir = __DIR__ . '/storage/app/public';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

// Create a new image - wider to accommodate text
$width = 400;
$height = 250;
$img = imagecreatetruecolor($width, $height);

// Make the background transparent
imagesavealpha($img, true);
$trans_colour = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $trans_colour);

// Colors
$lightGreen = imagecolorallocate($img, 107, 170, 100); // #6baa64
$darkGreen = imagecolorallocate($img, 0, 80, 0);
$black = imagecolorallocate($img, 0, 0, 0);

// Draw the green square base (slightly rounded corners)
imagefilledrectangle($img, 150, 30, 250, 130, $lightGreen);

// Add the leaf design - curved line
imagesetthickness($img, 3);
imagearc($img, 200, 80, 80, 100, 0, 180, $darkGreen);
imagesetthickness($img, 4);
imageline($img, 200, 30, 200, 80, $darkGreen);

// Add text "YOHANNES"
$font_size = 20;
$text = "YOHANNES";
imagestring($img, 5, 150, 150, $text, $black);

// Add smaller text "Hovenier & groenonderhoud"
$smallText = "Hovenier & groenonderhoud";
imagestring($img, 3, 120, 175, $smallText, $black);

// Save the image
$targetPath = $storageDir . '/yohannes_logo.png';
imagepng($img, $targetPath);
imagedestroy($img);

echo "Logo has been saved to: " . $targetPath . PHP_EOL;
echo "You can now use this logo in your invoices." . PHP_EOL; 