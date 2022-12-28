function sub(){    
    // get stock, price, and total price
    var harga = parseInt(document.getElementById("harga").value);
    var total = document.getElementById("total");
    
    // get number of order 
    var x = parseInt(document.getElementById("val").value);
    if (x > 0){
        document.getElementById("val").value = x - 1;
        total.innerHTML = parseInt(total.innerHTML) - harga;
    }
}

function add(){
    // get stock, price, and total price
    var stock = parseInt(document.getElementById("valstok").innerHTML);
    var harga = parseInt(document.getElementById("harga").value);
    var total = document.getElementById("total");

    // get number of order
    var x = parseInt(document.getElementById("val").value);

    if (x < stock){
        document.getElementById("val").value = x + 1;
        total.innerHTML = parseInt(total.innerHTML) + harga;
    }    
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

function ajaxpembelian(){
    // get variant name, num of order
    var x = parseInt(document.getElementById("val").value);
    var namavarian = document.getElementById("namavarian").innerHTML;
    var stock = parseInt(document.getElementById("valstok").innerHTML);

    if (x>0 && stock >= x){
      // ajax request
      var xhttp = new XMLHttpRequest();

      // apa
      xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("val").value = 0;
              document.getElementById("valstok").innerHTML = stock - x;
              alert(this.responseText);
          }
      }

      xhttp.open("POST", "ajax/ajaxpembelian.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("varian="+namavarian+"&num="+x);
    }
    else{
      if (stock < x){
        alert("Stok tidak mencukupi");
      }
    }
}

/* kalau stok berubah di layar jika + atau - dipencet */
// function sub(){    
//   // get stock, price, and total price
//   var stock = parseInt(document.getElementById("valstok").innerHTML);
//   var harga = parseInt(document.getElementById("harga").value);
//   var total = document.getElementById("total");
  
//   // get number of order 
//   var x = parseInt(document.getElementById("val").value);
//   if (x > 0){
//       document.getElementById("val").value = x - 1;
//       document.getElementById("valstok").innerHTML = stock+1;
//       total.innerHTML = parseInt(total.innerHTML) - harga;
//   }
// }

// function add(){
//   // get stock, price, and total price
//   var stock = parseInt(document.getElementById("valstok").innerHTML);
//   var harga = parseInt(document.getElementById("harga").value);
//   var total = document.getElementById("total");

//   // get number of order
//   var x = parseInt(document.getElementById("val").value);

//   if (stock >= 1){
//       document.getElementById("val").value = x + 1;
//       document.getElementById("valstok").innerHTML = stock-1;
//       total.innerHTML = parseInt(total.innerHTML) + harga;
//   }    
// }

// function getCookie(cname) {
//   let name = cname + "=";
//   let decodedCookie = decodeURIComponent(document.cookie);
//   let ca = decodedCookie.split(';');
//   for(let i = 0; i <ca.length; i++) {
//     let c = ca[i];
//     while (c.charAt(0) == ' ') {
//       c = c.substring(1);
//     }
//     if (c.indexOf(name) == 0) {
//       return c.substring(name.length, c.length);
//     }
//   }
//   return "";
// }

// function ajaxpembelian(){
//   // get variant name, num of order
//   var x = parseInt(document.getElementById("val").value);
//   var namavarian = document.getElementById("namavarian").innerHTML;

//   if (x>0){
//     // ajax request
//     var xhttp = new XMLHttpRequest();

//     // apa
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             alert(this.responseText);
//             document.getElementById("val").value = 0;
//         }
//     }

//     xhttp.open("POST", "ajax/ajaxpembelian.php", true);
//     xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xhttp.send("varian="+namavarian+"&num="+x);
//   }
// }

