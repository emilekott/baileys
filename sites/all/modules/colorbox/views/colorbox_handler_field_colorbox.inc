<?php

/**
 * @file
 * Views handlers for Colorbox module.
 */

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 */
class colorbox_handler_field_colorbox extends views_handler_field {
  function query() {
    // Do nothing, as this handler does not need to do anything to the query itself.
  }

  function option_definition() {
    $options = parent::option_definition();

    $options['trigger_field'] = array('default' => '');
    $options['popup'] = array('default' => '');
    $options['caption'] = array('default' => '');
    $options['gid'] = array('default' => TRUE);
    $options['custom_gid'] = array('default' => '');
    $options['width'] = array('default' => '600px');
    $options['height'] = array('default' => '400px');

    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    // Get a list of the available fields and arguments for token replacement.
    $options = array();
    $fields = array('trigger_field' => t('- None -'));
    foreach ($this->view->display_handler->get_handlers('field') as $field => $handler) {
      $options[t('Fields')]["[$field]"] = $handler->ui_name();
      // We only use fields up to (and including) this one.
      if ($field == $this->options['id']) {
        break;
      }

      $fields[$field] = $handler->definition['title'];
    }

    $form['trigger_field'] = array(
      '#type' => 'select',
      '#title' => t('Trigger field'),
      '#description' => t('Select the field that should be turned into the trigger for the Colorbox.  Only fields that appear before this one in the field list may be used.'),
      '#options' => $fields,
      '#default_value' => $this->options['trigger_field'],
      '#weight' => -12,
    );

    $form['popup'] = array(
      '#type' => 'textarea',
      '#title' => t('Popup'),
      '#description' => t('The Colorbox popup content. You may include HTML. You may enter data from this view as per the "Replacement patterns" below.'),
      '#default_value' => $this->options['popup'],
      '#weight' => -11,
    );

    $form['caption'] = array(
      '#type' => 'textfield',
      '#title' => t('Caption'),
      '#description' => t('The Colorbox Caption. You may include HTML. You may enter data from this view as per the "Replacement patterns" below.'),
      '#default_value' => $this->options['caption'],
      '#weight' => -10,
    );

    $form['gid'] = array(
      '#type' => 'checkbox',
      '#title' => t('Automatic generated Colorbox gallery'),
      '#description' => t('Enable Colorbox gallery using a generated gallery id for this view.'),
      '#default_value' => $this->options['gid'],
      '#weight' => -9,
    );

    $form['custom_gid'] = array(
      '#type' => 'textfield',
      '#title' => t('Custom Colorbox gallery'),
      '#description' => t('Enable Colorbox gallery with a given string as gallery. Overrides the automatically generated gallery id above.'),
      '#default_value' => $this->options['custom_gid'],
      '#weight' => -8,
    );

    $form['height'] = array(
      '#type' => 'textfield',
      '#title' => t('Height'),
      '#description' => t('Specify the height of the Colorbox popup window. Because the content is dynamic, we cannot detect this value automatically. Example: "100%", 500, "500px".'),
      '#default_value' => $this->options['height'],
      '#weight' => -7,
    );

    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#description' => t('Specify the width of the Colorbox popup window. Because the content is dynamic, we cannot detect this value automatically. Example: "100%", 500, "500px".'),
      '#default_value' => $this->options['width'],
      '#weight' => -6,
    );
  }

  /**
   * Render the trigger field and its linked popup information.
   */
  function render($values) {
    // Load the necessary js file for Colorbox activation.
    if (_colorbox_active() && !variable_get('colorbox_inline', 0)) {
      drupal_add_js(drupal_get_path('module', 'colorbox') . '/js/colorbox_inline.js');
    }

    // We need to have multiple unique IDs, one for each record.
    static $i = 0;
    $i++;

    // Return nothing if no trigger filed is selected.
    if (empty($this->options['trigger_field'])) {
      return;
    }

    // Get the token information and generate the value for the popup and the
    // caption.
    $tokens = $this->get_render_tokens($this->options['alter']);
    $popup = filter_xss_admin($this->options['popup']);
    $caption = filter_xss_admin($this->options['caption']);
    $popup = strtr($popup, $tokens);
    $caption = strtr($caption, $tokens);

    $width = $this->options['width'] ? $this->options['width'] : '';
    $height = $this->options['height'] ? $this->options['height'] : '';
    $gallery_id = !empty($this->options['custom_gid']) ? $this->options['custom_gid'] : ($this->options['gid'] ? 'gallery-' . $this->view->name : '');
    $link_text = $tokens["[{$this->options['trigger_field']}]"];
    $link_text .= '<div class="play-button"></div>';
    $link_options = array(
      'html' => TRUE,
      'fragment' => 'colorbox-inline-' . $i,
      'query' => array(
        'width' => $width,
        'height' => $height,
        'title' => $caption,
        'inline' => 'true'
      ),
      'attributes' => array(
        'class' => 'colorbox-inline',
        'rel' => $gallery_id
      )
    );
    // Remove any parameters that aren't set.
    $link_options['query'] = array_filter($link_options['query']);

    $link_tag = l($link_text, '', $link_options);

    // The outside div is there to hide all of the divs because if the specific Colorbox
    // div is hidden it won't show up as a Colorbox.
    return $link_tag . '<div style="display: none;"><div id="colorbox-inline-' . $i . '">' . $popup . '</div></div>';
  }
}
