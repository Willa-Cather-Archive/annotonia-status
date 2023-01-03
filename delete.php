<?php
  include "env/config.php";
  include "lib/helpers.php";

  // get info about the annotation you're about to delete
  $res = get_annotation_by_id($_GET["id"]);
  $anno = json_decode($res, true);

  // Delete it!!!
  $delurl = sprintf($flask_url."/annotations/%06d", $_GET["id"]);
  $del = curl_init();
  curl_setopt($del, CURLOPT_URL, $delurl);
  // Set user agent to not trigger mod_security rule for no user agent
  curl_setopt($del, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
  curl_setopt($del, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($del, CURLOPT_CUSTOMREQUEST, "DELETE");
  // Disable cert checking to bypass curl cert error with Acme.sh certs
  // Local only traffic and public data, so not really a risk
  curl_setopt($del, CURLOPT_SSL_VERIFYPEER, 0);
  $delres = curl_exec($del);
  curl_close($del);
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <div class="container">
      <?php include "layout/navbar.php"; ?>
      <h2>Deleted Annotation <?php echo $anno["id"] ?></h2>
      <h3>Deletion Success: </h3>
      <?php echo print_r($delres) ?>
      <div class="row">
        <div class="col-md-3">
          <p>Annotation Id: <?php echo $anno["id"] ?></p>
          <p>Letter Id: <?php echo $anno["pageID"] ?></p>
        </div>
        <div class="col-md-9">
          <p>Highlight: <span class="quote"><?php echo $anno["quote"] ?></span></p>
          <p>Annotation: 
            <div class="text">
              <?php echo htmlspecialchars($anno["text"]) ?>
            </div>
          </p>
        </div>
      </div>
      <a href="<?php echo $status_link_url ?>">Back to Status View</a>
    </div>
  </body>
</html>
