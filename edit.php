<?php
  include "env/config.php";
  include "lib/helpers.php";

  $res = get_annotation_by_id($_GET["id"]);
  $anno = json_decode($res, true);
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <div class="container">
      <?php include "layout/navbar.php"; ?>
      <h2>Editing Annotation <?php echo $anno["id"] ?></h2>
      <form action="<?php echo $status_link_url?>/edit_save.php" method="post">
        <!-- hidden fields -->
        <input type="hidden" name="id" value="<?php echo $anno['id'] ?>"/>
        <div class="row anno">
          <!-- Identification and Links -->
          <div class="col-md-3">
            <h5>Letter: <?php echo $anno["pageID"] ?></h5>
            <p>ID: <?php echo $anno["id"] ?></p>
            <?php if (isset($anno["pageID"])): ?>
              <a href="<?php echo $boilerplate_url?><?php echo $anno["pageID"]?>.html">Annotate</a>
                 | 
                <a href="<?php echo $catherletters_url?><?php echo $anno["pageID"]?>.html">Cather&nbsp;View</a>
            <?php else: ?>
              No links available for nonexistent id
            <?php endif; ?>
          </div>
          <div class="col-md-2">
            <p>
              Status Tag:
              <select name="tags">
                <?php echo generate_tag_dropdown($anno["tags"][0]) ?>
              </select>
            </p>
            <p>
              <label>
                <input id="ref_toggle" type="checkbox" name="ref"
                  <?php
                    $ref = (isset($anno["anno_ref_id"])
                      && $anno["anno_ref_id"] !== "") ? true : false;
                    if ($ref) echo "checked"
                  ?>
                > Reference another annotation
              </label>
            </p>
          </div>
          <!-- Annotation content -->
          <div class="col-md-7">
            <h5>
              Highlight:
              <span class="quote"><?php echo $anno["quote"]?></span>
            </h5>
            <div id="anno"<?php if ($ref) echo ' class="hide"' ?>>
              <label for="anno_text">Annotation Text: </label>
              <textarea id="anno_text" class="form-control" name="text" rows="8"><?php echo $anno["text"] ?></textarea>
            </div>
            <div id="anno_ref"<?php if (! $ref) echo ' class="hide"' ?>>
              <label for="anno_ref_id"> Referenced Annotation ID:</label>
              <input id="anno_ref_id" class="form-control" type="text" name="anno_ref_id"
                <?php if ($ref) echo " value=\"$anno[anno_ref_id]\"" ?>
              >
            </div>
            <div class="col-md-6">
              <input class="form-control save" type="submit" value="Save"/>
            </div>
          </div>
        </form>
        <a href="<?php echo $status_link_url ?>">Back to Status View</a>
      </div>
    </div>

    <script>
      $(function () {
        $("#ref_toggle").click(function() {
          $("#anno").toggleClass("hide");
          $("#anno_ref").toggleClass("hide");

          var $ref_id = $("#anno_ref_id");
          if (! $("#ref_toggle").prop("checked")) {
            // Empty ref ID input, otherwise annotation text will be discarded
            $ref_id.data("previous", $ref_id.val());
            $ref_id.val('');
          }
          else {
            // Restore value if saved when unchecking the toggle
            if ($ref_id.data("previous") !== undefined) {
              $ref_id.val($ref_id.data("previous"));
            }
          }
        });
      });
    </script>
  </body>
</html>

