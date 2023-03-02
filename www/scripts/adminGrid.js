const root = ReactDOM.createRoot(document.querySelector("#personal"));
  
function genAranzmanButton(){
    return (
        <div class="col-xs-7">
            <a href="dodaj_aranzman.php" class="btn btn-primary"><i class="material-icons">&#xE147;</i> <span>Dodaj Aranzman</span></a>				
        </div>
    );
}
function genTable(){
  return (
    <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ime Aranzmana</th>						
                        <th>Datum kreiranja</th>
                        <th>Datum Isteka</th>
                        <th>Status</th>
                        <th>Promeni/Ukloni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><a href="#"> Aranzman#1</a></td>
                        <td>04/10/2023</td>                        
                        <td>04/11/2023</td>
                        <td><span class="status text-success">&bull;</span> Active</td>
                        <td>
                            <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="material-icons">&#xE8B8;</i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE5C9;</i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><a href="#">Aranzman#2</a></td>
                        <td>04/10/2023</td>                       
                        <td>04/12/2023</td>
                        <td><span class="status text-success">&bull;</span> Active</td>
                        <td>
                            <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="material-icons">&#xE8B8;</i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE5C9;</i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><a href="#">Aranzman#3</a></td>
                        <td>02/10/2023</td>
                        <td>05/11/2023</td>
                        <td><span class="status text-danger">&bull;</span> Suspended</td>                        
                        <td>
                            <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="material-icons">&#xE8B8;</i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE5C9;</i></a>
                        </td>                        
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><a href="#">Aranzman#4</a></td>
                        <td>01/21/2023</td>
                        <td>02/05/2023</td>
                        <td><span class="status text-success">&bull;</span> Active</td>
                        <td>
                            <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="material-icons">&#xE8B8;</i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE5C9;</i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><a href="#">Aranzman#5</a></td>
                        <td>01/03/2023</td>                        
                        <td>01/15/2023</td>
                        <td><span class="status text-warning">&bull;</span> Inactive</td>
                        <td>
                            <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="material-icons">&#xE8B8;</i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE5C9;</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
  );
}
function genPagination(){
  return (
    <div class="clearfix">
    <div class="hint-text">Prikazano <b>5</b> od <b>60000</b> aranzmana</div>
    <ul class="pagination">
        <li class="page-item"><a href="#" class="page-link">Prethodna</a></li>
        <li class="page-item active"><a href="#" class="page-link">1</a></li>
        <li class="page-item"><a href="#" class="page-link">2</a></li>
        <li class="page-item"><a href="#" class="page-link">3</a></li>
        <li class="page-item"><a href="#" class="page-link">4</a></li>
        <li class="page-item"><a href="#" class="page-link">5</a></li>
        <li class="page-item"><a href="#" class="page-link">Sledeca</a></li>
    </ul>
</div>
  );
}

  root.render([genAranzmanButton(),genTable(),genPagination()]);
  
