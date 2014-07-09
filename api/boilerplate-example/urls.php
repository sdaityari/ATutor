<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

// The prefix common to all URLs in this boilerplate example app
$boilerplate_url_prefix = "/boilerplate";

$boilerplate_base_urls = array(
    "/" => "BoilerplateClass",
    "/:number" => "BoilerplateClassWithUrlParameter"
);

$boilerplate_urls = generate_urls($boilerplate_base_urls, $boilerplate_url_prefix);

?>
