<?php
/*
 *  uploader_0_tmpname e.g. p15l8898k11a20c2612sv1jri11431.jpg
 * uploader_0_name	e.g. tumblr_lcvaneL4DT1qf6ccbo1_500.jpg
 * uploader_0_status e.g. done
 * uploader_count e.g. 1
  */

include("bencode.php");

# Get torrent data from temp upload file
$torrent_data = file_get_contents(ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload" . DIRECTORY_SEPARATOR . preg_replace("/[\\\//]", "", $_POST['uploader_0_tmpname']));

# Decode torrent data and determine info_hash
# Save all relevant values
$bencode = new Bencode();
$result = $Bencode::decode($torrent_data);
$info_hash = sha1(Bencode::encode($result['info']));
$torrent_filename = $_POST['uploader_0_name'];

# Send data to MediaTag for lookup


header("Location: page_tags.php?torrent_filename=" . $torrent_filename);
//tmpname

exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="css/plupload.css" type="text/css" media="screen" />
<title>Plupload - Form dump</title>
<style type="text/css">
body {background: #9A7C5F;}
</style>
</head>
<body>

<h1>Post dump</h1>

<p>Shows the form items posted.</p>

uploader_0_tmpname	p15l8898k11a20c2612sv1jri11431.jpg
uploader_0_name	tumblr_lcvaneL4DT1qf6ccbo1_500.jpg
uploader_0_status	done
uploader_count	1

<table>
	<tr>
		<th>Name</th>
		<th>Value</th>
	</tr>
	<?php $count = 0; foreach ($_POST as $name => $value) { ?>
	<tr class="<?php echo $count % 2 == 0 ? 'alt' : ''; ?>">
		<td><?php echo $name ?></td>
		<td><?php echo nl2br(htmlentities(stripslashes($value))) ?></td>
	</tr>
	<?php } ?>
</table>

</body>
</html>
