<?php

class fmResizer {

	private $file;			// source file
	private $crop;			// crop params (if needed)
	private $watermark;		// need watermark
	private $fm;			// filemanager instance
	private $settings;		// settings for fm

	private $width; 		// initial width
	private $height; 		// initial height
	private $proportion;	// original image proportion
	private $type;			// image type

	function __construct(&$fm, $file, $type = false, $crop = false) {
		$this->fm 	= $fm;
		$this->file = $file;
		if (!$type) {
			if (!$this->setType($file)) {
				$this->fm->setError("Resizer :: unable to define file type");
			}
		} else {
			$this->type = $mime;
		}
		$this->crop = $crop;	
		#$this->watermark = $watermark;
		$this->settings = $this->fm->getSettings();
	}

	public function initiateResize() {
		if (@!file_exists($this->file)) {
			$this->fm->setError("Resizer :: file does not exist on given path");
			return false;
		}

		$resized = true;

		#$this->crop = array('x' => 200, 'y' => 200, 'w' => 1600, 'h'=> 1000);

		// if added file needs crop, do it first
		if (is_array($this->crop) && !empty($this->crop)) {
			if (!$resized &= $this->crop()) {
				$this->fm->setError('Resizer :: image crop failed');
				return false;
			}
		}

		// if file needs resize
		foreach ($this->settings['resize'] as $item => $set) {
			// getting original size and setting the proportion
			$info = getimagesize($this->file);
	        list($this->width, $this->height) = $info;
	        $this->proportion = $this->width/$this->height;

        	// make copy of the image and resize it
			$new_file_name = $this->fm->getResizedFileName($this->file, $item);
			if ($this->makeImageCopy($this->file, $new_file_name)) {
				if (!isset($set['type'])) $set['type'] = 'cut';
				(isset($set['color'])) ? $color = $set['color'] : $color = array(255,255,255);
				$resized &= $this->resize($new_file_name, $set['w'], $set['h'], $set['type'], $color);
			}
		}

		// after copies are done, add watermarks to original (if needed)
		if ($this->watermark) {
			if (!$image = $this->openImage($this->file)) {
				$this->fm->setError("Resizer :: error opening file (".$this->file.")");
				return false;
			}

			$image = $this->addWatermark($image);
			$this->saveImage($image, $this->file);
		}

		return $resized;
	}

