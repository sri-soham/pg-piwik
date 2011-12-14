/*!
 * Piwik - Web Analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

function widgetsHelper()
{
}

/**
 * Returns the available widgets fetched via AJAX (if not already done)
 * 
 * @return object containing available widgets
 */
widgetsHelper.getAvailableWdigets = function ()
{
    if(!widgetsHelper.availableWidgets)
    {
        var ajaxRequest =
        {
            type: 'GET',
            url: 'index.php?module=Dashboard&action=getAvailableWidgets&token_auth='+piwik.token_auth+'&idSite='+piwik.idSite,
            dataType: 'json',
            async: false,
            error: piwikHelper.ajaxHandleError,
            success: function(data){ 
                widgetsHelper.availableWidgets = data;
            }
        };
        $.ajax(ajaxRequest);
    }
    
    return widgetsHelper.availableWidgets;

}

widgetsHelper.getWidgetCategoryNameFromUniqueId = function (uniqueId)
{
	var widgets = widgetsHelper.getAvailableWdigets();
	for(var widgetCategory in widgets) {
		var widgetInCategory = widgets[widgetCategory];
		for(var i in widgetInCategory) {
			if(widgetInCategory[i]["uniqueId"] == uniqueId) {
				return widgetCategory;
			}
		}
	}
	return false;
};

widgetsHelper.getWidgetObjectFromUniqueId = function (uniqueId)
{
	var widgets = widgetsHelper.getAvailableWdigets();
	for(var widgetCategory in widgets) {
		var widgetInCategory = widgets[widgetCategory];
		for(var i in widgetInCategory) {
			if(widgetInCategory[i]["uniqueId"] == uniqueId) {
				return widgetInCategory[i];
			}
		}
	}
	return false;
};

widgetsHelper.getWidgetNameFromUniqueId = function (uniqueId)
{
	widget = this.getWidgetObjectFromUniqueId(uniqueId);
	if(widget == false) {
		return false;
	}
	return widget["name"];
};

widgetsHelper.getLoadWidgetAjaxRequest = function (widgetUniqueId, widgetParameters, onWidgetLoadedCallback)
{
	var ajaxRequest = 
	{
		widgetUniqueId:widgetUniqueId,
		type: 'GET',
		url: 'index.php',
		dataType: 'html',
		async: true,
		error: piwikHelper.ajaxHandleError,		
		success: onWidgetLoadedCallback,
		data: piwikHelper.getQueryStringFromParameters(widgetParameters) + "&widget=1&idSite="+piwik.idSite+"&period="+piwik.period+"&date="+broadcast.getValueFromUrl('date')
	};
	return ajaxRequest;
};

widgetsHelper.getEmptyWidgetHtml = function (uniqueId, widgetName)
{
	return '<div id="'+uniqueId+'" class="widget">'+
				'<div class="widgetTop">'+
					'<div class="button" id="close">'+
						'<img src="themes/default/images/close.png" title="'+_pk_translate('Dashboard_Close_js')+'" />'+
					'</div>'+
					'<div class="button" id="maximise">'+
						'<img src="themes/default/images/maximise.png" title="'+_pk_translate('Dashboard_Maximise_js')+'" />'+
					'</div>'+
					'<div class="button" id="minimise">'+
						'<img src="themes/default/images/minimise.png" title="'+_pk_translate('Dashboard_Minimise_js')+'" />'+
					'</div>'+
					'<div class="widgetName">'+widgetName+'</div>'+
				'</div>'+
				'<div class="widgetContent">'+ 
					'<div class="widgetLoading">'+
						_pk_translate('Dashboard_LoadingWidget_js') +
					'</div>'+
				'</div>'+
			'</div>';
};

/**
 * widgetPreview jQuery Extension
 * 
 * Converts an dom element to a widget preview
 * Widget preview contains an categorylist, widgetlist and a preview
 */
