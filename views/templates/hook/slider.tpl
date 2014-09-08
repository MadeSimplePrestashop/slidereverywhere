{counter assign='bxcounter'}
{if $slider}
    <script type="text/javascript">
        var slidereverywhere;
        $(document).ready(function() {
            slidereverywhere = $('.bxslider{$bxcounter}').bxSlider({
        {if $slider->options->mode}mode: '{$slider->options->mode|escape:'html':'UTF-8'}',{/if}
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
        {if $slider->options->speed}
                speed: {$slider->options->speed},{/if}
            {if $slider->options->easing_jquery && !$slider->options->useCSS}
                easing: '{$slider->options->easing_jquery}',{/if}
                {if $slider->options->easing_css && $slider->options->useCSS}
                easing: '{$slider->options->easing_css}',{/if}
            })
            })
                </script>
                {if $slides}
                    <div class="sliderseverywhere {$slider->alias}}"> 
                        <ul class="bxslider{$bxcounter}">
                            {foreach from=$slides item='slide' name='slider'}
                                <li>
                                    {if $slide.video}
                                        {$slide.video}
                                    {elseif $slide.image}
                                        {if $slide.url}<a href="{$slide.url}" {if $slide.target}target="{$slide.target}"{/if}>{/if}
                                            <img src="{$link->getMediaLink($slide.image_helper.dir|cat:$slide.image|escape:'htmlall':'UTF-8')}" alt="{$slide.caption|escape:'htmlall':'UTF-8'}" title="{$slide.caption|escape:'htmlall':'UTF-8'}" />
                                            {if $slide.url}</a>{/if}
                                        {/if}
                                </li>
                            {/foreach}
                        </ul>
                        {if $slider->options->pagerCustom}
                            <div id="bx-pager{$bxcounter}" class="thumbpager">
                                {foreach from=$slides item='slide' name='slider'}
                                    <a data-slide-index="{$smarty.foreach.slider.index}" href=""  >
                                        {$slide.image_helper.thumb}
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