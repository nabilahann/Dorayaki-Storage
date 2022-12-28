function showpervariant(){
    document.getElementById("menu").style.display = "none";
    document.getElementById("big").style.width = "80%";
    document.getElementById("search-variant").style.display = "grid";
}

function ajaxriwayat(){
    document.getElementById("menukembali").style.display="block";

    // query
    var namevariant = document.getElementById("nama").value;
    var query = "?variant="+namevariant;

    // ajax request
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var table = document.getElementById("isi");
            table.innerHTML = xhttp.responseText;
            }
    }

    xhttp.open("GET", "ajax/ajaxriwayat.php"+query, true);
    xhttp.send();
}

function ajaxriwayatself(){
    document.getElementById("big").style.width = "80%";
    document.getElementById("menu").style.display = "none";
    document.getElementById("menukembali").style.display="block";

    // ajax request
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var table = document.getElementById("isi");
            table.innerHTML = xhttp.responseText;
            }
    }

    xhttp.open("GET", "ajax/ajaxriwayat.php", true);
    xhttp.send();
}