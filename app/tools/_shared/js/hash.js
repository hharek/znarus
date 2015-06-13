var _hash;

/**
 * Разобрать урл
 */
function _hash_parse(hash)
{
	var obj = {};
	var reg = /^#?([0-9a-z_]+)\/([0-9a-z_]+)(\??[^#]*)(#?.*)/;
	
	if(!reg.test(hash))
	{
		throw new Error("Hash. Урл «" + hash + "» задан неверно.");
	}
	
	var match = hash.match(reg);
	obj.mod = match[1];
	obj.act = match[2];
	obj.get = match[3];
	obj.after = match[4];
	obj.url = "#" + obj.mod + "/" + obj.act + obj.get;
	
	return obj;
}

/* Разбор хэша */
$(function()
{
	/* Exe по умолчанию */
	if(window.location.hash === "" || window.location.hash === "#")
	{
		window.location.hash = _settings.hash_default;
	}
	
	_exe(window.location.hash);
	_hash = window.location.hash;
	
	/* Изменение хэша */
	$(window).bind("hashchange", function() 
	{
		if (_hash_parse(_hash).url !== _hash_parse(window.location.hash).url)
		{
			_exe(window.location.hash);
			_hash = window.location.hash;
		}
	});
});