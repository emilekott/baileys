<?php

/**
 * @file
 * Hooks provided by Bundle copy.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Implements hook_bundle_copy_info().
 *
 * Return info for bundle copy. The first key is
 * the name of the entity_type.
 */
function hook_bundle_copy_info() {
  return array(
    'node' => array(
      'bundle_export_callback' => 'node_type_get_type',
      'bundle_save_callback' => 'node_type_save',
      'bundle_clone_load_callback' => 'node_type_get_type',
      'bundle_clone_save_callback' => 'node_type_save',
      'bundle_clone_bundlename_validate' => 'node_type_load',
      'export_menu' => array(
        'path' => 'admin/structure/types/export',
        'access arguments' => 'administer content types',
      ),
      'import_menu' => array(
        'path' => 'admin/structure/types/import',
        'access arguments' => 'administer content types',
      ),
      'clone_menu' => array(
        'path' => 'admin/structure/types/clone',
        'access arguments' => 'administer content types',
      ),
    ),
  );
}

/**
 * @} End of "addtogroup hooks".
 */
