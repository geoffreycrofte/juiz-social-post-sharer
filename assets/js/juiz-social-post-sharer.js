/**!
 * Plugin Name: Nobs • Share Buttons
 * Plugin URI: https://sharebuttons.social
 * Author: Geoffrey Crofte
 * Updated: 2.3.2 - No jQuery needed anymore.
 */
;
document.addEventListener("DOMContentLoaded", function(event) {
    const formatParams = function( params ) {
        return "?" + Object.keys(params).map(function(key){
                return key+"="+encodeURIComponent(params[key])
            }).join("&");
    };
    const serializeArray = function (form) {
        var arr = [];
        Array.prototype.slice.call(form.elements).forEach(function (field) {
            if (!field.name || field.disabled || ['file', 'reset', 'submit', 'button'].indexOf(field.type) > -1) return;
            if (field.type === 'select-multiple') {
                Array.prototype.slice.call(field.options).forEach(function (option) {
                    if (!option.selected) return;
                    arr.push({
                        name: field.name,
                        value: option.value
                    });
                });
                return;
            }
            if (['checkbox', 'radio'].indexOf(field.type) >-1 && !field.checked) return;
            arr.push({
                name: field.name,
                value: field.value
            });
        });
        return arr;
    };
    const nobs_format_number = function(num) {
        if (typeof parseInt(num) !== 'NaN' ) {
            if (num >= 1000000000) return parseInt(num / 1000000000) + "b";
            if (num >= 1000000) return parseInt(num / 1000000) + "m";
            if (num >= 1000) return parseInt(num / 1000) + "k";
            return num;
        } else {
            return 0;
        }
    };
    const nobs_count_type = function() {
        if ( document.querySelector('.juiz_sps_counters.counters_both') ) {
            return 'both';
        } else if ( document.querySelector('.juiz_sps_counters.counters_subtotal' ) ) {
            return 'subtotal';
        } else if ( document.querySelector('.juiz_sps_counters.counters_total' ) ) {
            return 'total';
        }
        return 'nope';
    };
    let open_modal,
        bookmark_it;

    /*
    var pinterest_url = https://api.pinterest.com/v1/urls/count.json?url=https://google.com;
    // return : receiveCount({"url":"https://google.com","count":1})
    */
    if ( document.querySelector('.juiz_sps_counters') ) {
        const nobs               = document.querySelectorAll('.juiz_sps_counters');
        const nobs_plugin_url    = document.querySelector('.juiz_sps_info_plugin_url').value;
        const nobs_post_url      = document.querySelector('.juiz_sps_info_permalink').value;
        const nobs_post_id       = document.querySelector('.juiz_sps_info_post_id').value;

        // Reach each occurrence of a bar of buttons.
        nobs.forEach(function(btnbar) {
            let nobs_items =  btnbar.querySelectorAll('.juiz_sps_item');
            let post_id = document.querySelector('.juiz_sps_links').getAttribute('data-post-id');
            let nobs_item_class = '';
            let nobs_total_count = 0;

            if ( btnbar.classList.contains('counters_total') ) {
                nobs_item_class = 'juiz_hidden_counter';
            }

            if ( nobs_items === null ) {
                return;
            }

            let to_send = {
                'action': 'jsps-get-counters',
                'jsps-get-counters-nonce': jsps.getCountersNonce,
                'id': post_id
            };

            // XHR Request.
            let xhr = new XMLHttpRequest();

            // Setup our listener to process completed requests
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    let data = JSON.parse( xhr.response );

                    data = typeof data.data === "object" ? data.data[1] : {};

                    nobs_items.forEach(function(item) {
                        if ( item.classList.contains('juiz_sps_totalcount_item') ) {
                            return;
                        }

                        let key = item.querySelector('button, a').getAttribute('data-nobs-key');

                        if ( ! data[ key ] ) {
                            return;
                        }

                        let counter = document.createElement('span');
                        counter.classList.add('juiz_sps_counter');
                        if ( nobs_item_class !== '' ) {
                            counter.classList.add( nobs_item_class );
                        }
                        counter.setAttribute('data-nobs-raw-count', data[ key ] );
                        counter.textContent = nobs_format_number( parseInt( data[ key ] ) );
                        item.prepend( counter );
                        nobs_total_count = nobs_total_count + parseInt( data[ key ] );
                    });

                    // If btnbar ask for totalcount (both or total only)
                    // then displays total counter.
                    if ( ! btnbar.classList.contains('counters_subtotal') ) {
                        let total_counter = document.createElement('span');
                        let total_number  = document.createElement('span');
                        let total_element = btnbar.querySelector('.juiz_sps_totalcount');
                        let total_text    = total_element.getAttribute('title');

                        total_counter.classList.add('juiz_sps_total_text');
                        total_counter.classList.add('juiz_sps_maybe_hidden_text');
                        total_counter.textContent = total_text;

                        total_number.classList.add('juiz_sps_total_number');
                        total_number.setAttribute('data-nobs-raw-count', nobs_total_count );
                        total_number.textContent = nobs_format_number( nobs_total_count );

                        // Append the total count
                        total_element.append( total_counter, total_number );
                        total_element.setAttribute('title', total_text + ' ' + nobs_total_count );
                    }

                    if ( parseInt( nobs_total_count ) === 0 ) {
                        if ( document.querySelectorAll('.juiz_sps_totalcount_item') ) {
                            document.querySelectorAll('.juiz_sps_totalcount_item').forEach(function(el) {
                                el.classList.add('juiz_sps_maybe_hidden_text');
                            });
                        }
                    }
                } else {
                    console.warn('The request failed!');
                }

            };

            xhr.open( 'GET', jsps.ajax_url + formatParams( to_send ) );
            xhr.send();
        });
    }

    /**
     * Count Increment.
     */
    if ( document.querySelector('.juiz_sps_item') ) {
        let buttons = document.querySelectorAll('.juiz_sps_item button, .juiz_sps_item a');

        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                // Avoid programmatic click duplicate the count.
                let _event = e;
                if ( e.clientX === 0 ) {
                    return;
                }

                // Do the prevention, then count.
                e.preventDefault();
                let _this = this;
                let network = _this.getAttribute('data-nobs-key');
                let post_id  = document.querySelector('.juiz_sps_links').getAttribute('data-post-id');

                let to_send = {
                    'action': 'jsps-click-count',
                    'jsps-click-count-nonce': jsps.clickCountNonce,
                    'id': post_id,
                    'network': network
                };

                // XHR Request.
                let xhrcount = new XMLHttpRequest();

                // Setup our listener to process completed requests
                xhrcount.onload = function () {
                    if (xhrcount.status >= 200 && xhrcount.status < 300) {
                        let data = JSON.parse( xhrcount.response );
                        let net_items = document.querySelectorAll('.juiz_sps_link_' + network );

                        if ( data.success === true && document.querySelector('.juiz_sps_counters') ) {
                            let subtotals = document.querySelector('.juiz_sps_link_' + network + ' .juiz_sps_counter');
                            let total = document.querySelector('.juiz_sps_totalcount');
                            let new_count = parseInt( data.data[2] );

                            // If it has a counter already, increment
                            if ( subtotals ) {
                                net_items.forEach(function(el){
                                    if ( el.querySelector('.juiz_sps_counter') === null ) {
                                        return;
                                    }
                                    el.querySelector('.juiz_sps_counter').innerHTML = nobs_format_number( new_count ); 
                                });
                            }
                            // Else build counters, by button.
                            else if ( nobs_count_type() === 'both' || nobs_count_type() === 'subtotal' ) {
                                let counter = document.createElement('span');
                                counter.classList.add('juiz_sps_counter');
                                counter.textContent = nobs_format_number( new_count )

                                net_items.forEach(function(el) {
                                    el.prepend( counter );
                                });
                            }

                            // If it has a global counter already, increment
                            if ( total ) {
                                let totals = document.querySelectorAll('.juiz_sps_totalcount');
                                totals.forEach(function(tot){

                                    let nb = tot.querySelector('.juiz_sps_total_number');
                                    let currCount = nb.getAttribute('data-nobs-raw-count');
                                    nb.textContent = nobs_format_number( parseInt( currCount ) + 1 );
                                    nb.setAttribute('data-nobs-raw-count', parseInt( currCount ) + 1 );
                                });
                            }
                            // Else, build the total container with it.
                            else {
                                if ( nobs_count_type() === 'both' || nobs_count_type() === 'total' ) {

                                }
                            }
                        } else {
                            // Count not successful or no need to increase it visually.
                            // For now, do nothing about it.
                        }
                    } else {
                        console.warn( xhrcount, xhrcount.status );
                    }

                    // Finally do the thing it was supposed to do.
                    if ( _this.href && _this.href !== '' && _this.href !== '#' && network !== 'mail' && network !== 'bookmark' ) {
                        if ( _this.target === '_blank') {
                            // Better than window.open() action.
                            let link = document.createElement('a');
                            link.href = _this.href;
                            link.target = '_blank';
                            link.id = 'nobs-temp';

                            document.querySelector('body').prepend(link);
                            document.getElementById('nobs-temp').click();
                            document.getElementById('nobs-temp').remove();
                            // window.open( _this.href );
                        } else {
                            document.location.href = _this.href;
                        }
                    } else {
                        switch ( network ) {
                            case 'bookmark':
                                bookmark_it(_event);
                                break;
                            case 'mail':
                                open_modal(_event);
                                break;
                            case 'print':
                                if ( window.print ) {
                                    window.print();
                                }
                                break;
                            case 'shareapi':
                                // Handled inline. Should be the count and async share without issue.
                                break;
                            default:
                                console.log('Ooops');
                        }
                    }
                };
                xhrcount.open( 'GET', jsps.ajax_url + formatParams( to_send ) );
                xhrcount.send();

                return false;
            });
        });
    }

    /**
     * 
     * E-mail button
     * 
     */
    if ( document.querySelector('.juiz_sps_link_mail') ) {

        const email_buttons = document.querySelectorAll('.juiz_sps_link_mail');
        open_modal = function(event) {
            
            event.preventDefault();

            if ( document.querySelector('.juiz-sps-modal') ) {
                return;
            }
            let animation = 400;
            let focusedEl = document.activeElement;
            let post_id = event.target.closest('.juiz_sps_links').getAttribute('data-post-id');
            let modalContent = '<div class="juiz-sps-modal-inside">' +
                '<div class="juiz-sps-modal-header">' +
                '<p id="juiz-sps-email-title" class="juiz-sps-modal-title">' + jsps.modalEmailTitle + '</p>' +
                '</div>' +
                '<div class="juiz-sps-modal-content">' +
                '<form id="jsps-email-form" name="jsps-email" action="' + jsps.modalEmailAction + '" method="post" enctype="application/x-www-form-urlencoded" novalidate>' +
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
                '</div>';
            let jsps_modal = document.createElement('div');
            const isValidEmail = function(email) {
                    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                };

            // Modal attributes
            jsps_modal.classList.add('juiz-social-post-sharer-modal');
            jsps_modal.classList.add('juiz-sps-modal');
            jsps_modal.setAttribute('aria-hidden', 'true');
            jsps_modal.setAttribute('role', 'dialog');
            jsps_modal.setAttribute('aria-labelledby', 'juiz-sps-email-title');
            jsps_modal.setAttribute('data-post-id', post_id);

            document.querySelector('body').append( jsps_modal );
            
            let modal = document.querySelector('.juiz-sps-modal');
            modal.innerHTML = modalContent;
            
            setTimeout(function(){
                modal.classList.add('jsps-modal-show');
                modal.setAttribute('aria-hidden', 'false');
                modal.querySelector('form > p:first-child input').focus();
            }, 10);

            document.getElementById('jsps-friend-email').addEventListener('input', function() {
                let val = this.value,
                    reg = /\s*(?:[;,]|$)\s*/,
                    count = 0,
                    phrase = '';

                val = val.split(reg);

                val.forEach(function(email) {
                    count = isValidEmail(email) ? count + 1 : count;
                });

                if ( document.querySelector('[for="jsps-friend-email"] span') ) {
                    document.querySelector('[for="jsps-friend-email"] span').remove();
                }

                if (count === 0) return;

                if (count > 1) {
                    phrase = jsps.modalRecipientNbs;
                    phrase = phrase.replace("{number}", count);
                } else {
                    phrase = jsps.modalRecipientNb;
                }

                let countspan = document.createElement('span');
                countspan.setAttribute( 'aria-live', 'polite' );
                countspan.innerHTML = phrase;

                document.querySelector('[for="jsps-friend-email"]').append( countspan );
            });

            // On form SUBMIT.
            document.getElementById('jsps-email-form').addEventListener('submit', function(e) {

                e.preventDefault();

                let datas   = serializeArray( this ),
                    modal   = this.closest('.juiz-sps-modal'),
                    post_id = modal.getAttribute('data-post-id'),
                    loader  = this.querySelector('.juiz-sps-loader'),
                    reg     = /\s*(?:[;,]|$)\s*/;

                loader.classList.add('is-active')
                loader.innerHTML = jsps.modalLoader;

                // do something with datas[2]
                // when multiple friends
                // send currently &friend=email,email2
                // should send &friend[]=email&friend[]=email2

                var to_send = {
                    'action': 'jsps-email-friend',
                    'jsps-email-friend-nonce': jsps.modalEmailNonce,
                    'id': post_id,
                    'name': datas[0].value,
                    'email': datas[1].value,
                    //'friend': datas[2].value,
                    'friend': datas[2].value.split(reg),
                    'message': datas[3].value
                };

                // XHR Request.
                let xhrmod = new XMLHttpRequest();

                // Setup our listener to process completed requests
                xhrmod.onload = function () {
                    if (xhrmod.status >= 200 && xhrmod.status < 300) {
                        let data = JSON.parse( xhrmod.response );
                        if ( data.success === true ) {

                            let successmsg = document.createElement('div');
                            successmsg.classList.add('juiz-sps-success');
                            successmsg.classList.add('juiz-sps-message');

                            modal.querySelector('.juiz-sps-modal-content').replaceChild(
                                successmsg, // new one
                                modal.querySelector('form') // old one
                            );

                            modal.querySelector('.juiz-sps-success').innerHTML = '<svg width="130px" height="86px" viewBox="0 0 130 86" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="presentation"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><path d="M129.832519,0.8459473 C129.832031,0.8449707 129.831543,0.8439942 129.831055,0.8439942 C129.694336,0.6389161 129.493652,0.5002442 129.271484,0.4367676 C129.271484,0.4367676 129.271484,0.4367676 129.270996,0.4367676 C129.268066,0.4367676 129.268066,0.4348145 129.267578,0.435791 C129.265625,0.435791 129.265625,0.4338379 129.26416,0.4348144 C129.262695,0.4348144 129.262207,0.4348144 129.258301,0.4338378 C129.256836,0.4328612 129.255371,0.4328612 129.254883,0.4328612 C129.253906,0.4328612 129.252441,0.4318846 129.251465,0.4318846 C129.250976,0.4328612 129.251465,0.430908 129.247558,0.430908 C129.245117,0.430908 129.24414,0.4299314 129.24414,0.4299314 C129.242187,0.4318845 129.241211,0.4289548 129.239746,0.4289548 L129.239258,0.4289548 C129.237793,0.4279782 129.236816,0.4279782 129.23584,0.4279782 C129.234863,0.4279782 129.233886,0.4270016 129.232422,0.4270016 C129.226562,0.426025 129.229492,0.426025 129.229004,0.426025 C129.227539,0.4250484 129.226074,0.4240719 129.225586,0.4250484 C129.223632,0.4250484 129.223144,0.4250484 129.222168,0.4250484 C129.081054,0.3928218 128.93164,0.3918453 128.787597,0.4221187 L0.8061523,25.7277832 C0.3925781,25.8098145 0.074707,26.1428223 0.0112304,26.5588379 C-0.0517579,26.9758301 0.1533202,27.3879395 0.5239257,27.5891113 L38.7761841,48.296875 L44.2290039,84.748291 C44.2433472,84.8442383 44.2748413,84.9345703 44.3149414,85.0205078 C44.3240967,85.0400391 44.3364258,85.0568848 44.3468018,85.0759277 C44.3948975,85.1638183 44.4549561,85.243164 44.5269165,85.3127441 C44.5414429,85.3269043 44.5529175,85.3422851 44.5683594,85.3557129 C44.6381836,85.4157715 44.7144776,85.46875 44.800293,85.5080567 C44.8154908,85.5151368 44.8327027,85.5122071 44.8481446,85.5185547 C44.8656007,85.5256348 44.8774415,85.5400391 44.8955079,85.5461426 C45.0024415,85.583252 45.1108399,85.5998535 45.2177735,85.5998535 C45.3156739,85.5998535 45.4129029,85.5832519 45.5073243,85.5546875 C45.5412598,85.5444336 45.5709229,85.5253906 45.6035157,85.5117187 C45.652649,85.4909667 45.7036134,85.4753417 45.7495118,85.4465332 L72.343628,68.7521973 L98.4980469,85.442627 C98.6606446,85.5461426 98.8476563,85.5998536 99.0361328,85.5998536 C99.1445312,85.5998536 99.2539062,85.5822755 99.3588867,85.5461427 C99.6464844,85.4484864 99.8740234,85.2248536 99.9770508,84.9387208 L129.937012,1.7492677 C129.950195,1.7131349 129.961914,1.6760255 129.971191,1.6389161 C129.972656,1.6320802 129.97168,1.636963 129.97168,1.6359864 C129.98291,1.5891114 129.990723,1.5422364 129.995606,1.4943848 C130.01416,1.2980957 129.975586,1.0949707 129.873535,0.9123535 C129.86084,0.8898926 129.847168,0.8674316 129.833008,0.8459472 C129.832519,0.8459473 129.832519,0.8459473 129.832519,0.8459473 Z M3.9179688,27.1516113 L122.299317,3.7443847 L39.7011719,46.5227051 L3.9179688,27.1516113 Z M45.59729,80.3791504 L40.7855225,48.213623 L118.380859,8.0256347 L54.2226563,55.5783691 C54.1802369,55.6098632 54.1459351,55.6489257 54.1095582,55.685791 C54.0936891,55.7021484 54.0759889,55.7143555 54.0612184,55.7316894 C53.9859621,55.8193359 53.9310304,55.9199218 53.8900758,56.0270996 C53.8854371,56.0393066 53.8753053,56.0473633 53.8710939,56.0598144 L45.59729,80.3791504 Z M47.0717163,82.2556152 L55.3543091,57.9106445 L70.4785767,67.5620117 L47.0717163,82.2556152 Z M98.519043,83.0842285 L56.5800781,56.3205566 L126.938476,4.1721191 L98.519043,83.0842285 Z" fill="#FFF" fill-rule="nonzero"></path></g></svg>' +
                                '<p role="alert">' + data.data + '</p>' +
                                '</div>';
                        } else if ( data.success === false ) {
                            if ( ! modal.querySelector('.juiz-sps-error') ) {
                                let errormsg = document.createElement('div');

                                errormsg.classList.add('juiz-sps-error');
                                errormsg.classList.add('juiz-sps-message');

                                modal.querySelector('form').prepend( errormsg );

                                modal.querySelector('.juiz-sps-error').innerHTML = data.data[1];
                            } else {
                                modal.querySelector('.juiz-sps-error').innerHTML = data.data[1];
                            }

                            loader.classList.remove('is-active');

                            var temp = setInterval(function() {
                                loader.querySelector('img').remove();
                                clearInterval(temp);
                            }, 300);
                        }
                    } else {
                        if ( modal.querySelector('.juiz-sps-error') ) {
                            modal.querySelector('.juiz-sps-error').remove();
                        }
                        let errorContent = jsps.modalErrorGeneric + '<br><code style="font-size:.8em;">' + xhrmod.statusText + '(' + xhrmod.status + ')</code>';
                        let errorMsg = document.createElement('div');

                        errorMsg.classList.add('juiz-sps-error');
                        errorMsg.classList.add('juiz-sps-message');
                        errorMsg.innerHTML = errorContent;

                        modal.querySelector('form').append( errorMsg );
                        loader.classList.remove('is-active');
                    }
                };
                xhrmod.open( 'GET', jsps.ajax_url + formatParams( to_send ) );
                xhrmod.send();

                return false;
            })

            // On CLOSE button.
            document.querySelector('.juiz-sps-close').addEventListener('click', function() {
                modal.setAttribute('aria-hidden', 'true');
                modal.classList.remove('jsps-modal-show');
                focusedEl.focus();
                temp = setInterval(function() {
                    modal.remove();
                }, 400);
                return false;
            });

            // On ESC key CLOSE the modal.
            window.addEventListener('keyup', function(e) {
                if (e.code !== "Escape") {
                    return;
                }
                if ( document.querySelector('.juiz-sps-modal[aria-hidden="false"]') ) {
                    document.querySelector('.juiz-sps-close').click();
                }
                return;
            });

            // Accessibility.
            // TODO: not enough
            document.querySelector('.juiz-sps-close').addEventListener('blur', function() {
                this.closest('.juiz-sps-modal').querySelector('form > p:first-child input').focus();
                return false;
            });

            return false;
        };
    }

    /**
     * 
     * Remove Print Button if API not supported.
     * 
     */
    if ( ! window.print && document.querySelector('.juiz_sps_link_print') ) {
        let print_btns = document.querySelectorAll('.juiz_sps_link_print');
        print_btns.forEach(function(el) {
            el.remove();
        });
    }

    /**
     * 
     * Bookmark button
     * 
     */
    if (
        ( 'addToHomescreen' in window && window.addToHomescreen.isCompatible ) ||
        ( window.sidebar && window.sidebar.addPanel ) ||
        ( window.external && ('AddFavorite' in window.external) ) ||
        (typeof chrome === 'undefined') ||
        (typeof chrome !== 'undefined')
    ) {
        bookmark_it = function(e) {
            e.preventDefault();
            // Thanks to:
            // https://www.thewebflash.com/how-to-add-a-cross-browser-add-to-favorites-bookmark-button-to-your-website/
            let bookmarkURL = window.location.href,
                bookmarkTitle = document.title;

            if ( 'addToHomescreen' in window && window.addToHomescreen.isCompatible ) {
                // Mobile browsers
                addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
            }
            // Firefox version < 23
            else if ( window.sidebar && window.sidebar.addPanel ) {
                window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
            } else if (window.external && ('AddFavorite' in window.external)) {
                // IE Favorite
                window.external.AddFavorite(bookmarkURL, bookmarkTitle);
            } else {
                // Other browsers (mostly all of them since 2019)
                command = (/Mac/i.test(navigator.userAgent) ? 'Cmd' : 'Ctrl') + '+D';
                message = e.target.getAttribute('data-alert') || e.target.closest('a, button').getAttribute('data-alert');
                message = message.replace(/%s/, command);
                alert( message );
                return false;
            }
            return false;
        };
    } else {
        if ( document.querySelector('.juiz_sps_link_bookmark') ) {
            document.querySelectorAll('.juiz_sps_link_bookmark').forEach(function(el) {
                el.remove();
            });
        }
    }
});