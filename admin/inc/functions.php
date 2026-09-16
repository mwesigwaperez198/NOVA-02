<?php
function get_ext($pdo,$fname)
{
	$up_filename=$_FILES[$fname]["name"];
	$file_basename = substr($up_filename, 0, strripos($up_filename, '.'));
	$file_ext = substr($up_filename, strripos($up_filename, '.'));
	return $file_ext;
}

function ext_check($pdo,$allowed_ext,$my_ext) 
{
	$arr1 = explode("|",$allowed_ext);
	$count_arr1 = count($arr1);
	for($i=0;$i<$count_arr1;$i++) {
		$arr1[$i] = '.'.$arr1[$i];
	}
	$stat = 0;
	for($i=0;$i<$count_arr1;$i++) {
		if($my_ext == $arr1[$i]) { $stat = 1; break; }
	}
	return $stat == 1;
}

function get_ai_id($pdo,$tbl_name) 
{
	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE '$tbl_name'");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row) { $next_id = $row['Auto_increment']; }
	return $next_id ?? null;
}
