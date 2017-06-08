<?php

################################################################
#
# configuration

$SHARED_WEBHOOK_SECRET = "7.%5%)%vr~z-Sk>{";
$REPO_DIR = "/home/kelseyurgo/eds";
// $LIVE_DIR = "/home/kelseyurgo/eds";

################################################################

function run($thecmd){
    exec($thecmd." 2>&1", $output, $return);
    if ($return != 0){
        echo "<pre>Failed ($return): [$thecmd]\n";
        print_r($output);
        echo "</pre>";
        exit();
    }
}

# confirm payload signature
$payload = file_get_contents('php://input');
$signature = "sha1=".hash_hmac('sha1', $payload, $SHARED_WEBHOOK_SECRET);
if ( $signature !== $_SERVER['HTTP_X_HUB_SIGNATURE'] ) {
    http_response_code(403);
    exit("BAD_SECRET\n");
}

# change into the repository directory
chdir($REPO_DIR) or die("could not chdir into [$REPO_DIR]");

# get a fresh checkout
run("git pull");

# generate new static site output
// run("source venv/bin/activate && make publish");

# sync to live apache directory
// run("rsync -r $REPO_DIR/output/ $LIVE_DIR/");

# done
echo "[$REPO_DIR] updated\n";

?>