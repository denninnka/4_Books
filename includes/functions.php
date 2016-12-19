<?php
mb_internal_encoding('UTF-8');
$con = mysqli_connect('localhost', 'root', 'wdr9173zdv50', 'books');
if (!$con) {
    echo "Грешка, моля опитайте по-късно";
}
mysqli_set_charset($con, 'UTF8');

function getAuthors($con)
{
    $q = mysqli_query($con, 'SELECT * FROM authors');
    if (mysqli_error($con)) {
        return false;
    }
    $return = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $return[] = $row;
    }
    return $return;
}

function isAuthorIdExist($con, $ids){
	if (!is_array($ids) || count($ids)==0) {
		return false;
	}
	$q = mysqli_query($con, 'SELECT * FROM authors WHERE author_id IN(' .implode(',', $ids).')');
	if (mysqli_error($con)) {
		return false;;
	}
	if (mysqli_num_rows($q)==count($ids)) {
		return true;
	}
	return false;
}
$sorting = array('DESC' => 'Низходящ' , 'ASC' => 'Възходящ' );