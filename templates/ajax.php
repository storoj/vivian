<?
if(is_array($this->result)){
	header('Content-type: application/json');
	echo json_encode($this->result);
} else {
	echo $this->result;
}
?>