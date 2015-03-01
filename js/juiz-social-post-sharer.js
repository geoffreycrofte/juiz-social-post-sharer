/*
Plugin Name: Juiz Social Post Sharer
Plugin URI: http://creativejuiz.fr
Author: Geoffrey Crofte, Dejan Lukan
*/


/* Juiz SPS core */

;jQuery(document).ready(function($){
    function juiz_formurl(type, url, pluginurl) {
      if(type == "twitter") { 
        return "https://cdn.api.twitter.com/1/urls/count.json?url=" + url + "&callback=?"; 
      }
      if(type == "delicious") { 
        return "https://feeds.delicious.com/v2/json/urlinfo/data?url=" + url + "&callback=?"; 
      }
      if(type == "linkedin") { 
        return "https://www.linkedin.com/countserv/count/share?format=jsonp&url=" + url + "&callback=?"; 
      }
      if(type == "pinterest") { 
        return "https://api.pinterest.com/v1/urls/count.json?callback=?&url=" + url; 
      }
      if(type == "facebook") { 
        return "https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22"+url+"%22"; 
      }
      if(type == "google") { 
        return pluginurl + "js/get-noapi-counts.php?nw=google&url=" + url; 
      }
      if(type == "stumble") { 
        return pluginurl + "js/get-noapi-counts.php?nw=stumble&url=" + url; 
      }
    }

  jQuery.fn.juiz_get_counters = function(){
    return this.each(function() {
      var plugin_url = $(this).find('.juiz_sps_info_plugin_url').val(),
      $twitter       = $(this).find('.juiz_sps_link_twitter'),
      $linkedin      = $(this).find('.juiz_sps_link_linkedin'),
      $delicious     = $(this).find('.juiz_sps_link_delicious'),
      $facebook      = $(this).find('.juiz_sps_link_facebook'),
      $pinterest     = $(this).find('.juiz_sps_link_pinterest'),
      $google        = $(this).find('.juiz_sps_link_google'),
      $stumble       = $(this).find('.juiz_sps_link_stumbleupon'),
      item_class     = '';
      
      // Check both the http/https URL for the number of shares.
      url1 = $(this).find('.juiz_sps_info_permalink').val();
      url2 = "";
      if (url1.substring(0,5) == "http:") {
        url2 = url1.replace("http:", "https:");
      }
      else if(url1.substring(0,6) == "https:") {
        url2 = url1.replace("https:", "http:");
      }

      if ( $(this).hasClass('counters_total') ) {
        item_class = ' juiz_hidden_counter';
      }

      if ( $twitter.length ) {
        var twitter_counter = 0;
	      $.when(
          $.getJSON(juiz_formurl("twitter", url1), function(data) { twitter_counter += data.count; }), 
	        $.getJSON(juiz_formurl("twitter", url2), function(data) { twitter_counter += data.count; }))
        .then(function() {
          $twitter.prepend('<span class="juiz_sps_counter'+item_class+'">' + twitter_counter + '</span>');
        });
      }
      if ( $linkedin.length ) {
        var linkedin_counter = 0;
	      $.when(
          $.getJSON(juiz_formurl("linkedin", url1), function(data) { linkedin_counter += data.count; }))
        .then(function() {
          $linkedin.prepend('<span class="juiz_sps_counter'+item_class+'">' + linkedin_counter + '</span>');
        });
      }
      if ( $pinterest.length ) {
        var pinterest_counter = 0;
	      $.when(
          $.getJSON(juiz_formurl("pinterest", url1), function(data) { pinterest_counter += data.count; }), 
  	      $.getJSON(juiz_formurl("pinterest", url2), function(data) { pinterest_counter += data.count; }))
        .then(function() {
          $pinterest.prepend('<span class="juiz_sps_counter'+item_class+'">' + pinterest_counter + '</span>');
        });
      }
      if ( $google.length ) {
        var google_counter = 0;
  	    $.when(
          $.getJSON(juiz_formurl("google", url1, plugin_url), function(data) { google_counter += parseInt(data.count); }), 
  	      $.getJSON(juiz_formurl("google", url2, plugin_url), function(data) { google_counter += parseInt(data.count); }))
        .then(function() {
          $google.prepend('<span class="juiz_sps_counter'+item_class+'">' + google_counter + '</span>');
        });
      }
      if ( $stumble.length ) {
        var stumble_counter = 0;
      	$.when(
          $.getJSON(juiz_formurl("stumble", url1, plugin_url), function(data) { stumble_counter += data.count; }), 
      	  $.getJSON(juiz_formurl("stumble", url2, plugin_url), function(data) { stumble_counter += data.count; }))
        .then(function() {
          $stumble.prepend('<span class="juiz_sps_counter'+item_class+'">' + stumble_counter + '</span>');
        });
      }
      if ( $facebook.length ) {
        var facebook_counter = 0;
      	$.when(
          $.getJSON(juiz_formurl("facebook", url1), function(data) { if(data.data.length > 0) { facebook_counter += data.data[0].total_count; }}), 
          $.getJSON(juiz_formurl("facebook", url2), function(data) { if(data.data.length > 0) { facebook_counter += data.data[0].total_count; }}))
        .then(function() {
          $facebook.prepend('<span class="juiz_sps_counter'+item_class+'">' + facebook_counter + '</span>');
        });
      }
      if ( $delicious.length ) {
        var delicious_counter = 0;
      	$.when(
          $.getJSON(juiz_formurl("delicious", url1), function(data) { delicious_counter += data[0].total_posts; }), 
      	  $.getJSON(juiz_formurl("delicious", url2), function(data) { delicious_counter += data[0].total_posts; }))
        .then(function() {
          $delicious.prepend('<span class="juiz_sps_counter'+item_class+'">' + delicious_counter + '</span>');
        });
      }
    });
  }; // juiz_get_counters()

  jQuery.fn.juiz_update_counters = function(){
    return this.each(function(){

      var $group      = $(this);
      var $total_count   = $group.find('.juiz_sps_totalcount');
      var $total_count_nb = $total_count.find('.juiz_sps_t_nb');
      var total_text = $total_count.attr('title');
      $total_count.prepend('<span class="juiz_sps_total_text">'+total_text+'</span>');

      function count_total() {
        var total = 0;
        $group.find('.juiz_sps_counter').each(function(){
          total += parseInt($(this).text());
        });
        $total_count_nb.text(total);
      }
      setInterval(count_total, 1200);

    });
  }; // juiz_get_counters()

  $('.juiz_sps_links.juiz_sps_counters').juiz_get_counters();
  // only when total or both option is checked
  if ($('.juiz_sps_links.juiz_sps_counters.counters_subtotal').length == 0) {
    $('.juiz_sps_counters .juiz_sps_links_list').juiz_update_counters();
  }
});
/*
//var google_url = "https://clients6.google.com/rpc?key=YOUR_API_KEY";
//var digg_url = "http://services.digg.com/2.0/story.getInfo?links=" + url + "&type=javascript&callback=?"; 
*/
