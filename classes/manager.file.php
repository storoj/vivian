<?

class FileManager {
	public static $instance;
	private $table;
	public $errors = array();
    public $error_flag = false;

	private $active_repository;
	private $settings = array();

	private $resizible_formats = array('jpg', 'jpeg', 'png', 'gif');
    private $width;
    private $height;

	// restricted extensions
	private $restricted_ext = array( 'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'exe', 
				'hta', 'ins', 'isp', 'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr',
				'sct', 'shb', 'sys', 'vb',  'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh');

	// restricted types (mime)
	private $restricted_mime = array('application/octet-stream', 'application/x-sh');

	public static function getInstance() {
		if (!isset(self::$instance)) {
			if (!defined("DB_NAME")) {
				echo("U must define \"DB_NAME\" constant to use this class");
				return false;
			}
			self::$instance = new FileManager();
		}

		return self::$instance;
	}

	function __construct ()	{
		if (defined("DB_PREFIX")) $this->prefix = DB_PREFIX;
		if (defined("DB_NAME")) $dbname = DB_NAME;
		$this->table = $this->prefix."filemanager";

        $sql = "SHOW TABLES LIKE '".$this->table."'";
		$exists = @mysql_num_rows(mysql_query($sql));
		if ($exists == 0) {
			$this->CreateRepositoryTable();
		}
	}

