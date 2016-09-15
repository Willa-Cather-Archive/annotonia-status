<?php
  include "env/config.php";
  include "lib/helpers.php";

  // get info about the annotation you're about to edit
  $curl = curl_init();
  $url = $flask_url."/annotations/".$_GET["id"];
  $url = str_replace(" ", "%20", $url);
  curl_setopt($curl, CURLOPT_URL, $url);
  // Set user agent to not trigger mod_security rule for no user agent
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl); 
  curl_close($curl);

  $anno = json_decode($res, true);
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <div class="container">
      <?php include "layout/navbar.php"; ?>
      <h2>Editing Annotation <?php echo $anno["id"] ?></h2>
      <form action="<?php echo $status_link_url?>/edit.php">
        <!-- hidden fields -->

        <div class="row anno">
          <!-- Identification and Links -->
          <div class="col-md-2">
            <h5>Letter: <?php echo $anno["pageID"] ?></h5>
            <p>ID: <?php echo $anno["id"] ?></p>
            <?php if (isset($anno["pageID"])): ?>
              <a href="<?php echo $boilerplate_url?><?php echo $anno["pageID"]?>.html">Annotate</a>
                 | 
                <a href="<?php echo $catherletters_url?><?php echo $anno["pageID"]?>.html">Cather View</a>
            <?php else: ?>
              No links available for nonexistent id
            <?php endif; ?>
          </div>
          <div class="col-md-2">
            Change tag:
            <select name="tags">
              <?php echo generate_tag_dropdown($anno["tags"][0]) ?>
            </select>
          </div>
          <!-- Annotation content -->
          <div class="col-md-8">
            <h5>
              Highlight:
              <span class="quote"><?php echo $anno["quote"]?></span>
            </h5>
            <p>Click to edit:</p>
            <div class="well well-sm" contenteditable="true">
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
          </div>
          <!-- tags -->
          <div class="row tags">
            <?php echo $tag_html ?>
          </div>
          <input class="form-control save" type="submit" value="Save Changes!"/>
        </form>
        <!-- Delete Annotation -->
        <form action="<?php echo $status_link_url?>/delete.php">
          <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
          <input class="form-control delete" type="submit" value="Delete"/>
        </form>
        <a href="<?php echo $status_link_url ?>">Back to Status View</a>
      </div>
    </div>
  </body>
</html>

