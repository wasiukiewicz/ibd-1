$(function() {
	$(".aDodajDoKoszyka").click(function() {
		const $a = $(this);
		
		$.post($a.attr('href'), { id_ksiazki: $a.data('id') }, function(resp) {
			if(resp == 'ok') {
				const wKoszyku = $("#wKoszyku").text() * 1 + 1;
				$("#wKoszyku").text(wKoszyku);
				$a.replaceWith('<i class="fas fa-check"></i>');
			} else {
				alert('Wystąpił błąd: ' + resp);
			}
		});
		
		return false;
	});
});