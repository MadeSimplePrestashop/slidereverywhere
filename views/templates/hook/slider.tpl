{counter assign='bxcounter'}
{if $slider}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.bxslider{$bxcounter}').bxSlider({
            mode: '{$slider->options->mode}',
                    captions: {$slider->options->captions|truefalse},
                    auto: {$slider->options->auto|truefalse},
                    autoControls: {$slider->options->autoControls|truefalse},
                    infiniteLoop: {$slider->options->infiniteLoop|truefalse},
                    hideControlOnEnd: {$slider->options->hideControlOnEnd|truefalse},
                    adaptiveHeight: {$slider->options->adaptiveHeight|truefalse},
        {if $slider->options->slideWidth}slideWidth: {$slider->options->slideWidth},{/if}
        {if $slider->options->minSlides}minSlides: {$slider->options->minSlides},{/if}
        {if $slider->options->maxSlides}maxSlides: {$slider->options->maxSlides},{/if}
        {if $slider->options->slideMargin}slideMargin: {$slider->options->slideMargin},{/if}
                pager: {$slider->options->pager|truefalse},
        {if $slider->options->pagerType}pagerType: '{$slider->options->pagerType}',{/if}
        {if $slider->options->pagerCustom}pagerCustom: '#bx-pager{$bxcounter}',{/if}
        {if isset($slider->options->video)}video: {$slider->options->video|truefalse},{/if}
                useCSS: {$slider->options->useCSS|truefalse},
                        ticker: {$slider->options->ticker|truefalse},
                        tickerHover: {$slider->options->tickerHover|truefalse},
        {if $slider->options->startSlide}    startSlide: {$slider->options->startSlide},{/if}
                randomStart: {$slider->options->randomStart|truefalse},
        {if $slider->options->speed}    speed: {$slider->options->speed},{/if}
        {if $slider->options->easing_jquery && !$slider->options->useCSS}    easing: '{$slider->options->easing_jquery}',{/if}
        {if $slider->options->easing_css && $slider->options->useCSS} easing: '{$slider->options->easing_css}',{/if}
            })
            })
    </script>
    {if $slides}
        <ul class="bxslider{$bxcounter}">
            {foreach from=$slides item='slide' name='slider'}
                <li>
                    {if $slide.image}
                        {if $slide.url}<a href="{$slide.url}" {if $slide.target}target="{$slide.target}"{/if}>{/if}
                            <img src="{$link->getMediaLink($slide.image_helper.dir|cat:$slide.image|escape:'htmlall':'UTF-8')}" alt="{$slide.caption|escape:'htmlall':'UTF-8'}" title="{$slide.caption|escape:'htmlall':'UTF-8'}" />
                            {if $slide.url}</a>{/if}
                        {else}
                        <iframe src="http://player.vimeo.com/video/17914974"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                    {/if}
                </li>
            {/foreach}
        </ul>
        {if $slider->options->pagerCustom}
            <div id="bx-pager{$bxcounter}">
                {foreach from=$slides item='slide' name='slider'}
                    <a data-slide-index="{$smarty.foreach.slider.index}" href="">{$slide.image_helper.thumb}</a>
                {/foreach}
            </div>
        {/if}
    {/if}
{/if}