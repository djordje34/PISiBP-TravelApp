const root = ReactDOM.createRoot(document.querySelector("#personal"));

function genProfileEditName(){
  
    return (
        <div class ="d-flex flex-row my-3"> 
        
        <div class="col-md-6 ">
    <input type="email" class="form-control" id="ime"/>
    <label for="ime" class="form-label">Ime</label>
  </div>
  <div class="col-md-6 ">
    <input type="text" class="form-control" id="prezime"/>
    <label for="prezime" class="form-label">Prezime</label>
  </div>

  </div>
    );

  }
function genProfileAdresa(){
    return (
    <div class="my-3">

    <input type="text" class="form-control" id="adresa"/>
    <label for="adresa" class="form-label">Adresa</label>
  </div>
    );
}
function genProfileKartica(){
    return (
        <div class="my-3">

        <input type="text" class="form-control" id="kartica"/>
        <label for="kartica" class="form-label">Broj kartice</label>
      </div>
    );
}

  root.render([genProfileEditName(),genProfileAdresa(),genProfileKartica()]);