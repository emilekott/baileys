<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */

//inner wrapper added and a way of plotting tech markers

function severne_html5_fieldset($variables) {
    $element = $variables['element'];
    element_set_attributes($element, array('id'));
    _form_set_class($element, array('form-wrapper'));
    
    //check for tech marker
    if ($element['#attributes']['id'] == 'field_collection_item_field_tech_marker_full_group_marker_position') {
        //plot the coordinates of the tech point
        $x = $element['field_x_position'][0]['#markup']-25+57;
        $y = $element['field_y_position'][0]['#markup']-25+30;
        $output = '<div class="marker-wrapper"><a href="#"><div class="tech-marker" style="left: '.$x.'px; top: '.$y.'px;"></div></a></div>';
    } 
    
    else {
        $output = '<fieldset' . drupal_attributes($element['#attributes']) . '><div class="fieldset-top"></div><div class="clear"></div>';

        if (!empty($element['#title'])) {
            // Always wrap fieldset legends in a SPAN for CSS positioning.
            $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';
        }
        $output .= '<div class="fieldset-wrapper">';
        if (!empty($element['#description'])) {
            $output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
        }
        $output .= $element['#children'];
        if (isset($element['#value'])) {
            $output .= $element['#value'];
        }
        $output .= '<div class="clear"></div></div><div class="clear"></div><div class="fieldset-bottom"></div>';
        $output .= "</fieldset>\n";
    }
    
    //need to change this to not render if empty
    
    if ($element['#id'] == 'node_product_full_group_product_media' || $element['#id'] == 'node_product_full_group_faq'){

        if (! (strstr($element['#children'],'views-row') )){
            return '';
        }
    }
    
    if ($element['#id'] == 'node_product_full_group_product_technology'){
       if (! (strstr($element['#children'],'article') )){
            return '';
        }
    }
    
    return $output;
}

function severne_html5_file_icon($variables) {
    $file = $variables['file'];
    $icon_directory = $variables['icon_directory'];

    $mime = check_plain($file->filemime);

    if ($mime == 'application/pdf') {
        $icon_url = base_path() . path_to_theme() . '/images/pdf-icon.png';
    } else {
        $icon_url = file_icon_url($file, $icon_directory);
    }


    return '<img class="file-icon" alt="" title="' . $mime . '" src="' . $icon_url . '" />';
}

//make files default to new window / tab if they are text or pdf
function severne_html5_file_link($variables) {
    $file = $variables['file'];
    $icon_directory = $variables['icon_directory'];

    $url = file_create_url($file->uri);
    $icon = theme('file_icon', array('file' => $file, 'icon_directory' => $icon_directory));

    // Set options as per anchor format described at
    // http://microformats.org/wiki/file-format-examples
    $options = array(
        'attributes' => array(
            'type' => $file->filemime . '; length=' . $file->filesize,
        ),
    );

    // Use the description as the link text if available.
    if (empty($file->description)) {
        $link_text = $file->filename;
    } else {
        $link_text = $file->description;
        $options['attributes']['title'] = check_plain($file->filename);
    }

    //open files of particular mime types in new window
//add mime types to this array if you want more than just PDF files
    $new_window_mimetypes = array('application/pdf', 'text/plain');
    if (in_array($file->filemime, $new_window_mimetypes)) {
        $options['attributes']['target'] = '_blank';
    }


    return '<span class="file">' . $icon . ' ' . l($link_text, $url, $options) . '</span>';
}


function severne_html5_delta_blocks_breadcrumb($variables) {
    $breadcrumb = $variables['breadcrumb'];
    
    if (!empty($breadcrumb)) {
        // Provide a navigational heading to give context for breadcrumb links to
        // screen-reader users. Make the heading invisible with .element-invisible.
        $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

        $crumbs = '<div class="breadcrumb">';
        $array_size = count($breadcrumb);
        $i = 0;
        while ($i < $array_size) {
            $crumbs .= '<span class="breadcrumb-' . $i;
            if ($i == 0) {
                $crumbs .= ' first';
            }
            /* if ($i+1 == $array_size) {
              $crumbs .= ' last';
              } */
            $crumbs .= '">' . $breadcrumb[$i] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;&nbsp;';
            $i++;
        }
        $crumbs .= '<span class="active">' . drupal_get_title() . '</span></div>';
        return $crumbs;
    }
}




