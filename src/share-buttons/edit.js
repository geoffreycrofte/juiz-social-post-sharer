import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { 
    SelectControl, 
    Toolbar,
    Button,
    Tooltip,
    PanelBody,
    PanelRow,
    FormToggle,
    TextControl,
    ToggleControl,
    ToolbarGroup,
    Disabled, 
    RadioControl,
    RangeControl,
    CustomSelectControl,
    FontSizePicker
} from '@wordpress/components';

import {
    RichText,
    AlignmentToolbar,
    BlockControls,
    BlockAlignmentToolbar,
    InspectorControls,
    InnerBlocks,
    withColors,
    PanelColorSettings,
    getColorClassName
} from '@wordpress/editor';

import { useState } from '@wordpress/element';
import { withSelect, widthDispatch } from '@wordpress/data';

const {
    withState
} = wp.compose;


/* Server side rendering part */
const networkOptions = [
    { label: 'Select your networks', value: null },
    { label: 'LinkedIn', value: 'linkedin' },
    { label: 'Twitter', value: 'twitter' },
    { label: 'Facebook', value: 'facebook' },
    { label: 'Mix', value: 'mix' },
];

/*wp.apiFetch({path: "/wp/v2/business_category?per_page=100"}).then(posts => {
    jQuery.each( posts, function( key, val ) {
        categoryOptions.push({label: val.name, value: val.slug});
    });
}).catch( 

)*/

const edit = props => {
    const {attributes: {counters, buttons, network, url, }, className, setAttributes } = props;

    const setNetworks = buttons => {
        props.setAttributes( { buttons } );
        console.log(buttons);
    };

    const [ hasCounters, setCounters ] = useState( false );

    const inspectorControls = (
        <InspectorControls key="inspector">
            <PanelBody key="formating-option" title={ __( 'Buttons Options' )}>

                <PanelRow key="counters">
                    <ToggleControl
                        key="counters-options"
                        help={
                            hasCounters ? 'Displays counters.' : 'Does not display counters.'
                        }
                        label={ __( 'Display Counters' ) }
                        checked={ hasCounters }
                        value={ counters }
                        onLoad = { () => {
                            [ hasCounters, setCounters ] = useState( false )
                        } }
                        onChange = { () => {
                            setCounters( ( state ) => ! state );
                        } }
                    />
                </PanelRow>

                <PanelRow key="buttons">
                    <CustomSelectControl
                        __nextUnconstrainedWidth
                        key="buttons-options"
                        multiple
                        label = { __( 'Buttons' ) }
                        value = { buttons }
                        options = { networkOptions }
                        onChange = { setNetworks }
                    />
                </PanelRow>

                <PanelRow key="url-to-share-row">
                    <TextControl
                        key="url-to-share"
                        label={ __( 'URL of the page to share (current post by default)' ) }
                        value={ url ? url : '' }
                        onChange={ ( value ) => setAttributes( { url: value} ) }
                    />
                </PanelRow>
            </PanelBody>
        </InspectorControls>
    );

    return [
        <div key="nobs-container" className={ props.className }>
            <ServerSideRender
                block="nobs/share-buttons"
                attributes = { props.attributes }
            />

            { /*inspectorControls*/ }
        </div>
    ];
};

export default edit;

