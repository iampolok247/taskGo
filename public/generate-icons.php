<?php
/**
 * PWA Icon Generator from Logo
 * 
 * This script downloads the TaskGo logo and generates all required PWA icons.
 * Run this once: php generate-icons.php
 */

// Your logo URL
$logoUrl = 'https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png';

$sizes = [16, 32, 72, 96, 128, 144, 152, 192, 384, 512];
$iconDir = __DIR__ . '/icons';

if (!is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
}

echo "Downloading logo from: $logoUrl\n";

// Download the logo
$logoData = file_get_contents($logoUrl);
if (!$logoData) {
    die("Error: Could not download logo!\n");
}

// Save original logo temporarily
$tempLogo = $iconDir . '/temp-logo.png';
file_put_contents($tempLogo, $logoData);

// Load the original logo
$originalLogo = imagecreatefrompng($tempLogo);
if (!$originalLogo) {
    // Try as JPEG
    $originalLogo = imagecreatefromjpeg($tempLogo);
}
if (!$originalLogo) {
    // Try as string
    $originalLogo = imagecreatefromstring($logoData);
}

if (!$originalLogo) {
    die("Error: Could not process logo image!\n");
}

$origWidth = imagesx($originalLogo);
$origHeight = imagesy($originalLogo);

echo "Original logo size: {$origWidth}x{$origHeight}\n\n";

// Generate each icon size
foreach ($sizes as $size) {
    // Create new image with transparency
    $icon = imagecreatetruecolor($size, $size);
    
    // Enable alpha blending
    imagealphablending($icon, false);
    imagesavealpha($icon, true);
    
    // Fill with primary color background (for maskable icons)
    $primary = imagecolorallocate($icon, 99, 102, 241); // #6366f1
    imagefill($icon, 0, 0, $primary);
    
    // Calculate padding (10% on each side for maskable safe zone)
    $padding = $size * 0.1;
    $innerSize = $size - ($padding * 2);
    
    // Calculate aspect ratio to fit logo
    $ratio = min($innerSize / $origWidth, $innerSize / $origHeight);
    $newWidth = $origWidth * $ratio;
    $newHeight = $origHeight * $ratio;
    
    // Center the logo
    $x = ($size - $newWidth) / 2;
    $y = ($size - $newHeight) / 2;
    
    // Copy and resize logo onto icon
    imagecopyresampled(
        $icon, $originalLogo,
        $x, $y, 0, 0,
        $newWidth, $newHeight,
        $origWidth, $origHeight
    );
    
    // Save the icon
    $filename = $iconDir . "/icon-{$size}x{$size}.png";
    imagepng($icon, $filename, 9);
    imagedestroy($icon);
    
    echo "✓ Created: icon-{$size}x{$size}.png\n";
}

// Generate splash screens with logo centered
$splashSizes = [
    [640, 1136],
    [750, 1334],
    [1242, 2208],
    [1125, 2436],
];

echo "\nGenerating splash screens...\n";

foreach ($splashSizes as $splash) {
    $width = $splash[0];
    $height = $splash[1];
    
    $image = imagecreatetruecolor($width, $height);
    
    // Primary gradient background
    $primary = imagecolorallocate($image, 99, 102, 241);
    imagefill($image, 0, 0, $primary);
    
    // Add logo in center (20% of width)
    $logoSize = $width * 0.25;
    $ratio = min($logoSize / $origWidth, $logoSize / $origHeight);
    $newWidth = $origWidth * $ratio;
    $newHeight = $origHeight * $ratio;
    
    $x = ($width - $newWidth) / 2;
    $y = ($height - $newHeight) / 2 - ($height * 0.05); // Slightly above center
    
    imagecopyresampled(
        $image, $originalLogo,
        $x, $y, 0, 0,
        $newWidth, $newHeight,
        $origWidth, $origHeight
    );
    
    // Add "TaskGo" text below logo
    $white = imagecolorallocate($image, 255, 255, 255);
    $fontSize = $width * 0.08;
    $textY = $y + $newHeight + ($height * 0.05);
    
    // Center text (approximate)
    $text = "TaskGo";
    $textWidth = strlen($text) * ($fontSize * 0.6);
    $textX = ($width - $textWidth) / 2;
    
    // Use built-in font (no TTF needed)
    $font = 5; // Largest built-in font
    $textX = ($width - (strlen($text) * imagefontwidth($font))) / 2;
    $textY = $y + $newHeight + 30;
    imagestring($image, $font, $textX, $textY, $text, $white);
    
    // Save
    $filename = $iconDir . "/splash-{$width}x{$height}.png";
    imagepng($image, $filename, 9);
    imagedestroy($image);
    
    echo "✓ Created: splash-{$width}x{$height}.png\n";
}

// Clean up
imagedestroy($originalLogo);
unlink($tempLogo);

echo "\n✅ All icons generated successfully from your logo!\n";
echo "Icons are in: {$iconDir}/\n";
