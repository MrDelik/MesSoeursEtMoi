window.addEventListener("DOMContentLoaded", (event) => {
    var MyButtonHtml = '<button id="setRetailerPrice" style="background:royalblue;padding:0.25rem 0.5rem;color:white;border-radius:0.5rem;margin:0.5rem;border:none;">Appliquer le prix retailer pour tous les produits</button>';
    var MyDom = document.querySelector('#alg_wc_price_by_user_role_per_product .price_by_roles_display');
    var MyDomHTML = MyDom.innerHTML;
    function setRetailerPrice(){
        var MyRetailerPrice = prompt("Indiquez les prix pour les retailers");
        if(MyRetailerPrice != '') {
            MyRetailerPrice = MyRetailerPrice;
        }
        var myRetailersInput = document.querySelectorAll('.price_by_roles_display .wc_input_price');
        myRetailersInput.forEach(function(input){
            var checkClass = input.id.includes("regular_price_retailer", 0);
            if(checkClass) {
                input.value = MyRetailerPrice;
            }
        });
    }
    MyDomHTML = MyButtonHtml + MyDomHTML ;  
    MyDom.insertAdjacentHTML('afterbegin' , MyButtonHtml);
    var myRetailerPriceBtn = document.getElementById('setRetailerPrice');
    myRetailerPriceBtn.addEventListener('click' , setRetailerPrice);
});
