<?php
  include "env/config.php";

  // get info about the annotation you're about to delete
  $curl = curl_init();
  $url = $flask_url."/annotations/".$_GET["id"];
  $url = str_replace(" ", "%20", $url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl); 
  curl_close($curl);

  $anno = json_decode($res, true);

  // Delete it!!!
  $delurl = $flask_url."/annotations/".$_GET["id"];
  $del = curl_init();
  curl_setopt($del, CURLOPT_URL, $delurl);
  // setting user agent for the benefit of mod_security
  curl_setopt($del, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64) Cather Annotonia Status Deletion');
  curl_setopt($del, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($del, CURLOPT_CUSTOMREQUEST, "DELETE");
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
