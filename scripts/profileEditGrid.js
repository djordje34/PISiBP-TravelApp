const root = ReactDOM.createRoot(document.querySelector("#personal"));

function genProfileEditName(){
    return (
        <div class ="d-flex flex-row my-3"> 
        
        <div class="col-md-6 ">
    <input type="text" class="form-control" name="ime" id="ime"/>
    <label for="ime" class="form-label">Ime</label>
  </div>
  <div class="col-md-6 ">
    <input type="text" class="form-control" name="prezime" id="prezime"/>
    <label for="prezime" class="form-label">Prezime</label>
  </div>

  </div>
    );
    
  }
function genProfileAdresa(){
    return (
    <div class="my-3">

    <input type="text" class="form-control" name="adresa" id="adresa"/>
    <label for="adresa" class="form-label">Adresa</label>
  </div>
    );
}
function genProfileKartica(){
    return (
        <div class="my-3">

        <input type="text" class="form-control" name="kartica" id="kartica"/>
        <label for="kartica" class="form-label">Broj kartice</label>
      </div>
    );
}


function genSubmitButton(){
  return (
    <div class = "my-3">
    <button type="submit" class="btn btn-dark btn-lg prikaz ">Submit</button>
    </div>
  );
}

  root.render([genProfileEditName(),genProfileAdresa(),genProfileKartica(),genSubmitButton()]);
  
  let test = document.getElementById('ime')
  test.value='<?php echo escape($user->data()->name); ?>';