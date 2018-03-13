<?php

namespace Drupal\twig_addons;

use \BasaltInc\TwigTools;
use \Webmozart\PathUtil\Path;

class TwigNamespaces extends \Twig_Loader_Filesystem {
  public $namespaceFiles = [];
  public $twigLoaderConfig = [];
  /**
   * TwigNamespaces constructor.
   * @param \Drupal\twig_addons\GetInfo $info
   * @throws \Exception
   * @throws \Twig_Error_Loader
   */
  public function __construct($info) {
    parent::__construct();
    if (empty($info->namespaceFiles)) {
      return;
    }
    try {
      $this->namespaceFiles = $info->namespaceFiles;
      $file = $info->namespaceFiles[0];
      $filePath = Path::join($file['pathRoot'], $file['path']);
      $twigNamespaceConfig = TwigTools\Utils::getData($filePath);
      $this->twigLoaderConfig = TwigTools\Namespaces::buildLoaderConfig($twigNamespaceConfig, $file['pathRoot']);
      foreach ($this->twigLoaderConfig as $key => $value) {
        foreach ($value['paths'] as $path) {
          $this->addPath($path, $key);
        }
      }
    } catch (Exception $exception) {
      $errorMsg = 'Error adding Twig Namespaces from: ' . $filePath;
      \Drupal::logger('twig_addons')->error($errorMsg);
      drupal_set_message($errorMsg, 'error');
    }
  }
}
