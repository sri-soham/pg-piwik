<div class="home" id="content" style="display: block;">
<a graphid="VisitsSummarygetEvolutionGraph" name="evolutionGraph"></a>
<h2>{'Live_VisitorLog'|translate}</h2>
<div id="{$properties.uniqueId}" class="visitorLog">

{assign var=maxIdVisit value=0}
{if isset($arrayDataTable.result) and $arrayDataTable.result == 'error'}
		{$arrayDataTable.message}
	{else}
		{if count($arrayDataTable) == 0}
		<a name="{$properties.uniqueId}"></a>
		<div class="pk-emptyDataTable">{'CoreHome_ThereIsNoDataForThisReport'|translate}</div>
		{else}
			<a name="{$properties.uniqueId}"></a>

	<table class="dataTable" cellspacing="0" width="100%" style="width:100%;">
	<thead>
	<tr>
	<th style="display:none"></th>
	<th id="label" class="sortable label" style="cursor: auto;width:12%" width="12%">
	<div id="thDIV">{'General_Date'|translate}<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:13%" width="13%">
	<div id="thDIV">{'General_Visitors'|translate}<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:15%" width="15%">
	<div id="thDIV">{'Live_Referrer_URL'|translate}<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:62%" width="62%">
	<div id="thDIV">{'General_ColumnNbActions'|translate}<div></th>
	</tr>
	</thead>
	<tbody>

