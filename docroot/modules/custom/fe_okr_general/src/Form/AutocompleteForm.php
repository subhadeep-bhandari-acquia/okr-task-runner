<?php

namespace Drupal\fe_okr_general\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Custom form.
 */
class AutocompleteForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jquery_task_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $options = [
      '' => '- Select -',
    ];

    $parentTags = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('tags', 0, 1, TRUE);

    foreach($parentTags as $parentTag) {
      $options[$parentTag->id()] = $parentTag->name->value;
    }

    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Tag'),
      '#options' => $options,
      '#ajax' => [
        'callback'  => '::getChildren',
        'wrapper'   => ['autocomplete-wrapper'],
      ]
    ];


    $form['childterm'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#value' => '',
      '#prefix' => '<div id="autocomplete-wrapper">',
      '#suffix' => '</div>',
    ];

    // Based on the tag display child tags only
    if (!empty($tid = $form_state->getValue('type', false))) {
      $form['childterm'] = [
        '#type' => 'textfield',
        '#prefix' => '<div id="autocomplete-wrapper">',
        '#suffix' => '</div>',
        '#autocomplete_route_name' => 'fe_okr_general.autocomplete',
        '#autocomplete_route_parameters' => ['tid' => $tid],
      ];
    }


    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#attributes' => [
        'id' => 'assignmentSubmit',
      ],
      '#disabled' => TRUE,
    ];
    // Attach the library.
    $form['#attached']['library'][] = 'fe_okr_general/fe_okr_general_js_jquery_task';

    return $form;
  }

  function getChildren(array &$form, FormStateInterface $form_state) {
    $form['childterm']['#value'] = '';
    return $form['childterm'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage(t("Form submission Done!! Submitted Values are:"));
 	  foreach ($form_state->getValues() as $key => $value) {
 	    \Drupal::messenger()->addMessage($key . ': ' . $value);
    }
  }

}
