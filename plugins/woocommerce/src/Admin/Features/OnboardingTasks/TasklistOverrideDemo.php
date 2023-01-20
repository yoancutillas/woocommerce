<?php

namespace Automattic\WooCommerce\Admin\Features\OnboardingTasks;
use Automattic\WooCommerce\Admin\Features\OnboardingTasks\DemoTask;

class TasklistOverrideDemo {

  public function __construct() {
    add_filter( 'woocommerce_admin_experimental_onboarding_tasklists', array( $this, 'override_task_lists' ) );
  }

  /**
   * Override task lists
   *
   * @param array $task_lists Task lists.
   * @return array
   */
  public function override_task_lists( $lists ) {
    $demo = new DemoTask();
    $tasklist = new TaskList(
      array(
        'id'                      => 'setup',
        'title'                   => 'Demo',
        'display_progress_header' => true,
        'event_prefix'            => 'tasklist_',
        'options'                 => array(
          'use_completed_title' => true,
        ),
        'visible'                 => true,
      )
    );
    $tasklist->add_task($demo);
    $newLists = array(
      'setup' => $tasklist,
    );
    return $newLists;
  }
}

new TasklistOverrideDemo();