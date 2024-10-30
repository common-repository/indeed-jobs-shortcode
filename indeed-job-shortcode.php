<?php

/**
 * Plugin Name: Indeed Jobs Shortcode
 * Plugin URI: http://masanoriuehara.com
 * Description: This plugin allow users to add shortcode for indeed job API.
 * Version: 1.0.0
 * Author: Masanori Uehara
 * Author URI: http://masanoriuehara.com
 * License: GPL2.
 */
require_once 'class.indeed-shortcode.php';
require_once 'admin/class.indeed-shortcode-admin.php';

function indeed_add_shortcode($atts)
{
    $indeed_shortcode_setting_options = get_option('indeed_jobs_shortcode_option_name');

    $importer = new IndeedImporter(isset($indeed_shortcode_setting_options['indeed_api_key_0']) ? esc_attr($indeed_shortcode_setting_options['indeed_api_key_0']) : null);

    $defaults = array(
      'v' => '2',
      'format' => 'json',
      'q' => 'Engineer',
      'l' => '',
      'sort' => '',
      'radius' => '25',
      'st' => '',
      'jt' => '',
      'start' => '0',
      'limit' => '10',
      'fromage' => '',
      'highlight' => '0',
      'filter' => '1',
      'latlong' => '0',
      'co' => 'us',
      'chnl' => '',
      'userip' => $_SERVER['REMOTE_ADDR'],
      'useragent' => $_SERVER['HTTP_USER_AGENT'],
      'class' => 'indeed-shortcode'
    );

    foreach ($defaults as $default => $value) { // add defaults
        if (!@array_key_exists($default, $atts)) { // mute warning with "@" when no params at all
            $atts[$default] = $value;
        }
    }

    $jobs = $importer->getSearch($atts);

    $html = "<div class='$atts[class]'>";
    if (isset($jobs['results'])) {
      foreach ($jobs['results'] as $job) {
          if (isset($indeed_shortcode_setting_options['layout_template_1'])) {
            $html .= nl2br($indeed_shortcode_setting_options['layout_template_1']);
            $html = (isset($job['jobtitle'])) ? str_replace("[jobtitle]", $job['jobtitle'] , $html) : $html;
            $html = (isset($job['company'])) ? str_replace("[company]", $job['company'] , $html) : $html;
            $html = (isset($job['city'])) ? str_replace("[city]", $job['city'] , $html) : $html;
            $html = (isset($job['state'])) ? str_replace("[state]", $job['state'] , $html) : $html;
            $html = (isset($job['country'])) ? str_replace("[country]", $job['country'] , $html) : $html;
            $html = (isset($job['formattedLocation'])) ? str_replace("[formattedLocation]", $job['formattedLocation'] , $html) : $html;
            $html = (isset($job['source'])) ? str_replace("[source]", $job['source'] , $html) : $html;
            $html = (isset($job['date'])) ? str_replace("[date]", $job['date'] , $html) : $html;
            $html = (isset($job['snippet'])) ? str_replace("[snippet]", $job['snippet'] , $html) : $html;
            $html = (isset($job['url'])) ? str_replace("[url]", $job['url'] , $html) : $html;
            $html = (isset($job['onmousedown'])) ? str_replace("[onmousedown]", $job['onmousedown'] , $html) : $html;
            $html = (isset($job['latitude'])) ? str_replace("[latitude]", $job['latitude'] , $html) : $html;
            $html = (isset($job['longitude'])) ? str_replace("[longitude]", $job['longitude'] , $html) : $html;
            $html = (isset($job['jobkey'])) ? str_replace("[jobkey]", $job['jobkey'] , $html) : $html;
            $html = (isset($job['sponsored'])) ? str_replace("[sponsored]", $job['sponsored'] , $html) : $html;
            $html = (isset($job['expired'])) ? str_replace("[expired]", $job['expired'] , $html) : $html;
            $html = (isset($job['formattedLocationFull'])) ? str_replace("[formattedLocationFull]", $job['formattedLocationFull'] , $html) : $html;
            $html = (isset($job['formattedRelativeTime'])) ? str_replace("[formattedRelativeTime]", $job['formattedRelativeTime'] , $html) : $html;

          }
      }
    } else {
      echo $jobs;
    }
    $html .= "</div>";

    return html_entity_decode($html);
}
add_shortcode('indeed', 'indeed_add_shortcode');
