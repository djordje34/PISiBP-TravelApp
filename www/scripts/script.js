$(document).ready(function() {
    
    $("select[id='kontinent']").on('change', function (e) {
        $optionSelected = $("option:selected", this);
        $valueSelected = this.value;

        $.ajax({
            url: 'vratiDrzave.php',
            type: 'post',
            data: {vratiDrzave: $valueSelected},
            success: function(response){
                $('#drzaveOvde').empty().append(response); 
            }
            })
             });


    $("button[name='pretraziSobe']").click(function(){
        $kreveti =   $("select[name='brojKreveta']").val()
        $tip =       $("select[name='tipSobe']").val()
        $cenaMin =   $("input[name='minCena']").val()
        $cenaMax =   $("input[name='maxCena']").val()
        $aran_id =   $("input[name='aran_id']").val()
        $broj_zvezdica =   $("input[name='broj_zvezdica']").val()

        //dodati sortiranje
        $.ajax({
            url: 'vratiSobe.php',
            type: 'post',
            data: {vratiSobe: 1,kreveti:$kreveti,tip:$tip,cenaMin:$cenaMin,cenaMax:$cenaMax,broj_zvezdica:$broj_zvezdica,aran_id:$aran_id},
            success: function(response){
                $('#prikazSoba').empty().append(response);
            }
        });
    });        
});


