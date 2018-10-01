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
      <h4>Annotonia Search</h4>

      <div class="results">
        <?php
          // GET a request to the flask url for the requested tag (or no tags if all annotations)
          $curl = curl_init();

          if (isset($_GET["q"]) && $_GET["q"] !== "") {
            $url = (isset($_GET["type"]) && $_GET["type"] === "ref")
              ? "$flask_url/search_raw?size=$flask_results_max&q=anno_ref_id:". rawurlencode($_GET["q"])
              : "$flask_url/search_raw?size=$flask_results_max&q=ids_quote_and_text:". rawurlencode($_GET["q"])
            ;
          }
          else { $url = "$flask_url/search_raw?size=$flask_results_max&q=*"; }

          curl_setopt($curl, CURLOPT_URL, $url);
          // Set user agent to not trigger mod_security rule for no user agent
          curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $res = curl_exec($curl); 
          curl_close($curl);

          // Parse json and display results
          $annotations = json_decode($res, true);
        ?>
        <h5>
          <?php echo $annotations["hits"]["total"] ?>
          result(s) found for search:
          <?php echo $_GET["q"] ?>
        </h5>
        <?php
          $ref_present = false;
          for ($i = 0; $i < $annotations["hits"]["total"]; $i++):
            $anno = $annotations["hits"]["hits"][$i]["_source"];
            if (isset($anno["anno_ref_id"]) && $anno["anno_ref_id"] !== "") {
              $ref_present = true;
              break;
            }
          endfor;

          if ($ref_present) {
            echo <<<END
        <div class="form-inline">
          <div class="checkbox">
            <label for="ref-toggle">
              <input id="ref-toggle" class="ref-toggle" type="checkbox"
END;
            if (!isset($_GET["type"]) || !$_GET["type"] === "ref") {
              echo 'checked="checked"';
            }
            echo <<<END
              >
              Hide reference annotations
            </label>
          </div>
        </div>
END;
          }
        ?>
        <hr>
        <div>
          <?php for ($i = 0; $i < $annotations["hits"]["total"]; $i++):
            $anno = $annotations["hits"]["hits"][$i]["_source"];
            $anno["id"] = $annotations["hits"]["hits"][$i]["_id"];
            $tag_html = generate_tags($anno["tags"]);
            include "includes/annotation.php";
          endfor ?>
        </div>
      </div>
    </div>
  </body>
</html>
