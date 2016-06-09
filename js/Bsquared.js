/**
 * Created by Aaron Young on 6/8/2016.
 */

var BSQUARED = BSQUARED || {};

BSQUARED.Main = (function(){
   var year = new Date().getFullYear();


    
    return {
        init: function (){
            var footerYear = $('#footerYear').html('2014-'+ year);
            function activeFunction() {
                var currentPage = document.getElementById('homePageActive');
                currentPage.className += ' active';
            }
        }
    }
})();

$(document).ready(function(){
   BSQUARED.Main.init(); 
});