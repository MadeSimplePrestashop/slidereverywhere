{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{$default_code='<div id="b2" class="az-element az-row row" style="" data-az-id="b2" data-azb="az_row" data-azat-device="sm" data-azcnt="true"><div class="az-element az-ctnr az-column  col-sm-12" style="" data-az-id="b3" data-azb="az_column" data-azat-width="1/1" data-azcnt="true"><div class="az-element az-layers " data-az-id="b4" data-azb="az_layers" ></div></div></div>'}
<input type="hidden" value="{if isset($slide) && isset($slide->builder) && empty($slide->builder) == false}{$slide->builder}{else}{$default_code|urlencode}{/if}" name="builder" id="builder" />
<a target="_blank" href="{$builder_url}" >
    <button type="button"  class="btn btn-default">{l s='Open builder in new window' mod='sliderseverywhere'}</button>
</a>
<br /><br />
<small>{l s='Just preview. Open buider in new window for full functionality' mod='slidereverywhere'}</small> <br /><br /><div class="az-container-case"><div id="az-preview" class="az-container">{if isset($slide) && isset($slide->builder) && empty($slide->builder) == false}{$slide->builder|urldecode}{/if}</div></div>
