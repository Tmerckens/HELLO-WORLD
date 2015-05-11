<?php
//Connects to your Database 
mysql_connect("localhost", "root", "") or die(mysql_error()); 
mysql_select_db("login") or die(mysql_error()); 

 //checks cookies to make sure they are logged in 
 if(isset($_COOKIE['name'])){ 

 	$username = $_COOKIE['name']; 
 	$pass = $_COOKIE['pass']; 
 	$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error()); 

 	while($info = mysql_fetch_array( $check )){ 

		//if the cookie has the wrong password, they are taken to the login page 
 		if ($pass != $info['password']){
			header("Location: login.php"); 
 		}
		//otherwise they are shown the admin area
		else{
		?>
			Upload an image: <br>
			max size(600x500 px) <br>
			format: PNG/JPG/GIF <br>
			The name of the image will be used as title.
			<form action="upload.php" method="post" enctype="multipart/form-data" name="uploadform">
			<input type="hidden" name="MAX_FILE_SIZE" value="350000">
			<input name="picture" type="file" id="picture" size="50">
			<input name="upload" type="submit" id="upload" value="Upload Picture!">
			</form> 
			<?php 
// if something was posted, start the process... 
if(isset($_POST['upload'])) 
{ 

// define the posted file into variables 
$name = $_FILES['picture']['name']; 
$tmp_name = $_FILES['picture']['tmp_name']; 
$type = $_FILES['picture']['type']; 
$size = $_FILES['picture']['size']; 

// get the width & height of the file (we don't need the other stuff) 
list($width, $height, $typeb, $attr) = getimagesize($tmp_name); 
     
// if width is over 600 px or height is over 500 px, kill it     
if($width>600 || $height>500) 
{ 
echo $name . "'s dimensions exceed the 600x500 pixel limit."; 
?> <a href="upload.php">Click here</a> to try again. <?php ; 
die(); 
} 

// if the mime type is anything other than what we specify below, kill it     
if(!( 
$type=='image/jpeg' || 
$type=='image/png' || 
$type=='image/gif' 
)) { 
echo $type .  " is not an acceptable format."; 
?> <a href="upload.php">Click here</a> to try again. <?php ; 
die(); 
} 

// if the file size is larger than 350 KB, kill it 
if($size>'350000') { 
echo $name . " is over 350KB. Please make it smaller."; 
?> <a href="upload.php">Click here</a> to try again. <?php ; 
die(); 
}
// if your server has magic quotes turned off, add slashes manually 
if(!get_magic_quotes_gpc()){ 
$name = addslashes($name); 
} 

// open up the file and extract the data/content from it 
$extract = fopen($tmp_name, 'r'); 
$content = fread($extract, $size); 
$content = addslashes($content); 
fclose($extract);  		

// connect to the database 
include "connect.php"; 

// the query that will add this to the database 
$addfile = "INSERT INTO files (name, size, type, content ) ". 
           "VALUES ('$name', '$size', '$type', '$content')"; 

mysql_query($addfile) or die(mysql_error());
 
							
// get the last inserted ID if we're going to display this image next 
$inserted_fid = mysql_insert_id(); 

$text = file_get_contents('items.json');
$item = json_decode($text, true);
$id = $inserted_fid;
$title = $name;
$url = $inserted_fid;
$thumbnail = $inserted_fid;
$time = time();
$source = "self";
$add = array('id' => $id, 'title' => $title, 'url' => $url, 'thumbnail' => $thumbnail, 'time' => $time, 'source' => $source);

foreach($item as $x => &$rx_item) {
	$x_item = array_reverse($rx_item);
	array_push($x_item, $add);
	$rx_item = array_reverse($x_item);
}

$jsonstring = json_encode($item);
file_put_contents('items.json', $jsonstring);

mysql_close();  

echo "Successfully uploaded your picture!";
?>
return to <a href="home.php">home</a>

<?php
// we still have to close the original IF statement. If there was nothing posted, kill the page. 
}else{die("No uploaded file present"); 
} 		
 		}
	}
}

 else{ //if the cookie does not exist, they are taken to the login screen 
	header("Location: login.php"); 
 }
 ?>