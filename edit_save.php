<?php
  include "env/config.php";
  include "lib/helpers.php";

  // get the original annotation info
  $res = get_annotation_by_id($_POST["id"]);

  // add in the fields the user may have altered
  $anno = json_decode($res, true);
  if (isset($_POST["anno_ref_id"]) && $_POST["anno_ref_id"] !== "") {
    # Don't allow self-referencing by changing to invalid 000000
    if ($_POST["anno_ref_id"] == $anno["id"]) $_POST["anno_ref_id"] = "000000";

    $anno["anno_ref_id"] = $_POST["anno_ref_id"];
    $anno["text"] = "";
  }
  else {
    $anno["anno_ref_id"] = "";
    $anno["text"] = $_POST["text"];
  }
  $anno["tags"] = array($_POST["tags"]);
  $anno_update = json_encode($anno);

  // PUT a request to update the annotation
  $puturl = sprintf($flask_url."/annotations/%06d", $_POST["id"]);
  $put = curl_init();
  curl_setopt($put, CURLOPT_URL, $puturl);
  curl_setopt($put, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($anno_update)));
  curl_setopt($put, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86)64; Annotonia Status)');
  curl_setopt($put, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($put, CURLOPT_POSTFIELDS, $anno_update);
  curl_setopt($put, CURLOPT_RETURNTRANSFER, 1);
  // Disable cert checking to bypass curl cert error with Acme.sh certs
  // Local only traffic and public data, so not really a risk
  curl_setopt($put, CURLOPT_SSL_VERIFYPEER, 0);
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
