<?php

namespace Example\Helper;

/**
 * Class TinyMCE
 * @package Example\Helper
 */
final class TinyMCE
{
  const STYLE_FORMATS = [
//    [
//      'title' => 'Typography',
//      'items' => [
//        [
//          'title' => 'Transform',
//          'items' => [
//            [
//              'title' => 'Uppercase',
//              'selector' => '*',
//              'styles' => [
//                'text-transform' => 'uppercase'
//              ]
//            ]
//          ]
//        ],
//        [
//          'title' => 'Color',
//          'items' => [
//            [
//              'title' => 'Red',
//              'selector' => '*',
//              'styles' => [
//                'color' => '#FF0000'
//              ]
//            ]
//          ]
//        ]
//      ]
//    ],
//    [
//      'title' => 'Buttons',
//      'items' => [
//        [
//          'title' => 'Default',
//          'selector' => 'button, a',
//          'classes' => 'button'
//        ],
//        [
//          'title' => 'Invert',
//          'selector' => 'button, a',
//          'classes' => 'button button--invert'
//        ],
//        [
//          'title' => 'Big',
//          'selector' => 'button, a',
//          'classes' => 'button button--big'
//        ]
//      ]
//    ]
  ];

  /**
   * Enables TinyMCE custom formats
   */
  public static function enableCustomFormats()
  {
    add_filter('mce_buttons', function($buttons) {
      array_unshift($buttons, 'styleselect');
      return $buttons;
    });
    add_filter('tiny_mce_before_init', function($formats) {
      $formats['style_formats'] = json_encode(self::STYLE_FORMATS);
      return $formats;
    });
  }
}
