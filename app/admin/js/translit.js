/* Массив сопоставления */
var alfavit_rus = 
{
	"а" : "a",	"б" : "b",	"в" : "v", 
	"г" : "g",	"д" : "d",	"е" : "e",
	"ё" : "e",	"ж" : "gh",	"з" : "z",
	"и" : "i",	"й" : "i",	"к" : "k",
	"л" : "l",	"м" : "m",	"н" : "n",
	"о" : "o",	"п" : "p",	"р" : "r",
	"с" : "s",	"т" : "t",	"у" : "u",
	"ф" : "f",	"х" : "h",	"ц" : "c",
	"ч" : "ch",	"ш" : "sh",	"щ" : "sh",
	"ъ" : "",	"ы" : "i",	"ь" : "",
	"э" : "je",	"ю" : "ju",	"я" : "ja"
};

/* Транслитерация */
function translit(word)
{
	var translit = "";
	var simbol_bad = false;
	
	word = word.toLowerCase();
	
	for(var i=0; i<word.length; i++)
	{
		var simbol = word[i];
		if(/^[a-z0-9\_]$/.test(simbol))
		{
			translit += simbol;
			simbol_bad = false;
		}
		else if(/^[а-яё]$/.test(simbol))
		{
			translit += alfavit_rus[simbol];
			simbol_bad = false;
		}
		else
		{
			if(!simbol_bad)
			{translit += "-";}
			
			simbol_bad = true;
		}
	}
	
	if(translit[translit.length-1] === "-")
	{
		translit = translit.substr(0, translit.length-1);
	}
	
	return translit;
}

/* Транслитерация на русский */
function translit_rus(word)
{
	var translit = "";
	var simbol_bad = false;
	
	word = word.toLowerCase();
	
	for(var i=0; i<word.length; i++)
	{
		var simbol = word[i];
		if(/^[a-z0-9а-яё\_]$/.test(simbol))
		{
			translit += simbol;
			simbol_bad = false;
		}
		else
		{
			if(!simbol_bad)
			{translit += "-";}
			
			simbol_bad = true;
		}
	}
	
	if(translit[translit.length-1] === "-")
	{
		translit = translit.substr(0, translit.length-1);
	}
	
	return translit;
}