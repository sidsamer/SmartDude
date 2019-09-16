function NoteBody(id) {
    var x = document.getElementById(id);
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
function ButtonOnFunc(obj){
	

	 if (obj.style.color == 'LightGray') {
        obj.style.color = '#0080ff';
    } else {
        obj.style.color = 'LightGray';
    }
	
}
