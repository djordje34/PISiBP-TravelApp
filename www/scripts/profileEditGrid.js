const root = ReactDOM.createRoot(document.querySelector("#personal"));

function genSubmitButton(){
  return (
    <div class="form-btn" > <button class="submit-btn" type="submit">PROMENI</button> </div>
  );
}

  root.render([genSubmitButton()]);