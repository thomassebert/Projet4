// Dates désactivées dans le datepicker 'Date de la visite'
var disabledDates = [2, 7, [2017,10,1],[2017,11,25],[2018,4,1],[2018,10,1],[2018,11,25],[2019,4,1],[2019,10,1],[2019,11,25],[2020,4,1],[2020,10,1],[2020,11,25],[2021,4,1]];

// Variables
var choosenDate = "";
var chooseDateListener;



// FONCTIONS

// Initialise le datapicker du champ "Date de la visite"
// 
function visitDatepickerInitializer() {

	$('.datepicker').pickadate({
			selectMonths: true, 
		    labelMonthNext: 'Mois suivant',
		  	labelMonthPrev: 'Mois précédent',
		 	labelMonthSelect: 'Choisir un mois',
		    labelYearSelect: 'Choisir une année',
		    monthsFull: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
		    monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Dec' ],
		    weekdaysFull: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
		    weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
		    weekdaysLetter: [ 'D', 'L', 'Ma', 'Me', 'J', 'V', 'S' ],
		    clear: 'Effacer',
		    close: 'Ok',
		    format: 'dddd dd mmmm yyyy',
		    formatSubmit: 'dd-mm-yyyy',
		  	hiddenName: true,
		  	firstDay: 1,
		    closeOnSelect: false,
			selectYears : 2,
			today : "Aujourd'hui",
			min : true,
			max : 730,
			disable : disabledDates
		});

}

// Initialise le datapicker du champ "Date de naissance"
// 
function birthDatepickerInitializer() {

	$('.datepickerBirthday').pickadate({
			selectMonths: true, 
		    labelMonthNext: 'Mois suivant',
		  	labelMonthPrev: 'Mois précédent',
		 	labelMonthSelect: 'Choisir un mois',
		    labelYearSelect: 'Choisir une année',
		    monthsFull: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
		    monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Dec' ],
		    weekdaysFull: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
		    weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
		    weekdaysLetter: [ 'D', 'L', 'Ma', 'Me', 'J', 'V', 'S' ],
		    clear: 'Effacer',
		    close: 'Ok',
		    format: 'dddd dd mmmm yyyy',
		    formatSubmit: 'dd-mm-yyyy',
		  	hiddenName: true,
		  	firstDay: 1,
		    closeOnSelect: false,
			selectYears : 100,
			today : "",
			max : true
		});

}

// Récupère les dates où 1000 réservations ont été faites
// 
function getFullDays() {

	$.ajax({
		url: "http://louvre.thomassebert.com/tickets/getFullDays",
		method: "get"
	}).done(function(msg){
		return msg['dates'];	
	});
}

// Formate la date actuelle au format yyyy-mm-dd
// 
function getTodayDateFormat() {

	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth();
	var monthPlus = today.getMonth()+1;
	var day = today.getDate();
	if(monthPlus.toString().length === 1)
	{
		monthPlus = '0'+monthPlus;
	}
	if(day.toString().length === 1)
	{
		day = '0'+day;
	}
	var todayFormat = {date : day + '-' + monthPlus + '-' + year, datepickerFormat : [year, month, day], currentHour : today.getHours(), currentYear : year};

	return todayFormat;

}

// Récupère le nombre de places restantes pour le jour sélectionné
// 
function getRemainingTickets($date) {
	$.ajaxSetup({async: false});
	$.ajax({
				url: "http://louvre.thomassebert.com/tickets/getRemainingTickets",
				method: "post",
				data: JSON.stringify($date)
			}).done(function(msg){
				remainingTicketsView(msg['number']);
			});
}

// Compte le nombre de formulaires "Billet" 
// 
function getTicketFormsNumber() {

	var ticketFormsNumber = 0;
	var ticketForms = $('.ticketFormPart');
	$.each(ticketForms, function(i, ticketForm){
		ticketFormsNumber++;
	});

	return ticketFormsNumber;
}

// Ajoute des dates à disabledDates (données JSON contenant les dates au format [[année, mois-1, jour]] )
// 
function addDisabledDates() {

	var response = getFullDays();
	var today = getTodayDateFormat();
	if(today['currentHour']>=17)
	{
		disabledDates.push(today['datepickerFormat']);
	}
	$.each(response, function(i, item) {
		disabledDates.push(item);
		});

	visitDatepickerInitializer();

}

