/**
 * Plugin Name: Juiz Social Post Sharer
 * Plugin URI: http://creativejuiz.fr
 * Author: Geoffrey Crofte
 */
;
jQuery(document).ready(function($) {
    jQuery.fn.juiz_get_counters = function() {
        return this.each(function() {

            var plugin_url = $(this).find('.juiz_sps_info_plugin_url').val(),
                url = $(this).find('.juiz_sps_info_permalink').val(),
                $facebook = $(this).find('.juiz_sps_link_facebook'),
                $pinterest = $(this).find('.juiz_sps_link_pinterest'),
                item_class = '';

            if ($(this).hasClass('counters_total')) {
                item_class = ' juiz_hidden_counter';
            }

            // return : [{"url": "http://www.creativejuiz.fr/blog", "total_posts": 2}]
            var pinterest_url = "//api.pinterest.com/v1/urls/count.json?callback=?&url=" + url;
            // return : ({"count": 0, "url": "http://stylehatch.co"})
            var facebook_url = "//graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22" + url + "%22";
            // return : {"data": [{"like_count": 6,"total_count": 25,"share_count": 9,"click_count": 0,"comment_count": 10}]}
            //var google_url		= plugin_url+"js/get-noapi-counts.php?nw=google&url=" + url;
            //var stumble_url		= plugin_url+"js/get-noapi-counts.php?nw=stumble&url=" + url;

            if ($pinterest.length) {
                $.getJSON(pinterest_url)
                    .done(function(data) {
                        $pinterest.prepend('<span class="juiz_sps_counter' + item_class + '">' + data.count + '</span>');
                    });
            }
            if ($facebook.length) {
                $.getJSON(facebook_url)
                    .done(function(data) {
                        var facebookdata = 0;
                        if (data.data.length > 0) facebookdata = data.data[0].total_count;
                        $facebook.prepend('<span class="juiz_sps_counter' + item_class + '">' + facebookdata + '</span>');
                    });
            }
        });
    }; // juiz_get_counters()

    jQuery.fn.juiz_update_counters = function() {
        return this.each(function() {

            var $group = $(this);
            var $total_count = $group.find('.juiz_sps_totalcount');
            var $total_count_nb = $total_count.find('.juiz_sps_t_nb');
            var total_text = $total_count.attr('title');
            $total_count.prepend('<span class="juiz_sps_total_text">' + total_text + '</span>');

            function count_total() {
                var total = 0;
                $group.find('.juiz_sps_counter').each(function() {
                    total += parseInt($(this).text());
                });
                $total_count_nb.text(total);
            }
            setInterval(count_total, 3000);

        });
    }; // juiz_get_counters()

    $('.juiz_sps_links.juiz_sps_counters').juiz_get_counters();
    // only when total or both option is checked
    if ($('.juiz_sps_links.juiz_sps_counters.counters_subtotal').length == 0) {
        $('.juiz_sps_counters .juiz_sps_links_list').juiz_update_counters();
    }

    /**
     * E-mail button
     */
    if ($('.juiz_sps_link_mail').length) {

        $('.juiz_sps_link_mail').find('a').on('click', function() {

            if ($('.juiz-sps-modal').length === 0) {
                var animation = 400,
                    post_id = $(this).closest('.juiz_sps_links').data('post-id'),
                    jsps_modal = '<div class="juiz-social-post-sharer-modal juiz-sps-modal" aria-hidden="true" role="dialog" aria-labelledby="juiz-sps-email-title" data-post-id="' + post_id + '">' +
                    '<div class="juiz-sps-modal-inside">' +
                    '<div class="juiz-sps-modal-header">' +
                    '<p id="juiz-sps-email-title" class="juiz-sps-modal-title">' + jsps.modalEmailTitle + '</p>' +
                    '</div>' +
                    '<div class="juiz-sps-modal-content">' +
                    '<form id="jsps-email-form" name="jsps-email" action="' + jsps.modalEmailAction + '" method="post" novalidate>' +
                    '<p class="juiz-sps-input-line">' +
                    '<label for="jsps-your-name">' + jsps.modalEmailName + '</label>' +
                    '<input type="text" id="jsps-your-name" name="jsps-your-name" aria-required="true" required="required" value="" autofocus>' +
                    '</p>' +
                    '<p class="juiz-sps-input-line">' +
                    '<label for="jsps-your-email">' + jsps.modalEmailYourEmail + '</label>' +
                    '<input type="email" id="jsps-your-email" name="jsps-your-email" aria-required="true" required="required" value="">' +
                    '</p>' +
                    '<p class="juiz-sps-input-line">' +
                    '<label for="jsps-friend-email">' + jsps.modalEmailFriendEmail + '</label>' +
                    '<input type="email" id="jsps-friend-email" name="jsps-friend-email" aria-required="true" required="required" value="">' +
                    '</p>' +
                    '<p class="juiz-sps-textarea-line">' +
                    '<label for="jsps-message">' + jsps.modalEmailMessage + ' <small>(' + jsps.modalEmailOptional + ')</small><span class="juiz-sps-label-info">' + jsps.modalEmailMsgInfo + '</span></label>' +
                    '<textarea id="jsps-message" name="jsps-message" cols="50" rows="7" aria-required="false"></textarea>' +
                    '</p>' +
                    '<div class="juiz-sps-submit-infos">' +
                    '<p class="juiz-sps-message-info"> ' + jsps.modalEmailInfo + '</p>' +
                    '<p class="juiz-sps-submit-line">' +
                    '<button type="submit" id="jsps-email-submit"><span class="juiz-sps-loader"></span><span class="juiz-sps-submit-txt">' + jsps.modalEmailSubmit + '</span></button>' +
                    '</p>' +
                    '</div>' +
                    '</form>' +
                    '</div>' +
                    '<button class="juiz-sps-close" type="button"><i class="juiz-sps-icon-close">×</i><span class="juiz-sps-hidden">' + jsps.modalClose + '</span></button>' +
                    '<div class="juiz-sps-modal-footer"><p>' + jsps.modalEmailFooter + '</p></div>' +
                    '</div>' +
                    '</div>',
                    isValidEmail = function(email) {
                        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        return re.test(email);
                    };

                $('body').append(jsps_modal);
                $('.juiz-sps-modal').hide().fadeIn(animation).attr('aria-hidden', 'false')
                    .find('input:first').focus();

                $('#jsps-friend-email').on('input', function() {
                    let val = this.value,
                        reg = /\s*(?:[;,]|$)\s*/,
                        count = 0,
                        phrase = '';

                    val = val.split(reg);
                    $(val).each(function(i) {
                        count = isValidEmail(val[i]) ? count + 1 : count;
                    });

                    $('[for="jsps-friend-email"] span').remove();

                    if (count === 0) return;

                    if (count > 1) {
                        phrase = jsps.modalRecipientNbs;
                        phrase = phrase.replace("{number}", count);
                    } else {
                        phrase = jsps.modalRecipientNb;
                    }

                    $('[for="jsps-friend-email"]').append('<span>' + phrase + '</span>');
                });

            }

            // On form SUBMIT.
            $('body').on('submit.jsps', '#jsps-email-form', function(e) {

                var datas = $(this).serializeArray(),
                    $modal = $(this).closest('.juiz-sps-modal'),
                    post_id = $modal.data('post-id'),
                    $loader = $(this).find('.juiz-sps-loader');

                $loader.addClass('is-active').html(jsps.modalLoader);

                var to_send = {
                    'action': 'jsps-email-friend',
                    'jsps-email-friend-nonce': jsps.modalEmailNonce,
                    'id': post_id,
                    'name': datas[0].value,
                    'email': datas[1].value,
                    'friend': datas[2].value,
                    'message': datas[3].value
                };

                $.get(jsps.ajax_url, to_send, function(data) {
                    if (data.success === true) {
                        $modal.find('form').replaceWith('<div class="juiz-sps-success juiz-sps-message">' +
                            '<svg width="130px" height="86px" viewBox="0 0 130 86" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><path d="M129.832519,0.8459473 C129.832031,0.8449707 129.831543,0.8439942 129.831055,0.8439942 C129.694336,0.6389161 129.493652,0.5002442 129.271484,0.4367676 C129.271484,0.4367676 129.271484,0.4367676 129.270996,0.4367676 C129.268066,0.4367676 129.268066,0.4348145 129.267578,0.435791 C129.265625,0.435791 129.265625,0.4338379 129.26416,0.4348144 C129.262695,0.4348144 129.262207,0.4348144 129.258301,0.4338378 C129.256836,0.4328612 129.255371,0.4328612 129.254883,0.4328612 C129.253906,0.4328612 129.252441,0.4318846 129.251465,0.4318846 C129.250976,0.4328612 129.251465,0.430908 129.247558,0.430908 C129.245117,0.430908 129.24414,0.4299314 129.24414,0.4299314 C129.242187,0.4318845 129.241211,0.4289548 129.239746,0.4289548 L129.239258,0.4289548 C129.237793,0.4279782 129.236816,0.4279782 129.23584,0.4279782 C129.234863,0.4279782 129.233886,0.4270016 129.232422,0.4270016 C129.226562,0.426025 129.229492,0.426025 129.229004,0.426025 C129.227539,0.4250484 129.226074,0.4240719 129.225586,0.4250484 C129.223632,0.4250484 129.223144,0.4250484 129.222168,0.4250484 C129.081054,0.3928218 128.93164,0.3918453 128.787597,0.4221187 L0.8061523,25.7277832 C0.3925781,25.8098145 0.074707,26.1428223 0.0112304,26.5588379 C-0.0517579,26.9758301 0.1533202,27.3879395 0.5239257,27.5891113 L38.7761841,48.296875 L44.2290039,84.748291 C44.2433472,84.8442383 44.2748413,84.9345703 44.3149414,85.0205078 C44.3240967,85.0400391 44.3364258,85.0568848 44.3468018,85.0759277 C44.3948975,85.1638183 44.4549561,85.243164 44.5269165,85.3127441 C44.5414429,85.3269043 44.5529175,85.3422851 44.5683594,85.3557129 C44.6381836,85.4157715 44.7144776,85.46875 44.800293,85.5080567 C44.8154908,85.5151368 44.8327027,85.5122071 44.8481446,85.5185547 C44.8656007,85.5256348 44.8774415,85.5400391 44.8955079,85.5461426 C45.0024415,85.583252 45.1108399,85.5998535 45.2177735,85.5998535 C45.3156739,85.5998535 45.4129029,85.5832519 45.5073243,85.5546875 C45.5412598,85.5444336 45.5709229,85.5253906 45.6035157,85.5117187 C45.652649,85.4909667 45.7036134,85.4753417 45.7495118,85.4465332 L72.343628,68.7521973 L98.4980469,85.442627 C98.6606446,85.5461426 98.8476563,85.5998536 99.0361328,85.5998536 C99.1445312,85.5998536 99.2539062,85.5822755 99.3588867,85.5461427 C99.6464844,85.4484864 99.8740234,85.2248536 99.9770508,84.9387208 L129.937012,1.7492677 C129.950195,1.7131349 129.961914,1.6760255 129.971191,1.6389161 C129.972656,1.6320802 129.97168,1.636963 129.97168,1.6359864 C129.98291,1.5891114 129.990723,1.5422364 129.995606,1.4943848 C130.01416,1.2980957 129.975586,1.0949707 129.873535,0.9123535 C129.86084,0.8898926 129.847168,0.8674316 129.833008,0.8459472 C129.832519,0.8459473 129.832519,0.8459473 129.832519,0.8459473 Z M3.9179688,27.1516113 L122.299317,3.7443847 L39.7011719,46.5227051 L3.9179688,27.1516113 Z M45.59729,80.3791504 L40.7855225,48.213623 L118.380859,8.0256347 L54.2226563,55.5783691 C54.1802369,55.6098632 54.1459351,55.6489257 54.1095582,55.685791 C54.0936891,55.7021484 54.0759889,55.7143555 54.0612184,55.7316894 C53.9859621,55.8193359 53.9310304,55.9199218 53.8900758,56.0270996 C53.8854371,56.0393066 53.8753053,56.0473633 53.8710939,56.0598144 L45.59729,80.3791504 Z M47.0717163,82.2556152 L55.3543091,57.9106445 L70.4785767,67.5620117 L47.0717163,82.2556152 Z M98.519043,83.0842285 L56.5800781,56.3205566 L126.938476,4.1721191 L98.519043,83.0842285 Z" fill="#FFF" fill-rule="nonzero"></path></g></svg>' +
                            '<p>' + data.data + '</p>' +
                            '</div>');
                    } else if (data.success === false) {
                        if ($modal.find('.juiz-sps-error').length === 0) {
                            $modal.find('form').prepend('<div class="juiz-sps-error juiz-sps-message">' + data.data[1] + '</div>');
                        } else {
                            $modal.find('.juiz-sps-error').text(data.data[1]);
                        }

                        $loader.removeClass('is-active');
                        var temp = setInterval(function() {
                            $loader.find('img').remove();
                            clearInterval(temp);
                        }, 300);
                    }
                }, 'json').fail(function(data) {
                    $modal.find('.juiz-sps-error').remove();
                    $modal.find('form').append('<div class="juiz-sps-error juiz-sps-message">' + jsps.modalErrorGeneric + '<br><code style="font-size:.8em;">' + data.statusText + '(' + data.status + ')</code></div>');
                    $loader.removeClass('is-active');
                });

                return false;
            })

            // On CLOSE button.
            .on('click.jsps', '.juiz-sps-close', function() {
                var $modal = $('.juiz-sps-modal').fadeOut(animation).attr('aria-hidden', 'true'),
                    temp = setInterval(function() {
                        $modal.remove();
                    }, animation + 20);
                return false;
            });

            // On ESC key CLOSE the modal.
            $(window).on('keyup.jsps', function(e) {
                if (e.keyCode === 27) {
                    if ($('.juiz-sps-modal[aria-hidden="false"]')) {
                        $('.juiz-sps-close').trigger('click.jsps');
                    } else {
                        return;
                    }
                }
                return;
            });

            // Accessibility.
            $('.juiz-sps-close').on('blur', function() {
                $(this).closest('.juiz-sps-modal').find('input:first').focus();
                return false;
            });

            return false;
        });
    }

    /**
     * Print button
     */
    if (window.print) {
        $('.juiz_sps_link_print').on('click', function() {
            window.print();
            return false;
        });
    } else {
        $('.juiz_sps_link_print').remove();
    }

    /**
     * Bookmark button
     */
    if (
        ('addToHomescreen' in window && window.addToHomescreen.isCompatible) ||
        (window.sidebar && window.sidebar.addPanel) ||
        ((window.sidebar && /Firefox/i.test(navigator.userAgent)) || (window.opera && window.print)) ||
        (window.external && ('AddFavorite' in window.external)) ||
        (typeof chrome === 'undefined') ||
        (typeof chrome !== 'undefined')
    ) {
        $('.juiz_sps_link_bookmark').find('a').on('click', function(e) {
            // Thanks to:
            // https://www.thewebflash.com/how-to-add-a-cross-browser-add-to-favorites-bookmark-button-to-your-website/
            var bookmarkURL = window.location.href,
                bookmarkTitle = document.title;

            /*if ( 'addToHomescreen' in window && window.addToHomescreen.isCompatible ) {
            	// Mobile browsers
            	addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
            }
            else */
            if (window.sidebar && window.sidebar.addPanel) {
                // Firefox version < 23
                window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
            } else if ((window.sidebar && /Firefox/i.test(navigator.userAgent)) || (window.opera && window.print)) {
                // Firefox version >= 23 and Opera Hotlist
                $(this).attr({
                    href: bookmarkURL,
                    title: bookmarkTitle,
                    rel: 'sidebar'
                }).off(e);
                //return true;
            } else if (window.external && ('AddFavorite' in window.external)) {
                // IE Favorite
                window.external.AddFavorite(bookmarkURL, bookmarkTitle);
            } else {
                // Other browsers (mainly WebKit - Chrome/Safari)
                command = (/Mac/i.test(navigator.userAgent) ? 'Cmd' : 'Ctrl') + '+D';
                message = $(this).data('alert');
                message = message.replace(/%s/, command);

                alert(message);

                return false;

            }

            return false;
        });
    } else {
        $('.juiz_sps_link_bookmark').remove();
    }

});