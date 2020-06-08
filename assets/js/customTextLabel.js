
window.addEventListener("DOMContentLoaded", (event) => {
    var retailerText = document.querySelectorAll('.nasa-list-category');
    retailerText.forEach(function(e){
        let links = e.querySelectorAll('a');
        links.forEach(function(link){
            if(link.innerHTML == 'Retailers') {
                link.style.display = "none";
            }
        });
    });
        
    var mainCatFilter = document.querySelector('.cat-item-retailers');
    var mainCatVoirTout = document.querySelector('.cat-item-voir-tout');
    mainCatFilter.style.display = "none";
    mainCatVoirTout.style.display = "none";
    
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