{foreach from=$arrayDataTable item=visitor}
{if $maxIdVisit == 0 || $visitor.columns.idVisit < $maxIdVisit}
{assign var=maxIdVisit value=$visitor.columns.idVisit}
{/if}

	<tr class="label{cycle values='odd,even'}">
	<td style="display:none;"></td>
	<td class="label" style="width:12%" width="12%">

				<strong>{$visitor.columns.serverDatePrettyFirstAction} - {$visitor.columns.serverTimePrettyFirstAction}</strong>
				{if !empty($visitor.columns.visitIp)} <br/>IP: {$visitor.columns.visitIp}{/if}
				
				{if (isset($visitor.columns.provider)&&$visitor.columns.provider!='IP')} 
					<br />
					{'Provider_ColumnProvider'|translate}: 
					<a href="{$visitor.columns.providerUrl}" target="_blank" title="{$visitor.columns.providerUrl}" style="text-decoration:underline;">
						{$visitor.columns.provider}
					</a>
				{/if}
				{if !empty($visitor.columns.customVariables)}
					<br/>
					{foreach from=$visitor.columns.customVariables item=customVariable key=id}
						{capture assign=name}customVariableName{$id}{/capture}
						{capture assign=value}customVariableValue{$id}{/capture}
						<br/><acronym title="{'CustomVariables_CustomVariables'|translate} (index {$id})">{$customVariable.$name}</acronym>: {$customVariable.$value}
					{/foreach}
				{/if}
				
	</td>
	<td class="label" style="width:13%" width="13%">
		&nbsp;<img src="{$visitor.columns.countryFlag}" title="{$visitor.columns.country}, Provider {$visitor.columns.provider}" />
		&nbsp;<img src="{$visitor.columns.browserIcon}" title="{$visitor.columns.browserName} with plugins {$visitor.columns.plugins} enabled" />
		&nbsp;<img src="{$visitor.columns.operatingSystemIcon}" title="{$visitor.columns.operatingSystem}, {$visitor.columns.resolution} ({$visitor.columns.screenType})" />
		&nbsp;{if $visitor.columns.visitConverted}<img src="{$visitor.columns.goalIcon}" title="{'General_VisitConvertedGoal'|translate} - {$visitor.columns.goalType}" />{/if}
		{if $visitor.columns.visitorType=='returning'}
			&nbsp;<img src="plugins/Live/templates/images/returningVisitor.gif" title="{'General_ReturningVisitor'|translate}" />
		{/if}
		<br/>
		{if count($visitor.columns.pluginsIcons) > 0}
			<hr />
			{'UserSettings_Plugins'|translate}:
				{foreach from=$visitor.columns.pluginsIcons item=pluginIcon}
					<img src="{$pluginIcon.pluginIcon}" title="{$pluginIcon.pluginName|capitalize:true}" alt="{$pluginIcon.pluginName|capitalize:true}" />
				{/foreach}
		{/if}
	</td>

	<td class="column" style="width:20%" width="20%">
		<div class="referer">
			{if $visitor.columns.referrerType == 'website'}
				{'Referers_ColumnWebsite'|translate}:
				<a href="{$visitor.columns.referrerUrl|escape:'html'}" target="_blank" title="{$visitor.columns.referrerUrl|escape:'html'}" style="text-decoration:underline;">
					{$visitor.columns.referrerName|escape:'html'}
				</a>
			{/if}
			{if $visitor.columns.referrerType == 'campaign'}
				{'Referers_Campaigns'|translate}
				<br />
				<a href="{$visitor.columns.referrerUrl|escape:'html'}" target="_blank" title="{$visitor.columns.referrerUrl|escape:'html'}" style="text-decoration:underline;">
					{$visitor.columns.referrerName|escape:'html'}
				</a>
			{/if}
			{if $visitor.columns.referrerType == 'search'}
				{if !empty($visitor.columns.searchEngineIcon)}
					<img src="{$visitor.columns.searchEngineIcon}" alt="{$visitor.columns.referrerName|escape:'html'}" /> 
				{/if}
				{$visitor.columns.referrerName|escape:'html'}
				<br />
				{if !empty($visitor.columns.referrerKeyword)}{'Referers_Keywords'|translate}:{/if}
				<a href="{$visitor.columns.referrerUrl|escape:'html'}" target="_blank" style="text-decoration:underline;">
					{if !empty($visitor.columns.referrerKeyword)}
						"{$visitor.columns.referrerKeyword|escape:'html'}"
					{/if}
				</a>
			{/if}
			{if $visitor.columns.referrerType == 'direct'}{'Referers_DirectEntry'|translate}{/if}
		</div>
	</td>
	<td class="column {if $visitor.columns.visitConverted}highlightField{/if}" style="width:55%" width="55%">
			<strong>
				{$visitor.columns.actionDetails|@count}
				{if $visitor.columns.actionDetails|@count <= 1}
					{'Live_Action'|translate} 
				{else}
					{'Live_Actions'|translate}
				{/if}
				- {$visitor.columns.visitDurationPretty}
			</strong>
			<br />
			<ol style="list-style:decimal inside none;">
			{foreach from=$visitor.columns.actionDetails item=action}
				<li title="{$action.serverTimePretty|escape:'html'} - {$action.pageUrl|escape:'html'}">
					{if strlen(trim($action.pageTitle))>0}
					 	{$action.pageTitle|escape:'html'|truncate:60:"...":true}
						<br/>
					{/if}
					{if !empty($action.pageUrl)}
					 	<a href="{$action.pageUrl|escape:'html'}" target="_blank" style="margin-left: 25px;text-decoration:underline;">{$action.pageUrl|escape:'html'|truncate:80:"...":true}</a>
					{else}
						{$javascriptVariablesToSet.pageUrlNotDefined}
					{/if}
					{if $visitor.columns.goalConvertedPageId eq $action.pageId}
						<ul class="actionGoalDetails">
							<li>
								<img src="{$visitor.columns.goalIcon}" title="{$visitor.columns.goalType}" /> 
								<strong>{$visitor.columns.goalName}</strong>, 
								{if $visitor.columns.goalRevenue > 0}{'Live_GoalRevenue'|translate}: <strong>{$visitor.columns.goalRevenue} {$visitor.columns.siteCurrency}</strong>{/if}
							</li>
						</ul>
					{/if}
				</li>
			{/foreach}
			</ol>
	</td>
	</tr>
{/foreach}
	</tbody>
	</table>

	{/if}
	{if count($arrayDataTable) == 20}
	{* We set a fake large rows count so that 'Next' paginate link is forced to display
	   This is hard coded because the Visitor Log datatable is not fully loaded in memory, 
	   but needs to fetch only the N rows in the logs
	   *}
	{php}$this->_tpl_vars['javascriptVariablesToSet']['totalRows'] = 100000; {/php}
	{/if}
	{if $properties.show_footer}
		{include file="CoreHome/templates/datatable_footer.tpl"}
	{/if}
	{include file="CoreHome/templates/datatable_js.tpl"}
	<script type="text/javascript" defer="defer">
		dataTables['{$properties.uniqueId}'].param.maxIdVisit = {$maxIdVisit};
	</script>
{/if}
</div>

{literal}
<style type="text/css">
 hr {
	background:none repeat scroll 0 0 transparent;
	border-color:-moz-use-text-color -moz-use-text-color #EEEEEE;
	border-style:none none solid;
	border-width:0 0 1px;
	color:#CCCCCC;
	margin:0 2em 0.5em;
	padding:0 0 0.5em;
 }

</style>
{/literal}
</div>
