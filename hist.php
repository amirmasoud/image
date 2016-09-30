<?php
require 'image.php';

$img = new image('assets/img/bird.gif', 'gif');

$img->hist()->diagram();
