<?php
/**
 * Plugin Name:       Guttenberg Link in Bio
 * Description:       Build Link in bio pages using guttenberg blocks, Sections and Social Icons too.
 * Version:           1.0
 * Requires at least: 6.5
 * Tested up to:      7.0
 * Requires PHP:      8.0
 * Author:            ReallyUsefulPlugins.com
 * Author URI:        https://Reallyusefulplugins.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       guttenberg-link-in-bio
 * Website:           https://reallyusefulplugins.com
 */


if (!defined('ABSPATH')) exit;

add_action('init', function () {

    wp_register_style(
        'lpb-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    wp_register_script(
        'lpb-blocks',
        '',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-compose'],
        '1.0',
        true
    );

    wp_add_inline_script('lpb-blocks', <<<'JS'
(function(){
    const { registerBlockType } = wp.blocks;
    const { createElement: el, Fragment } = wp.element;
    const {
        InspectorControls,
        InnerBlocks,
        MediaUpload,
        MediaUploadCheck,
        RichText,
        URLInputButton
    } = wp.blockEditor;
    const {
        PanelBody,
        Button,
        TextControl,
        ToggleControl,
        RangeControl,
        ColorPalette,
        SelectControl
    } = wp.components;

    const colors = [
        { name: 'Black', color: '#000000' },
        { name: 'White', color: '#ffffff' },
        { name: 'Light grey', color: '#f3f3f3' },
        { name: 'Pink', color: '#ec008c' },
        { name: 'Yellow', color: '#ffe600' },
        { name: 'Blue', color: '#0988c9' }
    ];

    const socialOptions = [
        { label: 'Facebook', value: 'fa-brands fa-facebook-f' },
        { label: 'Instagram', value: 'fa-brands fa-instagram' },
        { label: 'YouTube', value: 'fa-brands fa-youtube' },
        { label: 'TikTok', value: 'fa-brands fa-tiktok' },
        { label: 'X / Twitter', value: 'fa-brands fa-x-twitter' },
        { label: 'LinkedIn', value: 'fa-brands fa-linkedin-in' },
        { label: 'Website / Link', value: 'fa-solid fa-link' },
        { label: 'Email', value: 'fa-solid fa-envelope' },
        { label: 'Phone', value: 'fa-solid fa-phone' },
        { label: 'Video', value: 'fa-solid fa-video' },
        { label: 'Star', value: 'fa-solid fa-star' }
    ];

    const alignOptions = [
        { label: 'Left', value: 'left' },
        { label: 'Centre', value: 'center' },
        { label: 'Right', value: 'right' },
        { label: 'Justify', value: 'justify' }
    ];

    const layoutOptions = [
        { label: 'Default Clean', value: 'default' },
        { label: 'Minimal', value: 'minimal' },
        { label: 'Card', value: 'card' },
        { label: 'Phone Card', value: 'phone' },
        { label: 'Full Bleed', value: 'full-bleed' },
        { label: 'Split Hero', value: 'split' },
        { label: 'Compact', value: 'compact' },
        { label: 'Spacious', value: 'spacious' },
        { label: 'Centered Stack', value: 'centered' },
        { label: 'Editorial', value: 'editorial' },
        { label: 'Poster', value: 'poster' },
        { label: 'Glass', value: 'glass' },
        { label: 'Bittersweet', value: 'bittersweet' },
        { label: 'Chestnut', value: 'chestnut' },
        { label: 'Depths', value: 'depths' },
        { label: 'Dieter', value: 'dieter' },
        { label: 'Domesticated Mango', value: 'domesticated-mango' },
        { label: 'Eggplant', value: 'eggplant' },
        { label: 'Fiscal Jungle', value: 'fiscal-jungle' },
        { label: 'Lemmings', value: 'lemmings' },
        { label: 'Massimo', value: 'massimo' },
        { label: 'Mauve', value: 'mauve' },
        { label: 'Midnight', value: 'midnight' },
        { label: 'Night Shift', value: 'night-shift' },
        { label: 'Orange Juice', value: 'orange-juice' },
        { label: 'Rare Delight', value: 'rare-delight' },
        { label: 'Razzle Dazzle', value: 'razzle-dazzle' },
        { label: 'Roseanna', value: 'roseanna' },
        { label: 'Royal', value: 'royal' },
        { label: 'Scarlet Midnight', value: 'scarlet-midnight' },
        { label: 'Stalenhag', value: 'stalenhag' },
        { label: 'Tokuma', value: 'tokuma' },
        { label: 'Top Shelf', value: 'top-shelf' },
        { label: 'Vibe', value: 'vibe' }
    ];

    const buttonTypeOptions = [
        { label: 'Classic Shadow', value: 'classic' },
        { label: 'Flat Pill', value: 'flat-pill' },
        { label: 'Outline', value: 'outline' },
        { label: 'Soft Shadow', value: 'soft-shadow' },
        { label: 'Hard Offset', value: 'hard-offset' },
        { label: 'Underline', value: 'underline' },
        { label: 'Square', value: 'square' },
        { label: 'Rounded Card', value: 'rounded-card' },
        { label: 'Glass', value: 'glass' },
        { label: 'Gradient', value: 'gradient' },
        { label: 'Neo Brutal', value: 'neo-brutal' },
        { label: 'Minimal Text', value: 'minimal-text' }
    ];


    function colourControl(label, value, onChange) {
        return el(Fragment, {},
            el('p', { className: 'lpb-control-label' }, label),
            el(ColorPalette, { colors, value, onChange })
        );
    }


    function iconPreview(iconClass, imageUrl, extraClass) {
        if (imageUrl) return el('img', { className: 'lpb-inline-icon-img ' + (extraClass || ''), src: imageUrl, alt: '' });
        if (iconClass) return el('i', { className: iconClass + ' ' + (extraClass || '') });
        return null;
    }

    registerBlockType('lpb/wrapper', {
        title: 'Link Page Wrapper',
        icon: 'layout',
        category: 'design',
        supports: { align: ['full'] },
        attributes: {
            fullPageBg: { type: 'string', default: '#f3f3f3' },
            blockBg: { type: 'string', default: '#f3f3f3' },
            applyFullBg: { type: 'boolean', default: true },
            maxWidth: { type: 'number', default: 640 },
            paddingTop: { type: 'number', default: 0 },
            paddingBottom: { type: 'number', default: 60 },
            logo: { type: 'string', default: '' },
            logoWidth: { type: 'number', default: 220 },
            textColor: { type: 'string', default: '#000000' },
            buttonBg: { type: 'string', default: '#ffffff' },
            buttonText: { type: 'string', default: '#000000' },
            buttonBorder: { type: 'string', default: '#000000' },
            buttonShadow: { type: 'string', default: '#000000' },
            buttonRadius: { type: 'number', default: 999 },
            h1Desktop: { type: 'number', default: 42 },
            h1Tablet: { type: 'number', default: 36 },
            h1Mobile: { type: 'number', default: 30 },
            h2Desktop: { type: 'number', default: 36 },
            h2Tablet: { type: 'number', default: 30 },
            h2Mobile: { type: 'number', default: 26 },
            h3Desktop: { type: 'number', default: 26 },
            h3Tablet: { type: 'number', default: 23 },
            h3Mobile: { type: 'number', default: 21 },
            paragraphDesktop: { type: 'number', default: 18 },
            paragraphTablet: { type: 'number', default: 17 },
            paragraphMobile: { type: 'number', default: 16 },
            headingLineHeight: { type: 'number', default: 115 },
            paragraphLineHeight: { type: 'number', default: 150 },
            headingAlignDesktop: { type: 'string', default: 'center' },
            headingAlignTablet: { type: 'string', default: 'center' },
            headingAlignMobile: { type: 'string', default: 'center' },
            paragraphAlignDesktop: { type: 'string', default: 'center' },
            paragraphAlignTablet: { type: 'string', default: 'center' },
            paragraphAlignMobile: { type: 'string', default: 'center' },
            layoutStyle: { type: 'string', default: 'default' },
            buttonType: { type: 'string', default: 'classic' }
        },
        edit({ attributes, setAttributes }) {
            const style = {
                background: attributes.blockBg,
                color: attributes.textColor,
                maxWidth: attributes.maxWidth + 'px',
                paddingTop: attributes.paddingTop + 'px',
                paddingBottom: attributes.paddingBottom + 'px',
                '--lpb-button-bg': attributes.buttonBg,
                '--lpb-button-text': attributes.buttonText,
                '--lpb-button-border': attributes.buttonBorder,
                '--lpb-button-shadow': attributes.buttonShadow,
                '--lpb-button-radius': attributes.buttonRadius + 'px',
                '--lpb-h1-desktop': attributes.h1Desktop + 'px',
                '--lpb-h1-tablet': attributes.h1Tablet + 'px',
                '--lpb-h1-mobile': attributes.h1Mobile + 'px',
                '--lpb-h2-desktop': attributes.h2Desktop + 'px',
                '--lpb-h2-tablet': attributes.h2Tablet + 'px',
                '--lpb-h2-mobile': attributes.h2Mobile + 'px',
                '--lpb-h3-desktop': attributes.h3Desktop + 'px',
                '--lpb-h3-tablet': attributes.h3Tablet + 'px',
                '--lpb-h3-mobile': attributes.h3Mobile + 'px',
                '--lpb-p-desktop': attributes.paragraphDesktop + 'px',
                '--lpb-p-tablet': attributes.paragraphTablet + 'px',
                '--lpb-p-mobile': attributes.paragraphMobile + 'px',
                '--lpb-heading-line-height': (attributes.headingLineHeight / 100),
                '--lpb-p-line-height': (attributes.paragraphLineHeight / 100),
                '--lpb-heading-align-desktop': attributes.headingAlignDesktop,
                '--lpb-heading-align-tablet': attributes.headingAlignTablet,
                '--lpb-heading-align-mobile': attributes.headingAlignMobile,
                '--lpb-p-align-desktop': attributes.paragraphAlignDesktop,
                '--lpb-p-align-tablet': attributes.paragraphAlignTablet,
                '--lpb-p-align-mobile': attributes.paragraphAlignMobile
            };

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Page Background', initialOpen: true },
                        el(ToggleControl, {
                            label: 'Apply full-page background colour',
                            checked: attributes.applyFullBg,
                            onChange: v => setAttributes({ applyFullBg: v })
                        }),
                        colourControl('Full page background', attributes.fullPageBg, v => setAttributes({ fullPageBg: v })),
                        colourControl('Block background', attributes.blockBg, v => setAttributes({ blockBg: v }))
                    ),
                    el(PanelBody, { title: 'Layout & Style Presets', initialOpen: false },
                        el(SelectControl, { label: 'Layout / visual style', value: attributes.layoutStyle, options: layoutOptions, onChange: v => setAttributes({ layoutStyle: v }) }),
                        el(SelectControl, { label: 'Default button type', value: attributes.buttonType, options: buttonTypeOptions, onChange: v => setAttributes({ buttonType: v }) }),
                        el(RangeControl, { label: 'Content width', value: attributes.maxWidth, min: 320, max: 1000, onChange: v => setAttributes({ maxWidth: v }) }),
                        el(RangeControl, { label: 'Top padding', value: attributes.paddingTop, min: 0, max: 160, onChange: v => setAttributes({ paddingTop: v }) }),
                        el(RangeControl, { label: 'Bottom padding', value: attributes.paddingBottom, min: 0, max: 160, onChange: v => setAttributes({ paddingBottom: v }) })
                    ),
                    el(PanelBody, { title: 'Responsive Typography', initialOpen: false },
                        el('p', { className: 'lpb-control-label' }, 'Heading 1 size'),
                        el(RangeControl, { label: 'Desktop', value: attributes.h1Desktop, min: 18, max: 90, onChange: v => setAttributes({ h1Desktop: v }) }),
                        el(RangeControl, { label: 'Tablet', value: attributes.h1Tablet, min: 18, max: 80, onChange: v => setAttributes({ h1Tablet: v }) }),
                        el(RangeControl, { label: 'Mobile', value: attributes.h1Mobile, min: 16, max: 64, onChange: v => setAttributes({ h1Mobile: v }) }),
                        el('p', { className: 'lpb-control-label' }, 'Heading 2 size'),
                        el(RangeControl, { label: 'Desktop', value: attributes.h2Desktop, min: 18, max: 80, onChange: v => setAttributes({ h2Desktop: v }) }),
                        el(RangeControl, { label: 'Tablet', value: attributes.h2Tablet, min: 18, max: 70, onChange: v => setAttributes({ h2Tablet: v }) }),
                        el(RangeControl, { label: 'Mobile', value: attributes.h2Mobile, min: 16, max: 56, onChange: v => setAttributes({ h2Mobile: v }) }),
                        el('p', { className: 'lpb-control-label' }, 'Heading 3 size'),
                        el(RangeControl, { label: 'Desktop', value: attributes.h3Desktop, min: 14, max: 60, onChange: v => setAttributes({ h3Desktop: v }) }),
                        el(RangeControl, { label: 'Tablet', value: attributes.h3Tablet, min: 14, max: 54, onChange: v => setAttributes({ h3Tablet: v }) }),
                        el(RangeControl, { label: 'Mobile', value: attributes.h3Mobile, min: 14, max: 48, onChange: v => setAttributes({ h3Mobile: v }) }),
                        el('p', { className: 'lpb-control-label' }, 'Paragraph size'),
                        el(RangeControl, { label: 'Desktop', value: attributes.paragraphDesktop, min: 12, max: 40, onChange: v => setAttributes({ paragraphDesktop: v }) }),
                        el(RangeControl, { label: 'Tablet', value: attributes.paragraphTablet, min: 12, max: 36, onChange: v => setAttributes({ paragraphTablet: v }) }),
                        el(RangeControl, { label: 'Mobile', value: attributes.paragraphMobile, min: 12, max: 32, onChange: v => setAttributes({ paragraphMobile: v }) }),
                        el(RangeControl, { label: 'Heading line height %', value: attributes.headingLineHeight, min: 90, max: 180, onChange: v => setAttributes({ headingLineHeight: v }) }),
                        el(RangeControl, { label: 'Paragraph line height %', value: attributes.paragraphLineHeight, min: 100, max: 220, onChange: v => setAttributes({ paragraphLineHeight: v }) }),
                        el('p', { className: 'lpb-control-label' }, 'Heading alignment'),
                        el(SelectControl, { label: 'Desktop', value: attributes.headingAlignDesktop, options: alignOptions, onChange: v => setAttributes({ headingAlignDesktop: v }) }),
                        el(SelectControl, { label: 'Tablet', value: attributes.headingAlignTablet, options: alignOptions, onChange: v => setAttributes({ headingAlignTablet: v }) }),
                        el(SelectControl, { label: 'Mobile', value: attributes.headingAlignMobile, options: alignOptions, onChange: v => setAttributes({ headingAlignMobile: v }) }),
                        el('p', { className: 'lpb-control-label' }, 'Paragraph alignment'),
                        el(SelectControl, { label: 'Desktop', value: attributes.paragraphAlignDesktop, options: alignOptions, onChange: v => setAttributes({ paragraphAlignDesktop: v }) }),
                        el(SelectControl, { label: 'Tablet', value: attributes.paragraphAlignTablet, options: alignOptions, onChange: v => setAttributes({ paragraphAlignTablet: v }) }),
                        el(SelectControl, { label: 'Mobile', value: attributes.paragraphAlignMobile, options: alignOptions, onChange: v => setAttributes({ paragraphAlignMobile: v }) })
                    ),
                    el(PanelBody, { title: 'Logo', initialOpen: false },
                        el(MediaUploadCheck, {},
                            el(MediaUpload, {
                                onSelect: media => setAttributes({ logo: media.url }),
                                allowedTypes: ['image'],
                                render: ({ open }) => el(Button, { variant: 'secondary', onClick: open }, attributes.logo ? 'Change Logo' : 'Upload Logo')
                            })
                        ),
                        attributes.logo && el(Button, { isDestructive: true, onClick: () => setAttributes({ logo: '' }) }, 'Remove Logo'),
                        el(RangeControl, { label: 'Logo width', value: attributes.logoWidth, min: 60, max: 420, onChange: v => setAttributes({ logoWidth: v }) })
                    ),
                    el(PanelBody, { title: 'Colours', initialOpen: false },
                        colourControl('Text colour', attributes.textColor, v => setAttributes({ textColor: v })),
                        colourControl('Default button background', attributes.buttonBg, v => setAttributes({ buttonBg: v })),
                        colourControl('Default button text', attributes.buttonText, v => setAttributes({ buttonText: v })),
                        colourControl('Default button border', attributes.buttonBorder, v => setAttributes({ buttonBorder: v })),
                        colourControl('Default button shadow', attributes.buttonShadow, v => setAttributes({ buttonShadow: v })),
                        el(RangeControl, { label: 'Default button radius', value: attributes.buttonRadius, min: 0, max: 999, onChange: v => setAttributes({ buttonRadius: v }) })
                    )
                ),
                el('div', { className: 'lpb-editor-shell', style: { background: attributes.applyFullBg ? attributes.fullPageBg : 'transparent' } },
                    el('div', { className: 'lpb-wrapper lpb-layout-' + attributes.layoutStyle + ' lpb-button-type-' + attributes.buttonType, style },
                        attributes.logo && el('img', { className: 'lpb-logo', src: attributes.logo, style: { width: attributes.logoWidth + 'px' } }),
                        el(InnerBlocks, {
                            allowedBlocks: ['core/heading','core/paragraph','core/spacer','lpb/section','lpb/button','lpb/socials','core/image'],
                            template: [
                                ['core/heading', { level: 2, textAlign: 'center', content: 'Your page title' }],
                                ['core/paragraph', { align: 'center', content: 'Add a short description for this link page.' }],
                                ['core/spacer', { height: '38px' }],
                                ['lpb/button', { label: 'Primary Link' }],
                                ['lpb/button', { label: 'Secondary Link' }],
                                ['lpb/section', { heading: 'Optional Section', leftIcon: 'fa-solid fa-star', rightIcon: 'fa-solid fa-star' }],
                                ['lpb/socials']
                            ]
                        })
                    )
                )
            );
        },
        save({ attributes }) {
            const style = {
                background: attributes.blockBg,
                color: attributes.textColor,
                maxWidth: attributes.maxWidth + 'px',
                paddingTop: attributes.paddingTop + 'px',
                paddingBottom: attributes.paddingBottom + 'px',
                '--lpb-button-bg': attributes.buttonBg,
                '--lpb-button-text': attributes.buttonText,
                '--lpb-button-border': attributes.buttonBorder,
                '--lpb-button-shadow': attributes.buttonShadow,
                '--lpb-button-radius': attributes.buttonRadius + 'px',
                '--lpb-h1-desktop': attributes.h1Desktop + 'px',
                '--lpb-h1-tablet': attributes.h1Tablet + 'px',
                '--lpb-h1-mobile': attributes.h1Mobile + 'px',
                '--lpb-h2-desktop': attributes.h2Desktop + 'px',
                '--lpb-h2-tablet': attributes.h2Tablet + 'px',
                '--lpb-h2-mobile': attributes.h2Mobile + 'px',
                '--lpb-h3-desktop': attributes.h3Desktop + 'px',
                '--lpb-h3-tablet': attributes.h3Tablet + 'px',
                '--lpb-h3-mobile': attributes.h3Mobile + 'px',
                '--lpb-p-desktop': attributes.paragraphDesktop + 'px',
                '--lpb-p-tablet': attributes.paragraphTablet + 'px',
                '--lpb-p-mobile': attributes.paragraphMobile + 'px',
                '--lpb-heading-line-height': (attributes.headingLineHeight / 100),
                '--lpb-p-line-height': (attributes.paragraphLineHeight / 100),
                '--lpb-heading-align-desktop': attributes.headingAlignDesktop,
                '--lpb-heading-align-tablet': attributes.headingAlignTablet,
                '--lpb-heading-align-mobile': attributes.headingAlignMobile,
                '--lpb-p-align-desktop': attributes.paragraphAlignDesktop,
                '--lpb-p-align-tablet': attributes.paragraphAlignTablet,
                '--lpb-p-align-mobile': attributes.paragraphAlignMobile
            };
            return el('div', { className: attributes.applyFullBg ? 'lpb-full-page-bg alignfull' : 'alignfull', 'data-lpb-page-bg': attributes.applyFullBg ? attributes.fullPageBg : '', style: { background: attributes.applyFullBg ? attributes.fullPageBg : undefined } },
                el('div', { className: 'lpb-wrapper lpb-layout-' + attributes.layoutStyle + ' lpb-button-type-' + attributes.buttonType, style },
                    attributes.logo && el('img', { className: 'lpb-logo', src: attributes.logo, style: { width: attributes.logoWidth + 'px' } }),
                    el(InnerBlocks.Content)
                )
            );
        }
    });

    registerBlockType('lpb/section', {
        title: 'Link Page Section',
        icon: 'editor-insertmore',
        category: 'design',
        parent: ['lpb/wrapper'],
        attributes: {
            heading: { type: 'string', default: 'Section Title' },
            icon: { type: 'string', default: '' },
            leftIcon: { type: 'string', default: '' },
            rightIcon: { type: 'string', default: '' },
            leftIconImage: { type: 'string', default: '' },
            rightIconImage: { type: 'string', default: '' },
            textColor: { type: 'string', default: '' },
            spacingTop: { type: 'number', default: 20 },
            spacingBottom: { type: 'number', default: 12 },
            paddingTop: { type: 'number', default: 0 },
            paddingBottom: { type: 'number', default: 12 },
            headingGap: { type: 'number', default: 12 }
        },
        edit({ attributes, setAttributes }) {
            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Section Settings', initialOpen: true },
                        colourControl('Heading colour', attributes.textColor, v => setAttributes({ textColor: v })),
                        el(RangeControl, { label: 'Outside spacing above section', value: attributes.spacingTop, min: 0, max: 100, onChange: v => setAttributes({ spacingTop: v }) }),
                        el(RangeControl, { label: 'Outside spacing below section', value: attributes.spacingBottom, min: 0, max: 100, onChange: v => setAttributes({ spacingBottom: v }) }),
                        el(RangeControl, { label: 'Section top padding', value: attributes.paddingTop, min: 0, max: 100, onChange: v => setAttributes({ paddingTop: v }) }),
                        el(RangeControl, { label: 'Section bottom padding', value: attributes.paddingBottom, min: 0, max: 100, onChange: v => setAttributes({ paddingBottom: v }) }),
                        el(RangeControl, { label: 'Distance from title to first button', value: attributes.headingGap, min: 0, max: 80, onChange: v => setAttributes({ headingGap: v }) })
                    ),
                    el(PanelBody, { title: 'Section Icons / Images', initialOpen: false },
                        el(SelectControl, { label: 'Left Font Awesome icon', value: attributes.leftIcon || attributes.icon, options: [{ label: 'None', value: '' }].concat(socialOptions), onChange: v => setAttributes({ leftIcon: v, icon: '' }) }),
                        el(MediaUploadCheck, {}, el(MediaUpload, { onSelect: media => setAttributes({ leftIconImage: media.url }), allowedTypes: ['image'], render: ({ open }) => el(Button, { variant: 'secondary', onClick: open }, attributes.leftIconImage ? 'Change Left Image/SVG' : 'Upload Left Image/SVG') })),
                        attributes.leftIconImage && el(Button, { isDestructive: true, onClick: () => setAttributes({ leftIconImage: '' }) }, 'Remove Left Image'),
                        el(SelectControl, { label: 'Right Font Awesome icon', value: attributes.rightIcon || attributes.icon, options: [{ label: 'None', value: '' }].concat(socialOptions), onChange: v => setAttributes({ rightIcon: v, icon: '' }) }),
                        el(MediaUploadCheck, {}, el(MediaUpload, { onSelect: media => setAttributes({ rightIconImage: media.url }), allowedTypes: ['image'], render: ({ open }) => el(Button, { variant: 'secondary', onClick: open }, attributes.rightIconImage ? 'Change Right Image/SVG' : 'Upload Right Image/SVG') })),
                        attributes.rightIconImage && el(Button, { isDestructive: true, onClick: () => setAttributes({ rightIconImage: '' }) }, 'Remove Right Image')
                    )
                ),
                el('section', { className: 'lpb-section', style: { marginTop: attributes.spacingTop + 'px', marginBottom: attributes.spacingBottom + 'px', paddingTop: attributes.paddingTop + 'px', paddingBottom: attributes.paddingBottom + 'px', '--lpb-section-heading-gap': attributes.headingGap + 'px' } },
                    el('div', { className: 'lpb-section-heading', style: { color: attributes.textColor || undefined } },
                        iconPreview(attributes.leftIcon || attributes.icon, attributes.leftIconImage, 'lpb-section-left-icon'),
                        el(RichText, { tagName: 'span', value: attributes.heading, onChange: v => setAttributes({ heading: v }), placeholder: 'Section title' }),
                        iconPreview(attributes.rightIcon || attributes.icon, attributes.rightIconImage, 'lpb-section-right-icon')
                    ),
                    el(InnerBlocks, { allowedBlocks: ['lpb/button','core/paragraph','core/image','core/spacer'], template: [['lpb/button', { label: 'New Button' }]] })
                )
            );
        },
        save({ attributes }) {
            return el('section', { className: 'lpb-section', style: { marginTop: attributes.spacingTop + 'px', marginBottom: attributes.spacingBottom + 'px', paddingTop: attributes.paddingTop + 'px', paddingBottom: attributes.paddingBottom + 'px', '--lpb-section-heading-gap': attributes.headingGap + 'px' } },
                attributes.heading && el('div', { className: 'lpb-section-heading', style: { color: attributes.textColor || undefined } },
                    iconPreview(attributes.leftIcon || attributes.icon, attributes.leftIconImage, 'lpb-section-left-icon'),
                    el(RichText.Content, { tagName: 'span', value: attributes.heading }),
                    iconPreview(attributes.rightIcon || attributes.icon, attributes.rightIconImage, 'lpb-section-right-icon')
                ),
                el(InnerBlocks.Content)
            );
        }
    });

    registerBlockType('lpb/button', {
        title: 'Link Button',
        icon: 'button',
        category: 'design',
        parent: ['lpb/wrapper','lpb/section'],
        attributes: {
            label: { type: 'string', default: 'Button' },
            url: { type: 'string', default: '#' },
            icon: { type: 'string', default: '' },
            bg: { type: 'string', default: '' },
            text: { type: 'string', default: '' },
            border: { type: 'string', default: '' },
            shadow: { type: 'string', default: '' },
            radius: { type: 'number', default: 999 },
            useCustom: { type: 'boolean', default: false },
            buttonType: { type: 'string', default: '' }
        },
        edit({ attributes, setAttributes }) {
            const style = attributes.useCustom ? {
                '--lpb-button-bg': attributes.bg || undefined,
                '--lpb-button-text': attributes.text || undefined,
                '--lpb-button-border': attributes.border || undefined,
                '--lpb-button-shadow': attributes.shadow || undefined,
                '--lpb-button-radius': attributes.radius + 'px'
            } : {};
            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Button Link', initialOpen: true },
                        el(URLInputButton, { url: attributes.url, onChange: v => setAttributes({ url: v }) }),
                        el(SelectControl, { label: 'Optional icon', value: attributes.icon, options: [{ label: 'None', value: '' }].concat(socialOptions), onChange: v => setAttributes({ icon: v }) }),
                        el(SelectControl, { label: 'Button type override', value: attributes.buttonType, options: [{ label: 'Use wrapper default', value: '' }].concat(buttonTypeOptions), onChange: v => setAttributes({ buttonType: v }) })
                    ),
                    el(PanelBody, { title: 'Custom Button Style', initialOpen: false },
                        el(ToggleControl, { label: 'Override default button colours', checked: attributes.useCustom, onChange: v => setAttributes({ useCustom: v }) }),
                        attributes.useCustom && el(Fragment, {},
                            colourControl('Background', attributes.bg, v => setAttributes({ bg: v })),
                            colourControl('Text', attributes.text, v => setAttributes({ text: v })),
                            colourControl('Border', attributes.border, v => setAttributes({ border: v })),
                            colourControl('Shadow', attributes.shadow, v => setAttributes({ shadow: v })),
                            el(RangeControl, { label: 'Radius', value: attributes.radius, min: 0, max: 999, onChange: v => setAttributes({ radius: v }) })
                        )
                    )
                ),
                el('a', { className: 'lpb-button' + (attributes.buttonType ? ' lpb-button-type-' + attributes.buttonType : ''), href: attributes.url, style, onClick: function(e){ e.preventDefault(); } },
                    attributes.icon && el('i', { className: attributes.icon }),
                    el(RichText, { tagName: 'span', value: attributes.label, onChange: v => setAttributes({ label: v }), placeholder: 'Button text' })
                )
            );
        },
        save({ attributes }) {
            const style = attributes.useCustom ? {
                '--lpb-button-bg': attributes.bg || undefined,
                '--lpb-button-text': attributes.text || undefined,
                '--lpb-button-border': attributes.border || undefined,
                '--lpb-button-shadow': attributes.shadow || undefined,
                '--lpb-button-radius': attributes.radius + 'px'
            } : {};
            return el('a', { className: 'lpb-button' + (attributes.buttonType ? ' lpb-button-type-' + attributes.buttonType : ''), href: attributes.url, style },
                attributes.icon && el('i', { className: attributes.icon }),
                el(RichText.Content, { tagName: 'span', value: attributes.label })
            );
        }
    });

    registerBlockType('lpb/socials', {
        title: 'Social Icons',
        icon: 'share',
        category: 'design',
        parent: ['lpb/wrapper'],
        attributes: {
            color: { type: 'string', default: '#000000' },
            size: { type: 'number', default: 28 },
            gap: { type: 'number', default: 24 },
            links: { type: 'array', default: [
                { icon: 'fa-brands fa-facebook-f', url: '#' },
                { icon: 'fa-brands fa-instagram', url: '#' },
                { icon: 'fa-brands fa-youtube', url: '#' }
            ] }
        },
        edit({ attributes, setAttributes }) {
            const update = (i, key, value) => {
                const links = [...attributes.links];
                links[i][key] = value;
                setAttributes({ links });
            };
            const add = () => setAttributes({ links: [...attributes.links, { icon: 'fa-solid fa-link', url: '#' }] });
            const remove = (i) => setAttributes({ links: attributes.links.filter((_, n) => n !== i) });

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Social Style', initialOpen: true },
                        colourControl('Icon colour', attributes.color, v => setAttributes({ color: v })),
                        el(RangeControl, { label: 'Icon size', value: attributes.size, min: 14, max: 64, onChange: v => setAttributes({ size: v }) }),
                        el(RangeControl, { label: 'Gap', value: attributes.gap, min: 4, max: 60, onChange: v => setAttributes({ gap: v }) })
                    )
                ),
                el('div', { className: 'lpb-social-editor-list' },
                    attributes.links.map((link, i) => el('div', { className: 'lpb-social-editor-item', key: i },
                        el(SelectControl, { label: 'Font Awesome Icon', value: link.icon, options: socialOptions, onChange: v => update(i, 'icon', v) }),
                        el(MediaUploadCheck, {}, el(MediaUpload, { onSelect: media => update(i, 'image', media.url), allowedTypes: ['image'], render: ({ open }) => el(Button, { variant: 'secondary', onClick: open }, link.image ? 'Change SVG/PNG' : 'Use SVG/PNG instead') })),
                        link.image && el(Button, { isDestructive: true, onClick: () => update(i, 'image', '') }, 'Remove SVG/PNG'),
                        el(URLInputButton, { url: link.url, onChange: v => update(i, 'url', v) }),
                        el(Button, { isDestructive: true, onClick: () => remove(i) }, 'Remove')
                    )),
                    el(Button, { variant: 'primary', onClick: add }, 'Add Icon')
                ),
                el('div', { className: 'lpb-socials', style: { '--lpb-social-color': attributes.color, '--lpb-social-size': attributes.size + 'px', '--lpb-social-gap': attributes.gap + 'px' } },
                    attributes.links.map((link, i) => el('a', { className: 'lpb-social', href: link.url, key: i, onClick: function(e){ e.preventDefault(); } }, link.image ? el('img', { className: 'lpb-social-img', src: link.image, alt: '' }) : el('i', { className: link.icon })))
                )
            );
        },
        save({ attributes }) {
            return el('div', { className: 'lpb-socials', style: { '--lpb-social-color': attributes.color, '--lpb-social-size': attributes.size + 'px', '--lpb-social-gap': attributes.gap + 'px' } },
                attributes.links.map((link, i) => el('a', { className: 'lpb-social', href: link.url, key: i, 'aria-label': link.icon }, link.image ? el('img', { className: 'lpb-social-img', src: link.image, alt: '' }) : el('i', { className: link.icon })))
            );
        }
    });
})();
JS);


    wp_register_script('lpb-frontend', '', [], '1.6.1', true);
    wp_add_inline_script('lpb-frontend', <<<'JS'
(function(){
    function applyLinkPageBackgrounds(){
        var blocks = document.querySelectorAll('.lpb-full-page-bg[data-lpb-page-bg]');
        if (!blocks.length) return;
        var bg = blocks[blocks.length - 1].getAttribute('data-lpb-page-bg');
        if (!bg) return;

        document.documentElement.style.background = bg;
        document.body.style.background = bg;

        var selectors = [
            '.wp-site-blocks',
            '.wp-site-blocks > main',
            'main.wp-block-group',
            '.entry-content',
            '.wp-block-post-content'
        ];

        selectors.forEach(function(selector){
            document.querySelectorAll(selector).forEach(function(el){
                el.style.background = bg;
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyLinkPageBackgrounds);
    } else {
        applyLinkPageBackgrounds();
    }
})();
JS);

    wp_register_style('lpb-style', false, ['lpb-fontawesome'], '1.6.1');
    wp_add_inline_style('lpb-style', <<<'CSS'
.lpb-full-page-bg,
.lpb-editor-shell {
    min-height: 100vh;
    box-sizing: border-box;
    padding: 0 16px 40px;
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
}
body:has(.lpb-full-page-bg),
html:has(.lpb-full-page-bg),
body:has(.lpb-full-page-bg) .wp-site-blocks,
body:has(.lpb-full-page-bg) .wp-block-post-content {
    background: var(--wp--preset--color--base, inherit);
}
.lpb-wrapper {
    margin-left: auto;
    margin-right: auto;
    min-height: 100vh;
    box-sizing: border-box;
    padding-left: 24px;
    padding-right: 24px;
    font-family: Arial, sans-serif;
}
.lpb-logo {
    display: block;
    max-width: 100%;
    height: auto;
    margin: 0 auto 56px;
}
.lpb-wrapper h1,
.lpb-wrapper h2,
.lpb-wrapper h3,
.lpb-wrapper p {
    color: inherit;
}
.lpb-wrapper .wp-block-heading {
    margin-top: 0;
    margin-bottom: 14px;
    line-height: var(--lpb-heading-line-height, 1.15);
    text-align: var(--lpb-heading-align-desktop, center) !important;
}
.lpb-wrapper h1.wp-block-heading { font-size: var(--lpb-h1-desktop, 42px); }
.lpb-wrapper h2.wp-block-heading { font-size: var(--lpb-h2-desktop, 36px); }
.lpb-wrapper h3.wp-block-heading { font-size: var(--lpb-h3-desktop, 26px); }
.lpb-wrapper p {
    font-size: var(--lpb-p-desktop, 18px);
    line-height: var(--lpb-p-line-height, 1.5);
    text-align: var(--lpb-p-align-desktop, center) !important;
}
@media (max-width: 900px) {
    .lpb-wrapper h1.wp-block-heading { font-size: var(--lpb-h1-tablet, 36px); }
    .lpb-wrapper h2.wp-block-heading { font-size: var(--lpb-h2-tablet, 30px); }
    .lpb-wrapper h3.wp-block-heading { font-size: var(--lpb-h3-tablet, 23px); }
    .lpb-wrapper p { font-size: var(--lpb-p-tablet, 17px); text-align: var(--lpb-p-align-tablet, center) !important; }
    .lpb-wrapper .wp-block-heading { text-align: var(--lpb-heading-align-tablet, center) !important; }
}
@media (max-width: 600px) {
    .lpb-wrapper h1.wp-block-heading { font-size: var(--lpb-h1-mobile, 30px); }
    .lpb-wrapper h2.wp-block-heading { font-size: var(--lpb-h2-mobile, 26px); }
    .lpb-wrapper h3.wp-block-heading { font-size: var(--lpb-h3-mobile, 21px); }
    .lpb-wrapper p { font-size: var(--lpb-p-mobile, 16px); text-align: var(--lpb-p-align-mobile, center) !important; }
    .lpb-wrapper .wp-block-heading { text-align: var(--lpb-heading-align-mobile, center) !important; }
}
.lpb-section {
    width: 100%;
    box-sizing: border-box;
}
.lpb-section-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 700;
    font-size: 16px;
    text-align: center;
    margin-bottom: var(--lpb-section-heading-gap, 12px);
}
.lpb-inline-icon-img {
    width: 1.15em;
    height: 1.15em;
    object-fit: contain;
    display: inline-block;
}
.lpb-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
    padding: 16px 24px;
    margin: 0 0 18px;
    background: var(--lpb-button-bg, #fff);
    color: var(--lpb-button-text, #000) !important;
    border: 3px solid var(--lpb-button-border, #000);
    border-radius: var(--lpb-button-radius, 999px);
    box-shadow: 0 8px 0 var(--lpb-button-shadow, #000);
    text-align: center;
    text-decoration: none !important;
    font-size: 18px;
    line-height: 1.2;
    font-weight: 600;
    transition: transform .2s ease, box-shadow .2s ease;
}
.lpb-button:hover {
    transform: translateY(3px);
    box-shadow: 0 5px 0 var(--lpb-button-shadow, #000);
}
.lpb-socials {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--lpb-social-gap, 24px);
    margin-top: 42px;
}
.lpb-social {
    color: var(--lpb-social-color, #000) !important;
    font-size: var(--lpb-social-size, 28px);
    line-height: 1;
    text-decoration: none !important;
}
.lpb-social-img {
    width: var(--lpb-social-size, 28px);
    height: var(--lpb-social-size, 28px);
    object-fit: contain;
    display: block;
}
.lpb-social-editor-list,
.lpb-social-editor-item {
    background: #fff;
    color: #111;
}
.lpb-social-editor-item {
    border: 1px solid #ddd;
    padding: 12px;
    margin-bottom: 12px;
}
.lpb-control-label {
    margin-bottom: 8px;
    font-weight: 600;
}


/* Layout / visual style presets */
.lpb-wrapper[class*="lpb-layout-"] { transition: background .2s ease, color .2s ease, border-radius .2s ease; }
.lpb-layout-minimal { background: transparent !important; min-height: auto; }
.lpb-layout-card { min-height: auto; border-radius: 32px; padding: 56px 32px; box-shadow: 0 18px 50px rgba(0,0,0,.12); }
.lpb-layout-phone { max-width: min(var(--lpb-max-width, 430px), 430px) !important; border-radius: 38px; padding: 64px 28px 44px; box-shadow: 0 24px 70px rgba(0,0,0,.16); overflow: hidden; }
.lpb-layout-full-bleed { max-width: none !important; width: 100%; padding-left: max(24px, calc((100vw - 680px)/2)); padding-right: max(24px, calc((100vw - 680px)/2)); }
.lpb-layout-split { display: grid; align-content: center; min-height: 100vh; }
.lpb-layout-compact { min-height: auto; padding-top: 24px !important; padding-bottom: 28px !important; }
.lpb-layout-compact .lpb-logo { margin-bottom: 24px; }
.lpb-layout-compact .lpb-button { margin-bottom: 10px; padding-top: 12px; padding-bottom: 12px; }
.lpb-layout-spacious { padding-top: 90px !important; padding-bottom: 90px !important; }
.lpb-layout-spacious .lpb-button { margin-bottom: 24px; padding-top: 20px; padding-bottom: 20px; }
.lpb-layout-centered { display:flex; flex-direction:column; justify-content:center; }
.lpb-layout-editorial .wp-block-heading { font-family: Georgia, serif; font-weight: 700; letter-spacing: -.02em; }
.lpb-layout-editorial .lpb-button { font-family: Georgia, serif; }
.lpb-layout-poster { text-transform: uppercase; letter-spacing: .04em; }
.lpb-layout-poster .lpb-button { letter-spacing: .06em; }
.lpb-layout-glass { background: rgba(255,255,255,.35) !important; backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1px solid rgba(255,255,255,.35); border-radius: 30px; }

/* 23 Oaknut-inspired visual presets. Existing manual colours still work; choose these as quick layout/style starts. */
.lpb-layout-bittersweet{--lpb-accent:#e4572e;background:#fff3e8!important;color:#24110c!important;font-family:Georgia,serif;}
.lpb-layout-chestnut{--lpb-accent:#7b3f2f;background:#f4eadf!important;color:#2a1712!important;font-family:Georgia,serif;}
.lpb-layout-depths{--lpb-accent:#66e3ff;background:#071927!important;color:#effaff!important;}
.lpb-layout-dieter{--lpb-accent:#111;background:#f7f4ee!important;color:#111!important;font-family:Arial,sans-serif;}
.lpb-layout-domesticated-mango{--lpb-accent:#ffb000;background:#fff0c7!important;color:#2a1a00!important;}
.lpb-layout-eggplant{--lpb-accent:#d6a2ff;background:#21132c!important;color:#fff6ff!important;}
.lpb-layout-fiscal-jungle{--lpb-accent:#b4ff5f;background:#0f2a1f!important;color:#f3ffe9!important;}
.lpb-layout-lemmings{--lpb-accent:#7a7a7a;background:#eeeeea!important;color:#121212!important;}
.lpb-layout-massimo{--lpb-accent:#e51b23;background:#fff!important;color:#101010!important;font-family:Arial Black,Arial,sans-serif;}
.lpb-layout-mauve{--lpb-accent:#875f9a;background:#f2e8f6!important;color:#28172f!important;}
.lpb-layout-midnight{--lpb-accent:#7dd3fc;background:#050816!important;color:#f8fbff!important;}
.lpb-layout-night-shift{--lpb-accent:#d8ff4f;background:#11131a!important;color:#f5f7ff!important;}
.lpb-layout-orange-juice{--lpb-accent:#ff7900;background:#fff4e6!important;color:#271100!important;}
.lpb-layout-rare-delight{--lpb-accent:#ff4aa2;background:#fff7fb!important;color:#23101a!important;}
.lpb-layout-razzle-dazzle{--lpb-accent:#ec008c;background:#fff1fb!important;color:#290019!important;}
.lpb-layout-roseanna{--lpb-accent:#b85d75;background:#fff0f3!important;color:#32131b!important;font-family:Georgia,serif;}
.lpb-layout-royal{--lpb-accent:#ffd54f;background:#20114a!important;color:#fff8de!important;}
.lpb-layout-scarlet-midnight{--lpb-accent:#ff334e;background:#090b1d!important;color:#fff4f5!important;}
.lpb-layout-stalenhag{--lpb-accent:#b16a45;background:#e6ded2!important;color:#261f1a!important;font-family:Georgia,serif;}
.lpb-layout-tokuma{--lpb-accent:#00a0a8;background:#e8fbf8!important;color:#002b2e!important;}
.lpb-layout-top-shelf{--lpb-accent:#c6a15b;background:#15110b!important;color:#fff8e7!important;font-family:Georgia,serif;}
.lpb-layout-vibe{--lpb-accent:#7c3aed;background:linear-gradient(135deg,#f0e7ff,#e8fff7)!important;color:#1f1235!important;}

.lpb-wrapper[class*="lpb-layout-"] .lpb-section-heading{color:var(--lpb-accent,currentColor);}

/* Button type presets */
.lpb-button-type-classic .lpb-button,.lpb-button.lpb-button-type-classic{background:var(--lpb-button-bg,#fff);color:var(--lpb-button-text,#000)!important;border:3px solid var(--lpb-button-border,#000);box-shadow:0 8px 0 var(--lpb-button-shadow,#000);border-radius:var(--lpb-button-radius,999px);}
.lpb-button-type-flat-pill .lpb-button,.lpb-button.lpb-button-type-flat-pill{box-shadow:none;border:0;border-radius:999px;background:var(--lpb-button-bg,#fff);}
.lpb-button-type-outline .lpb-button,.lpb-button.lpb-button-type-outline{box-shadow:none;background:transparent;border:2px solid var(--lpb-button-border,currentColor);color:var(--lpb-button-text,currentColor)!important;}
.lpb-button-type-soft-shadow .lpb-button,.lpb-button.lpb-button-type-soft-shadow{border:0;box-shadow:0 10px 30px rgba(0,0,0,.16);border-radius:22px;}
.lpb-button-type-hard-offset .lpb-button,.lpb-button.lpb-button-type-hard-offset{border:2px solid var(--lpb-button-border,#000);box-shadow:6px 6px 0 var(--lpb-button-shadow,#000);border-radius:14px;}
.lpb-button-type-hard-offset .lpb-button:hover,.lpb-button.lpb-button-type-hard-offset:hover{box-shadow:3px 3px 0 var(--lpb-button-shadow,#000);}
.lpb-button-type-underline .lpb-button,.lpb-button.lpb-button-type-underline{background:transparent;border:0;border-bottom:2px solid currentColor;box-shadow:none;border-radius:0;padding-left:0;padding-right:0;}
.lpb-button-type-square .lpb-button,.lpb-button.lpb-button-type-square{border-radius:0;box-shadow:none;border:2px solid var(--lpb-button-border,#000);}
.lpb-button-type-rounded-card .lpb-button,.lpb-button.lpb-button-type-rounded-card{border:0;border-radius:18px;box-shadow:0 2px 0 rgba(0,0,0,.08),0 14px 34px rgba(0,0,0,.1);}
.lpb-button-type-glass .lpb-button,.lpb-button.lpb-button-type-glass{background:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.55);box-shadow:0 12px 30px rgba(0,0,0,.1);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);}
.lpb-button-type-gradient .lpb-button,.lpb-button.lpb-button-type-gradient{border:0;box-shadow:none;background:linear-gradient(135deg,var(--lpb-button-bg,#fff),var(--lpb-accent,#ddd));}
.lpb-button-type-neo-brutal .lpb-button,.lpb-button.lpb-button-type-neo-brutal{border:3px solid #000;box-shadow:8px 8px 0 #000;border-radius:8px;font-weight:800;text-transform:uppercase;}
.lpb-button-type-minimal-text .lpb-button,.lpb-button.lpb-button-type-minimal-text{background:transparent;border:0;box-shadow:none;border-radius:0;justify-content:flex-start;padding-left:0;padding-right:0;}

@media (max-width: 600px) {
    .lpb-full-page-bg,
    .lpb-editor-shell {
        padding-left: 0;
        padding-right: 0;
    }
    .lpb-wrapper {
        max-width: none !important;
    }
}
CSS);

    register_block_type('lpb/wrapper', ['editor_script' => 'lpb-blocks', 'script' => 'lpb-frontend', 'style' => 'lpb-style', 'editor_style' => 'lpb-style']);
    register_block_type('lpb/section', ['editor_script' => 'lpb-blocks', 'style' => 'lpb-style', 'editor_style' => 'lpb-style']);
    register_block_type('lpb/button', ['editor_script' => 'lpb-blocks', 'style' => 'lpb-style', 'editor_style' => 'lpb-style']);
    register_block_type('lpb/socials', ['editor_script' => 'lpb-blocks', 'style' => 'lpb-style', 'editor_style' => 'lpb-style']);
});
