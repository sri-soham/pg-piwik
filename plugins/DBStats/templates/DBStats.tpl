{assign var=showSitesSelection value=false}
{assign var=showPeriodSelection value=false}
{include file="CoreAdminHome/templates/header.tpl"}
<div style="max-width:980px;">

<h2>{'DBStats_DatabaseUsage'|translate}</h2>
{assign var=totalSize value=$tablesStatus.Total.relpages}
<p>{'DBStats_MainDescription'|translate:$totalSize}
<br />
{'DBStats_LearnMore'|translate:"<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/setup-auto-archiving/' target='_blank'>Piwik Auto Archiving</a>"}
<br />
{'PrivacyManager_DeleteLogSettings'|translate}:	<a href='{url module="PrivacyManager" action="privacySettings"}#deleteLogsAnchor'>
{capture assign=clickDeleteLogSettings}{'PrivacyManager_DeleteLogSettings'|translate}{/capture}
		{'PrivacyManager_ClickHereSettings'|translate:"'$clickDeleteLogSettings'"}
	</a>
	
<table class="dataTable entityTable">
	<thead>
		<tr>
			<th>{'DBStats_Table'|translate}</th>
			<th>{'DBStats_PageCount'|translate}</th>
			<th>{'DBStats_RowCount'|translate}</th>
			<th>{'DBStats_SeqScans'|translate}</th>
			<th>{'DBStats_IdxScans'|translate}</th>
			<th>{'DBStats_TupInserted'|translate}</th>
			<th>{'DBStats_TupUpdated'|translate}</th>
			<th>{'DBStats_TupHotUpdated'|translate}</th>
			<th>{'DBStats_TupDeleted'|translate}</th>
			<th>{'DBStats_TupLive'|translate}</th>
			<th>{'DBStats_TupDead'|translate}</th>
		</tr>
	</thead>
	<tbody id="tables">
		{foreach from=$tablesStatus key=index item=table}
		<tr {if $table.relname == 'Total'}class="highlight" style="font-weight:bold;"{/if}>
			<td>
				{$table.relname}
			</td> 
			<td>
				{$table.relpages}
			</td> 
			<td>
				{$table.reltuples}
			</td> 
			<td>
				{$table.seq_scan}
			</td> 
			<td>
				{$table.idx_scan}
			</td> 
			<td>
				{$table.n_tup_ins}
			</td> 
			<td>
				{$table.n_tup_upd}
			</td> 
			<td>
				{$table.n_tup_hot_upd}
			</td> 
			<td>
				{$table.n_tup_del}
			</td> 
			<td>
				{$table.n_live_tup}
			</td> 
			<td>
				{$table.n_dead_tup}
			</td> 
		</tr>
		{/foreach}
	</tbody>
</table>

</div>

{include file="CoreAdminHome/templates/footer.tpl"}
