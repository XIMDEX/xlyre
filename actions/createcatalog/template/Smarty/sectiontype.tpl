<div class="folder-name folder-normal icon input-select">
	<input type="text" name="name" id="name" maxlength="100" class="cajaxg validable not_empty full-size" placeholder="{t}Name of your catalog{/t}">
        {if $sectionTypeCount > 1}
        <select id="type_sec" name="nodetype" class="caja validable not_empty folder-type">                                                           {foreach from=$sectionTypeOptions item=sectionTypeOption}
              <option {if ($sectionTypeOption.id == $selectedsectionType)} selected{/if} value="{$sectionTypeOption.id}">{$sectionTypeOption.name}</option>
	{/foreach}
          </select>
       {else}
	  <input name="nodetype" type="hidden" value="{$sectionTypeOptions.id}" />
       {/if}
</div>
