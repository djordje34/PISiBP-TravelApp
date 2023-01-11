import {genUsernameField, genPasswordField} from './formsGenerator';

const root = ReactDOM.createRoot(document.querySelector("#input"));

root.render([genUsernameField(),genPasswordField()]);
