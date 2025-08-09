<?php
/**
 * Site URL Rules
 *
 * You can define custom site URL rules here, which Craft will check in addition
 * to routes defined in Settings → Routes.
 *
 * Read all about Craft’s routing behavior, here:
 * https://craftcms.com/docs/4.x/routing.html
 */

 return [
  'prayer-room' => ['template' => 'prayer-room'],
  'membership/success' => ['template' => 'membership/success'],
  'membership/cancel' => ['template' => 'membership/cancel'],
  
  'articles/tag/<slug>' => ['template' => 'articles/tag'],
  'activities/tag/<slug>' => ['template' => 'activities/tag'],
];

