const root = ReactDOM.createRoot(document.querySelector("#personal"));

function genBookingEditName(){
    return (
      <div class="form-group"> <input class="form-control" type="text" placeholder="Ime Aranzmana"  id="ime" name="ime"/> <span class="form-label">Ime Aranzmana</span> </div>
    );
  }
function genBookingCena(){
  return (
    <div class="form-group"> <input class="form-control" type="text" placeholder="Cena" id="cena" name="cena"/> <span class="form-label">Cena</span> </div>
  );
}
function genBookingNapomena(){
  return (
    <div class="form-group"> <input class="form-control" type="text" placeholder="Napomena" id="napomena" name="napomena"/> <span class="form-label">Napomena</span> </div>
  );
}
function genBookingDateStartReturn(){
  return (
    <div class="row">
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control" type="date" id="starting_date" name="starting_date" required/> <span class="form-label">Datum Polaska</span> </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control" type="date" id="return_date" name="return_date" required/> <span class="form-label">Datum Povratka</span> </div>
    </div>
</div>
  );
}

function genSubmitButton(){
  return (
    <div class="form-btn"> <button class="submit-btn">DODAJ</button> </div>
  );
}
root.render([genBookingEditName(),genBookingCena(),genBookingNapomena(),genBookingDateStartReturn(),genSubmitButton()]);
  
