<?php
  $boilerplate_url = "/annotonia/tei/tei-boilerplate/cat.";

  $catherletters_dir = "/path/to/cocoon/annotonia/xml/letters/";
  $catherletters_url = "/letters/";

  # This value must be adjusted in sync with annotator-store's
  # See RESULTS_MAX_SIZE in annotator-store/annotator/elasticsearch.py
  $flask_results_max = 10000;
  $flask_url = "http://server.unl.edu:port";

  # Max number of annotations listed on the index page
  $status_index_annos_max = 50;
  $status_link_url = "/annotonia_status";
?>
