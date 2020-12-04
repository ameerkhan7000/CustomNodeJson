<?php

namespace Drupal\custom_node_json\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom controller.
 */
class NodeJson extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'custom_node_json';
  }

  /**
   * Controller function to return json data.
   */
  public function content($api, NodeInterface $node) {
    $serializer = \Drupal::service('serializer');
    $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * Custom access callback.
   */
  public function access($api, NodeInterface $node) {
    $siteapikey = \Drupal::config('system.site')->get('siteapikey');
    $access = TRUE;
    $type = $node->bundle();
    if ($api != $siteapikey || $type != 'page') {
      $access = FALSE;
    }
    return AccessResult::allowedIf($access);
  }

}
