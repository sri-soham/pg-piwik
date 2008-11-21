<?php 
$translations = array(
	'General_Locale' => 'it_IT.UTF-8',
	'General_TranslatorName' => 'Yusef Maali',
	'General_TranslatorEmail' => 'contact@yusefmaali.net',
	'General_EnglishLanguageName' => 'Italian',
	'General_OriginalLanguageName' => 'Italiano',
	'General_Unknown' => 'Sconosciuto',
	'General_Required' => '%s richiesto',
	'General_Error' => 'Errore',
	'General_Warning' => 'Attenzione',
	'General_BackToHomepage' => 'Torna alla HomePage di Piwik',
	'General_Yes' => 'Si',
	'General_No' => 'No',
	'General_Delete' => 'Elimina',
	'General_Edit' => 'Modifica',
	'General_Ok' => 'Ok',
	'General_Close' => 'Chiudi',
	'General_Logout' => 'Esci',
	'General_Done' => 'Fatto',
	'General_LoadingData' => 'Sto caricando i dati...',
	'General_ErrorRequest' => 'Oops&hellip; ci sono stati problemi durante la richiesta. Riprova ancora.',
	'General_Next' => 'Avanti',
	'General_Previous' => 'Indietro',
	'General_Table' => 'Tabella',
	'General_Piechart' => 'Grafico a torta',
	'General_TagCloud' => 'Nuvola di Tag',
	'General_VBarGraph' => 'Istogramma',
	'General_Refresh' => 'Aggiorna la pagina',
	'General_ColumnNbUniqVisitors' => 'Visitatori unici',
	'General_ColumnNbVisits' => 'Visite',
	'General_ColumnLabel' => 'Etichetta',
	'General_Save' => 'Salva',
	'CorePluginsAdmin_Plugins' => 'Plugin',
	'CorePluginsAdmin_Activated' => 'Attivato',
	'CorePluginsAdmin_ActivatedHelp' => 'Questo plugin non può essere disattivato',
	'CorePluginsAdmin_Deactivate' => 'Disattiva',
	'CorePluginsAdmin_Activate' => 'Attiva',
	'CorePluginsAdmin_MenuPlugins' => 'Plugin',
	'API_QuickDocumentation' => '<h2>Guida Veloce alle API</h2><p>Se non sono disponibili dati per la giornata odierna è possibile <a href=\'misc/generateVisits.php\' target=_blank>generare delle visite</a> usando lo script Visits Generator.</p><p>È possibile provare i differenti formati disponibili per ogni metodo, con Piwik è veramente facile estrarre tutti i dati che si desidera!</p><p><b>Per maggiori informazioni guardare la <a href=\'http://dev.piwik.org/trac/wiki/API\'>Documentazione Ufficiale</a> dell\'API o il <a href=\'http://dev.piwik.org/trac/wiki/API/Reference\'>Manuale Completo</a> dell\'API.</b></P><h2>Autenticazione degli utenti</h2><p>Se si vogliono <b>utilizzare i dati nei propri script, in un crontab, ecc... </b> si deve aggiungere il parametro <code><u>&token_auth=%s</u></code> alle chiamate dell\'API che abbiano bisogno di autenticazione.</p><p>Il token_auth è segreto tanto quanto il nome utente e la password, <b>non deve essere condiviso!</p>',
	'API_LoadedAPIs' => 'Ho caricato %s API correttamente',
	'CoreHome_NoPrivileges' => 'Ti sei autenticato come "%s" ma sembra che tu non abbia alcun tipo di permesso assegnato in Piwik.<br />Contatta l\'amministratore e fatti assegnare i permessi di tipo "view" per accedere.',
	'CoreHome_JavascriptDisabled' => 'JavaScript deve essere abilitato per poter usare Piwik in modalità standard.<br>Sembra che JavaScript sia disabilitato o non supportato dal proprio browser.<br>Per usare la modalità standard, abilitare JavaScript modificando le opzioni del browser e %1sriprovare nuovamente%2s.<br>',
	'CoreHome_TableNoData' => 'Non ci sono dati per questa tabella.',
	'CoreHome_CategoryNoData' => 'Non ci sono dati in questa categoria. Provare ad "Includere tutta la popolazione".',
	'CoreHome_ShowJSCode' => 'Guarda il codice javascript da inserire',
	'CoreHome_IncludeAllPopulation_js' => 'Includi tutta la popolazione',
	'CoreHome_ExcludeLowPopulation_js' => 'Escludi la bassa popolazione',
	'CoreHome_PageOf_js' => '%s di %s',
	'CoreHome_Loading_js' => 'Caricamento in corso...',
	'CoreHome_LocalizedDateFormat' => '%A %d %B %Y',
	'CoreHome_PeriodDay' => 'Giorno',
	'CoreHome_PeriodWeek' => 'Settimana',
	'CoreHome_PeriodMonth' => 'Mese',
	'CoreHome_PeriodYear' => 'Anno',
	'CoreHome_DaySu_js' => 'D',
	'CoreHome_DayMo_js' => 'L',
	'CoreHome_DayTu_js' => 'M',
	'CoreHome_DayWe_js' => 'M',
	'CoreHome_DayTh_js' => 'G',
	'CoreHome_DayFr_js' => 'V',
	'CoreHome_DaySa_js' => 'S',
	'CoreHome_MonthJanuary_js' => 'Gennaio',
	'CoreHome_MonthFebruary_js' => 'Febbraio',
	'CoreHome_MonthMarch_js' => 'Marzo',
	'CoreHome_MonthApril_js' => 'Aprile',
	'CoreHome_MonthMay_js' => 'Maggio',
	'CoreHome_MonthJune_js' => 'Giugno',
	'CoreHome_MonthJuly_js' => 'Luglio',
	'CoreHome_MonthAugust_js' => 'Agosto',
	'CoreHome_MonthSeptember_js' => 'Settembre',
	'CoreHome_MonthOctober_js' => 'Ottobre',
	'CoreHome_MonthNovember_js' => 'Novembre',
	'CoreHome_MonthDecember_js' => 'Dicembre',
	'Actions_SubmenuPages' => 'Pagine',
	'Actions_SubmenuOutlinks' => 'Link Esterni',
	'Actions_SubmenuDownloads' => 'Download',
	'Dashboard_AddWidget' => 'Aggiungi un widget...',
	'Dashboard_DeleteWidgetConfirm' => 'Sei sicuro di voler eliminare questo widget dalla dashboard?',
	'Dashboard_SelectWidget' => 'Selezionare il widget da aggiungere alla dashboard',
	'Dashboard_AddPreviewedWidget' => 'Aggiungere questo widget alla dashboard',
	'Dashboard_WidgetPreview' => 'Anteprima del widget',
	'Dashboard_TitleWidgetInDashboard_js' => 'Il widget è già presente nella dashboard',
	'Dashboard_TitleClickToAdd_js' => 'Click per aggiungere alla dashboard',
	'Dashboard_LoadingPreview_js' => 'Sto caricando l\'anteprima, attendere prego...',
	'Dashboard_LoadingWidget_js' => 'Sto caricando il widget, attendere prego...',
	'Dashboard_WidgetNotFound_js' => 'Widget non trovato',
	'Referers_SearchEngines' => 'Motori di ricerca',
	'Referers_Keywords' => 'Parole Chiave',
	'Referers_DirectEntry' => 'Richieste Dirette',
	'Referers_Websites' => 'Siti web',
	'Referers_Partners' => 'Partner',
	'Referers_Newsletters' => 'Newsletter',
	'Referers_Campaigns' => 'Campagne',
	'Referers_Evolution' => 'Evoluzione sul periodo',
	'Referers_Type' => 'Tipo di referrer',
	'Referers_TypeDirectEntries' => '%s richieste dirette',
	'Referers_TypeSearchEngines' => '%s da motori di ricerca',
	'Referers_TypePartners' => '%s dai partner',
	'Referers_TypeWebsites' => '%s dai siti web',
	'Referers_TypeNewsletters' => '%s dalle newsletter',
	'Referers_TypeCampaigns' => '%s dalle campagne',
	'Referers_Other' => 'Altro',
	'Referers_OtherDistinctSearchEngines' => '%s motori di ricerca distinti',
	'Referers_OtherDistinctKeywords' => '%s parole chiave distinte',
	'Referers_OtherDistinctWebsites' => '%1s siti web distinti (usando %2s url distinte)',
	'Referers_OtherDistinctPartners' => '%1s partner distinti (usando %2s url distinte)',
	'Referers_OtherDistinctCampaigns' => '%s campagne distinte',
	'Referers_TagCloud' => 'Nuvola di Tag',
	'Referers_SubmenuEvolution' => 'Evoluzione',
	'Referers_SubmenuSearchEngines' => 'Motori di ricerca e Parole chiave',
	'Referers_SubmenuWebsites' => 'Siti web',
	'Referers_SubmenuCampaigns' => 'Campagne',
	'Referers_SubmenuPartners' => 'Partner',
	'Referers_WidgetKeywords' => 'Lista di Parole Chiave',
	'Referers_WidgetPartners' => 'Lista di Partner',
	'Referers_WidgetCampaigns' => 'Lista di Campagne',
	'Referers_WidgetExternalWebsites' => 'Lista di Siti web esterni',
	'Referers_WidgetSearchEngines' => 'Miglior motore di ricerca',
	'Referers_WidgetOverview' => 'Riepilogo',
	'UserSettings_BrowserFamilies' => 'Famiglia di browser',
	'UserSettings_Browsers' => 'Browser',
	'UserSettings_Plugins' => 'Plugins supportati',
	'UserSettings_Configurations' => 'Configurazione',
	'UserSettings_OperatinsSystems' => 'Sistema Operativo',
	'UserSettings_Resolutions' => 'Risoluzione Video',
	'UserSettings_WideScreen' => 'Tipo di Monitor',
	'UserSettings_WidgetResolutions' => 'Risoluzione Video',
	'UserSettings_WidgetBrowsers' => 'Browser',
	'UserSettings_WidgetPlugins' => 'Plugin supportati',
	'UserSettings_WidgetWidescreen' => 'Tipo di Monitor',
	'UserSettings_WidgetBrowserFamilies' => 'Famiglia di Browser',
	'UserSettings_WidgetOperatingSystems' => 'Sistema Operativo',
	'UserSettings_WidgetGlobalVisitors' => 'Configurazione Generale',
	'UserSettings_SubmenuSettings' => 'Impostazioni',
	'UserCountry_Country' => 'Nazione',
	'UserCountry_Continent' => 'Continente',
	'UserCountry_DistinctCountries' => '%s nazioni distinte',
	'UserCountry_SubmenuLocations' => 'Località',
	'UserCountry_WidgetContinents' => 'Continente',
	'UserCountry_WidgetCountries' => 'Nazione',
	'UserCountry_country_ac' => 'Isole Ascension',
	'UserCountry_country_ad' => 'Andorra',
	'UserCountry_country_ae' => 'Emirati Arabi Uniti',
	'UserCountry_country_af' => 'Afghanistan',
	'UserCountry_country_ag' => 'Antigua e Barbuda',
	'UserCountry_country_ai' => 'Anguilla',
	'UserCountry_country_al' => 'Albania',
	'UserCountry_country_am' => 'Armenia',
	'UserCountry_country_an' => 'Antille Olandesi',
	'UserCountry_country_ao' => 'Angola',
	'UserCountry_country_aq' => 'Antartide',
	'UserCountry_country_ar' => 'Argentina',
	'UserCountry_country_as' => 'Samoa Americane',
	'UserCountry_country_at' => 'Austria',
	'UserCountry_country_au' => 'Australia',
	'UserCountry_country_aw' => 'Aruba',
	'UserCountry_country_az' => 'Azerbaijan',
	'UserCountry_country_ba' => 'Bosnia e Herzegovina',
	'UserCountry_country_bb' => 'Barbados',
	'UserCountry_country_bd' => 'Bangladesh',
	'UserCountry_country_be' => 'Belgio',
	'UserCountry_country_bf' => 'Burkina Faso',
	'UserCountry_country_bg' => 'Bulgaria',
	'UserCountry_country_bh' => 'Bahrain',
	'UserCountry_country_bi' => 'Burundi',
	'UserCountry_country_bj' => 'Benin',
	'UserCountry_country_bm' => 'Bermuda',
	'UserCountry_country_bn' => 'Bruneo',
	'UserCountry_country_bo' => 'Bolivia',
	'UserCountry_country_br' => 'Brasile',
	'UserCountry_country_bs' => 'Bahamas',
	'UserCountry_country_bt' => 'Bhutan',
	'UserCountry_country_bv' => 'Isola Bouvet',
	'UserCountry_country_bw' => 'Botswana',
	'UserCountry_country_by' => 'Belarus',
	'UserCountry_country_bz' => 'Belize',
	'UserCountry_country_ca' => 'Canada',
	'UserCountry_country_cc' => 'Isole Cocos (Keeling)',
	'UserCountry_country_cd' => 'Congo, Repubblica Democratica del',
	'UserCountry_country_cf' => 'Repubblica dell\'Africa Centrale',
	'UserCountry_country_cg' => 'Congo',
	'UserCountry_country_ch' => 'Svizzerra',
	'UserCountry_country_ci' => 'Costa d\'Avorio',
	'UserCountry_country_ck' => 'Isole Cook',
	'UserCountry_country_cl' => 'Cile',
	'UserCountry_country_cm' => 'Cameroon',
	'UserCountry_country_cn' => 'Cina',
	'UserCountry_country_co' => 'Colombia',
	'UserCountry_country_cr' => 'Costa Rica',
	'UserCountry_country_cs' => 'Serbia Montenegro',
	'UserCountry_country_cu' => 'Cuba',
	'UserCountry_country_cv' => 'Capo Verde',
	'UserCountry_country_cx' => 'Isola di Natale',
	'UserCountry_country_cy' => 'Cipro',
	'UserCountry_country_cz' => 'Repubblica Ceca',
	'UserCountry_country_de' => 'Germania',
	'UserCountry_country_dj' => 'Djibouti',
	'UserCountry_country_dk' => 'Danimarca',
	'UserCountry_country_dm' => 'Dominica',
	'UserCountry_country_do' => 'Repubblica Dominicana',
	'UserCountry_country_dz' => 'Algeria',
	'UserCountry_country_ec' => 'Ecuador',
	'UserCountry_country_ee' => 'Estonia',
	'UserCountry_country_eg' => 'Egitto',
	'UserCountry_country_eh' => 'Sahara Occidentale',
	'UserCountry_country_er' => 'Eritrea',
	'UserCountry_country_es' => 'Spagna',
	'UserCountry_country_et' => 'Etiopia',
	'UserCountry_country_fi' => 'Finlandia',
	'UserCountry_country_fj' => 'Fiji',
	'UserCountry_country_fk' => 'Isole Falkland (Malvinas)',
	'UserCountry_country_fm' => 'Micronesia, Stati Federati di',
	'UserCountry_country_fo' => 'Isole Faroe',
	'UserCountry_country_fr' => 'Francia',
	'UserCountry_country_ga' => 'Gabon',
	'UserCountry_country_gd' => 'Grenada',
	'UserCountry_country_ge' => 'Georgia',
	'UserCountry_country_gf' => 'Guyana Francese',
	'UserCountry_country_gg' => 'Guernsey',
	'UserCountry_country_gh' => 'Ghana',
	'UserCountry_country_gi' => 'Gibraltar',
	'UserCountry_country_gl' => 'Greenland',
	'UserCountry_country_gm' => 'Gambia',
	'UserCountry_country_gn' => 'Guinea',
	'UserCountry_country_gp' => 'Guadeloupe',
	'UserCountry_country_gq' => 'Guinea Equatoriale',
	'UserCountry_country_gr' => 'Grecia',
	'UserCountry_country_gs' => 'Georgia del Sud e Isole Sandwitch meridionali',
	'UserCountry_country_gt' => 'Guatemala',
	'UserCountry_country_gu' => 'Guam',
	'UserCountry_country_gw' => 'Guinea-Bissau',
	'UserCountry_country_gy' => 'Guyana',
	'UserCountry_country_hk' => 'Hong Kong',
	'UserCountry_country_hm' => 'Isole Heard e Isole McDonald',
	'UserCountry_country_hn' => 'Honduras',
	'UserCountry_country_hr' => 'Croazia',
	'UserCountry_country_ht' => 'Haiti',
	'UserCountry_country_hu' => 'Hungary',
	'UserCountry_country_id' => 'Indonesia',
	'UserCountry_country_ie' => 'Ireland',
	'UserCountry_country_il' => 'Israel',
	'UserCountry_country_im' => 'Man Island',
	'UserCountry_country_in' => 'India',
	'UserCountry_country_io' => 'British Indian Ocean Territory',
	'UserCountry_country_iq' => 'Iraq',
	'UserCountry_country_ir' => 'Iran, Islamic Republic of',
	'UserCountry_country_is' => 'Iceland',
	'UserCountry_country_it' => 'Italia',
	'UserCountry_country_je' => 'Jersey',
	'UserCountry_country_jm' => 'Jamaica',
	'UserCountry_country_jo' => 'Jordan',
	'UserCountry_country_jp' => 'Giappone',
	'UserCountry_country_ke' => 'Kenya',
	'UserCountry_country_kg' => 'Kyrgyzstan',
	'UserCountry_country_kh' => 'Cambodia',
	'UserCountry_country_ki' => 'Kiribati',
	'UserCountry_country_km' => 'Comoros',
	'UserCountry_country_kn' => 'Saint Kitts and Nevis',
	'UserCountry_country_kp' => 'Korea, Democratic People\'s Republic of',
	'UserCountry_country_kr' => 'Korea, Republic of',
	'UserCountry_country_kw' => 'Kuwait',
	'UserCountry_country_ky' => 'Cayman Islands',
	'UserCountry_country_kz' => 'Kazakhstan',
	'UserCountry_country_la' => 'Laos',
	'UserCountry_country_lb' => 'Lebanon',
	'UserCountry_country_lc' => 'Saint Lucia',
	'UserCountry_country_li' => 'Liechtenstein',
	'UserCountry_country_lk' => 'Sri Lanka',
	'UserCountry_country_lr' => 'Liberia',
	'UserCountry_country_ls' => 'Lesotho',
	'UserCountry_country_lt' => 'Lithuania',
	'UserCountry_country_lu' => 'Lussemburgo',
	'UserCountry_country_lv' => 'Latvia',
	'UserCountry_country_ly' => 'Libya',
	'UserCountry_country_ma' => 'Morocco',
	'UserCountry_country_mc' => 'Monaco',
	'UserCountry_country_md' => 'Moldova, Republic of',
	'UserCountry_country_mg' => 'Madagascar',
	'UserCountry_country_mh' => 'Marshall Islands',
	'UserCountry_country_mk' => 'Macedonia',
	'UserCountry_country_ml' => 'Mali',
	'UserCountry_country_mm' => 'Myanmar',
	'UserCountry_country_mn' => 'Mongolia',
	'UserCountry_country_mo' => 'Macau',
	'UserCountry_country_mp' => 'Northern Mariana Islands',
	'UserCountry_country_mq' => 'Martinique',
	'UserCountry_country_mr' => 'Mauritania',
	'UserCountry_country_ms' => 'Montserrat',
	'UserCountry_country_mt' => 'Malta',
	'UserCountry_country_mu' => 'Mauritius',
	'UserCountry_country_mv' => 'Maldives',
	'UserCountry_country_mw' => 'Malawi',
	'UserCountry_country_mx' => 'Mexico',
	'UserCountry_country_my' => 'Malaysia',
	'UserCountry_country_mz' => 'Mozambique',
	'UserCountry_country_na' => 'Namibia',
	'UserCountry_country_nc' => 'New Caledonia',
	'UserCountry_country_ne' => 'Niger',
	'UserCountry_country_nf' => 'Norfolk Island',
	'UserCountry_country_ng' => 'Nigeria',
	'UserCountry_country_ni' => 'Nicaragua',
	'UserCountry_country_nl' => 'Netherlands',
	'UserCountry_country_no' => 'Norway',
	'UserCountry_country_np' => 'Nepal',
	'UserCountry_country_nr' => 'Nauru',
	'UserCountry_country_nu' => 'Niue',
	'UserCountry_country_nz' => 'Nuova Zelanda',
	'UserCountry_country_om' => 'Oman',
	'UserCountry_country_pa' => 'Panama',
	'UserCountry_country_pe' => 'Peru',
	'UserCountry_country_pf' => 'French Polynesia',
	'UserCountry_country_pg' => 'Papua New Guinea',
	'UserCountry_country_ph' => 'Philippines',
	'UserCountry_country_pk' => 'Pakistan',
	'UserCountry_country_pl' => 'Polonia',
	'UserCountry_country_pm' => 'Saint Pierre and Miquelon',
	'UserCountry_country_pn' => 'Pitcairn',
	'UserCountry_country_pr' => 'Puerto Rico',
	'UserCountry_country_ps' => 'Territori Palestinesi',
	'UserCountry_country_pt' => 'Portogallo',
	'UserCountry_country_pw' => 'Palau',
	'UserCountry_country_py' => 'Paraguay',
	'UserCountry_country_qa' => 'Qatar',
	'UserCountry_country_re' => 'Reunion Island',
	'UserCountry_country_ro' => 'Romania',
	'UserCountry_country_rw' => 'Rwanda',
	'UserCountry_country_sa' => 'Saudi Arabia',
	'UserCountry_country_sb' => 'Solomon Islands',
	'UserCountry_country_sc' => 'Seychelles',
	'UserCountry_country_sd' => 'Sudan',
	'UserCountry_country_se' => 'Svezia',
	'UserCountry_country_sg' => 'Singapore',
	'UserCountry_country_sh' => 'Saint Helena',
	'UserCountry_country_si' => 'Slovenia',
	'UserCountry_country_sj' => 'Svalbard',
	'UserCountry_country_sk' => 'Slovakia',
	'UserCountry_country_sl' => 'Sierra Leone',
	'UserCountry_country_sm' => 'San Marino',
	'UserCountry_country_sn' => 'Senegal',
	'UserCountry_country_so' => 'Somalia',
	'UserCountry_country_sr' => 'Suriname',
	'UserCountry_country_st' => 'Sao Tome and Principe',
	'UserCountry_country_su' => 'Old U.S.S.R',
	'UserCountry_country_sv' => 'El Salvador',
	'UserCountry_country_sy' => 'Syrian Arab Republic',
	'UserCountry_country_sz' => 'Swaziland',
	'UserCountry_country_tc' => 'Turks and Caicos Islands',
	'UserCountry_country_td' => 'Chad',
	'UserCountry_country_tf' => 'French Southern Territories',
	'UserCountry_country_tg' => 'Togo',
	'UserCountry_country_th' => 'Thailand',
	'UserCountry_country_tj' => 'Tajikistan',
	'UserCountry_country_tk' => 'Tokelau',
	'UserCountry_country_tm' => 'Turkmenistan',
	'UserCountry_country_tn' => 'Tunisia',
	'UserCountry_country_to' => 'Tonga',
	'UserCountry_country_tp' => 'East Timor',
	'UserCountry_country_tr' => 'Turchia',
	'UserCountry_country_tt' => 'Trinidad and Tobago',
	'UserCountry_country_tv' => 'Tuvalu',
	'UserCountry_country_tw' => 'Taiwan',
	'UserCountry_country_tz' => 'Tanzania, United Republic of',
	'UserCountry_country_ua' => 'Ukraine',
	'UserCountry_country_ug' => 'Uganda',
	'UserCountry_country_uk' => 'Regno Unito',
	'UserCountry_country_gb' => 'Gran Bretagna',
	'UserCountry_country_um' => 'United States Minor Outlying Islands',
	'UserCountry_country_us' => 'Stati Uniti',
	'UserCountry_country_uy' => 'Uruguay',
	'UserCountry_country_uz' => 'Uzbekistan',
	'UserCountry_country_va' => 'Vatican City',
	'UserCountry_country_vc' => 'Saint Vincent and the Grenadines',
	'UserCountry_country_ve' => 'Venezuela',
	'UserCountry_country_vg' => 'Virgin Islands, British',
	'UserCountry_country_vi' => 'Virgin Islands, U.S.',
	'UserCountry_country_vn' => 'Vietnam',
	'UserCountry_country_vu' => 'Vanuatu',
	'UserCountry_country_wf' => 'Wallis and Futuna',
	'UserCountry_country_ws' => 'Samoa',
	'UserCountry_country_ye' => 'Yemen',
	'UserCountry_country_yt' => 'Mayotte',
	'UserCountry_country_yu' => 'Yugoslavia',
	'UserCountry_country_za' => 'Sud Africa',
	'UserCountry_country_zm' => 'Zambia',
	'UserCountry_country_zr' => 'Zaire',
	'UserCountry_country_zw' => 'Zimbabwe',
	'UserCountry_continent_eur' => 'Europa',
	'UserCountry_continent_afr' => 'Africa',
	'UserCountry_continent_asi' => 'Asia',
	'UserCountry_continent_ams' => 'America Centrale e del Sud',
	'UserCountry_continent_amn' => 'America del Nord',
	'UserCountry_continent_oce' => 'Oceania',
	'VisitsSummary_NbVisits' => '%s visite',
	'VisitsSummary_NbUniqueVisitors' => '%s visite uniche',
	'VisitsSummary_NbActions' => '%s azioni (pagine visualizzate)',
	'VisitsSummary_TotalTime' => '%s durata totale delle visite',
	'VisitsSummary_MaxNbActions' => '%s azioni massime per singola visita',
	'VisitsSummary_NbBounced' => '%s visite di una sola pagina',
	'VisitsSummary_Evolution' => 'Evoluzione negli ultimi 30 %ss',
	'VisitsSummary_Report' => 'Riepilogo',
	'VisitsSummary_GenerateTime' => '%s secondi per generare la pagina',
	'VisitsSummary_GenerateQueries' => '%s query eseguite',
	'VisitsSummary_WidgetLastVisits' => 'Grafico delle ultime visite',
	'VisitsSummary_WidgetVisits' => 'Riepilogo delle visite',
	'VisitsSummary_WidgetLastVisitors' => 'Grafico delle ultime visite uniche',
	'VisitsSummary_WidgetOverviewGraph' => 'Riepilogo con grafico',
	'VisitsSummary_SubmenuOverview' => 'Riepilogo',
	'VisitFrequency_Evolution' => 'Evoluzione sul periodo',
	'VisitFrequency_ReturnVisits' => '%s visite di ritorno',
	'VisitFrequency_ReturnActions' => '%s azioni delle visite di ritorno',
	'VisitFrequency_ReturnMaxActions' => '%s azioni massime per singola visita di ritorno',
	'VisitFrequency_ReturnTotalTime' => '%s durata totale delle visite di ritorno',
	'VisitFrequency_ReturnBounces' => '%s visite di ritorno su una sola pagina',
	'VisitFrequency_WidgetOverview' => 'Riepilogo della frequenza',
	'VisitFrequency_WidgetGraphReturning' => 'Grafico delle visite di ritorno',
	'VisitFrequency_SubmenuFrequency' => 'Frequenza',
	'VisitTime_LocalTime' => 'Visite orarie (tempo visitatore)',
	'VisitTime_ServerTime' => 'Visite orarie (tempo server)',
	'VisitTime_WidgetLocalTime' => 'Visite orarie (tempo visitatore)',
	'VisitTime_WidgetServerTime' => 'Visite orarie (tempo server)',
	'VisitTime_SubmenuTimes' => 'Orari',
	'VisitorInterest_VisitsPerDuration' => 'Durata delle visite',
	'VisitorInterest_VisitsPerNbOfPages' => 'Pagine per visita',
	'VisitorInterest_WidgetLengths' => 'Durata delle visite',
	'VisitorInterest_WidgetPages' => 'Pagine per visita',
	'VisitorInterest_SubmenuFrequencyLoyalty' => 'Frequenza e Fedeltà',
	'Provider_WidgetProviders' => 'Provider',
	'Provider_SubmenuLocationsProvider' => 'Località e Provider',
	'Login_LoginPasswordNotCorrect' => 'Nome Utente e Password non corretti',
	'Login_Login' => 'Nome Utente',
	'Login_Password' => 'Password',
	'Login_LoginOrEmail' => 'Nome utente o indirizzo email',
	'Login_LogIn' => 'Accedi',
	'Login_Logout' => 'Esci',
	'Login_LostYourPassword' => 'Hai perso la password?',
	'Login_RemindPassword' => 'Ricordami la password',
	'Login_PasswordReminder' => 'Inserire il nome utente o l\'indirizzo di posta elettronica. Verrà spedita una nuova password via email.',
	'Login_InvalidUsernameEmail' => 'Nome utente e/o indirizzo email non valido',
	'Login_MailTopicPasswordRecovery' => 'Recupero Password',
	'Login_MailPasswordRecoveryBody' => 'Ciao %1s, \n\nLa tua nuova password è: %2s \n\n Adesso puoi accedere a: %3s',
	'Login_PasswordSent' => 'La password è stata inviata. Controllare la propria email.',
	'Login_ContactAdmin' => 'Motivi possibili: il server potrebbe avere la funzione mail() disabilitata. <br>Contattare l\'amministratore.',
	'UsersManager_ManageAccess' => 'Gestione degli accessi',
	'UsersManager_Sites' => 'Siti',
	'UsersManager_AllWebsites' => 'Tutti i siti',
	'UsersManager_ApplyToAllWebsites' => 'Applica a tutti i siti',
	'UsersManager_User' => 'Utente',
	'UsersManager_PrivNone' => 'Nessun Accesso',
	'UsersManager_PrivView' => 'Visualizza',
	'UsersManager_PrivAdmin' => 'Amministra',
	'UsersManager_ChangeAllConfirm' => 'Sei sicuro di voler cambiare i permessi di "%s" in tutti i siti?',
	'UsersManager_Login' => 'Nome Utente',
	'UsersManager_Password' => 'Password',
	'UsersManager_Email' => 'Email',
	'UsersManager_Alias' => 'Alias',
	'UsersManager_Token' => 'token_auth',
	'UsersManager_Edit' => 'Modifica',
	'UsersManager_AddUser' => 'Aggiungi un nuovo utente',
	'UsersManager_MenuUsers' => 'Utenti',
	'UsersManager_DeleteConfirm_js' => 'Sei sicuro di voler cancellare l\'utente "%s"?',
	'UsersManager_ExceptionLoginExists' => 'Il nome utente "%s" è già presente.',
	'UsersManager_ExceptionEmailExists' => 'L\'indirizzo email "%s" è già associato ad un altro utente.',
	'UsersManager_ExceptionInvalidLogin' => 'Il nome utente deve contenere solo lettere, numeri o i caratteri "_" o "-" o "."',
	'UsersManager_ExceptionInvalidPassword' => 'La password deve essere lunga tra 6 e 26 caratteri.',
	'UsersManager_ExceptionInvalidEmail' => 'L\'indirizzo email non ha un formato valido.',
	'UsersManager_ExceptionDeleteDoesNotExist' => 'L\'utente "%s" non esiste e non può essere eliminato.',
	'UsersManager_ExceptionAdminAnonymous' => 'Non è possibile assegnare i permessi "admin" all\'utente "anonymous".',
	'UsersManager_ExceptionEditAnonymous' => 'L\'utente "anonymous" non può essere modificato o eliminato. È usato da Piwik per avere un utente che non ha ancora effettuato l\'accesso. Ad esempio, è possibile rendere le statistiche pubbliche assegnando all\'utente "anonymous" i permessi di "view".',
	'UsersManager_ExceptionUserDoesNotExist' => 'L\'utente "%s" non esiste.',
	'UsersManager_ExceptionAccessValues' => 'I parametri di accesso devono avere uno dei seguenti valori : [ %s ]',
	'SitesManager_Sites' => 'Siti',
	'SitesManager_JsCode' => 'Codice JavaScript',
	'SitesManager_JsCodeHelp' => 'Questo è il codice JavaScript che deve essere inserito in tutte le pagine',
	'SitesManager_ShowJsCode' => 'Mostra Codice',
	'SitesManager_NoWebsites' => 'Non è presente nessun sito da amministrare.',
	'SitesManager_AddSite' => 'Aggiungi un nuovo Sito',
	'SitesManager_Id' => 'Id',
	'SitesManager_Name' => 'Nome',
	'SitesManager_Urls' => 'URL',
	'SitesManager_MenuSites' => 'Siti',
	'SitesManager_DeleteConfirm_js' => 'Sei sicuro di voler eliminare il sito %s?',
	'SitesManager_ExceptionDeleteSite' => 'Non posso eliminare il sito in quanto è l\'unico registrato: è necessario aggiungere un nuovo sito prima di poter cancellare questo.',
	'SitesManager_ExceptionNoUrl' => 'Inserire almeno un URL per il sito.',
	'SitesManager_ExceptionEmptyName' => 'Il nome del sito non può essere vuoto.',
	'SitesManager_ExceptionInvalidUrl' => 'L\'indirizzo "%s" non è un URL valido.',
	'Installation_Installation' => 'Installazione',
	'Installation_InstallationStatus' => 'Avanzamento dell\'installazione',
	'Installation_PercentDone' => '%s %% Fatto',
	'Installation_NoConfigFound' => 'Non riesco a trovare il file di configurazione e stai cercando di accedere direttamente a Piwik.<br><b>&nbsp;&nbsp;&raquo; È possibile <a href="index.php">installare Piwik ora</a></b>.<br><small>Se Piwik è già stato installato in precedenza e il database è popolato con alcuni dati, non c\'è motivo di preoccuparsi, la nuova installazione di Piwik può riusare gli stessi dati!</small>',
	'Installation_MysqlSetup' => 'Configurazione di Mysql',
	'Installation_MysqlErrorConnect' => 'Si è verificato un errore durante la connessione al database MySql',
	'Installation_JsTag' => 'Javascript tag',
	'Installation_JsTagHelp' => '<p>Per tenere traccia di tutti i visitatori, inserire il codice JavaScript in tutte le pagine.</p><p>Piwik funziona con tutti i tipi di pagine (HTML, ASP, Perl o qualsiasi altro linguaggio) purchè non siano generate da PHP.</p><p>Questo è il codice da inserire: (copia e incolla in tutte le pagine)</p>',
	'Installation_Congratulations' => 'Congratulazioni',
	'Installation_CongratulationsHelp' => '<p>Congratulazioni! L\'installazione di Piwik è completata.</p><p>Assicurarsi di aver inserito il codice JavaScript in tutte le pagine e attendere il primo visitatore!</p>',
	'Installation_GoToPiwik' => 'Vai a Piwik',
	'Installation_SetupWebsite' => 'Configura un sito web',
	'Installation_SetupWebsiteError' => 'Si è verificato un errore inserendo il sito',
	'Installation_GeneralSetup' => 'Configurazione Generale',
	'Installation_GeneralSetupSuccess' => 'Configurazione generale ultimata con successo',
	'Installation_SystemCheck' => 'Verifica del sistema',
	'Installation_SystemCheckPhp' => 'Versione di PHP',
	'Installation_SystemCheckPdo' => 'Estensione Pdo',
	'Installation_SystemCheckPdoMysql' => 'Estensione Pdo_Mysql',
	'Installation_SystemCheckPdoError' => 'Abilitare le estensioni Pdo e Pdo_mysql nel file php.ini.',
	'Installation_SystemCheckPdoHelp' => 'Su un server windows aggiungere le seguenti righe nel file php.ini %s <br><br>Su un server Linux compilare php con le seguenti opzioni %s Nel file php.ini aggiungere le seguenti righe %s<br><br>Se hai bisogno di maggiori informazioni vai sul <a style="color:red" href="http://php.net/pdo">sito ufficiale di Php</a>.',
	'Installation_SystemCheckWriteDirs' => 'Directory con permessi di scrittura',
	'Installation_SystemCheckWriteDirsHelp' => 'Per correggere questo errore su un server Linux, provare a digitare i seguenti comandi',
	'Installation_SystemCheckMemoryLimit' => 'Limite di memoria',
	'Installation_SystemCheckMemoryLimitHelp' => 'Su un sito web con molto traffico, il processo di archiviazione può richiedere più memoria di quella attualmente disponibile.<br>Se necessario controllare la direttiva memory_limit nel file php.ini.',
	'Installation_SystemCheckGD' => 'GD &gt; 2.x (grafica)',
	'Installation_SystemCheckGDHelp' => 'Gli sparklines (i piccoli grafici accanto al testo) non saranno disponibili.',
	'Installation_SystemCheckTimeLimit' => 'set_time_limit() permesso',
	'Installation_SystemCheckTimeLimitHelp' => 'Su un sito web con molto traffico, il processo di archiviazione può richiedere più tempo di quello attualmente disponibile.<br>Se necessario controllare la direttiva max_execution_time nel file php.ini.',
	'Installation_SystemCheckMail' => 'mail() permessa',
	'Installation_SystemCheckError' => 'Si è verificato un errore. Deve essere corretto prima di procedere',
	'Installation_SystemCheckWarning' => 'Piwik funzionerà normalmente ma alcune funzionalità potrebbero non essere attive',
	'Installation_Tables' => 'Sto creando le tabelle',
	'Installation_TablesWarning' => 'Alcune <span id="linkToggle">tabelle di Piwik</span> sono già presenti nel database',
	'Installation_TablesFound' => 'Le seguenti tabelle sono state trovate nel database',
	'Installation_TablesWarningHelp' => 'Scegliere se riusare i dati esistenti nel database (da una precedente instanza di Piwik) o procedere con un nuova installazione cancellando tutti i vecchi dati.',
	'Installation_TablesReuse' => 'Riutilizza i dati esistenti',
	'Installation_TablesDelete' => 'Elimina tutti i vecchi dati',
	'Installation_TablesDeletedSuccess' => 'Tutti i vecchi dati di Piwik sono stati cancellati correttamente',
	'Installation_TablesCreatedSuccess' => 'Tabelle create correttamente!',
	'Installation_TablesDeleteConfirm' => 'Sei sicuro di voler eliminare tutte le tabelle di Piwik da questo database?',
	'Installation_Welcome' => 'Benvenuto!',
	'Installation_WelcomeHelp' => '<p>Piwik è un software open source per l\'analisi del traffico web capace di ricavare tutte le informazioni che si desidera ottenere dai propri visitatori.</p><p>L\'installazione è divisa in %s semplici passi e bastano solo 5 minuti!</p>',
	'TranslationsAdmin_MenuTranslations' => 'Traduzioni',
	'TranslationsAdmin_MenuLanguages' => 'Lingue',
	'TranslationsAdmin_Plugin' => 'Plugin',
	'TranslationsAdmin_Definition' => 'Definizione',
	'TranslationsAdmin_DefaultString' => 'Stringa di default (Inglese)',
	'TranslationsAdmin_TranslationString' => 'Stringa tradotta (lingua corrente: %s)',
	'TranslationsAdmin_Translations' => 'Traduzioni',
	'TranslationsAdmin_FixPermissions' => 'Correggi i permessi nel filesystem',
	'TranslationsAdmin_AvailableLanguages' => 'Lingue disponibili',
	'TranslationsAdmin_AddLanguage' => 'Aggiungi una lingua',
	'TranslationsAdmin_LanguageCode' => 'Codice della lingua',
	'TranslationsAdmin_Export' => 'Esporta Lingua',
	'TranslationsAdmin_Import' => 'Importa Lingua',
);