function severne_html5_media_gallery_collection($variables) {
  $element = $variables['element'];
  $collection = $element['#term'];

  $columns = $collection->media_gallery_columns[LANGUAGE_NONE][0]['value'];
  $grid = '<div class="media-gallery-collection mg-collection-' . $collection->vocabulary_machine_name . ' mg-col mg-col-' . $columns . '">';
  foreach (element_children($element['nodes']) as $nid) {
    // This invokes node.tpl.php, and where that calls render($content),
    // theme_media_gallery_teaser() is called.

    // Add the term to the node so that we can use it to configure the meta data
    $element['nodes'][$nid]['#node']->term = $collection;
    $teaser = drupal_render($element['nodes'][$nid]);

    // @todo Implement real display needs.
    $cell = $teaser;
    $grid .= $cell;
  }
  $grid .= '</div>';

  // Replace the 'nodes' element with the rendered grid while preserving its
  // weight, so that other fields that are part of the collection term get
  // rendered normally, and in the correct order.
  $weight = isset($element['nodes']['#weight']) ? $element['nodes']['#weight'] : 0;
  $element['nodes'] = array('#markup' => $grid, '#weight' => $weight);
  $output = drupal_render_children($element);

  return '<div id="collection-header"></div><div id="collection-container">'.$output.'</div><div id="collection-footer"></div>';
}



function severne_html5_media_gallery_media_item_lightbox($variables) {
  $element = $variables['element'];
  $gallery_node = new FieldsRSIPreventor($element['#media_gallery_entity']);
  $file = $element['#file'];

  // The lightbox JavaScript requires width and height attributes to be set on
  // the displayed image, but if we're displaying an image derivative, we need
  // to create it in order to know its width and height.
  // @todo Improve the JavaScript to not require this.
  if ($element['file']['#theme'] == 'image_style') {
    $style_name = $element['file']['#style_name'];
    $style_path = image_style_path($style_name, $file->uri);
    if (!file_exists($style_path)) {
      $style = image_style_load($style_name);
      image_style_create_derivative($style, $file->uri, $style_path);
    }
    $info = image_get_info($style_path);
    $element['file'] += array('#attributes' => array());
    $element['file']['#attributes'] += array('width' => $info['width'], 'height' => $info['height']);
  }

  $image = drupal_render($element['file']);

  $matches = NULL;
  if (preg_match('@<img .*?/>@', $image, $matches)) {
    $image = $matches[0];
  }
  else {

  }

  $gallery_id = $element['#media_gallery_entity']->nid;
  $media_id = $element['#file']->fid;

  // Create an array of variables to be added to the main image link.
  $link_vars = array();
  $link_vars['image'] = $image;
  $link_vars['link_path'] = "media-gallery/detail/$gallery_id/$media_id";
  $link_vars['no_link'] = $element['#bundle'] != 'image' ? TRUE : FALSE;

  if ($gallery_node->getValue('media_gallery_allow_download') == TRUE) {
    $download_link = $element['#bundle'] == 'image' ? theme('media_gallery_download_link', array('file' => $file)) : l(t('View detail page'), $link_vars['link_path']);
  }

  else {
    // Very ugly fix: This prevents the license info from being either hidden
    // or causing scrollbars (depending on the browser) in cases where a
    // download link is not being shown. There may be a CSS-only fix for this,
    // but we haven't found one yet.
    $download_link = '&nbsp;';
  }

  $media_gallery_detail =
      '<div class="lightbox-stack">' .
      theme('media_gallery_item', $link_vars) .
      '<div class="media-gallery-detail-info">' .
      $download_link .
      theme('media_gallery_license', array('element' => isset($element['field_license']) ? $element['field_license'] : array(), 'color' => 'medium', 'file' => $file)) .
      '</div></div>';
  // The license info has been themed already, keep it from being rendered as a child
  $element['field_license']['#access'] = FALSE;

  $output = 'Error';
  // If the format is to have the description as well, we add it here
  if (!empty($gallery_node->media_gallery_lightbox_extras[LANGUAGE_NONE][0]['value'])) {
    $output =
    '<div class="mg-lightbox-wrapper clearfix">' .
      '<div class="lightbox-title">' . drupal_render($element['media_title']) . '</div>' .
      '<div class="mg-lightbox-detail">' .
      $media_gallery_detail .
      '</div><div class="mg-lightbox-description">' .
        drupal_render_children($element) .
      '</div>' .
    '</div>';
  } else {
    $output = $media_gallery_detail;
  }

  return $output;
}



