{counter assign='bxcounter'}
{if $slider}
    <script type="text/javascript">
        var slidereverywhere;
                $(document).ready(function() {
        slidereverywhere = $('.bxslider{$bxcounter|intval}').bxSlider({
        {if $slider->options->mode}mode: '{$slider->options->mode|escape:'html':'UTF-8'}',{/if}
            captions: {$slider->options->captions|truefalse},
                    auto: {$slider->options->auto|truefalse},
                    controls: {$slider->options->controls|truefalse},
                    autoStart: {$slider->options->autoStart|truefalse},
                    autoControls: {$slider->options->autoControls|truefalse},
                    autoHover: {$slider->options->autoHover|truefalse},
                    infiniteLoop: {$slider->options->infiniteLoop|truefalse},
                    hideControlOnEnd: {$slider->options->hideControlOnEnd|truefalse},
                    adaptiveHeight: {$slider->options->adaptiveHeight|truefalse},
        {if $slider->options->slideWidth}slideWidth: {$slider->options->slideWidth|intval},{/if}
        {if $slider->options->minSlides}minSlides: {$slider->options->minSlides|intval},{/if}
        {if $slider->options->maxSlides}maxSlides: {$slider->options->maxSlides|intval},{/if}
        {if $slider->options->slideMargin}slideMargin: {$slider->options->slideMargin|intval},{/if}
            pager: {$slider->options->pager|truefalse},
        {if $slider->options->pagerType}pagerType: '{$slider->options->pagerType|escape:'html':'UTF-8'}',{/if}
        {if $slider->options->pagerCustom}pagerCustom: '#bx-pager{$bxcounter|intval}',{/if}
        {if isset($slider->options->video)}video: {$slider->options->video|truefalse},{/if}
            useCSS: {$slider->options->useCSS|truefalse},
                    ticker: {$slider->options->ticker|truefalse},
                    tickerHover: {$slider->options->tickerHover|truefalse},
        {if $slider->options->startSlide}    startSlide: {$slider->options->startSlide|intval},{/if}
            randomStart: {$slider->options->randomStart|truefalse},
        {if $slider->options->pause} pause: {$slider->options->pause|intval},{/if}
        {if $slider->options->speed} speed: {$slider->options->speed|intval},{/if}
        {if $slider->options->easing_jquery && !$slider->options->useCSS}
            easing: '{$slider->options->easing_jquery|escape:'html':'UTF-8'}',{/if}
            {if $slider->options->easing_css && $slider->options->useCSS}
            easing: '{$slider->options->easing_css|escape:'html':'UTF-8'}',{/if}
            onSlideBefore: function(slideElement) {
            },
            })
                {if $slider->options->element}
                    {if $slider->options->insert == 'after'}
            $('.{$slider->alias|escape:'html':'UTF-8'}').insertAfter($('{$slider->options->element|escape:'html':'UTF-8'}'));
                    {elseif $slider->options->insert == 'before'}
            $('.{$slider->alias|escape:'html':'UTF-8'}').insertBefore($('{$slider->options->element|escape:'html':'UTF-8'}'));
                    {elseif $slider->options->insert == 'prepend'}
            $('.{$slider->alias|escape:'html':'UTF-8'}').prependTo($('{$slider->options->element|escape:'html':'UTF-8'}'));
                    {elseif $slider->options->insert == 'append'}
            $('.{$slider->alias|escape:'html':'UTF-8'}').appendTo($('{$slider->options->element|escape:'html':'UTF-8'}'));
                    {elseif $slider->options->insert == 'replace'}
            $('{$slider->options->element|escape:'html':'UTF-8'}').replaceWith($('.{$slider->alias|escape:'html':'UTF-8'}'));
                    {/if}
                {/if}
            {/if}
            })
        </script>
        {if $slides}
            <div class="sliderseverywhere {$slider->alias|escape:'html':'UTF-8'}"> 
                <ul class="bxslider{$bxcounter|intval}">
                    {foreach from=$slides item='slide' name='slider'}
                        <li>
                            {if $slide.video}
                                {html_entity_decode($slide.video|escape:'htmlall':'UTF-8')}
                            {elseif $slide.image}
                                {if $slide.url}<a href="{$slide.url|escape:'html':'UTF-8'}" {if $slide.target}target="{$slide.target|escape:'html':'UTF-8'}"{/if}>{/if}
                                    <img src="{$link->getMediaLink($slide.image_helper.dir|cat:$slide.image)|escape:'htmlall':'UTF-8'}" alt="{$slide.caption|escape:'htmlall':'UTF-8'}" title="{$slide.caption|escape:'htmlall':'UTF-8'}" />
                                    {if $slide.url}</a>{/if}
                                {/if}
                        </li>
                    {/foreach}
                </ul>
                {if $slider->options->pagerCustom}
                    <div id="bx-pager{$bxcounter|intval}" class="thumbpager">
                        {foreach from=$slides item='slide' name='slider'}
                            <a data-slide-index="{$smarty.foreach.slider.index|intval}" href="" >
                                {html_entity_decode($slide.image_helper.thumb|escape:'htmlall':'UTF-8')}
                                {if $slide.image}
                                {elseif $slide.video}
                                    <i class="icon-video"></i>
                                {/if}
                            </a>
                        {/foreach}
                    </div>
                {/if}
            {/if}
        </div>
    {/if}