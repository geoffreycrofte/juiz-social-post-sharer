import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { dateI18n, format, __experimentalGetSettings } from '@wordpress/date';
import { setState } from '@wordpress/compose';

import edit from './edit';

registerBlockType( 'nobs/share-buttons', {
    title: 'Nobs • Share Buttons (beta)',
    icon: 'share',
    category: 'layout',
    attributes: {
        counters: {
            type: 'number',
            default: 0
        },
        buttons: {
            type: 'array',
            default: []
        },
        network: {
            type: 'string',
            default: ''
        },
        url: {
            type: 'string',
            default: ''
        }
    },
    edit: edit,
    save() { return null; } // rendered in PHP
} );
