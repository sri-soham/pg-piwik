<div id="{$id}" class="parentDiv">
{if isset($arrayDataTable.result) and $arrayDataTable.result == 'error'}
	{$arrayDataTable.message} 
{else}
	{if count($arrayDataTable) == 0}
	No data for this table.
	{else}
		<a name="{$id}"></a>
		<table class="dataTable"> 
		<thead>
		<tr>
		{foreach from=$dataTableColumns item=column}
			<th class="sortable" id="{$column.id}">{$column.name}</td>
		{/foreach}
		</tr>
		</thead>
		
		<tbody>
		{foreach from=$arrayDataTable item=row}
		<tr {if $row.idsubdatatable}class="subDataTable" id="{$row.idsubdatatable}"{/if}>
			{foreach from=$dataTableColumns key=idColumn item=column}
			<td>
				{if $idColumn==0 && isset($row.details.url)}<span id="urlLink">{$row.details.url}</span>{/if}
				{if $idColumn==0 && isset($row.details.logo)}<img src="{$row.details.logo}" />{/if}
				{$row.columns[$column.name]}
			</td>
			{/foreach}
		</tr>
		{/foreach}
		</tbody>
		</table>
	{/if}
	{include file="UserSettings/templates/datatable_footer.tpl"}
{/if}
</div>