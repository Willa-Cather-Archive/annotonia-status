<?php
  include "env/config.php";
  include "lib/helpers.php";

  // get the original annotation info
  $curl = curl_init();
  $url = $flask_url."/annotations/".$_POST["id"];
  $url = str_replace(" ", "%20", $url);
  curl_setopt($curl, CURLOPT_URL, $url);
  // Set user agent to not trigger mod_security rule for no user agent
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl); 
  curl_close($curl);

  // add in the fields the user may have altered
  $anno = json_decode($res, true);
  $anno["text"] = $_POST["text"];
  $anno["tags"] = array($_POST["tags"]);
  $anno_update = json_encode($anno);

  // PUT a request to update the annotation
  $puturl = $flask_url."/annotations/".$_POST["id"];
  $put = curl_init();
  curl_setopt($put, CURLOPT_URL, $puturl);
  curl_setopt($put, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($anno_update)));
  curl_setopt($put, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86)64; Annotonia Status)');
  curl_setopt($CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($put, CURLOPT_POSTFIELDS, $anno_update);
  curl_setopt($put, CURLOPT_RETURNTRANSFER, true);
  $putres = curl_exec($put);
  curl_close($put);
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <div class="container">
      <?php include "layout/navbar.php"; ?>
      
      <h2>Updated annotation: </h3>
      <?php  $tag_html = generate_tags($anno["tags"]); ?>
      <?php include "includes/annotation.php" ?>

      <a href="<?php echo $status_link_url ?>">Back to Status View</a>

      <h2>Status: </h2>
      <?php echo print_r($putres) ?>
    </div>

  </body>
</html>