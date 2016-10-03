
function enabledCoureurForm(){
		document.getElementById('name').disabled = false;
		document.getElementById('firstname').disabled = false;
		document.getElementById('anneeNaissance').disabled = false;
		document.getElementById('anneePrem').disabled = false;
		document.getElementById('codeTdf').disabled = false;
}
var btnModifier = document.getElementById('btnModifier');
btnModifier.addEventListener('click', function(){
	switch(categorie){
		case 1:
			enabledCoureurForm();
	}
	
})