function severne_html5_media_gallery_media_item_detail($variables) {
  $element = $variables['element'];
  $gallery_node = new FieldsRSIPreventor($element['#media_gallery_entity']);
  $file = $element['#file'];
  // Page number for next and previous pages and current page.
  $i_next = NULL;
  $i_previous = NULL;
  $i_current = NULL;

  // Not considering the possibility of this field being translatable, at the
  // moment. Is there a use-case for a media field (which is just a reference to
  // a media entity) to be translatable?
  $media_ids = array();
  foreach ($gallery_node->media_gallery_media[LANGUAGE_NONE] as $delta => $item) {
    $media_ids[] = _media_gallery_get_media_fid($item);
  }
  $media_ids = array_values(array_unique($media_ids));

  // Get the variables needed for previous and next buttons and "Image X of Y"
  // text.
  $num_items = count($media_ids);
  foreach ($media_ids as $i => $id) {
    if ($id == $file->fid) {
      $i_current = $i;
      break;
    }
  }

  $i_previous = $i_current - 1;
  $i_next = $i_current + 1;

  if ($i_previous < 0) {
    $i_previous = NULL;
  }

  $i_next = $i_current + 1;
  if ($i_next > $num_items - 1) {
    $i_next = NULL;
  }

  if ($gallery_node->getValue('media_gallery_allow_download') == TRUE) {
    $download_link = $element['#bundle'] == 'image' ? theme('media_gallery_download_link', array('file' => $file)) : '&nbsp;';
  }
  else {
    // Very ugly fix: This prevents the license info from being either hidden
    // or causing scrollbars (depending on the browser) in cases where a
    // download link is not being shown. There may be a CSS-only fix for this,
    // but we haven't found one yet.
    $download_link = '&nbsp;';
  }

  $previous_link = !is_null($i_previous) ? l(t('« Previous'), "media-gallery/detail/{$gallery_node->nid}/{$media_ids[$i_previous]}", array('html' => TRUE, 'attributes' => array('class' => 'prev'))) : '';
  $next_link = !is_null($i_next) ? l(t('Next »'), "media-gallery/detail/{$gallery_node->nid}/{$media_ids[$i_next]}", array('html' => TRUE, 'attributes' => array('class' => 'next'))) : '';

  // Render the file out in a wrapper
  $output =
    '<div class="media-gallery-detail-wrapper">' .
    '<div class="media-gallery-detail">' .
      drupal_render($element['file']) .
      '<div class="media-gallery-detail-info">' . $download_link .
        theme('media_gallery_license', array('element' => isset($element['field_license']) ? $element['field_license'] : array('#view_mode' => 'media_gallery_detail'), 'color' => 'dark', 'file' => $file)) .
      '</div>' .
      '<div class="media-gallery-detail-info">' .
        '<span class="media-gallery-back-link">' .
          l(t('« Back to gallery'), 'node/' . $gallery_node->nid) .
        '</span>' .
        '<span class="media-gallery-detail-image-info-wrapper">' .
          '<span class="media-gallery-image-count">' .
            t("Item @current of @total", array('@current' => $i_current + 1, '@total' => $num_items)) .
          '</span>' .
          '<span class="media-gallery-controls">' .
            $previous_link .
            (!empty($previous_link) && !empty($next_link) ? ' | ' : '') .
            $next_link .
          '</span>' .
        '</span>' .
      '</div>' .
    '</div>';

  // The license was already output above via a direct theme() call rather
  // than drupal_render().
  $element['field_license']['#printed'] = TRUE;

  // The file was rendered above, but due to a drupal_render() bug, might not be
  // marked as printed.
  // @todo Remove when http://drupal.org/node/1305220 is fixed.
  $element['file']['#printed'] = TRUE;

  // Render the remaining fields, but not the title, since that's output as
  // part of the page title.
  $element['media_title']['#access'] = FALSE;
  $output .=
    '<div class="no-overflow">' .
      drupal_render_children($element) .
    '</div></div>';

  return '<div id="detail-header"></div><div id="detail-container">'.$output.'<div class="clear"></div></div><div id="detail-footer"></div>';
}



