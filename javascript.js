//this function gets an id of a form (Create or Remove forms) and then toggle between the display states.
function NoteBody(id) {
    var x = document.getElementById(id);
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
// this func is not for use at the moment.
function ButtonOnFunc(obj){
	

	 if (obj.style.color == 'LightGray') {
        obj.style.color = '#0080ff';
    } else {
        obj.style.color = 'LightGray';
    }
	
}
