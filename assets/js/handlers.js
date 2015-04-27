/*var days = [];
function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}
function handleDays(btn){
	var flag = true;
	var day = +btn.id.replace('day', '');
	days.forEach(function(item, i, arr){
		if(day == arr[i]){
			arr.splice(i);
			$('#day'+day).css('background-color', '#008db6');
			flag = false;
		}
	});
	if(flag){
		days.push(day);
		days.sort;
		$('#day'+day).css('background-color', '#007192');
	}
}
function handleRegular(){
	if(days.length){
		$('#regularDays').val(days);
	} else {
		$('#regularDays').val(0);
	}
}
*/
function handleDays(btn){
	$(btn).toggleClass("success");
	var days = [];
	$('#regularDays').val("");
	$(".even-7 li .success").each(function() {
    	//alert( this.id );
    	days.push( +this.id.replace('day', '') );
	});
	days.sort;
    $('#regularDays').val(days);
}




