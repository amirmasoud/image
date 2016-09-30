<?php

/**
* Reading Image core class
*/
class image
{
	/**
	 * Image source file.
	 * @var string
	 */
	public $resource;

	/**
	 * Image width.
	 * @var integer
	 */
	public $width;

	/**
	 * Image height.
	 * @var integer
	 */
	public $height;

	/**
	 * Path to image.
	 * @var string
	 */
	public $path;

	/**
	 * Image size.
	 * @var integer bytes
	 */
	public $size;

	/**
	 * Image pixels as 2 dimentionals array.
	 * @var array
	 */
	public $pixels;

	/**
	 * Image histogram array
	 * @access private
	 * @var array
	 */
	private $hist;

	/**
	 * Read image from given resource.
	 * @param string $resource
	 */
	public function __construct($resource) 
	{
		$this->setResource($resource);
		$this->setWidth();
		$this->setHeight();
		$this->setSize();
		$this->read();
	}

	/**
	 * get image histogram
	 * @param integer $height height of diagram, default 300
	 * @param integer $bar length of bar, default 2
	 * @return obj
	 */
	public function hist()
	{
		$this->hist = array_fill(0, 256, 0);
		for($x = 0; $x < $this->width; $x++) {
			for($y = 0; $y < $this->height; $y++) {               
				$rgb = $this->colorAt($x, $y);
				$V = round(array_sum($rgb) / 3);

				// add the point to the histogram
				$this->hist[$V] += $V / ($this->width * $this->height);
			}
		}
		return $this;
	}

	/**
	 * Print histogram diagram
	 * @param  integer $height
	 * @param  integer $bar
	 * @return HTML
	 */
	public function diagram($height = 300, $bar = 2)
	{
		echo "<div style='width: " . (255 * $bar) . "px; border: 1px solid #000'>";
		for ($i=0; $i<255; $i++)
		{
			$h = ( $this->hist[$i]/max($this->hist) ) * $height;
			echo "<img src='assets/img/img.png' width='" . $bar . "' height='" . $h ."'>";
		}
		echo "</div>";
	}

	/**
	 * Print or return array
	 * @param  boolean $pretty
	 * @return array
	 */
	public function toArray($pretty = true) 
	{
		if ($pretty == true) {
			echo '<pre>';
			print_r($this);
			echo '</pre>';
			return;
		} else {
			return $this;
		}
	}

	/**
	 * Read image RGB color.
	 * @return array
	 */
	private function read()
	{
		for($x = 0; $x < $this->width; $x++) {
			for($y = 0; $y < $this->height; $y++) {
				$this->setRgbColor($x, $y);
			}
		}
	}

	/**
	 * Set image width property.
	 * @access private
	 */
	private function setWidth()
	{
		$this->width = imagesx($this->resource);
	}

	/**
	 * Set image height property.
	 * @access private
	 */
	private function setHeight()
	{
		$this->height = imagesy($this->resource);
	}

	/**
	 * Get RGB color of given pixel.
	 * @param  integer $x
	 * @param  integer $y
	 */
	private function setRgbColor($x, $y) 
	{
		$this->pixels[$x][$y] = [];
		$rgb = $this->colorAt($x, $y);
		$this->pixels[$x][$y] = 'rgb(' . implode(', ', $rgb) . ')';
	}

	/**
	 * RGB color at given coordinations.
	 * @param  width $x
	 * @param  height $y
	 * @return array
	 */
	private function colorAt($x, $y)
	{
		$rgb = imagecolorat($this->resource, $x, $y);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		return [$r, $g, $b];
	}

	/**
	 * Create image form jpeg resource.
	 * @param string $resource
	 */
	private function setResource($resource)
	{
		$this->path = $resource;
		$ext  = pathinfo($this->path, PATHINFO_EXTENSION);
		if ($ext == 'jpeg') {
			$this->resource = imagecreatefromjpeg($resource);
		} elseif ($ext == 'gif') {
			$this->resource = imagecreatefromgif($resource);
		}
	}

	private function setSize()
	{
		$this->size = filesize($this->path);
	}
}
