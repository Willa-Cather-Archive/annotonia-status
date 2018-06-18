<?php
  include "env/config.php";
  include "lib/helpers.php";
?>

<html>
  <?php include "layout/head.html"; ?>
  <script>
    $(function() {
      $(".ref-toggle").click(function() {
        $(".ref").each(function() {
          if ($(this).hasClass("collapsed")) { $(this).removeClass("collapsed") }
          else { $(this).addClass("collapsed") }
        });
      })
    });
  </script>
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
          $url = (isset($_GET["tag"])
            ? $flask_url."/search?limit=$status_index_annos_max&tags=" . $_GET["tag"]
            : $flask_url."/search?limit=$status_index_annos_max")
          ;
          $url = str_replace(" ", "%20", $url);
          curl_setopt($curl, CURLOPT_URL, $url);
          // Set user agent to not trigger mod_security rule for no user agent
          curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $res = curl_exec($curl); 
          curl_close($curl);

          // Parse json and display results
          $annotations = json_decode($res, true);
          $annos_returned = count($annotations["rows"]);
        ?>
        <h5>
          <?php echo $annotations["total"] ?> annotation(s), displaying the most
          recently edited <?php echo $annos_returned ?>
        </h5>
        <?php
          $refs_present = false;
          for ($i = 0; $i < $annos_returned; $i++):
            $anno = $annotations["rows"][$i];
            if (isset($anno["anno_ref_id"]) && $anno["anno_ref_id"] !== "") {
              $ref_present = true;
              break;
            }
          endfor;

          if ($ref_present) {
            echo <<<END
        <div class="form-inline">
          <div class="checkbox">
            <input id="ref-toggle" class="ref-toggle" type="checkbox"
              checked="checked">
            <label for="ref-toggle">Hide reference annotations</label>
          </div>
        </div>
END;
          }
        ?>
        <hr>
        <div>
          <?php for ($i = 0; $i < $annos_returned; $i++):
            $anno = $annotations["rows"][$i];
            $tag_html = generate_tags($anno["tags"]);
            include "includes/annotation.php";
          endfor ?>
        </div>
      </div>
    </div>
  </body>
</html>
