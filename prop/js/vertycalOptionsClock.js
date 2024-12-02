var $dOut = jQuery("#date"),
    $hOut = jQuery("#hours"),
    $mOut = jQuery("#minutes"),
    $sOut = jQuery("#seconds"),
    $ampmOut = jQuery("#ampm"),
    months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

function update() {
    var e = new Date,
        t = e.getHours() < 12 ? "AM" : "PM",
        u = 0 == e.getHours() ? 12 : e.getHours() > 12 ? e.getHours() - 12 : e.getHours(),
        a = e.getMinutes() < 10 ? "0" + e.getMinutes() : e.getMinutes(),
        r = e.getSeconds() < 10 ? "0" + e.getSeconds() : e.getSeconds(),
        s = days[e.getDay()] + ", " + months[e.getMonth()] + " " + e.getDate() + ", " + e.getFullYear();
    $dOut.text(s), $hOut.text(u), $mOut.text(a), $sOut.text(r), $ampmOut.text(t)
}
update(), window.setInterval(update, 1e3);