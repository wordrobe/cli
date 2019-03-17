<?php

namespace Example\Helper;

use Wordrobe\Helper\TemplateLocator;

/**
 * Class Router
 * @package Example\Helper
 */
final class Router
{
  /**
   * Locates a template
   * @param string $default
   * @return string
   * @throws \Exception
   */
  public static function locateTemplate($default)
  {
    $queried_object = get_queried_object();

    if (is_single()) {
      return TemplateLocator::get('single', 'example', $queried_object, $default);
    }

    if (is_page()) {
      return TemplateLocator::get('page', 'example', get_page_template(), $default);
    }

    if (is_category()) {
      return TemplateLocator::get('category', 'example', $queried_object, $default);
    }

    if (is_tag()) {
      return TemplateLocator::get('tag', 'example', $queried_object, $default);
    }

    if (is_tax()) {
      return TemplateLocator::get('taxonomy', 'example', $queried_object, $default);
    }

    if (is_author()) {
      return TemplateLocator::get('author', 'example', null, $default);
    }

    if (is_archive()) {
      return TemplateLocator::get('archive', 'example', $queried_object, $default);
    }

    if (is_search()) {
      return TemplateLocator::get('search', 'example', null, $default);
    }

    if (is_404()) {
      return TemplateLocator::get('404', 'example', null, $default);
    }

    return  TemplateLocator::get('index', 'example', null, $default);
  }

  /**
   * Adds rewrite rules for archives allowing urls like /post-type/term/sub-term
   * @param \WP_Rewrite $wp_rewrite
   */
  public static function rewriteCustomArchivesUrl(\WP_Rewrite $wp_rewrite)
  {
    $rules = [];
    $post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');

    foreach ($post_types as $post_type) {
      $post_type_name = $post_type->name;
      $post_type_slug = $post_type->rewrite['slug'];
      $taxonomies = get_taxonomies(['object_type' => [$post_type_name], 'public' => true, '_builtin' => false], 'objects');

      foreach ($taxonomies as $taxonomy) {
        $taxonomy_slug = $taxonomy->rewrite['slug'];
        $rules[$post_type_slug . '/' . $taxonomy_slug . '/?$'] = 'index.php?taxonomy=' . $taxonomy->name;
        $terms = get_terms($taxonomy->name, ['parent' => 0, 'hide_empty' => 0]);

        foreach ($terms as $term) {
          $rules[$post_type_slug . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
          $subterms = get_terms($taxonomy->name, ['parent' => $term->term_id, 'hide_empty' => 0]);

          foreach ($subterms as $subterm) {
            $rules[$post_type_slug . '/' . $term->slug . '/' . $subterm->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $subterm->slug;
          }
        }
      }
    }

    $wp_rewrite->rules = array_merge($rules, $wp_rewrite->rules);
  }

  /**
   * Initializes Router
   */
  public static function init()
  {
    add_filter('template_include', __NAMESPACE__ . '\Router::locateTemplate');
    add_action('generate_rewrite_rules', __NAMESPACE__ . '\Router::rewriteCustomArchivesUrl');
  }
}