function severne_html5_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.
  $next = array(
      'path' => drupal_get_path('theme', 'severne_html5').'/images/buttons/next.png',
      'alt' => 'next',
      'title' => 'next',
  );
  $last = array(
      'path' => drupal_get_path('theme', 'severne_html5').'/images/buttons/last.png',
      'alt' => 'last',
      'title' => 'last',
  );
  $first = array(
      'path' => drupal_get_path('theme', 'severne_html5').'/images/buttons/first.png',
      'alt' => 'first',
      'title' => 'first',
  );
  $prev = array(
      'path' => drupal_get_path('theme', 'severne_html5').'/images/buttons/previous.png',
      'alt' => 'previous',
      'title' => 'previous',
  );
  
  
  $next_image = theme('image', $next);
  $last_image = theme('image', $last);
  $first_image = theme('image', $first);
  $prev_image = theme('image', $prev);
  
  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : $first_image), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : $prev_image), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : $next_image), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  //print_r($li_next);  
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : $last_image), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}


function severne_html5_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  //   path only (which is always the current path for pager links). Apparently,
  //   none of the pager links is active at any time - but it should still be
  //   possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '>' . $text . '</a>';
}

function severne_html5_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  
  //print_r($element);exit;
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item', $element['#id']);
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}


function severne_html5_form_alter(&$form, &$form_state, $form_id) {
    if ($form_id == 'search_block_form') {
        $expand = theme('image', array(
            'path' => base_path() . path_to_theme() . '/images/search-expand.png',
            'alt' => 'Expand Search',
        ));
        $contract = theme('image', array(
            'path' => base_path() . path_to_theme() . '/images/search-minus.png',
            'alt' => 'Contract Search',
            'attributes' => array('id' => 'contract-search') 
        ));
        
        $expand_link = '<a href="#" id="expand-search">'.$expand.$contract.'</a>';
        
        
        $form['search_block_form']['#title'] = t(''); // Change the text on the label element
        //$form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty
        //$form['search_block_form']['#size'] = 20;  // define size of the textfield
        $form['search_block_form']['#default_value'] = t('Search the site...'); // Set a default value for the textfield
        //$form['search_block_form']['#prefix'] = $expand_link;
        // $form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
        $form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/buttons/go.png');

        // Add extra attributes to the text box
        $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search the site...';}";
        $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search the site...') {this.value = '';}";
    }
    if ($form_id == 'user_login_block')
        unset($form['links']);
    
    if ($form_id == 'search_form'){
        $form['basic']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/buttons/go.png');
    }
    
  
}


