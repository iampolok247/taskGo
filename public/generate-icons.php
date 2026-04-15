<?php
/**
 * PWA Icon Generator
 * 
 * This script generates placeholder PNG icons for the PWA.
 * Run this once: php generate-icons.php
 * 
 * For production, replace these with proper designed icons.
 */

$sizes = [16, 32, 72, 96, 128, 144, 152, 192, 384, 512];
$iconDir = __DIR__ . '/icons';

if (!is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
}

foreach ($sizes as $size) {
    $image = imagecreatetruecolor($size, $size);
    
    // Enable alpha blending
    imagealphablending($image, false);
    imagesavealpha($image, true);
    
    // Colors
    $primary = imagecolorallocate($image, 99, 102, 241); // #6366f1
    $white = imagecolorallocate($image, 255, 255, 255);
    $green = imagecolorallocate($image, 34, 197, 94); // #22c55e
    
    // Fill background with primary color
    imagefill($image, 0, 0, $primary);
    
    // Draw rounded rectangle effect (simplified)
    $cornerRadius = $size * 0.2;
    
    // Draw a simple checkmark/task icon
    $centerX = $size / 2;
    $centerY = $size / 2;
    $iconSize = $size * 0.4;
    
    // Draw clipboard rectangle
    $clipLeft = $centerX - $iconSize * 0.6;
    $clipTop = $centerY - $iconSize * 0.6;
    $clipRight = $centerX + $iconSize * 0.6;
    $clipBottom = $centerY + $iconSize * 0.8;
    
    imagesetthickness($image, max(1, $size / 30));
    imagerectangle($image, $clipLeft, $clipTop, $clipRight, $clipBottom, $white);
    
    // Draw checkmark
    $checkStartX = $centerX - $iconSize * 0.3;
    $checkMidX = $centerX - $iconSize * 0.05;
    $checkEndX = $centerX + $iconSize * 0.4;
    $checkStartY = $centerY;
    $checkMidY = $centerY + $iconSize * 0.25;
    $checkEndY = $centerY - $iconSize * 0.2;
    
    imagesetthickness($image, max(2, $size / 15));
    imageline($image, $checkStartX, $checkStartY, $checkMidX, $checkMidY, $green);
    imageline($image, $checkMidX, $checkMidY, $checkEndX, $checkEndY, $green);
    
    // Save the image
    $filename = $iconDir . "/icon-{$size}x{$size}.png";
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "Created: icon-{$size}x{$size}.png\n";
}

// Generate splash screens
$splashSizes = [
    [640, 1136],
    [750, 1334],
    [1242, 2208],
    [1125, 2436],
];

foreach ($splashSizes as $splash) {
    $width = $splash[0];
    $height = $splash[1];
    
    $image = imagecreatetruecolor($width, $height);
    
    // Gradient-like background
    $primary = imagecolorallocate($image, 99, 102, 241);
    imagefill($image, 0, 0, $primary);
    
    // Save
    $filename = $iconDir . "/splash-{$width}x{$height}.png";
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "Created: splash-{$width}x{$height}.png\n";
}

echo "\nAll icons generated successfully!\n";
echo "For production, please replace with professionally designed icons.\n";
