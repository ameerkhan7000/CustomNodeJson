<?php

namespace Drupal\custom_node_json\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * SiteInformationForm - Form alter.
 */
class ExtendedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form = parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => t("Custom field to set the API Key"),
    ];
    $form['actions']['submit']['#value'] = t('Update Configuration');

    return $form;
  }

  /**
   * Custom submit form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $siteapikey = $form_state->getValue('siteapikey');
    \Drupal::messenger()->addStatus('Site API Key has been saved with ' . $siteapikey);
    $this->config('system.site')
      ->set('siteapikey', $siteapikey)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