function severne_html5_media_gallery_teaser($variables) {
  $element = $variables['element'];
  $node = $element['#node'];

  if (isset($element['media_gallery_media'][0])) {
    $element['media_gallery_media'][0]['#theme'] = 'media_gallery_file_field_inline';
    $image = drupal_render($element['media_gallery_media'][0]);
  }
  else {
    $image = theme('image', array('path' => drupal_get_path('module', 'media_gallery') . '/images/empty_gallery.png'));
  }

  $link_vars = array();
  $link_vars['image'] = $image;
  $uri = entity_uri('node', $node);
  $link_vars['link_path'] = $uri['path'];
  $link_vars['classes'] = array('media-gallery-thumb');

  $output = '<div class="media-collection-item-wrapper">' . theme('media_gallery_item', $link_vars);

  // Set the variables to theme the meta data if there is a term on the node
  if (isset($node->term)) {
    $term = $node->term;
    $meta_vars = array();
    $meta_vars['location'] = $term->media_gallery_image_info_where[LANGUAGE_NONE][0]['value'];
    $meta_vars['title'] = $node->title;
    $meta_vars['link_path'] = $link_vars['link_path'];
    // Organize the file count by type. We only expect images and videos for
    // now, so we put those first and group the others together into a general
    // category at the end.
    $type_count = media_gallery_get_media_type_count($node, 'media_gallery_media_original');
    $description = array();
    if (isset($type_count['image'])) {
      $count = $type_count['image'];
      $description[] = format_plural($count, '<span>@num image</span>', '<span>@num images</span>', array('@num' => $count));
      unset($type_count['image']);
    }
    if (isset($type_count['video'])) {
      $count = $type_count['video'];
      $description[] = format_plural($count, '<span>@num video</span>', '<span>@num videos</span>', array('@num' => $count));
      unset($type_count['video']);
    }
    if (!empty($type_count)) {
      $count = array_sum($type_count);
      $description[] = format_plural($count, '<span>@num other item</span>', '<span>@num other items</span>', array('@num' => $count));
    }
    $meta_vars['description'] = implode(', ', $description);

    // Add the meta information
    //$output .= theme('media_gallery_meta', $meta_vars);
    $output .= '<div class="gallery-title">'.$meta_vars['title'].'</div><div class="gallery-description">'.$meta_vars['description'].'</div></div>';
  }

  return $output;
}



function severne_html5_media_gallery_item($variables) {
  $image = $variables['image'];
  $element = $variables['element'];
  $link_path = $variables['link_path'];
  $attributes = array();
  if (!empty($variables['classes'])) {
    $attributes['class'] = $variables['classes'];
  }
  if (!empty($variables['title'])) {
    // I'm fairly sure I don't like this solution.  But as Alex mentions in
    // theme_media_gallery_file_field_inline() the File Styles module isn't allowing
    // us access to the render array pre-rendering, so I'm doing a str_replace()
    // here specifically to address the title and alt for thumbnails.  This had
    // to be further modified to remove and then add the title and alt attributes
    // video thumbnails had no title and alt attributes so the string replace was
    // not triggering for them.
    $new_image = str_replace(array('title=""', 'alt=""'), array('', ''), $image);
    $image = str_replace('/>', ' title="'.$variables['title'].'" alt="'.$variables['title'].'" />', $new_image);;
  }
  if ($variables['title'] == 'Watch video'){
      //ugly!
      $image .= '<div class="gallery-play"></div>';
      
  }
  
  // Add sliding door top div and wrappers
  $item = '<div class="media-gallery-item"><div class="top"><div class="top-inset-1"><div class="top-inset-2"></div></div></div><div class="gallery-thumb-outer"><div class="gallery-thumb-inner">';
  // Create a link around the image
  $item .= empty($variables['no_link']) ? l($image, $link_path, array('html' => TRUE, 'attributes' => $attributes)) : $image;
  // Add sliding door bottom div and close wrappers
  $item .= '</div></div><div class="bottom"><div class="bottom-inset-1"><div class="bottom-inset-2"></div></div></div></div>';
  return $item;
}


