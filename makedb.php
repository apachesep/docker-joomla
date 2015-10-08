<?php
// Args: 0 => makedb.php, 1 => "$JOOMLA_DB_HOST", 2 => "$SUPERUSER", 3 => "$SA_PASSWORD", 
//       4 => "$PROJECT_NAME", 5 => "PROJECT_PASSWORD"

$stderr = fopen('php://stderr', 'w');
fwrite($stderr, "\nEnsuring Joomla database is present\n");
if (strpos($argv[1], ':') !== false)
{
	list($host, $port) = explode(':', $argv[1], 2);
}
else
{
	$host = $argv[1];
	$port = 3306;
}
$maxTries = 10;
do
{
	$mysql = new mysqli($host, $argv[2], $argv[3], '', (int) $port);
	if ($mysql->connect_error)
	{
		fwrite($stderr, "\nMySQL Connection Error: ({$mysql->connect_errno}) {$mysql->connect_error}\n");
		--$maxTries;
		if ($maxTries <= 0)
		{
			exit(1);
		}
		sleep(3);
	}
}
while ($mysql->connect_error);


// create user
if ($mysql->query("CREATE USER '".$mysql->real_escape_string($argv[4])."'@'%' IDENTIFIED BY '". $mysql->real_escape_string($argv[5]) ."'")){
	fwrite($stderr, "\nMySQL User Created\n");
} else {
	fwrite($stderr, "\nMySQL 'CREATE USER' Error:" . $mysql->error . "\n");
}

if (!$mysql->query('CREATE DATABASE IF NOT EXISTS `' . $mysql->real_escape_string($argv[4]) . '`'))
{
	fwrite($stderr, "\nMySQL 'CREATE DATABASE' Error: " . $mysql->error . "\n");
	$mysql->close();
	exit(1);
}
fwrite($stderr, "\nMySQL Database Created\n");

// set permissions
$mysql->query("GRANT ALL PRIVILEGES ON `".$mysql->real_escape_string($argv[4])."`.* TO '".$mysql->real_escape_string($argv[4])."'@'%'");



$mysql->close();
