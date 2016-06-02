(function () {
	var render = function () {
		var src = document.getElementById('pwid').src;
		widgetDomain = src.substring(src.indexOf('//'), src.indexOf('js'));
		var baseUrl = widgetDomain + 'productwidget/widget/view?';
		var params = {};
		if (typeof(sessionStorage.widgetid) == 'undefined')
			sessionStorage.widgetid = '';
		addFrames(baseUrl, params);
		submitView();
	};
	var buildParamUrl = function (params) {
		var result = '';
		for (p in params) {
			result += p + "=" + encodeURIComponent(params[p]) + "&";
		}
		return result;
	};
	var buildFrameStyle = function (dataset) {
		var result = '';
		result += "width:" + dataset.width + "px;";
		result += "height:" + dataset.height + "px;";
		result += "overflow:hidden;"
		return result;
	};
	var addFrames = function (baseUrl, params) {
		var elements = document.getElementsByClassName('productwidget');
		for (var i = 0; i < elements.length; i++) {
			var e = elements[i];
			params['wid'] = e.dataset.wid;
			var encodedParam = buildParamUrl(params);
			var frameStyle = buildFrameStyle(e.dataset);
			e.innerHTML = "<iframe style='" + frameStyle + "' src='" + baseUrl + encodedParam + "' frameborder='0' scrolling='no'></iframe>";
			sessionStorage.widgetid += params['wid'] + ",";
		}
	};
	var submitView = function () {
		var i = document.createElement('iframe');
		i.style.display = 'none';
		i.id = 'pwiid';
		i.src = widgetDomain + 'productwidget/widget/countView/wid/' + sessionStorage.widgetid + "/domain/" + encodeURIComponent(document.domain);
		document.getElementsByTagName('body')[0].appendChild(i);
		sessionStorage.widgetid = '';
		setTimeout(function(){
			document.getElementsByTagName('body')[0].removeChild(i);
		},1000);
	};
	if (typeof window.attachEvent != 'undefined'){
		// IE 8 and lower.
		window.attachEvent('onload', render);
	}else{
		window.addEventListener('load', render, false);
	}

}());