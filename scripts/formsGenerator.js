const root = ReactDOM.createRoot(document.querySelector("#input"));

function setSelector(tag){
return document.querySelector(tag);

}
function genUsernameField(){
    const e = React.createElement;

//return e(
 // 'input',
//  { size:"15" ,type:"text" ,id:"username", name:"username" ,className:"form-control form-control-lg" },

//);
return (
<div class="form-outline mb-3" id="uf">
                  <input size="15" type="email" id="email" name="email" class="form-control form-control-lg" />
                  <label class="form-label" for="username">E-mail</label>
                </div>
);
}

function genPasswordField(){
    const e = React.createElement;
    return (
      
      <div class="form-outline mb-3" ud ="pf">
                  <input size="15" type="password" id="password" name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="password">Lozinka</label>
                </div>
    );
}



root.render([genUsernameField(),genPasswordField()]);
