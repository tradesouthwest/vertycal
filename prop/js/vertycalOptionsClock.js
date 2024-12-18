var dOut = document.getElementById("date");
var hOut = document.getElementById("hours");
var mOut = document.getElementById("minutes");
var sOut = document.getElementById("seconds");
var ampmOut = document.getElementById("ampm");
var months = ''; /*json? $trans_months; */
var days = ''; /*josnify $trans_days; */

function update() {
  var e = new Date();
  var t = e.getHours() < 12 ? "AM" : "PM";
  var u = 0 === e.getHours() ? 12 : e.getHours() > 12 ? e.getHours() - 12 : e.getHours();
  var a = e.getMinutes() < 10 ? "0" + e.getMinutes() : e.getMinutes();
  var r = e.getSeconds() < 10 ? "0" + e.getSeconds() : e.getSeconds();
  var s = days[e.getDay()] + ", " + months[e.getMonth()] + " " + e.getDate() + ", " + e.getFullYear();

  dOut.textContent = s;
  hOut.textContent = u;
  mOut.textContent = a;
  sOut.textContent = r;
  ampmOut.textContent = t;
}

update();
window.setInterval(update, 1000);