(function($) {
    $.extend({
        widgetPreview: new function() {
            
            /**
             * Default settings for widgetPreview
             */
            var settings = {
                /**
                 * handler called after a widget preview is loaded in preview element
                 */
                onPreviewLoaded: function() {},
                /**
                 * handler called on click on element in widgetlist or widget header
                 */
                onSelect: function() {},
                /**
                 * callback used to determine if a widget is available or not
                 * unavailable widgets aren't chooseable in widgetlist
                 */
                isWidgetAvailable: function(widgetUniqueId){ return true; },
                /**
                 * should the lists and preview be reset on widget selection?
                 */
                resetOnSelect: false,
                /**
                 * css classes for various elements
                 */
                baseClass: 'widgetpreview-base',
                categorylistClass: 'widgetpreview-categorylist',
                widgetlistClass: 'widgetpreview-widgetlist',
                widgetpreviewClass: 'widgetpreview-preview',
                choosenClass: 'widgetpreview-choosen',
                unavailableClass: 'widgetpreview-unavailable'
            };
            
            var availableWidgets, widgetPreview, widgetAjaxRequest = null;
            
            /**
             * Returns the div to show category list in
             * - if element doesn't exist it will be created and added
             * - if element already exist it's content will be removed
             * 
             * @return category list element
             */
            function createWidgetCategoryList() {
                
                if(!$('.'+settings.categorylistClass, widgetPreview).length) {
                    $(widgetPreview).append('<ul class="'+ settings.categorylistClass +'"></ul>');
                } else {
                    $('.'+settings.categorylistClass, widgetPreview).empty();
                }
                
                for (var widgetCategory in availableWidgets) {
                    
                    $('.'+settings.categorylistClass, widgetPreview).append('<li>'+ widgetCategory +'</li>');
                }
                
                return $('.'+settings.categorylistClass, widgetPreview);
            };
            
            /**
             * Returns the div to show widget list in
             * - if element doesn't exist it will be created and added
             * - if element already exist it's content will be removed
             * 
             * @return widget list element
             */
            function createWidgetList() {
                
                if(!$('.'+settings.widgetlistClass, widgetPreview).length) {
                    $(widgetPreview).append('<ul class="'+ settings.widgetlistClass +'"></ul>');
                } else {
                    $('.'+settings.widgetlistClass+' li', widgetPreview).off('mouseover');
                    $('.'+settings.widgetlistClass+' li', widgetPreview).off('click');
                    $('.'+settings.widgetlistClass, widgetPreview).empty();
                }
                
                if($('.'+settings.categorylistClass+' .'+settings.choosenClass, widgetPreview).length) {
                    var position = $('.'+settings.categorylistClass+' .'+settings.choosenClass, widgetPreview).position().top -
                                   $('.'+settings.categorylistClass).position().top;
                    
                    $('.'+settings.widgetlistClass, widgetPreview).css('top', position);
                    $('.'+settings.widgetlistClass, widgetPreview).css('marginBottom', position);
                }
                
                return $('.'+settings.widgetlistClass, widgetPreview);
            };
            
            /**
             * Display the given widgets in a widget list
             * 
             * @param object widgets widgets to be displayed
             * @return void
             */
            function showWidgetList(widgets) {
                
                var widgetList   = createWidgetList();
                
                for(var j in widgets) {
                    var widgetName       = widgets[j]["name"];
                    var widgetUniqueId   = widgets[j]["uniqueId"];
                    var widgetParameters = widgets[j]["parameters"];
                    var widgetClass      = '';
                    if(!settings.isWidgetAvailable(widgetUniqueId)) {
                        widgetClass += ' ' + settings.unavailableClass;
                    }
                    
                    widgetList.append('<li class="'+ widgetClass +'" uniqueid="'+ widgetUniqueId +'">'+ widgetName +'</li>');
                }
                
                $('li:not(.'+settings.unavailableClass+')', widgetList).on('mouseover', function(){
                    var widgetUniqueId = $(this).attr('uniqueid');
                    
                    $('li', widgetList).removeClass(settings.choosenClass);
                    $(this).addClass(settings.choosenClass);
                    
                    showPreview(widgetUniqueId);
                });
                
                $('li:not(.'+settings.unavailableClass+')', widgetList).on('click', function(){
                    if(!$('.widgetLoading', widgetPreview).length) {
                        settings.onSelect($(this).attr('uniqueid'));
                        if(settings.resetOnSelect) {
                            resetWidgetPreview();
                        }
                    }
                    return false;
                });
            };
            
            /**
             * Returns the div to show widget preview in
             * - if element doesn't exist it will be created and added
             * - if element already exist it's content will be removed
             * 
             * @return preview element
             */
            function createPreviewElement() {
                
                if(!$('.'+settings.widgetpreviewClass, widgetPreview).length) {
                    $(widgetPreview).append('<div class="'+ settings.widgetpreviewClass +'"></div>');
                } else {
                    $('.'+settings.widgetpreviewClass+' .widgetTop', widgetPreview).off('click');
                    $('.'+settings.widgetpreviewClass, widgetPreview).empty();
                }
                
                return $('.'+settings.widgetpreviewClass, widgetPreview);
            };
            
            /**
             * Show widget with the given uniqueId in preview
             * 
             * @param string widgetUniqueId unique id of widget to display
             * @return void
             */
            function showPreview(widgetUniqueId) {
                
                // do not reload id widget already displayed
                if($('#'+widgetUniqueId, widgetPreview).length) return; 
                
                var previewElement = createPreviewElement();
                
                widget = widgetsHelper.getWidgetObjectFromUniqueId(widgetUniqueId);
                widgetParameters = widget['parameters'];
                
                emptyWidgetHtml = widgetsHelper.getEmptyWidgetHtml(
                                        widgetUniqueId, 
                                        '<div title="'+_pk_translate("Dashboard_AddPreviewedWidget_js")+'">'+
                                            _pk_translate('Dashboard_WidgetPreview_js')+
                                        '</div>'
                );
                previewElement.html(emptyWidgetHtml);
                
                var onWidgetLoadedCallback = function (response) {
                    widgetElement = $('#'+widgetUniqueId);
                    $('.widgetContent', widgetElement).html($(response));
                    settings.onPreviewLoaded(widgetUniqueId, widgetElement);
                    $('.'+settings.widgetpreviewClass+' .widgetTop', widgetPreview).on('click', function(){
                        settings.onSelect(widgetUniqueId);
                        if(settings.resetOnSelect) {
                            resetWidgetPreview();
                        }
                        return false;
                    });
                };
                
                // abort previous sent request
                if(widgetAjaxRequest) {
                    widgetAjaxRequest.abort();
                }
                
                ajaxRequest = widgetsHelper.getLoadWidgetAjaxRequest(widgetUniqueId, widgetParameters, onWidgetLoadedCallback);
                widgetAjaxRequest = $.ajax(ajaxRequest);
            };
            
            /**
             * Reset function
             * 
             * @return void
             */
            function resetWidgetPreview() {
                $('.'+settings.categorylistClass+' li', widgetPreview).removeClass(settings.choosenClass);
                createWidgetList();
                createPreviewElement();
            };
            
            /**
             * Constructor
             * 
             * @param object userSettings Settings to be used
             * @return void
             */
            this.construct = function(userSettings) {
                widgetPreview = this;
                
                $(this).addClass('widgetpreview-base');
                
                settings = jQuery.extend(settings, userSettings);
                
                // set onSelect callback
                if(typeof settings.onSelect == 'function') {
                    this.onSelect = settings.onSelect;
                }
                
                // set onPreviewLoaded callback
                if(typeof settings.onPreviewLoaded == 'function') {
                    this.onPreviewLoaded = settings.onPreviewLoaded;
                }
                
                availableWidgets = widgetsHelper.getAvailableWdigets();
                
                var categoryList = createWidgetCategoryList();
                
                $('li', categoryList).on('mouseover', function(){
                    var category = $(this).text();
                    var widgets = availableWidgets[category];
                    $('li', categoryList).removeClass(settings.choosenClass);
                    $(this).addClass(settings.choosenClass);
                    showWidgetList(widgets);
                    createPreviewElement(); // empty preview
                });
            };

        }
    });
    
    /**
     * Makes widgetPreview available with $().widgetPreview()
     */
    $.fn.extend({
        widgetPreview: $.widgetPreview.construct
    })
})(jQuery);
