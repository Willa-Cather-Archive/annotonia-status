<?php
  include "env/config.php";
  include "lib/helpers.php";

  // get the annotation info
  $curl = curl_init();
  $url = $flask_url."/annotations/".$_POST["id"];
  $url = str_replace(" ", "%20", $url);
  curl_setopt($curl, CURLOPT_URL, $url);
  // Set user agent to not trigger mod_security rule for no user agent
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl); 
  curl_close($curl);

  $anno = json_decode($res, true);
  $anno["text"] = $_POST["text"];
  $anno["tags"] = array($_POST["tags"]);

  $anno_update = json_encode($anno);

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
      
      <?php
        $tag_html = generate_tags($anno["tags"]);
      ?>
      <div>
        <div class="row">

          <!-- Identification and Links -->
          <div class="col-md-3">
            <h5>Letter: <?php echo $anno["pageID"] ?></h5>
            <div class="pull-right">
              <form action="<?php echo $status_link_url?>/edit.php">
                <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
                <input class="form-control edit" type="submit" value="Edit"/>
              </form>
              <?php echo $tag_html ?>
            </div>
            <p>ID: <?php echo $anno["id"] ?></p>
            <?php if (isset($anno["pageID"])): ?>
              <a href="<?php echo $boilerplate_url?><?php echo $anno["pageID"]?>.html">Annotate</a>
                 | 
                <a href="<?php echo $catherletters_url?><?php echo $anno["pageID"]?>.html">Cather View</a>
            <?php else: ?>
              No links available for nonexistent id
            <?php endif; ?>
          </div>

          <!-- Annotation content -->
          <div class="col-md-8">
            <h5>
              Highlight:
              <span class="quote"><?php echo $anno["quote"]?></span>
            </h5>
            <div class="well well-sm">
              <?php
                # Wrap image with link, limit size, display alt text
                echo preg_replace(
                  '/<img src="(.+?)" alt="(.*?)"(.*)?>/'
                  , <<<END
<div class="container-fluid">
  <a href="$1">
    <img class="img-responsive" src="$1" alt="$2" title="$2"$3>
  </a>
</div>
<span class="text">Alt Text "$2"</span>
END
                  , $anno["text"]
                );
              ?>
            </div>
          </div> <!-- /div col-md-8 -->

          <!-- Delete Annotation -->
          <div class="col-md-1">
            <form action="<?php echo $status_link_url?>/delete.php">
              <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
              <input class="form-control delete" type="submit" value="Delete"/>
            </form>
          </div>
        </div> <!-- /div col-md-8 -->
      </div>
      <a href="<?php echo $status_link_url ?>">Back to Status View</a>
      <h2>Status: </h2>
      <?php echo print_r($putres) ?>
    </div>

  </body>
</html>
