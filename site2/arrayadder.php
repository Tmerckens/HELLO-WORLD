<?php
$text = file_get_contents('items.json');
$ritem = json_decode($text, true);
$item = array_reverse($ritem);
$id = $_POST['id'];
$title = $_POST['title'];
$url = $_POST['url'];
$thumbnail = $_POST['thumbnail'];
$time = $_POST['createdtime'];
$source = "reddit";
$add = array('id' => $id, 'title' => $title, 'url' => $url, 'thumbnail' => $thumbnail, 'time' => $time, 'source' => $source);

foreach($item as $x => &$x_item) {
	array_push($x_item, $add);
}

$jsonstring = json_encode($item);
file_put_contents('items.json', $jsonstring);
?>