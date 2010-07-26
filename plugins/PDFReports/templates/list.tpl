<div id='entityEditContainer'>
	<table class="dataTable entityTable">
	<thead>
	<tr>
        <th class="first">{'General_Description'|translate}</th>
        <th>{'PDFReports_EmailSchedule'|translate}</th>
        <th>{'PDFReports_SendReportTo'|translate}</th>
        <th>{'General_Download'|translate}</th>
        <th>{'General_Edit'|translate}</th>
        <th>{'General_Delete'|translate}</th>
	</tr>
	</thead>
	
	{if empty($reports)}
	<tr><td colspan=6> 
	<br/>
	{'PDFReports_ThereIsNoPDFReportToManage'|translate:$siteName}. 
	<br/><br/>
	<a onclick='' id='linkAddReport'>&rsaquo; {'PDFReports_CreateAndSchedulePDFReport'|translate}</a>
	<br/><br/> 
	</td></tr>
	</table>
	{else}
		{foreach from=$reports item=report}
		<tr>
			<td class="first">{$report.description}</td>
	        <td>{$periods[$report.period]}
	        	<!-- Last sent on {$report.ts_last_sent} -->
	        </td>
			<td>{if $report.email_me == 1}{$currentUserEmail}{/if} 
				{$report.additional_emails|replace:",":" "}</td>
			<td><a href="{url module=API method='PDFReports.generateReport' idSite=$idSite date=$date idReport=$report.idreport outputType=$pdfDownloadOutputType}" target="_blank" name="linkDownloadReport" id="{$report.idreport}" class="link_but"><img src='plugins/UserSettings/images/plugins/pdf.gif' border="0" /> {'General_Download'|translate}</a></td>
			<td><a href='#' name="linkEditReport" id="{$report.idreport}" class="link_but"><img src='themes/default/images/ico_edit.png' border="0" /> {'General_Edit'|translate}</a></td>
			<td><a href='#' name="linkDeleteReport" id="{$report.idreport}" class="link_but"><img src='themes/default/images/ico_delete.png' border="0" /> {'General_Delete'|translate}</a></td>
		</tr>
		{/foreach}
	</table>
	<br/>
	<a onclick='' id='linkAddReport'>&rsaquo; {'PDFReports_CreateAndSchedulePDFReport'|translate}</a>
	{/if}
		
</div>
