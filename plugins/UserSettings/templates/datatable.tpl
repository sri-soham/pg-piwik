<div id="{$id}" class="parentDiv">
{if isset($dataTable.result) and $dataTable.result == 'error'}
	{$dataTable.message} 
{else}
	{if count($dataTable) == 0}
	No data for this table.
	{else}
		<table class="dataTable"> 
		<thead>
		<tr>
		{foreach from=$dataTableColumns item=column}
			<th class="sortable" id="{$column.id}">{$column.name}</td>
		{/foreach}
		</tr>
		</thead>
		
		<tbody>
		{foreach from=$dataTable item=row}
		<tr {if $row.idsubdatatable}class="subDataTable" id="{$row.idsubdatatable}"{/if}>
			{foreach from=$dataTableColumns key=idColumn item=column}
			<td>
				{if $idColumn==0 && isset($row.details.url)}<span id="urlLink">{$row.details.url}</span>{/if}
				{if $idColumn==0 && isset($row.details.logo)}<img src="{$row.details.logo}" />{/if}
				{if false && $idColumn==0}
					<span id="label">{$row.columns[$column.name]}</span>
				{else}
					{$row.columns[$column.name]}
				{/if}				
			</td>
			{/foreach}
		</tr>
		{/foreach}
		</tbody>
		</table>
	{/if}
	<div id="dataTableFeatures">
	<span id="dataTableExcludeLowPopulation"></span>
	
	<span id="dataTableSearchPattern">
		<input id="keyword" type="text" length="15">
		<input type="submit" value="Search">
	</span>
	
	<span id="dataTablePages"></span>
	<span id="dataTablePrevious">&lt; Previous</span>
	<span id="dataTableNext">Next &gt;</span>
	<span id="loadingDataTable"><img src="themes/default/images/loading-blue.gif"> Loading...</span>
	
	</div>	
		
	<script type="text/javascript" defer="defer">
	//$(document).ready( function(){$smarty.ldelim}
	{foreach from=$javascriptVariablesToSet key=name item=value}
	setDivVariable( '{$id}', '{$name}', '{$value}');
	{/foreach}
	//{$smarty.rdelim});
	
	</script>
	{/if}
</div>