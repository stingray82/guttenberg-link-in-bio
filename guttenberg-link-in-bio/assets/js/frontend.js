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
