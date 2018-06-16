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

          $url = (isset($_GET["q"]) && $_GET["q"] !== "")
            ? "$flask_url/search_raw?size=$flask_results_max&q=ids_quote_and_text:". rawurlencode($_GET["q"])
            : "$flask_url/search_raw?size=$flask_results_max&q=*"
          ;

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
        <div class="form-inline">
          <div class="checkbox">
            <input id="ref-toggle" class="ref-toggle" type="checkbox"
              checked="checked">
            <label for="ref-toggle">Hide reference annotations</label>
          </div>
        </div>
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
