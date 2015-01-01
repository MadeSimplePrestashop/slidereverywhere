{literal}
    <style>

        body > .container > .az-container { margin-top : 34px; margin-bottom : 100px; }
        .az-row > .controls {visibility:hidden !important}
        .az-column > .controls {visibility:hidden !important}
        .azexo-editor > div > .az-element:not(.az-row):hover:after {background:none !important; }
        .azexo-editor > div > div .az-element:not(.az-row)after {border:1px solid #ccc }
        .az-empty {display:none !important}
        .azexo-editor .az-row:hover .az-ctnr:after  {outline:0}
        .azexo-editor > div div  > .az-element:not(.az-row):after {
            content: ' ';
            margin: 0;
            padding: 0;
            position: absolute;
            z-index: -1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: grey;
            opacity: 0.2;
        }
        .av-tabs li:first-child {display:none}
        .ab-content div:first-child {display:none}
        .azexo-editor .az-element > .controls {display:none}
        .azexo-editor > .controls   {display:none !important}
        .azexo-editor .az-element.form-group > .controls, .azexo-editor .az-element > .controls {
            display: one !important;
        }
        .js-animation {display:none !important}
        .azexo-editor .az-layers .az-element > .controls {display:initial !important}
        div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers {border:1px solid #ccc !important}
        div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers > div.controls.btn-group {display:none !important}
        .az-button-menu {position: relative; z-index: 1000}
        .a-container-case {
            top: -70px;
            position: relative;
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.edit-layer').click(function () {
                $('div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers > div.controls.btn-group.btn-group-xs > button.control.edit').click();
            })
        })
    </script>
{/literal}

<div class="az-button-menu"><div class="pull-right">
        <button type="button" class="btn btn-default save-builder btn-continue btn-sm ">
            {l s='Save' mod='sliderseverywhere'}
        </button>
        <button type="button" class="btn btn-default save-return-builder btn-continue btn-sm ">
            {l s='Save and return' mod='sliderseverywhere'}
        </button>
    </div>
    
    <button type="button" class="btn btn-default btn-sm edit-layer">
        {l s="Edit layer" mod="sliderseverywhere"}
    </button>
    </div>
<div class="az-container-case">
    <div class="az-container">
        {if isset($builder) && $builder|default}
            {$builder|urldecode|escape:'UTF-8'}
        {else}
            <div id="b2" class="az-element az-row row" style="" data-az-id="b2" data-azb="az_row" data-azat-device="sm" data-azcnt="true"><div class="az-element az-ctnr az-column  col-sm-12" style="" data-az-id="b3" data-azb="az_column" data-azat-width="1/1" data-azcnt="true"><div class="az-element az-layers " data-az-id="b4" data-azb="az_layers" ></div></div></div>
                {/if}
    </div>
</div>

