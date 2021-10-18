<?php

/**
 * Plugin name: Word Count
 * Description: This plugin will count & show all words of wordpress posts.
 * Version: 1.0.0
 * Author: BM Tamim
 * Text Domain: word-count
 * Domain Path: /languages
 */

function wordcount_activation_hook()
{

}

register_activation_hook(__FILE__, 'wordcount_activation_hook');

function wordcount_deactivation_hook()
{

}

register_deactivation_hook(__FILE__, 'wordcount_deactivation_hook');

//Load text Domain
function wordcount_laod_text_domain()
{
    load_plugin_textdomain('word-count', false, dirname(__FILE__ . '/languages'));
}

add_action('plugin_loaded', 'wordcount_laod_text_domain');

function wordcount_single_post_word_count($content)
{
    $stripedContent = strip_tags($content);
    $wordsCount = str_word_count($stripedContent);
    $label = __('Total Words', 'word-count');
    $label = apply_filters('wordcount_heading_label', $label);
    $tag = apply_filters('wordcount_heading_tag', 'h2');
    $content .= sprintf('<%s>%s : %d</%s>', $tag, $label, $wordsCount, $tag);
    return $content;
}

add_filter('the_content', 'wordcount_single_post_word_count');

function wordcount_post_reading_time($content)
{
    $stripedContent = strip_tags($content);
    $wordsCount = str_word_count($stripedContent);

    $min = floor($wordsCount / 200);
    $sec = floor(($wordsCount % 200) / (200 / 60));

    $label = __('Total Reading Time', 'word-count');
    $label = apply_filters('wordcount_reading_heading_label', $label);
    $tag = apply_filters('wordcount_reading_heading_tag', 'h2');
    $content .= sprintf('<%s>%s : Minute %d - Second %d</%s>', $tag, $label, $min, $sec, $tag);
    return $content;
}

add_filter('the_content', 'wordcount_post_reading_time');

function wordcount_show_ads_in_post_content($content)
{
    $ads = '<h5>Ads will display here! </h5>';
    $interval = 2;

    $paragraphs = explode('</p>', $content);
    if (count($paragraphs) > $interval):
        $content = '';
        $i = 1;
        foreach ($paragraphs as $paragraph) {
            if ($i === $interval) {
                $content .= $paragraph . $ads;
                $i = 1;
                continue;
            }
            $content .= $paragraph;
            $i++;
        }
    else:
        $content .= $ads;
    endif;
    return $content;
}

add_filter('the_content', 'wordcount_show_ads_in_post_content');
