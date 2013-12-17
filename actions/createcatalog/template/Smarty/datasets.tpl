<div class="subfolders-available datasets  col2-3">
	<h3>{t}Create one or more datasets{/t}</h3>
        {if $subfolders|@count != 0}
        	{foreach from=$subfolders key=nt item=foldername}
                <div class="subfolder box-col1-1"><label for="4001" class="icon"><input type="text" class="text_label" name="namelst[]" placeholder="New Dataset" id="4001"><strong class="icon dataset"></strong></label><span class="info">A dataset should be for a single data in several formats.</span></div>  
        	{/foreach}  
		 <div class="subfolder box-col1-1 add_element"><a class="add-dataset" href="#"><strong class="icon add">Add</strong></a>
                 </div>
	{else}      
        	<p>{t}There aren't any avaliable subfolders for this section.{/t}</p>
	{/if}       
</div>
