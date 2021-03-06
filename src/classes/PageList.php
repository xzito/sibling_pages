<?php

namespace JB\SPW;

use JB\SPW\Helpers;

class PageList {

  private $origin;
  private $include_apex = false;

  public function __construct($list_options) {
    $this->origin = $this->find_origin();
    $this->include_apex = $list_options['apex'];
  }

  public function get_origin() {
    return $this->origin;
  }

  public function render() {
    $template = Helpers::get_template_path('_page_list.php');
    $pages = $this->find_sibling_pages();

    ob_start();
    include $template;
    $output = ob_get_contents();
    ob_end_clean();

    echo $output;
  }

  private function find_origin() {
    $post = $origin = get_post(get_the_ID());

    if ($post->post_parent) {
      $ancestors = get_post_ancestors($post->ID);
      $origin_index = count($ancestors) - 1;
      $origin = $ancestors[$origin_index];
    }

    return $origin;
  }

  private function find_sibling_pages() {
    $pages = $this->find_child_pages();

    return $pages;
  }

  private function find_child_pages() {
    $args = array(
      'parent' => $this->origin,
      'sort_column' => 'menu_order',
    );

    $pages = get_pages($args);

    if ($this->include_apex) {
      array_unshift($pages, get_post($this->origin));
    }

    return $pages;
  }

}

