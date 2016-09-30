<?php
require 'image.php';

$image1 = new image('assets/img/cat.jpg');
$image2 = new image('assets/img/cat_contrast.jpg');

echo '<br>';
// image file size check
if ($image1->size != $image2->size) {
	echo 'Not same<br>Different sizes.';
}

echo '<br>';
// width and height check
if ($image1->width != $image2->width ||
	$image1->height != $image2->height) {
	echo 'Not same<br>Different dimensions';
}

echo '<br>';
// PHP array comparison
if ($image1 !== $image2) {
	echo 'Not same<br>Different images';
}

echo '<br>';
// For loop comparison
if ($image1->width == $image2->width &&
	$image2->height == $image2->height) {
	for ($x = 0; $x < $image1->width; $x++) {
		for ($y = 0; $y < $image1->height; $y++) {
			if ($image1->pixels[$x][$y] != $image2->pixels[$x][$y]){
				echo 'Not same<br>Different images';
				break 2;
			}
		}
	}
}

echo '<br>';
// Image hash check
if (md5(serialize($image1)) != md5(serialize($image1))) {
	echo 'Not same<br>Different hash';
}