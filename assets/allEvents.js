const $ = require('jquery');

import './resources/blk/blk.css';

require('./resources/blk/blk.min');
import './styles/allEvents.scss';


 /*function myFunction() {
    var dots = document.getElementById("dots");
    var moreText = document.getElementById("more");
    var btnText = document.getElementById("myBtn");

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        btnText.innerHTML = "Read more";
        moreText.style.display = "none";
    } else {
        dots.style.display = "none";
        btnText.innerHTML = "Read less";
        moreText.style.display = "inline";
    }
}*/


$(window).on("load", function () {
    if($('#hideDiv').length){
        setTimeout(function() { $("#hideDiv").fadeOut(1500); }, 5000)
    }
});