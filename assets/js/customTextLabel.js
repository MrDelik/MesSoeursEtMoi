
window.addEventListener("DOMContentLoaded", (event) => {
    
    var colorBullets = document.querySelectorAll('.nasa-product-content-nasa_color-wrap > div');
    colorBullets.forEach(function(e){
        let colors = e.querySelectorAll('a');
        let colorNum = '';
        colors.forEach(function(color){
            color.style.width = "0px";
            color.style.height = "0px";
            color.style.overflow = "hidden";
            color.style.opacity = "0";
        });

        if(colors.length < 2){
            colorNum = '<div class="customTxtLabel"><strong>' + colors.length + '</strong>' +  '&nbspcolor</div>';
        }
        else {
            colorNum = '<div class="customTxtLabel"><strong>' + colors.length + '</strong>' +  '&nbspcolors</div>';
        }

        let checkCustomTxtLabel =  e.querySelector('.customTxtLabel');
        if(checkCustomTxtLabel) {
            checkCustomTxtLabel.innerHTML = colorNum;
        }
        else {
            e.innerHTML += colorNum;
        }

    });
});

