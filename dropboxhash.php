<?php
/**
 * Basic implementation of the Dropbox API Content Hash:
 *   https://www.dropbox.com/developers/reference/content-hash
 */

define("BLOCK_SIZE", "4194304");

$options = getopt("f:h");

if (isset($options['h']))
{
?>
-f [filename]      Run on this file
-h                 This help
<?php
}

if (isset($options['f']))
	$filename = $options['f'];
else
	die("Error: filename required (-f)\n");


if (!file_exists($filename))
{
	die("Error: file does not exist\n");
}
else
{
	$filesize = filesize($filename);
	
	$fp = fopen($filename, "r");
	
	if (!$fp)
		die("Error: can't open $filename for reading\n");

	$blocks = ceil($filesize / BLOCK_SIZE);

	$string_to_hash = "";
	
	for ($i = 0; $i < $blocks; $i++)
	{
		$block_hash = hash("sha256", fread($fp, BLOCK_SIZE), true);
		$string_to_hash .= $block_hash;
	}
	
	print hash("sha256", $string_to_hash)."\n";
}
