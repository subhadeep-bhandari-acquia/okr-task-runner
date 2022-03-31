<?php

namespace Drupal\fe_expert_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteController extends ControllerBase {

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new VocabularyResetForm object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Handler for autocomplete.
   */
  public function autoCompleteHandler(Request $request, $tid) {
    $results = [];
    $input = $request->query->get('q');
    
    if (!$input) {
      return new JsonResponse($results);
    }

    $input = Xss::filter($input);

    $query = $this->database->select('taxonomy_term__parent', 't');
    $query->join('taxonomy_term_field_data', 'td', 'td.tid = t.entity_id');
    $query->fields('t', ['entity_id']);
    $query->condition('t.bundle', 'tags');
    $query->condition('t.parent_target_id', $tid);
    $query->condition('td.name', $input . '%', 'LIKE');
    $query->groupBy('t.entity_id');
    $query->range(0, 10);
    $ids = $query->execute()->fetchCol();

    $terms = $ids ? Term::loadMultiple($ids) : [];


    foreach ($terms as $term) {
      $label = [
        $term->getName(),
        '<small>(' . $term->id() . ')</small>',
      ];
      $results[] = [
        'value' => EntityAutocomplete::getEntityLabels([$term]),
        'label' => implode(' ', $label),
      ];
    }

    return new JsonResponse($results);
  }

}