// Surveille la date choisie par l'utilisateur et modifie la liste "Type de billet " en conséquence (il n'est pas possible de réserver un billet Journée pour le jour même passé 14h), affiche le nombre de places restantes pour ce jour
// 
function choosenDateListener() {

	var newChoosenDate = $('input[type="hidden"][name="booking[calendar][day]"]').val();

	// Si l'utilisateur choisit une date
	if(newChoosenDate !== choosenDate)
	{
		choosenDate = newChoosenDate;
		// On récupère et on affiche le nombre de places restantes pour cette date
		getRemainingTickets(newChoosenDate);
		// Si la date est celle d'aujourd'hui et qu'il est plus de 14h, on désactive l'option 'Type de billet : Journée'
		var today = getTodayDateFormat();
		var optionJournee = document.querySelector('.bookingType li');
		var inputValue = document.querySelector('.bookingType input');
		if((newChoosenDate === today['date']) && today['currentHour'] >= 14)
		{
			optionJournee.setAttribute('class', 'disabled');
			inputValue.setAttribute('value', 'Demi-journée');
		}
		else
		{
			optionJournee.removeAttribute('class');
			inputValue.setAttribute('value', 'Journée');
		}
	}	
}

function remainingTicketsView(remainingTickets) {

	var message = '<div class="ticketsNumber col s12 l4 offset-l1"> <strong>Places libres:</strong> ' + remainingTickets + '</div>';
		$('.ticketsNumber').remove();
		$('.visitDateInput').after(message);

}

// Envoi des données de la partie "calendrier"
// 
function calendarPartSubmit() {

	var formData = {
            'day'              : $('input[type="hidden"][name="booking[calendar][day]"]').val(),
            'bookingType'      : $('.bookingType input').val()
        };

	var JSONvalues = JSON.stringify(formData);

	$.ajax({
		url: "http://louvre.thomassebert.com/tickets/calendarPartSubmit",
		method: "post",
		data: JSONvalues
	}).done(function(msg){
		if(msg['message'] !== 0)
		{
    		alert(msg['message']);
		}
		else
		{
			calendarPartHide();
			var ticketFormsNumber = getTicketFormsNumber();
			if(ticketFormsNumber === 0)
			{
				addTicketForm();
			}
		}
	});
}

// Cache le formulaire "choix de la date" et affiche le récapitulatif
// 
function calendarPartHide() {

	$('.calendarValid').fadeOut();
	var newContent = '<div class="card-content calendarChoiceDetails valign-wrapper "><div class="center-align"><strong>Date de la visite:</strong> '+$('input[type="hidden"][name="booking[calendar][day]"]').val()+' </div> <div class=""> <strong>Type de billet:</strong> '+$('.bookingType input').val()+' </div> <a class="btn-floating waves-effect waves-light right blue tooltipped calendarEdit " data-position="left" data-delay="50" data-tooltip="Modifier"><i class="material-icons">mode_edit</i></a></div>';
	setTimeout(function() {
		$('.calendarValid').append(newContent);
		$('.calendarValid').fadeIn('slow');
		$('.calendarForm').hide();
		$('.tooltipped').tooltip({delay: 50});
		$('.calendarEdit').click(function(){
			$('.tooltipped').tooltip('remove');
			calendarPartShow();
		});
	}, 400);
}

// Efface le récapitulatif et re-affiche le formulaire "choix de la date"
// 
function calendarPartShow() {

		$('.calendarChoiceDetails').remove();
		$('.calendarForm').show();
		$('.calendarValid').fadeIn('slow');
		$('.tooltipped').tooltip({delay: 50});

}

// Ajoute un formulaire "billet"
// 
function addTicketForm() {

	hideTicketForms();
	showTicketFormListener();
	var ticketFormsNumber = getTicketFormsNumber() + 1;
	var ticketNumber = $('.ticketFormContainer');
	if(ticketNumber.length == 0)
	{
		number = 1;
	}
	else
	{
		lastOne = ticketNumber[ticketNumber.length - 1]
		number = parseInt($(lastOne).attr('number')) + 1;
	}
	var template = $('.ticketsContainer').attr('data-prototype').replace(/__changeTitle__/g, ticketFormsNumber).replace(/__change__/g, number);
	$('.ticketsContainer').append(template);
	$('.empty-cart').hide();
	$('.ticketsList').append('<li id="'+number+'" class="ticketli">Billet '+number+' - <span class="priceTicket">0</span> €');
	$('select').material_select();
	birthDatepickerInitializer();
	deleteTicketFormListener();
	$('.tooltipped').tooltip({delay: 50});

}

