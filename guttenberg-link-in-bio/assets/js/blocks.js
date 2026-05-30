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
