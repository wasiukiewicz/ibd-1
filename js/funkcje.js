jQuery.fn.center = function () {
	this.css("position", "absolute");
	this.css("top", ($(window).height() - this.height()) / 2 + $(window).scrollTop() + "px");
	this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px");
	return this;
};

$(function() {
	// dodawanie książki do koszyka
	$(".aDodajDoKoszyka").click(function() {
		const $a = $(this);
		
		$.post($a.attr('href'), { id_ksiazki: $a.data('id') }, function(resp) {
			if(resp == 'ok') {
				//const wKoszyku = $("#wKoszyku").text() * 1 + 1;
				//$("#wKoszyku").text(wKoszyku);
				//$a.replaceWith('<i class="fas fa-check"></i>');
				$('#wKoszyku').load(" #wKoszyku")
			} else {
				alert('Wystąpił błąd: ' + resp);
			}
		});
		
		return false;
	});

	$(".aUsunZKoszyka").click(function() {
		const $a = $(this);
		
		$.post($a.attr('href'), { id_koszyka: $a.data('id') }, function(resp) {
			if(resp == 'ok') {
				$a.parent().parent().remove();
				$('#suma').load(" #suma")
				$('#wKoszyku').load(" #wKoszyku")
			} else {
				alert('Wystąpił błąd: ' + resp);
			}
		});
		
		return false;
	});

	
	// autorzy
	$("#fDodajAutora").hide();
	$("#aDodajAutora").click(() => {
		$("#fDodajAutora").toggle();
		return false;
	});
	$(".aUsunAutora").click(usunRekord);
	
	// użytkownicy
	$("#fDodajUzytkownika").hide();
	$("#aDodajUzytkownika").click(() => {
		$("#fDodajUzytkownika").toggle();
		return false;
	});
	$(".aUsunUzytkownika").click(usunRekord);

    // książki
    $("#fDodajKsiazke").hide();
    $("#aDodajKsiazke").click(function () {
        $("#fDodajKsiazke").toggle();
        return false;
    });
    $(".aUsunKsiazke").click(usunRekord);
	
	// pokaż spinner w czasie wykonywania żądań AJAX
	$('#ajaxLoading').hide();
	$(document)
		.ajaxStart(() => {
			$('#ajaxLoading').center();
			$('#ajaxLoading').show();
		})
		.ajaxStop(() => {
			$('#ajaxLoading').hide();
		});
});

/**
 * Usuwa rekord.
 *
 */
function usunRekord()
{
	const $a = $(this);
	const odp = confirm("Czy na pewno chcesz usunąć rekord?");

	if (odp) {
		$.post($a.attr('href'), (response) => {
			if (response == 'ok') {
				$a.parents('tr').find('td').css('textDecoration', 'line-through');
				$a.parent().html("");
			} else {
				alert('Wystąpił błąd przy przetwarzaniu zapytania. Prosimy spróbować ponownie.');
			}
		});
	}
	
	return false;
}