	private function resize($file, $w = 0, $h = 0, $type = 'cut', $color = array(255,255,255)) {
		if (!@file_exists($file)) {
			$this->fm->setError("Resizer :: file does not exist (resize : ".$file.")");
			return false;
		}

		if ($w == 0 && $h == 0) {
			$this->fm->setError("Resizer :: resizer got invalid params");
			return false;
		}

		if ($w == 0) {
			$w = round($h * $this->proportion);
		} elseif ($h == 0) {
			$h = round($w / $this->proportion);
		}
		$new_proportion = $w/$h;

		// del this
		$this->watermark = false;

		switch ($type) {
			case 'cut':
				if ($this->proportion > $new_proportion) {
					$src_w = round($this->height * $new_proportion);
					$src_h = $this->height;
					$src_x = round(($this->width - $src_w)/2);
					$src_y = 0;
				} else {
					$src_w = $this->width;
					$src_h = round($this->width / $new_proportion);
					$src_x = 0;
					$src_y = round(($this->height - $src_h)/2);
				}

				if ($source_image = $this->openImage($file)) {
					$new_image = imagecreatetruecolor($w, $h);
					#echo("From: ".$src_x." - ".$src_y." using ".$src_w."x".$src_h);
					imagecopyresampled($new_image, $source_image, 0, 0, $src_x, $src_y, $w, $h, $src_w, $src_h);

					// if image needs watermarks
					if ($this->watermark) {
						$new_image = $this->addWatermark($new_image);
					}

					return $this->saveImage($new_image, $file);
				}
				return false;
			case 'fill':
				if ($this->proportion > $new_proportion) {
					$filled_width = $w;
					$filled_height = $h / $this->proportion;
					$dst_x = 0;
					$dst_y = round(($h - $filled_height)/2);
				} else {
					$filled_width = $w * $this->proportion;
					$filled_height = $h;
					$dst_x = round(($w - $filled_width)/2);
					$dst_y = 0;
				}
				if ($source_image = $this->openImage($file)) {
					$new_image = imagecreatetruecolor($filled_width, $filled_height);
					imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, 
						$filled_width, $filled_height, $this->width, $this->height);

					// if image needs watermarks
					if ($this->watermark) {
						$new_image = $this->addWatermark($new_image);
					}

					$empty_image = imagecreatetruecolor($w, $h);
					$color = imagecolorallocatealpha($empty_image, $color[0], $color[1], $color[2], $color[3]);
					imagefill($empty_image, 0, 0, $color);

					#echo("To: ".$dst_x." - ".$dst_y." using ".$filled_width."x".$filled_height);
					imagecopy($empty_image, $new_image, $dst_x, $dst_y, 0, 0, $filled_width, $filled_height);
					return $this->saveImage($empty_image, $file);
				}

				return false;
		}
	}

	private function setType($file) {
		$mime = mime_content_type($file);
		switch($mime) {
			case 'image/jpeg':
				$this->type = "jpg";
				return true;
			case 'image/png':
				$this->type = "png";
				return true;
			case 'image/gif':
				$this->type = "gif";
				return true;
			default:
				return false;
		}
	}

	private function crop() {
		// no param - works only for original
		if ($image = $this->openImage($this->file)) {
			if (is_numeric($this->crop['x'])
				&& is_numeric($this->crop['y'])
				&& is_numeric($this->crop['w'])
				&& is_numeric($this->crop['h'])) {
					$new_image = imagecreatetruecolor($this->crop['w'], $this->crop['h']);
   					imagecopyresampled($new_image, $image, 0, 0, 
   						$this->crop['x'], $this->crop['y'], 
   						$this->crop['w'], $this->crop['h'], 
   						$this->crop['w'], $this->crop['h']);
   					return $this->saveImage($new_image, $this->file);
			}
		}

		return false;
	}

	private function addWatermark($image) {
		// image resource is given, should be called for every image
		$h = imagesy($image);
		$w = imagesx($image);

		// count line periodicity and margins
		// (just to make it look pretty)
		if ($h > 400)
			$line_num = 10;
		elseif ($h > 100)
			$line_num = 5;
		elseif ($h >= 50)
			$line_num = 3;
		else
			$line_num = 2;

		$margin = floor($h / ($line_num + 1));
		$margin_top = floor(($h - ($margin * ($line_num - 1))) / 2);

		if ($w > 200)
			$margin_side = 20;
		else 
			$margin_side = ceil($w * 0.075);

		// draw lines on image
		for ($i = 0; $i < $line_num; $i++) {
			$y_pos = $margin_top + ($margin * $i);
			$x_pos_start = $margin_side;
			$x_pos_end = $w - $margin_side;
			$color = imagecolorallocatealpha($image, 255, 0, 0, 74);
			imageline($image, $x_pos_start, $y_pos, $x_pos_end, $y_pos, $color);
		}

		return $image;
	}

	private function makeImageCopy($source, $dest) {
		if (!@copy($source, $dest)) {
			$this->fm->setError("Resizer :: error while making image copy (".$source." -> ".$dest.")");
			return false;
		}

		return true;
	}

	private function openImage($file) {
		switch($this->type)	{
			case 'jpg':
				return @imagecreatefromjpeg($file);
			case 'png':
				return @imagecreatefrompng($file);
			case 'gif':
				return @imagecreatefromgif($file);
			default:
				$this->fm->setError("Resizer :: file is not an image");
				return false;
		}

		return false;
	}

	private function saveImage($image, $path, $rewrite = true, $quality = 100) {
		if(trim($path) == '' || $image === false) {
			$this->fm->setError("Resizer :: error while saving resized/croped image");
			return false;
		}

		switch($this->type)	{
			case 'jpg':
				if(!$rewrite && @file_exists($path)) return false;
				if(!is_numeric($quality) || $quality < 0 || $quality > 100) $quality = 100;
				imagejpeg($image, $path, $quality);
				return true;
			case 'png':
				if(!$rewrite && @file_exists($path)) return false;
				imagepng($image, $path);
				return true;
			case 'gif':
				if(!$rewrite && @file_exists($path)) return false;
				imagegif($image, $path);
				return true;
			default:
				return false;
		}
	}
}

?>