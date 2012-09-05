<?php

/**
 * @file
 * Customize the display of a complete webform.
 *
 * This file may be renamed "webform-form-[nid].tpl.php" to target a specific
 * webform on your site. Or you can leave it "webform-form.tpl.php" to affect
 * all webforms on your site.
 *
 * Available variables:
 * - $form: The complete form array.
 * - $nid: The node ID of the Webform.
 *
 * The $form array contains two main pieces:
 * - $form['submitted']: The main content of the user-created form.
 * - $form['details']: Internal information stored by Webform.
 */
?>
<?php
  // Print out the main part of the form.
  // Feel free to break this up and move the pieces within the array.
    //print_r($form['actions']);
    $form['actions']['submit']['#type'] = 'image_button';
    $form['actions']['submit']['#src'] = base_path() . path_to_theme() . '/images/buttons/submit.png';
    
    $form['actions']['submit'] = array(
        '#type' => 'image_button', 
        '#src' => base_path() . path_to_theme() . '/images/buttons/submit.png',
        '#name' => 'op',
        '#button_type' => 'submit',
        );
    print drupal_render($form['submitted']);

  // Always print out the entire $form. This renders the remaining pieces of the
  // form that haven't yet been rendered above.
  print drupal_render_children($form);