// Cache les formulaire "Billet"
// 
function hideTicketForms() {

	var ticketForms = $('.ticketFormPart');
	$.each(ticketForms, function(i, ticketForm){
		$(this).find('.ticketFormContent').children().hide();
		var name = $(this).find('.name').val();
		var firstname = $(this).find('.firstname').val();
		var birthdate = $(this).find('input[type="hidden"]').val();
		var country = $(this).find('select').val();
		var discount = $(this).find('.discount');
		if(discount.is(":checked"))
		{
			discount = "Oui";
		}
		else
		{
			discount = "Non";
		}
		var newContent = '<div class="newContent"><div class="recapContent"><div class="col s12 m6 l3"> <strong>' + firstname + ' ' + name + '</strong></div> <div class="col s12 m6 l3"> <strong>Date de naissance: </strong>' + birthdate + ' </div> <div class="col s12 m6 l3"> <strong>Pays: </strong>' + country + '</div> <div class="col s12 m6 l3"> <strong>Tarif réduit: </strong>' + discount + '</div> <div class="right buttonPadding"> <a class="btn-floating waves-effect waves-light blue tooltipped ticketformEdit" data-position="bottom" data-delay="50" data-tooltip="Modifier"><i class="material-icons">mode_edit</i></a> </div></div> <div class="right buttonPadding"> <a class="btn-floating waves-effect waves-light red tooltipped ticketformDelete" data-position="left" data-delay="50" data-tooltip="Supprimer"><i class="material-icons">delete</i></a> </div> </div>';

		$(this).find('.ticketFormContent').append(newContent);
		deleteTicketFormListener();
		showTicketFormListener();
		$('.tooltipped').tooltip({delay: 50});
	});

}

// Montre un formulaire "Billet" caché
// 
function showTicketFormListener() {

	var ticketForms = $('.ticketFormContainer');
	$.each(ticketForms, function(i, ticketForm){
		var editButton = $(this).find('.ticketformEdit');
		editButton.click(function(){
			hideTicketForms();
			$('.tooltipped').tooltip('remove');
			$(ticketForm).find('.ticketFormContent .newContent').remove();
			$(ticketForm).find('.ticketFormContent').children().show();
			$('.tooltipped').tooltip({delay: 50});
		});
	});
}
//addEventListener sur les boutons "modifier" => remove() le contenu de newContent du formulaire concerné et $(this).find('.ticketFormContent').children().show();

// Supprime un formulaire "Billet"
// 
function deleteTicketFormListener() {

	var ticketForms = $('.ticketFormContainer');
	$.each(ticketForms, function(i, ticketForm){
		var deleteButton = $(this).find('.ticketformDelete');
		deleteButton.click(function(){
			ticketForms[i].remove();
			$('.ticketli').remove();
			var newTicketForms = $('.ticketFormContainer');
			if(newTicketForms.length == 0)
			{
				calendarPartShow();
				$('.empty-cart').show();
			}
			var getTicketForms = $('.ticketFormContainer');

			$.each(getTicketForms, function(i, ticketsForm){
				$(this).find('.ticketNumber').html(i+1);
				$('.ticketsList').append('<li id="'+(i+1)+'" class="ticketli">Billet '+(i+1)+' - <span class="priceTicket"></span> €');
			});

		});
	});
}

