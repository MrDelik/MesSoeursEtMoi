class CustomObserver{
    constructor( params = {} ){
        this.params = {
            selector: '.nasa-attr-ux-item',
            activeClass: 'nasa-active',
            valueContainerSelectorPart: '.selected-',
            parentSelector: '.product-warp-item'
        };

        /* hydration */
        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }

        document.querySelectorAll(this.params.selector).forEach( button => {
            if( button.classList.contains(this.params.activeClass) ){
                this.toggleInformation(button);
            }

            button.addEventListener('click', e => {
                e.preventDefault();

                this.toggleInformation(this.getButton(e.target));
            });
        });
    }

    toggleInformation(button){
        let valueContainer = this.getParent(button).querySelector(this.params.valueContainerSelectorPart + button.dataset.pa);
        valueContainer.textContent = button.dataset.value;

        if( button.dataset.pa === 'color' ){
            priceUpdater.updatePrice(
                this.getParent(button),
                button.dataset.value
            );
        }
    }

    getButton( target ){
        return target.closest(this.params.selector);
    }

    getParent( node ){
        return node.closest(this.params.parentSelector);
    }
}


class PriceUpdater{
    constructor( params = {} ){
        this.params = {
            selector: '.retailer-price-container',
            priceContainer: '.nasa-product-content-variable-warp',
        };

        /* hydration */
        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }
    }

    getPrice( row, selectedColor ){
        let jsonObjsArr = JSON.parse(row.querySelector(this.params.priceContainer).dataset.product_variations);

        let price;
        let objNbrs = jsonObjsArr.length;
        for(let i = 0; i < objNbrs; i++){
            let value = jsonObjsArr[i];

            if(value.attributes.attribute_pa_color === selectedColor){
                price = value.retailer_price;
                break;
            }
        }

        return price;
    }

    updatePrice(row, selectedColor){
        row.querySelector(this.params.selector).textContent = this.getPrice( row, selectedColor );
    }
}

class QuantityObserver{
    constructor( params = {} ){
        this.params = {
            quantityFieldSelector: '.quantity',
            buttonSelector: '.save-retailer',
            parentSelector: '.product-warp-item'
        };

        document.querySelectorAll(this.params.quantityFieldSelector).forEach(input => {
            input.addEventListener('input', e => this.quantityObserver(e));
        });
    }

    quantityObserver(e){
        if( e.target.value > 0 ){
            this.activateButton( e.target.closest(this.params.parentSelector) );
        }
        else{
            this.deactivateButton( e.target.closest(this.params.parentSelector) );
        }
    }

    activateButton( row ){
        row.querySelector(this.params.buttonSelector).disabled = false;
    }

    deactivateButton( row ){
        row.querySelector(this.params.buttonSelector).disabled = true;
    }
}

class RetailSaver{
    constructor( params = {} ){
        this.params = {
            buttonSelector: '.save-retailer',
        };

        /* hydration */
        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }

        document.querySelectorAll(this.params.buttonSelector).forEach(button => {
            button.addEventListener('click', this.save);
        });
    }

    save( e ){
        e.preventDefault();

        let product = new Product({}, customObserver.getParent(e.target));
        productDetailsManager.addDetails(
            customObserver.getParent(e.target),
            {
                id: product.getId(),
                color : product.getColor(),
                size : product.getSize(),
                qty : product.getQty(),
                unitprice : product.getUnitPrice(),
                totalprice: product.calculatePrice()
            }
        );

        let productToStringify = {};
        productToStringify[product.getId()] = {};
        productToStringify[product.getId()][product.getColor()+'-'+product.getSize()] = {
            qty: product.getQty(),
            price: product.getUnitPrice()
        };

        if( CookieManager.exist('retailerProducts') ){
            let existingCookie = JSON.parse(CookieManager.get('retailerProducts'));
            let colorSize = product.getColor()+'-'+product.getSize();

            if( product.getId() in existingCookie ){
                existingCookie[product.getId()][colorSize]  =  productToStringify[product.getId()][colorSize];
            }
            else{
                existingCookie[product.getId()] = productToStringify[product.getId()];
            }

            productToStringify = existingCookie;
        }

        CookieManager.set('retailerProducts', JSON.stringify(productToStringify));
    }
}