	private function createRepositoryTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->table."` (
				`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`name` VARCHAR(64) NOT NULL,
				`description` TEXT NOT NULL,
				`nesting` INT(4) DEFAULT 1,
				`root` VARCHAR(256) NOT NULL,
				`allowed_ext` TEXT NOT NULL,
				`min_file_size` INT(11) DEFAULT 0,
				`max_file_size` INT(11) DEFAULT 0,
				`resize` TEXT NOT NULL,
				`min_img_size` VARCHAR(64) NOT NULL,
				`max_img_size` VARCHAR(64) NOT NULL
			) ENGINE=MYISAM DEFAULT CHARSET=UTF8";
		if (!mysql_query($sql)) {
			$this->setError("Ошибка при создании таблицы файлового менеджера! (error: ".mysql_error().")");
            die (mysql_error());
			return false;
		}

		return true;
	}

	public function addRepository($name, $root, $settings, $description = '') {

		$sql = "SELECT COUNT(*) FROM `".$this->table."` WHERE `name` = '".$name."'";
        $res = mysql_query($sql) or die (mysql_error());
		$res = mysql_fetch_row($res);

		if ($res[0] != 0) {
			$this->setError('Репозиторий уже существует!');
			return false;
		}

        // max file size
        if (isset($settings['max_file_size'])) {
            $max_size = $settings['max_file_size'];
            if (!is_numeric($max_size))
                $max_size = $this->getNumericSize($max_size);
        } else {
            $max_size = 0;
        }

        // min file size
        if (isset($settings['min_file_size'])) {
            $min_size = $settings['min_file_size'];
            if (!is_numeric($min_size))
                $min_size = $this->getNumericSize($min_size);
        } else {
            $min_size = 0;
        }

        // nesting
        if (isset($settings['nesting']) && is_numeric($settings['nesting'])) {
            $nesting = $settings['nesting'];
        } else {
            $nesting = 3;
        }


		// add new repository to main filemanager table
		$sql = "INSERT INTO `".$this->table."` SET 
			`name` = 			'".$name."',
			`description` = 	'".mysql_real_escape_string($description)."',
			`root` = 			'".mysql_real_escape_string($root)."',
			`nesting` = 		'".$nesting."',
			`allowed_ext` = 	'".implode(",", $settings['allowed_ext'])."',
			`min_file_size` = 	'".$min_size."',
			`max_file_size` = 	'".$max_size."',
			`resize` = 			'".serialize($settings['resize'])."',
			`min_img_size` =    '".serialize($settings['min_img_size'])."',
			`max_img_size` =    '".serialize($settings['max_img_size'])."'";
		if (!mysql_query($sql)) {
			$this->setError("Ошибка при добавлении репозитория
				(sql: ".$sql." error: ".mysql_error().")");
            die(mysql_error());
			return false;
		}

		// creating main directory for all files
		if (!is_dir($root.$name)) {
			if (!@mkdir($root.$name, 0777, true))
				$this->setError("Не удалось создать репозиторий");
		}

		// creating table for for repository
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->prefix.$name."_rep` (
				`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`date` INT(11) NOT NULL,
				`full_path` VARCHAR(256) NOT NULL,
				`ext` VARCHAR(4) NOT NULL,
				`original_name` VARCHAR(256) NOT NULL,
				`size` INT(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=UTF8";
		if (!mysql_query($sql)) {
			$this->setError("Ошибка при создании таблицы для репозитория &laquo;".$name."&raquo
				(sql: ".$sql." error: ".mysql_error().")");
			return false;
		}

		$this->errors = array();
		$this->setRepository($name);

		return true;
	}

	public function setRepository($name = '') {
		if (!$name) {
			// if name is not specified choose first repository (if any exists)
			$sql = "SELECT * FROM `".$this->table."` ORDER BY `id` LIMIT 1";
		} else {
			$sql = "SELECT * FROM `".$this->table."` WHERE `name` = '".$name."' LIMIT 1";
		}
		$res = @mysql_fetch_assoc(mysql_query($sql));

		if (!$res) {
			if (!$name) 
				$this->setError("Репозиторий не выбран");
			else
				$this->setError("Репозиторий &laquo;".$name."&raquo; не найден");
			return false;
		}

		if (!is_dir($res['root'].$res['name'])) {
			$this->setError("Для репозитория &laquo;".$res['name']."&raquo; задан неверный путь");
			return false;
		}

		$this->active_repository = $res['name'];
		$this->settings = array(
			'rep_table' =>  	$this->prefix.$res['name']."_rep",
			'file_root' => 		$res['root'].$res['name']."/",
			'absolute_path' => 	$_SERVER['DOCUMENT_ROOT']."/".$res['root'].$res['name']."/",
            'nesting' => 		$res['nesting'],
			'ext' => 			explode(",", $res['allowed_ext']),
			'min_file_size' => 	$res['min_file_size'],
			'max_file_size' => 	$res['max_file_size'],
            'resize' => 		unserialize($res['resize']),
            'min_img_size' =>   unserialize($res['min_img_size']),
            'max_img_size' =>   unserialize($res['max_img_size']),
			'description' =>	$res['description']
		);

		return true;
	}

	public function showRepositoryInfo() {
		$sql = "SELECT COUNT(*) FROM `".$this->settings['rep_table']."`";
		$num = mysql_fetch_row(mysql_query($sql));

		$str = "<p>
			&laquo;".ucfirst($this->active_repository)."&raquo; repository info:<br/>
			Files added: ".$num[0]."<br/>";
			if ($this->settings['description']) 
				$str .= "Description: ".$this->settings['description']."<br/>";
			$str .= "Repository table: ".$this->settings['rep_table']."<br/>
			Nesting level: ".$this->settings['nesting']."<br/>";
			if (is_array($this->settings['ext']) && !empty($this->settings['ext'])) 
				$str .= "Allowed file extensions: ".implode(", ", $this->settings['ext'])."<br/>";
			if ($this->settings['max_size'] > 0)
				$str .= "Max file size: ".($this->settings['max_size']/1024)."Kb<br/>";
			if (is_array($this->settings['resize']) && !empty($this->settings['resize'])) {
				$str .= "Resizes for image files: ";
				$sizes = array();
				foreach($this->settings['resize'] as $k=>$el) {
					$sizes[] = $k." - ".$el['w']."x".$el['h'];
				}
				$str .= implode(", ", $sizes);
			}
			$str .= "</p>";
		return $str;
	}

	/*
	* working with tmp file
	*/
	public function addFile($file_path, $file_name = '', $del_source = true, $crop = false) {
		if (!file_exists($file_path)) {
			$this->setError("Не возможно добавить файл в репозиторий (файл не найден по заданному пути)");
			return false;
		}
		
		if ($file_name) {
			$tmp = explode('.', $file_name);
	        $ext = $tmp[count($tmp)-1];
	        unset($tmp[count($tmp)-1]);
	        $file_name = implode("", $tmp);
		} else {
			$file_name = pathinfo($file_path, PATHINFO_FILENAME);
			$ext = pathinfo($file_path, PATHINFO_EXTENSION);
		}

		$size = filesize($file_path);
		$mime = $this->getMimeType($file_path);
		//echo("<br/>".$ext.", ".$file_name.", ".$size.", ".$mime);

		// checking file
		if (!$this->checkFile($ext, $size, $mime, $file_path)) {
			@unlink($file_path);
			return false;
		}

		// adding file to get id
		$sql = "INSERT INTO `".$this->settings['rep_table']."` SET 
			`original_name` = '".$file_name."'";
		if (!mysql_query($sql)) {
			$this->setError("Ошибка при добавлении записи файла в таблицу репозитория (".$this->settings['rep_table'].")");
			return false;
		}
		$file_id = mysql_insert_id();

		// creating path
		$hash = md5($file_id);
		if (!is_array($splited = $this->splitStr($hash, $this->settings['nesting']))) {
			$this->delIncompleteRecord($file_id);
			$this->setError("Не удается создать необходимую директорию");
			return false;
		}

		// creating directory (if needed)
		$move_to = $this->settings['absolute_path'].implode("/", $splited);
		if (!is_dir($move_to)) {
			// recursive directory creation
			if (!@mkdir($move_to, 0777, true)) {
				$this->delIncompleteRecord($file_id);
				$this->setError("Не удается создать необходимую директорию для указанного файла (".$move_to.")");
				return false;
			}
		}

		// using full path with file name
		$move_to .= "/".$file_id.".".$ext;

		// moving file to repository
		if (is_uploaded_file($file_path)) {
			if (!move_uploaded_file($file_path, $move_to)) {
				$this->delIncompleteRecord($file_id);
				$this->setError("Не удается переместить указанный файл в репозиторий (uploaded: ".$file_path." to ".$move_to.")");
				return false;
			}
		} else {
			if ($del_source) {
				if (!rename($file_path, $move_to)) {
					$this->delIncompleteRecord($file_id);
					$this->setError("Не удается переместить указанный файл в репозиторий (".$file_path." to ".$move_to.")");
					return false;
				}
			} else {
				if (!copy($file_path, $move_to)) {
					$this->delIncompleteRecord($file_id);
					$this->setError("Не удается скопировать указанный файл в репозиторий (".$file_path." to ".$move_to.")");
					return false;
				}
			}
		}


		// if uploaded file is an image
		$mime_parts = explode("/", $mime);
		if ($mime_parts[0] == 'image') {
            if (is_array($this->settings['resize'])
			&& !empty($this->settings['resize'])
			&& in_array($ext, $this->resizible_formats)) {
				$resizer = new fmResizer($this, $move_to);
				if (!$resizer->initiateResize()) {
					$this->setError("Ошибка при ресайзе изображения");
				}
            }
		}


		// compliting
		$full_path = '/'.$this->settings['file_root']
					.implode("/", $splited)
					."/".$file_id.".".$ext;
		$sql = "UPDATE `".$this->settings['rep_table']."` SET 
			`full_path` = 	'".$full_path."',
			`date` = 		'".time()."',
			`ext` = 		'".$ext."',
			`size` = 		'".$size."'
			WHERE `id` = '".$file_id."'";
		if (!mysql_query($sql)) {
			$this->delIncompleteRecord($file_id);
			$this->setError("Ошибка при сохранении записи в таблице репозитория (error: ".mysql_error().")");
			return false;
		}

		// del this later
		//echo("File added! id: ".$file_id);

		return array('id' => $file_id, 'path' => $full_path,
            'size' => $size, 'img_size' => $this->width.'x'.$this->height);
	}

	public function deleteFile($id) {

        // check if id or full_path is given
        if(is_numeric($id)) {
            $condition = " `id` = '".$id."' ";
        } else {
            $condition = " `full_path` = '".$id."' ";
        }

		$sql = "SELECT `id`, `full_path` FROM `".$this->settings['rep_table']."` WHERE ".$condition;
		if (!$res = mysql_query($sql)) {
			$this->setError("Не удалось найти указанный файл в репозитории");
			return false;
		}
		$res = mysql_fetch_assoc($res);
		$path = $_SERVER['DOCUMENT_ROOT']."/".$res['full_path'];

		if (!file_exists($path)) {
			$this->setError("Не удалось найти указанный файл в репозитории");
			return false;
		}

		if (!@unlink($path)) {
			$this->setError("Не удалось удалить указанный файл из репозитория");
			return false;
		}

		// deleting of resized copies gona be here
        foreach($this->settings['resize'] as $key => $el) {
            $res_path = self::getResizedFileName($res['full_path'], $key);
            if (!@unlink($_SERVER['DOCUMENT_ROOT']."/".$res_path)) {
                $this->setError("Не удалось удалить копию изображения :: ".$res_path);
                return false;
            }
        }

		$sql = "DELETE FROM `".$this->settings['rep_table']."` WHERE ".$condition;
		if (!mysql_query($sql)) {
			$this->setError("Не удалось удалить запись из таблицы репозитория для удаленного файла");
			return false;
		}

		return true;
	}

	public function replaceFile($old_id, $file_path, $file_name = '', $del_source = false) {
		$file_info = $this->addFile($file_path, $file_name, $del_source);

		if (isset($file_info['id']) && $file_info['id']) $this->deleteFile($old_id);
		return $file_info;
	}

	private function delIncompleteRecord($id) {
		$sql = "DELETE FROM `".$this->settings['rep_table']."` WHERE `id` = '".$id."'";
		mysql_query($sql);
	}

	private function splitStr($str, $nesting, $block = 2) {
		$need_size = $nesting * $block;
		if (strlen($str) >= $need_size) {
			$splited = array();
			for ($i = 0; $i < $nesting; $i++) {
				$splited[] = substr($str, $block * $i, $block);
			}

			return $splited;
		}

		return false;
	}

	/*
	* check files for the restrictions
	* allowed formats, max file size, mime
	*/
	private function checkFile($ext, $size, $mime, $file_path) {
		// check file size (if needed)
		if ($this->settings['max_file_size'] > 0) {
			if ($size > $this->settings['max_file_size']) {
				$this->setError("Указанный файл слишком большой");
				return false;
			}
        }
        if ($this->settings['min_file_size'] > 0) {
            if ($size < $this->settings['min_file_size']) {
                $this->setError("Указанный файл слишком маленький");
                return false;
            }
        }

        // image size check
        $mime_parts = explode("/", $mime);
        if ($mime_parts[0] == 'image') {
            $image = $this->openImage($file_path, $this->setType($mime));
            $h = imagesy($image);
            $w = imagesx($image);

            $min_img = $this->settings['min_img_size'];
            if (is_array($min_img) && !empty($min_img)) {
                if ($min_img['w'] > 0 && $w < $min_img['w']) {
                    $this->setError("Файл должен быть не менее ".$min_img['w']."px в ширину");
                    return false;
                }
                if ($min_img['h'] > 0 && $h < $min_img['h']) {
                    $this->setError("Файл должен быть не менее ".$min_img['h']."px в высоту");
                    return false;
                }
            }

            $max_img = $this->settings['max_img_size'];
            if (is_array($max_img) && !empty($max_img)) {
                if ($max_img['w'] > 0 && $w > $max_img['w']) {
                    $this->setError("Файл должен быть не более ".$max_img['w']."px в ширину");
                    return false;
                }
                if ($max_img['h'] > 0 && $h > $max_img['h']) {
                    $this->setError("Файл должен быть не более ".$max_img['h']."px в высоту");
                    return false;
                }
            }

            $this->height = $h;
            $this->width  = $w;
        }

		// simple extension check
		if (in_array($ext, $this->restricted_ext)) {
			$this->setError("Файлы указанного типа запрещены к загрузке на сервер");
			return false;
		}

		// check file mime-type
		if (in_array($mime, $this->restricted_mime)) {
			$this->setError("Файлы указанного типа запрещены к загрузке на сервер");
			return false;
		}

		// check using repository settings (if needed)
		if (is_array($this->settings['ext']) && !empty($this->settings['ext'])) {
			if (!in_array($ext, $this->settings['ext'])) {
				$this->setError("Файлы указанного типа запрещены к загрузке в репозиторий");
				return false;
			}
		}

		return true;
	}

	private function getMimeType($file_path) {
		$file_info = new finfo(FILEINFO_MIME);
		$mime_type = $file_info->buffer(file_get_contents($file_path));
		$parts = explode(";", $mime_type);

		return trim($parts[0]);
	}

	public function getFilePath($id, $type = NULL) {
		$sql = "SELECT `full_path` FROM `".$this->settings['rep_table']."` WHERE `id` = '".$id."'";
		$res = mysql_fetch_assoc(mysql_query($sql));

		if (!$res) {
			$this->setError("Не удалось найти указанный файл (несуществующий id)");
			return false;
		}

		$path = $res['full_path'];

		if ($type == 'host') {
			return $_SERVER['HTTP_HOST']."/".$path;
		} elseif ($type == 'abs') {
			return $_SERVER['DOCUMENT_ROOT']."/".$path;
		} else {
			return $path;
		}
	}

	public function getFileName($id) {
		$sql = "SELECT `original_name`, `ext` FROM `".$this->settings['rep_table']."` WHERE `id` = '".$id."'";
		$res = mysql_fetch_assoc(mysql_query($sql));

		if (!$res) {
			$this->setError("Не удалось получить имя файла (несуществующий id)");
			return false;
		}

		return $res['original_name'].".".$res['ext'];
	}

	public static function getResizedFileName($path_mod, $modifier) {
        // todo :: remake
		$path = explode("/", $path_mod);
		$path[count($path) - 1] = preg_replace("/([\d]*)[^.]*\.([\w]{3,4})$/", "$1_".$modifier.".$2", $path[count($path) - 1]);

        $new_path = implode("/", $path);

        //if (file_exists(PATH_ROOT.$new_path))
		    return $new_path;

        //return $path_mod;
	}

    public static function getIdFromPath($path) {
        $path = explode("/", $path);
        $path = $path[count($path) - 1];
        preg_match('/([\d]*)[^.]*\.([\w]{3,4})$/', $path, $matches);

        return $matches[1];
    }

	public function getFile($id) {
		$sql = "SELECT `original_name`, `ext`, `full_path`, `size`, `date`
				FROM `".$this->settings['rep_table']."` WHERE `id` = '".$id."'";
		$file_info = mysql_fetch_assoc(mysql_query($sql));

		if (!$file_info) {
			$this->setError("Не удалось получить информацию о файле (несуществующий id)");
			return false;
		}

		$path = $_SERVER['DOCUMENT_ROOT']."/".$file_info['full_path'];

		if (!file_exists($path)) {
	        header ("HTTP/1.0 404 Not Found");
	        exit;
	    }

	    $name = $file_info['original_name'].".".$file_info['ext'];
	    //$fsize = $file_info['size'];
	    $fsize = filesize($path);
	    $ftime = date("D, d M Y H:i:s T", $file_info['date']);
	    $fd = @fopen($path, "rb");
	    if (!$fd){
	      header ("HTTP/1.0 403 Forbidden");
	      exit;
	    }

	    if ($_SERVER["HTTP_RANGE"]) {
	        $range = $_SERVER["HTTP_RANGE"];
	        $range = str_replace("bytes=", "", $range);
	        $range = str_replace("-", "", $range);
	        if ($range) {fseek($fd, $range);}
	    }
	    $content = fread($fd, $fsize);
	    fclose($fd);

	    if ($range) {
	        header("HTTP/1.1 206 Partial Content");
	    } else {
	        header("HTTP/1.1 200 OK");
	    }

	    header("Content-Disposition: attachment; filename=".$name);
	    header("Last-Modified: $ftime");
	    header("Accept-Ranges: bytes");
	    header("Content-Length: ".($fsize-$range));
	    header("Content-Range: bytes $range-".($fsize -1)."/".$fsize);
	    header("Content-type: application/octet-stream");

	    print $content;
	    exit;
	}

    private function getNumericSize($max_size) {
        // get size from symbol value
        $multipliers = array('K' => 1024, 'M' => 1048576, 'G' => 1073741824);

        if (preg_match("#^([\d]+)([\w]{1})$#", $max_size, $res)) {
            $mult = strtoupper($res[2]);
            if (array_key_exists($mult, $multipliers)) {
                $max_size = $res[1] * $multipliers[$mult];
            } else {
                $this->setError('Настройки репозитория неверны');
                return false;
            }
        } else {
            $this->setError('Настройки репозитория неверны');
            return false;
        }

        return $max_size;
    }

	public function getSettings() {
		return $this->settings;
	}

	public function setError($val) {
        $this->error_flag = true;
		$this->errors[] = $val;

		// todo :: throw exeption?
	}

	public function showErrors() {
		if (empty($this->errors)) {
			return "No errors occured";
		} else {
			return "Error log:<br/>".implode("<br/>", $this->errors);
		}
	}


    private function openImage($file, $mime) {
        switch($mime)	{
            case 'jpg':
                return @imagecreatefromjpeg($file);
            case 'png':
                return @imagecreatefrompng($file);
            case 'gif':
                return @imagecreatefromgif($file);
            default:
                $this->setError("Ресайзер :: файл не является изображением");
                return false;
        }
    }

    private function setType($mime) {
        switch($mime) {
            case 'image/jpeg':
                return "jpg";
            case 'image/png':
                return "png";
            case 'image/gif':
                return "gif";
            default:
                return false;
        }
    }


    /*
     * Plupload extension
     * getting file in selected runtime (using chunks possible)
     */
    public function uploadFile() {
        // HTTP headers for no cache etc
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        //define('DIRECTORY_SEPARATOR', "/");

        // Settings
        //$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = PATH_FILES_TMP_ABS;

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);

        // Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        // Clean the fileName for security reasons
        $original_name = $fileName;
        //$fileName = iconv('UTF-8', 'windows-1251', $fileName);
        $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
        	$ext = strrpos($fileName, '.');
        	$fileName_a = substr($fileName, 0, $ext);
        	$fileName_b = substr($fileName, $ext);

        	$count = 1;
        	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
        		$count++;

        	$fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Create target dir
        if (!file_exists($targetDir))
        	@mkdir($targetDir);

        // Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
        	while (($file = readdir($dir)) !== false) {
        		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        		// Remove temp file if it is older than the max age and is not the current file
        		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
        			@unlink($tmpfilePath);
        		}
        	}

        	closedir($dir);
        } else
            $this->setError("Plupload :: Не удалось записать файл во временную директорию");
        	//die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');


        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
        	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
        	$contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
        	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

                //$original_name = $_FILES['file']['name'];

        		// Open temp file
        		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
        		if ($out) {
        			// Read binary input stream and append it to temp file
        			$in = fopen($_FILES['file']['tmp_name'], "rb");

        			if ($in) {
        				while ($buff = fread($in, 4096))
        					fwrite($out, $buff);
        			} else
                        $this->setError("Plupload :: Failed to open input stream.");
        				//die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
        			fclose($in);
        			fclose($out);
        			@unlink($_FILES['file']['tmp_name']);
        		} else
                    $this->setError("Plupload :: Failed to open output stream.");
        			//die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        	} else
                $this->setError("Plupload :: Не удалось переместить закачанный файл");
        		//die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
        	// Open temp file
        	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
        	if ($out) {
        		// Read binary input stream and append it to temp file
        		$in = fopen("php://input", "rb");

                //$original_name = $fileName;

        		if ($in) {
        			while ($buff = fread($in, 4096))
        				fwrite($out, $buff);
        		} else
                    $this->setError("Plupload :: Failed to open input stream.");
        			//die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

        		fclose($in);
        		fclose($out);
        	} else
                $this->setError("Plupload :: Failed to open output stream.");
        		//die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
        	// Strip the temp .part suffix off
            //$name = preg_replace('#(.*?)\/[^\/]*$#', '$1/'.$original_name, $fileName);
        	rename("{$filePath}.part", $filePath);
        }

        //echo(json_encode(array('result' => 'something good happened!')));

        return array('loaded' => $filePath, 'original_name' => $original_name);
    }
}

?>