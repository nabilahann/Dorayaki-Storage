function getMenu() {
  var rate_value;
  if (document.getElementById("menuadd").checked) {
    rate_value = document.getElementById("menuadd").value;
  } else if (document.getElementById("menusub").checked) {
    rate_value = document.getElementById("menusub").value;
  }
  return rate_value;
}

function sub() {
  // get number of order, change menu
  var x = parseInt(document.getElementById("val").value);

  // change
  if (x > 0) {
    document.getElementById("val").value = x - 1;
  }
}

function add() {
  // get stock, price, and total price
  var stock = parseInt(document.getElementById("valstok").innerHTML);

  // get number of order, change menu
  var x = parseInt(document.getElementById("val").value);
  var rate_value = getMenu();

  // change
  if (rate_value == "penambahan stok") {
    document.getElementById("val").value = x + 1;
  } else if (rate_value == "pengurangan stok") {
    if (stock > x) {
      document.getElementById("val").value = x + 1;
    }
  }
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function ajaxperubahan() {
  // get variant name, num of order, menu (add stock or reduce stock)
  var x = parseInt(document.getElementById("val").value);
  var namavarian = document.getElementById("namavarian").innerHTML;
  var stock = parseInt(document.getElementById("valstok").innerHTML);

  var rate_value = getMenu();

  var y = x;
  if (rate_value == "pengurangan stok") {
    y = -1 * x;
    if (stock < x) {
      alert("stok tidak mencukupi");
      return;
    }
  }

  if (x > 0) {
    // ajax request
    var xhttp = new XMLHttpRequest();

    // apa
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("val").value = 0;
        //document.getElementById("valstok").innerHTML = stock + y;
        alert(this.responseText);
      }
    };
    // untuk milestone 1
    //xhttp.open("POST", "ajax/ajaxperubahan.php", true);
    // untuk milestone 2
    xhttp.open("POST", "soapclient/request.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("variant=" + namavarian + "&num=" + x + "&menu=" + rate_value);
  }
}

/* KALAU MAU stok di layar berubah langsung jika + atau - diklik */
//   function getMenu(){
//     var rate_value;
//     if (document.getElementById('menuadd').checked) {
//       rate_value = document.getElementById('menuadd').value;
//     }
//     else if (document.getElementById('menusub').checked){
//       rate_value = document.getElementById('menusub').value;
//     }
//     return rate_value;
// }

// function sub(){
//     // get stock, price, and total price
//     var stock = parseInt(document.getElementById("valstok").innerHTML);

//     // get number of order, change menu
//     var x = parseInt(document.getElementById("val").value);
//     var rate_value = getMenu();

//     // change
//     if (x>0){
//         document.getElementById("val").value = x -1;
//         if (rate_value == "penambahan stok"){
//             document.getElementById("valstok").innerHTML = stock - 1;
//         }
//         else if (rate_value == "pengurangan stok"){
//             document.getElementById("valstok").innerHTML = stock+1;
//         }
//     }
// }

// function add(){
//     // get stock, price, and total price
//     var stock = parseInt(document.getElementById("valstok").innerHTML);

//     // get number of order, change menu
//     var x = parseInt(document.getElementById("val").value);
//     var rate_value = getMenu();

//     // change
//     if (rate_value == "penambahan stok"){
//         document.getElementById("val").value = x+1;
//         document.getElementById("valstok").innerHTML = stock+1;
//     }
//     else if (rate_value == "pengurangan stok"){
//         if (stock >= 1){
//             document.getElementById("val").value = x + 1;
//             document.getElementById("valstok").innerHTML = stock-1;
//         }
//     }
// }

// function getCookie(cname) {
//     let name = cname + "=";
//     let decodedCookie = decodeURIComponent(document.cookie);
//     let ca = decodedCookie.split(';');
//     for(let i = 0; i <ca.length; i++) {
//       let c = ca[i];
//       while (c.charAt(0) == ' ') {
//         c = c.substring(1);
//       }
//       if (c.indexOf(name) == 0) {
//         return c.substring(name.length, c.length);
//       }
//     }
//     return "";
// }

// function ajaxperubahan(){
//     // get variant name, num of order, menu (add stock or reduce stock)
//     var x = parseInt(document.getElementById("val").value);
//     var namavarian = document.getElementById("namavarian").innerHTML;

//     var rate_value = getMenu();

//     if (x>0){
//       // ajax request
//       var xhttp = new XMLHttpRequest();

//       // apa
//       xhttp.onreadystatechange = function() {
//           if (this.readyState == 4 && this.status == 200) {
//               alert(this.responseText);
//               document.getElementById("val").value = 0;
//           }
//       }

//       xhttp.open("POST", "ajax/ajaxperubahan.php", true);
//       xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//       xhttp.send("variant="+namavarian+"&num="+x+"&menu="+rate_value);
//     }
//   }
