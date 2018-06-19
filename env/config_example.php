<?php
  $boilerplate_url = "/annotonia/tei/tei-boilerplate/cat.";

  $catherletters_dir = "/path/to/cocoon/annotonia/xml/letters/";
  $catherletters_url = "/letters/";

  # If limit changed, must also change:
  # index.max_result_window in /etc/elasticsearch/elasticsearch.yml
  # RESULTS_MAX_SIZE in annotator-store/annotator/elasticsearch.py
  # $flask_limit in annotonia-converter/config.rb
  $flask_results_max = 10000;
  $flask_url = "http://server.unl.edu:port";

  # Max number of annotations listed on the index page
  $status_index_annos_max = 50;
  $status_link_url = "/annotonia_status";
?>
