const root = ReactDOM.createRoot(document.querySelector("#personal"));

function genBookingEditName(){
    return (
      <div class="row">
      <div class="col-md-6">
          <div class="form-group"> <input class="form-control ime" type="text" placeholder="Unesite vase ime" id="ime" name="ime"/> <span class="form-label">Ime</span> </div>
      </div>
      <div class="col-md-6">
          <div class="form-group"> <input class="form-control prezime" type="text" placeholder="Unesite vase prezime" id="prezime" name="prezime"/> <span class="form-label">Prezime</span> </div>
      </div>
  </div>
    );
  }
function genBookingPeople(){
  return (
    <div class="row">
  <div class="col-md-6">
          <div class="form-group"> <input class="form-control clan_odr" type="text" placeholder="Unesite broj odraslih" id="clanovi_odrasli" name="clanovi_odrasli"/> <span class="form-label">Br. odraslih</span> </div>
      </div>
      <div class="col-md-6">
          <div class="form-group"> <input class="form-control clan_deca" type="text" placeholder="Unesite broj dece" id="clanovi_deca" name="clanovi_deca"/> <span class="form-label">Br. dece</span> </div>
      </div>
  </div>
  );
}

function genBookingEmailPhone(){
  return (
    <div class="row">
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control email" type="email" placeholder="Unesite vas Email" id="email" name="email"/> <span class="form-label">Email</span> </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control tel" type="tel" placeholder="Unesite vas telefon" id="kontakt" name="kontakt"/> <span class="form-label">Telefon</span> </div>
    </div>
</div>
  );
}
function genBookingRoomBroj(){
  return (
      <div class="form-group"> <select class="form-control broj_soba" name="broj_soba" id="broj_soba" required >
              <option value="" selected hidden>Broj soba</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
          </select> <span class="select-arrow"></span> <span class="form-label">Broj soba</span> </div>
  );
}
function genBookingRoom_type(){
  return (
    <div class="form-group"> <input class="form-control kartica" type="text" placeholder="Broj kartice" id="kartica" name="kartica"/> <span class="form-label">Broj kartice</span> </div>
    
  );
}
function genSubmitButton(){
  return (
    <div class="row">
      <div class="col-md-6">
    <div class="form-btn" > <button class="submit-btn zakazi" type="button">ZAKAZI</button> </div>
    </div>
      <div class="col-md-6">
        <div class="form-btn" > <button class="submit-btn proveriCenu" type="button">Proveri Cenu</button> </div>
      </div>
    </div>

    
  );
}


  root.render([genBookingEditName(),genBookingPeople(),genBookingRoomBroj(),genBookingRoom_type(),genBookingEmailPhone(),genSubmitButton()]);
  

$(document).ready(function() {
  $(document).on("change",".broj_soba", function (event) {
    event.preventDefault();
    console.log($(this).val(),typeof($(this).val()) )
    let valueSelected=$(this).val()
    console.log("ocitalo"+valueSelected)
    let rtn = "<div class='row'>"+
    "<div class='form-group col-md-6'>"+
    "<label for='beds' style='color:white'>Broj kreveta:</label>"+
    "<select class='form-control brojKreveta' name='brojKreveta' id='beds'>"+
        "<option>1</option>"+
        "<option>2</option>"+
        "<option>3</option>"+
        "<option>4</option>"+
    "</select>"+
    "</div>"+
    "<div class='form-group col-md-6'>"+
    "<label for='room_type' style='color:white'>Tip sobe:</label>"+
    "<select class='form-control tipSobe' name='tipSobe' id='room_type'>"+
        "<option value='Soba'>Soba (Standardna)</option>"+
        "<option value='Studio'>Studio</option>"+
        "<option value='Family'>Family</option>"+
        "<option value='Suite'>Suite</option>"+
        "<option value='Superior'>Superior</option>"+
    "</select>"+
    "</div>"+
"</div>";

$('.appendForms').empty();
    for(var i = 1; i <= valueSelected; i++){

        $('.appendForms').append(rtn); 

    }
});
$(document).on("click",".zakazi", function (event) {
  var kreveti = $(".brojKreveta");
  var tipovi = $(".tipSobe");
  var listakreveta = [];
  for(var i = 0; i < kreveti.length; i++){
    listakreveta[i] = $(kreveti[i]).val();
  }
  var listatipova = [];
  for(var i = 0; i < tipovi.length; i++){
    listatipova[i] = $(tipovi[i]).val();
  }
  console.log(listakreveta, listatipova);
  var ime = document.getElementById('ime').value
  var prezime = document.getElementById('prezime').value
  var clan_odr =    document.getElementById('clanovi_odrasli').value
  var clan_deca =     document.getElementById('clanovi_deca').value
  var email     =   document.getElementById('email').value
  var kontakt   =   document.getElementById('kontakt').value
  var kartica   =   document.getElementById('kartica').value
  var broj_soba   =   document.getElementById('broj_soba').value
  $.ajax({
    url: 'booking.php',
    type: 'post',
    data: {booking: 1, listakreveta:listakreveta, listatipova:listatipova, ime:ime, prezime:prezime, clan_odr:clan_odr, clan_deca:clan_deca, email:email, kontakt:kontakt, kartica:kartica, broj_soba:broj_soba},
    success: function(response){
        if (response) {
          window.location.href = 'ponude.php';
        }
    }
});
});
});
$(document).on("click",".proveriCenu", function (event) {
  var kreveti = $(".brojKreveta");
  var tipovi = $(".tipSobe");
  var listakreveta = [];
  for(var i = 0; i < kreveti.length; i++){
    listakreveta[i] = $(kreveti[i]).val();
  }
  var listatipova = [];
  for(var i = 0; i < tipovi.length; i++){
    listatipova[i] = $(tipovi[i]).val();
  }
  var clan_odr =    document.getElementById('clanovi_odrasli').value
  var clan_deca =     document.getElementById('clanovi_deca').value
  var broj_soba   =   document.getElementById('broj_soba').value
  $.ajax({
    url: 'cena.php',
    type: 'post',
    data: {proveriCenu: 1, listakreveta:listakreveta, listatipova:listatipova, clan_odr:clan_odr, clan_deca:clan_deca, broj_soba:broj_soba},
    success: function(response){
      $('#ukupna_cena').empty().append(response);
    }
});
});
