<?php
// Set Variables
$LOCAL_REPO_ROOT = "/var/www/html";  
$REMOTE_REPO     = "";  //github專案路徑
$BRANCH          = "master";

if ( $_POST['payload'] ) {
  // Only respond to POST requests from Github
  // If there is already a repo, just run a git pull to grab the latest changes
  shell_exec("cd {$LOCAL_REPO_ROOT} && git pull origin master");
  die("done " . mktime());
}

?>
