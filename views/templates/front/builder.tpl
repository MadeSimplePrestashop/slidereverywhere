<div class="az-button-menu clearfix">
    <div class="pull-right" data-intro="{l s="DON'T FORGET ON THESE ACTIONS" mod="sliderseverywhere"}" data-position="top">
        <button type="button" class="btn btn-default save-builder btn-sm ">
            <i class="glyphicon glyphicon-floppy-saved"></i>
            {l s='Save' mod='sliderseverywhere'}
        </button>
        <button type="button" class="btn btn-default save-return-builder btn-sm ">
            <i class="glyphicon glyphicon-floppy-remove"></i>
            {l s='Save and return' mod='sliderseverywhere'}
        </button>
        <button type="button" class="btn btn-default save-reload-builder btn-sm ">
            <i class="glyphicon glyphicon-floppy-open"></i>
            {l s='Save and reload' mod='sliderseverywhere'}
        </button>
        <button type="button" class="btn btn-default return-builder btn-sm ">
            <i class="glyphicon glyphicon-remove"></i>
            {l s='Close' mod='sliderseverywhere'}
        </button>

    </div>
    <div class="pull-left"  data-position="bottom">
        
        <button type="button" class="btn btn-default btn-sm help-layer{if !$slide->builder|default} help-layer-load{/if}">
            <i class="glyphicon glyphicon-question-sign"></i>
        </button>
        &nbsp;
        <button data-intro="{l s="CONTINUE HERE" mod="sliderseverywhere"}"  type="button" class="btn btn-default btn-sm  add-to-layer">
            <i class="glyphicon glyphicon-plus"></i>
            {l s="Insert content " mod="sliderseverywhere"}
        </button>
        <button  type="button" class="btn btn-default btn-sm edit-layer " data-intro="{l s="START HERE" mod="sliderseverywhere"}" data-position="top">
            <i class="glyphicon glyphicon-edit"></i>
            {l s="Edit layer" mod="sliderseverywhere"}
        </button>
        <button  type="button" class="btn btn-default btn-sm switch-options" data-intro="{l s="TURN ON/OFF ELEMENTS BUTTONS" mod="sliderseverywhere"}" data-position="bottom">
            <i class="glyphicon glyphicon-eye-close"></i>
            {l s="Turn on/off control buttons" mod="sliderseverywhere"}
        </button>
    </div>
</div>
        <div class="az-style"></div>
<div class="az-container-case">
    <textarea name="builder" id="builder" cols="30" rows="10"></textarea>
</div>