//Contrôle l'état des formulaires "Billet" et modifie l'affichage
//
function ticketFormsStateListener() {
	var ticketForms = $('.ticketFormPart');
	if(ticketForms.length >= 0)
	{
		var validTicketsForms = 0;
		totalPrice = 0;

		$.each(ticketForms, function(i, ticketForm){
			var name = $(this).find('.name').val();
			var firstname = $(this).find('.firstname').val();
			var birthdate = $(this).find('input[type="hidden"]').val();
			var discount = $(this).find('.discount');
			var regexName = /[a-zA-Z]+/;
			var regexBirthdate = /[0-9][0-9]\-[0-9][0-9]\-[0-9][0-9][0-9][0-9]/;
			if (regexName.test(name) && regexName.test(firstname) && regexBirthdate.test(birthdate)) 
			{
				$(this).find('.ticketFormState').removeClass('yellow darken-2');
				$(this).find('.ticketFormState').addClass('green');
				$(this).find('.ticketFormState').html('<i class="material-icons left">check</i> Complet</span>' );
				validTicketsForms ++;
			}
			else
			{
				$(this).find('.ticketFormState').removeClass('green');
				$(this).find('.ticketFormState').addClass('yellow darken-2');
				$(this).find('.ticketFormState').html('<i class="material-icons left">report</i> A compléter </span>' );
			}
			if (regexBirthdate.test(birthdate)) 
			{
				today = getTodayDateFormat();
				birthYear = birthdate.substr(-4);
				age = today['currentYear']-birthYear;
				var price;
				switch(true) {
					case age < 4 :
						price = 0;
						break;
					case age >= 4 && age <12 :
						price = 8;
						break;
					case age >=12 && age <60 :
						price = 16;
						break;
					case age >= 60 :
						price = 10;
						break;
					default :
						price = 0;
				}
				if (discount.is(":checked")) 
				{
					price = 10;
				}
				$(this).find('.ticketPrice').html(' - ' + price + '€');
				$('#'+(i+1)).find('.priceTicket').html(price);
				totalPrice += price;
			}
			else
			{
				$(this).find('.ticketPrice').html(' - 0 €');
			}
		});
		var shoppingCartPart = $('.shoppingCartPart');
		if(ticketForms.length >= 1 && validTicketsForms === ticketForms.length)
		{

			if(!$('.ticketFormAdd').length)
			{
				shoppingCartPart.find('.card-action').show();
				shoppingCartPart.find('.card-action').prepend('<div class="ticketFormAdd col s6 center-align"><a class="btn-floating btn-large tooltipped waves-effect waves-light blue" data-delay="50" data-tooltip="Ajouter un billet"><i class="material-icons right">add</i></a></div>');	
				$('.ticketFormAdd').click(function(){
					addTicketForm();
				});
				$('.entireFormSubmit').show();	
				$('.tooltipped').tooltip('remove');
				$('.tooltipped').tooltip({delay: 50});
			}

		}
		else
		{
			$('.ticketFormAdd').remove();
			$('.entireFormSubmit').hide();
			shoppingCartPart.find('.card-action').hide();
			$('.tooltipped').tooltip('remove');
			$('.tooltipped').tooltip({delay: 50});
		}
		if(ticketForms.length >= 1){
			$('.totalPrice').html('<strong class="total">Total: </strong>'+totalPrice+' €');
		}
		else{
			$('.totalPrice').html('');
		}
		
	}
}

// Procède au paiement, si succès envoie le formulaire et passe à la page de récap
// 
function proceedPayment($token) {
	$.ajax({
				url: "http://louvre.thomassebert.com/tickets/proceedPayment",
				method: "post",
				data: JSON.stringify($token)
			}).done(function(msg){
				if(msg['state'])
				{
					$('.formPart form').submit();
				}
				else
				{
					alert('Erreur!');
				}
			});
}

// Chargement de la page
// 
$(document).ready(function () {

	$('.entireFormSubmit').hide();

	addDisabledDates();

	setInterval(function(){
		choosenDateListener();
	}, 500);

	$('.calendarPartSubmit').click(function(e){
		e.preventDefault();
		calendarPartSubmit();
	});

	setInterval(function(){
		ticketFormsStateListener();
	}, 500);
	
	var handler = StripeCheckout.configure({
	  key: 'pk_test_6pRNASCoBOKtIshFeQd4XMUh',
	  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
	  locale: 'auto',
	  token: function(token) {
	    // You can access the token ID with `token.id`.
	    // Get the token ID to your server-side code for use.
	    $('.formPart form').submit();
	  }
	});

	$('.paymentSubmit').click(function(e) {
		e.preventDefault();
	  // Open Checkout with further options:
	  handler.open({
	    name: 'Musée du Louvre',
	    description: 'Billeterie',
	    zipCode: true,
	    amount: totalPrice*100,
	    email: $('.userEmail').attr('email'),
	    zipCode: true,
	    billingAddress: true,
	    currency: 'EUR',
	    allowRememberMe: false
	  });
	  
	});

	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() {
	  handler.close();
	});
	
});

$(document).ready(function() {
    $('select').material_select();
  });


