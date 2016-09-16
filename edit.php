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
            <textarea name="text" rows="10" cols="80"><?php echo $anno["text"] ?></textarea>
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

