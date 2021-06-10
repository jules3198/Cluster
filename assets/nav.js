const $ = require('jquery');

import './resources/blk/blk.css';

require('./resources/blk/blk.min');
import './styles/nav.scss';


$(window).on("load", function () {
    $(window).on('scroll',function (){
            if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
                document.getElementById("navigation").style.padding = "30px 10px";
                document.getElementById("logo").style.fontSize = "25px";
            } else {
                document.getElementById("navigation").style.padding = "80px 10px";
                document.getElementById("logo").style.fontSize = "40px";
            }
    })
});