class ProductDetails{
    constructor( params = {} ){
        this.params = {
            detailsSelector: '.retailer-product-recap',
            templateSelector: '#productDetailsTemplate'
        };

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }
    }

    /**
     * Add the details to the product detail list and register them
     * @param row
     * @param productDetails
     */
    addDetails( row, productDetails ){
        let selector = productDetails.color+'-'+productDetails.size+'-'+productDetails.id;
        let detailsContainer = row.querySelector('#'+selector);

        if(  detailsContainer !== null ){
            this.applyDetails(detailsContainer, productDetails);
        }
        else{
            detailsContainer = document.querySelector(this.params.templateSelector).cloneNode(true);
            this.applyDetails( detailsContainer.content, productDetails );
            detailsContainer.content.firstElementChild.id  = selector;
            row.querySelector(this.params.detailsSelector).appendChild(detailsContainer.content);
        }
    }

    /**
     * Apply the product details to the selected element
     * @param elem
     * @param infos
     * @returns {*}
     */
    applyDetails(elem, infos){
        elem.querySelector('.product-color').style.backgroundColor = infos.color;
        elem.querySelector('.product-size').textContent = infos.size;
        elem.querySelector('.product-Qty').textContent = infos.qty;
        elem.querySelector('.product-unit-price').textContent = infos.unitprice;
        elem.querySelector('.product-total-price').textContent = infos.totalprice;

        return elem;
    }
}

/**
 * General class to simplify the informations recuperations
 */
class Product{
    constructor( params = {}, row = undefined ){
        this.params = {
            idSelector: '[data-product-id]',
            qtySelector: '.quantity',
            unitPriceSelector: '.retailer-price-container',
            colorSelector: '.selected-color',
            sizeSelector: '.selected-size'
        };

        this._row = row;

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }
    }

    set row( row ){
        this._row = row;
    }
    get row(){
        return this._row;
    }

    getId(){
        return this.row.querySelector(this.params.idSelector).dataset.productId;
    }
    getQty(){
        return this.row.querySelector( this.params.qtySelector ).value;
    }
    getUnitPrice(){
        return this.row.querySelector( this.params.unitPriceSelector ).textContent.trim();
    }
    getColor(){
        return this.row.querySelector( this.params.colorSelector ).textContent.trim();
    }
    getSize(){
        return this.row.querySelector( this.params.sizeSelector ).textContent.trim();
    }
    calculatePrice(){
        let qty = parseInt(this.getQty());
        let unitPrice = parseFloat(this.getUnitPrice());

        return qty * unitPrice;
    }
}

/**
 * Static class to simplify the use of the cookie with JavaScript
 */
class CookieManager{
    /**
     * Get  the value of an existing cookie
     * @param name
     * @returns {string}
     */
    static get( name ){
        let cookies = document.cookie.split('; ');
        let index = cookies.findIndex(cookie => cookie.indexOf(name+'=') !== -1);
        if( index !== -1 ){
            return cookies[index].split('=')[1];
        }
        else{
            throw new Error('Cookie does not exist');
        }
    }

    /**
     * Create a new cookie
     * @param name
     * @param value
     * @param path
     * @param domain
     * @param maxAge
     * @param secure
     * @param sameSite
     */
    static set( name, value, path = '', domain = '', maxAge = '', secure = '', sameSite = ''){
        let cookieStr =  name+'='+value;

        if( path !== '' ){
            cookieStr += ' ;path='+path;
        }
        if( domain !== '' ){
            cookieStr += ' ;domain='+domain;
        }
        if( maxAge !== '' ){
            cookieStr += ' ;max-age='+maxAge;
        }
        if( secure !== '' ){
            cookieStr += ' ;secure='+secure;
        }
        if( sameSite !== '' ){
            cookieStr += ' ;same-site='+sameSite;
        }

        document.cookie = cookieStr;
    }

    /**
     * Check if a coooki is registered
     * Keep in mind that if the cookie was set with the HTTP flag, you don't have access to it with javascript
     * @param name
     * @returns {boolean}
     */
    static exist( name = '' ){
        return document.cookie.split('; ').some( cookie => cookie.indexOf(name+'=') !== -1);
    }

    /**
     * Add a value to an existing cookie.
     * Create the cookie if it does not exist
     * @param name
     * @param value
     */
    static add( name, value ){
        if( this.exist(name) ){
            document.cookie = this.get(name) + value;
        }
        else{
            this.set(name, value);
        }
    }
}


let priceUpdater = new PriceUpdater();
let customObserver = new CustomObserver();
let quantityObserver = new QuantityObserver();
let retailSaver = new RetailSaver();
let productDetailsManager = new ProductDetails();
