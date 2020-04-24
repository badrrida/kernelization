$("#dont").on('change', function () {
    var checked = $("input[id='dont']:checked").length === 1 ? true : false;
        $("input[id='bootcamp']").prop('disabled',checked);
		$("input[id='badge']").prop('disabled',checked);
   
});
