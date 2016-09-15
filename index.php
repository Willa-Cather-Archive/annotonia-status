<?php
  include "env/config.php";
  include "lib/helpers.php";

  function make_link($tag, $search = null) {
    $search = $search ? $search : $tag;
    $html = "";
    $class = ($_GET["tag"] == $search ? 'active' : 'inactive');
    $html .= "<li role='presentation' class='" . $class . "'>";
    if ($tag == "") {
      $html .= "<a href='" . $GLOBALS["status_link_url"] . "''>All Annotations</a>";
    } else {
      $html .= "<a href='" . $GLOBALS["status_link_url"] . "?tag=" . $search . "''>" . $tag . "</a>";
    }
    $html .= "</li>";
    return $html;
  }
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <?php include "layout/navbar.php"; ?>
    <div class="container">
      <h4>Annotations by Status</h4>

      <div class="navbar">
        <ul class="nav nav-pills">
          <?php echo make_link(""); ?>
          <?php echo make_link("Needs Correction", "Correction"); ?>
          <?php echo make_link("Needs Annotation", "Annotation"); ?>
          <?php echo make_link("Draft"); ?>
          <?php echo make_link("Complete"); ?>
          <?php echo make_link("Published"); ?>
        </ul>
      </div>

      <div class="results">
        <?php 
          // GET a request to the flask url for the requested tag (or no tags if all annotations)
          $curl = curl_init();
          $url = (isset($_GET["tag"]) ? $flask_url."/search?limit=2000&tags=" . $_GET["tag"] : $flask_url."/search?limit=2000");
          $url = str_replace(" ", "%20", $url);
          curl_setopt($curl, CURLOPT_URL, $url);
          // Set user agent to not trigger mod_security rule for no user agent
          curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $res = curl_exec($curl); 
          curl_close($curl);

          // Parse json and display results
          $annotations = json_decode($res, true);
          $anno_length = count($annotations["rows"]);
        ?>
        <h5><?php echo $anno_length ?> annotation(s)</h5>
        <hr>
        <div>
          <?php for ($i = 0; $i < $anno_length; $i++): ?>
            <?php
              $row = $annotations["rows"][$i];
              $tag_html = generate_tags($row["tags"]);
            ?>
            <div>
              <div class="row">

                <!-- Identification and Links -->
                <div class="col-md-3">
                  <h5>Letter: <?php echo $row["pageID"] ?></h5>
                  <div class="pull-right"><?php echo $tag_html ?></div>
                  <p>ID: <?php echo $row["id"] ?></p>
                  <?php if (isset($row["pageID"])): ?>
                    <a href="<?php echo $boilerplate_url?><?php echo $row["pageID"]?>.html">Annotate</a>
                       | 
                      <a href="<?php echo $catherletters_url?><?php echo $row["pageID"]?>.html">Cather View</a>
                  <?php else: ?>
                    No links available for nonexistent id
                  <?php endif; ?>
                </div>

                <!-- Annotation content -->
                <div class="col-md-8">
                  <h5>
                    Highlight:
		    <span class="quote"><?php echo $row["quote"]?></span>
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
                          , $row["text"]
                        );
                      ?>
		  </div>
                </div>

                <!-- Delete Annotation -->
                <div class="col-md-1">
                  <form action="<?php echo $status_link_url?>/delete.php">
                    <input type="hidden" name="id" value="<?php echo $row['id']?>"/>
                    <input class="form-control delete" type="submit" value="Delete"/>
                  </form>
                </div>
              </div>
            </div>
            <hr>
          <?php endfor ?>
        </div>
      </div>
    </div>
  </body>
</html>
