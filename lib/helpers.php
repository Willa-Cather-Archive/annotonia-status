<?php
  include "../env/config.php";

  function get_annotation_by_id($flask_url, $id) {
    $curl = curl_init();
    $url = sprintf($flask_url."/annotations/%06d", $id);
    $url = str_replace(" ", "%20", $url);
    curl_setopt($curl, CURLOPT_URL, $url);
    // Set user agent to not trigger mod_security rule for no user agent
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curl); 
    curl_close($curl);
    return $res;
  }

  function get_color($row_type) {
    $colors = array(
      "Needs Correction" => "label-danger",
      "Needs Annotation" => "label-warning",
      "Draft" => "label-info",
      "Complete" => "label-primary",
      "Published" => "label-success"
    );
    return $colors[$row_type];
  }

  function generate_tags($tags) {
    $tag_length = count($tags);
    $html = '<div class="label-wrap">';
    for ($i = 0; $i < $tag_length; $i++) {
       $tag = $tags[$i];
       $type = get_color($tag);
       $html .= "<span class='label " . $type . "'>" . $tag . "</span>";
    }
    $html .= "</div>";
    return $html;
  }

  function generate_tag_dropdown($selected) {
    $tags = array("Complete", "Draft", "Needs Annotation", "Needs Correction", "Published");
    $html = '';
    foreach ($tags as $tag) {
      if ($tag == $selected) {
        $html .= "<option value='" . $tag . "' selected>" . $tag . "</option>";
      } else {
        $html .= "<option value='" . $tag . "'>" . $tag . "</option>";
      }
    }
    return $html;
  }

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
