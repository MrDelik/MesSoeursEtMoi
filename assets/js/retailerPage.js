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
        if( button.dataset.pa === 'size' ){
            valueContainer.textContent = button.dataset.value;
        }
        else if( button.dataset.pa === 'color' ){
            valueContainer.style.backgroundColor = button.firstElementChild.style.backgroundColor;
            valueContainer.textContent = button.dataset.value;

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
                colorName: product.getColorName(),
                colorCode : product.getColorCode(),
                size : product.getSize(),
                qty : product.getQty(),
                unitprice : product.getUnitPrice(),
                totalprice: product.calculatePrice()
            }
        );

        let productToStringify = {};
        productToStringify[product.getId()] = {};
        productToStringify[product.getId()][product.getColorName()+'-'+product.getSize()] = {
            qty: product.getQty(),
            price: product.getUnitPrice(),
            colorCode : product.getColorCode()
        };

        if( CookieManager.exist('retailerProducts') ){
            let existingCookie = JSON.parse(CookieManager.get('retailerProducts'));
            let colorSize = product.getColorName()+'-'+product.getSize();

            if( product.getId() in existingCookie ){
                existingCookie[product.getId()][colorSize] = productToStringify[product.getId()][colorSize];
            }
            else{
                existingCookie[product.getId()] = productToStringify[product.getId()];
            }

            productToStringify = existingCookie;
        }

        /* age set at one week from now */
        CookieManager.set(
            'retailerProducts',
            JSON.stringify(productToStringify),
            '/',
            '',
            60 * 60 * 24 * 7
        );

        /* Show validation button */
        saveOrderButton.showButton();
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
        elem.querySelector('.product-color').style.backgroundColor = infos.colorCode;
        elem.querySelector('.product-color').textContent = infos.colorName;
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
            nameSelector: '.nasa-show-one-line a',
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
    getName(){
        return this.row.querySelector(this.params.nameSelector).textContent.trim();
    }
    getQty(){
        return this.row.querySelector( this.params.qtySelector ).value;
    }
    getUnitPrice(){
        return this.row.querySelector( this.params.unitPriceSelector ).textContent.trim();
    }
    getColorName(){
        return this.row.querySelector( this.params.colorSelector ).textContent.trim();
    }
    getColorCode(){
        return this.row.querySelector( this.params.colorSelector ).style.backgroundColor;
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
    static set( name, value, path = '/', domain = '', maxAge = '', secure = '', sameSite = ''){
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

class SaveOrderButton{
    constructor(params = {}){
        this.params = {
            selector: '.save-retailer-order',
            buttonContainerSelector: '.save-retailer-order-container',
            onClass: 'show',
            copyCheckboxSelector: '.receive-copy'
        };

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }

        let button = document.querySelector(this.params.selector);
        if( button !== null ){
            button.addEventListener('click', this.saveOrder);
        }
    }

    showButton(){
        document.querySelector(this.params.buttonContainerSelector).classList.add( this.params.onClass );
    }

    hideButton(){
        document.querySelector(this.params.buttonContainerSelector).classList.remove( this.params.onClass );
    }

    saveOrder( e ){
        e.preventDefault();

        let fd = new FormData();
        fd.append('action', 'save_retailer_order');
        fd.append('_ajax_nonce', this.dataset.sec);
        fd.append('selectedBillingAddress', document.querySelector('#billingAddressSelect').value);

        let copyCheckbox = document.getElementById( 'receiveOrderCopy' );
        if( copyCheckbox.checked ){
            fd.append('getCopy', copyCheckbox.value);
        }

        jQuery.ajax({
            type: 'POST',
            url:'/wp-admin/admin-ajax.php',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(xhrObj, settings){
                LoaderIconManager.show( '#retailerOrderLoader' );
            },
            success: function( data, status, jqXhr ){
                Swal.fire({
                    icon: 'success',
                    title : data.message,
                    timer: 1500
                });
            },
            error:function( data, jqXhr, errorThrown ){
                Swal.fire({
                    icon: 'error',
                    title : data.message
                });
            },
            complete: function(){
                LoaderIconManager.hide( '#retailerOrderLoader' );
            }
        });
    }
}

/**
 * Handle the modal showing and hiding
 */
class ModalHandler{
    constructor( params = {} ){
        this.params = {
            selector: '.modal-toggler',
            modalSelector: '.modal',
            backgroundSelector: '.modal-background',
            closeModalSelector: '.modal-close',
            onClass: 'on',
            scrollLockClass: 'scroll-locked'
        };

        document.querySelectorAll( this.params.selector ).forEach(button => button.addEventListener('click', e => this.show(e)));
        document.querySelectorAll( this.params.backgroundSelector ).forEach( background => background.addEventListener('click', e => {
            this.hide( e.target.closest(this.params.backgroundSelector).getAttribute('id') );
        }));

        document.querySelectorAll( this.params.closeModalSelector ).forEach(button => button.addEventListener('click', e => {
            this.hide( e.target.closest(this.params.backgroundSelector).getAttribute('id') );
        }));

        document.querySelectorAll( this.params.modalSelector ).forEach(modal => modal.addEventListener('click', e => e.stopPropagation()));
    }

    show( e ){
        document.body.classList.add(this.params.scrollLockClass);
        let modalId = e.target.closest(this.params.selector).dataset.target;
        let modal = document.getElementById( modalId );
        modal.style.display = 'block';
        setTimeout(() => {
            modal.classList.add( this.params.onClass );
        }, 100);
    }

    hide( modalId ){
        document.body.classList.remove(this.params.scrollLockClass);
        let modal = document.getElementById( modalId );
        modal.addEventListener('transitionend', function(){
            modal.style.display = '';
        }, {once: true});

        modal.classList.remove( this.params.onClass );
    }
}

class AddressObserver{
    constructor( params = {} ){
        this.params = {
            selector : '#billingAddressSelect',
            addressSelector: '.address',
            selectedClass: 'selected-address'
        };

        let addressSelect = document.querySelector(this.params.selector);
        if( addressSelect !== null ){
            addressSelect.addEventListener('change', e => this.toggleAddress(e));
        }
    }

    toggleAddress(e){
        let selectedAddress = document.querySelector(this.params.addressSelector+'.'+this.params.selectedClass);
        if( selectedAddress !== null ){
            selectedAddress.classList.remove(this.params.selectedClass);
        }

        document.getElementById(e.target.value).classList.add(this.params.selectedClass);
    }

    getSelectedAddress(){
        return document.querySelector(this.params.addressSelector+'.'+this.params.selectedClass).innerHTML;
    }
}

class StepsSwitcher{
    constructor(params = {}){
        this.params = {
            buttonContainerSelector : '.steps-button-container',
            stepSwitcherSelector: '.step-switcher',
            activeClass: 'step-active'
        };

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }

        document.querySelectorAll(this.params.stepSwitcherSelector).forEach(button => button.addEventListener('click', e => this.switchStep(e)));
    }

    switchStep(e){
        e.preventDefault();
        let button = e.target.closest(this.params.stepSwitcherSelector);

        if( 'trigger' in button.dataset && button.dataset.trigger === 'orderRecap' ){
            recapManager.createRecap();
        }

        let targetedStep = button.dataset.target;

        document.querySelectorAll('.'+this.params.activeClass).forEach(step => step.classList.remove(this.params.activeClass));
        document.querySelectorAll('.'+targetedStep).forEach(step => step.classList.add(this.params.activeClass));
    }
}

/**
 * Class that manage the retailer order recap.
 * Allow to build it and get informations quickly
 */
class OrderRecapManager{
    constructor(params = {}){
        this.params = {
            containerSelector: '.orderRecapContainer',
            templateSelector: '#recapRowTemplate',
            totalPriceContainerSelector: '.totalPriceRecap',
            shippingAddressSelector: '.billing-address.address-container'
        };

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }
    }

    createRecap(){
        if( CookieManager.exist('retailerProducts') ){
            let order = JSON.parse( CookieManager.get('retailerProducts') );
            let orderDocFrag = document.createDocumentFragment();

            let total = 0;
            for(let productID in order){
                let product = new Product({}, document.querySelector('[data-product-id="'+productID+'"]'));

                for(let colorSize in order[productID]){
                    let [color, size] = colorSize.split('-');
                    let detailRow = document.querySelector(this.params.templateSelector).content.cloneNode(true);

                    let totalRow = order[productID][colorSize].qty * order[productID][colorSize].price;

                    detailRow.querySelector('.productInfos').textContent = product.getName() + ' - ' + size + ' - ' + color;
                    detailRow.querySelector('.productQuantity').textContent = order[productID][colorSize].qty;
                    detailRow.querySelector('.productPrice .value-container').textContent = order[productID][colorSize].price;
                    detailRow.querySelector('.productTotal .value-container').textContent = totalRow;

                    total += totalRow;

                    orderDocFrag.appendChild( detailRow );
                }
            }

            let orderContainer = document.querySelector(this.params.containerSelector);
            while( orderContainer.firstElementChild !== null ){
                orderContainer.firstElementChild.remove();
            }

            this.setTotalPrice(total);
            this.setBillingAddress( addressObserver.getSelectedAddress() );
            orderContainer.appendChild(orderDocFrag);
        }
    }

    setTotalPrice( total ){
        document.querySelector(this.params.totalPriceContainerSelector).textContent = total;
    }

    setBillingAddress( address ){
        document.querySelector( this.params.shippingAddressSelector ).innerHTML = address;
    }
}

/**
 * Static class that allow to show and hide a loader icon
 */
class LoaderIconManager{
    static show( loaderSelector ){
        document.querySelector(loaderSelector).style.display = '';
    }

    static hide( loaderSelector ){
        document.querySelector(loaderSelector).style.display = 'none';
    }
}

/**
 * Select Replacement
 * Select replacement is a simple component to be able to design a selectbox
 * while maintaining the normal API for smartphones
 * To work properly, the select node must be a child of the specified selector (.select-replacement by default)
 */
class SelectReplacement{
    constructor( params = {} ){
        this.params = {
            selector: '.select-replacement',
            optionsContainerSelector: '.select-replacement-options',
            optionSelector: '.select-replacement-option',
            openClass: 'open',
            visibleOptions: 5
        };

        for(let param in params){
            if( param in this.params ){
                this.params[param] = params[param];
            }
        }

        document.querySelectorAll( this.params.selector+' select' ).forEach(( selectNode ) => {
            selectNode.selectReplacementInst = this;
            selectNode.addEventListener('mousedown', this.open);
        });

        document.querySelectorAll( this.params.optionsContainerSelector ).forEach(( optionsContainer ) => {
            optionsContainer.addEventListener('mousedown', (e) => e.stopImmediatePropagation());
            optionsContainer.addEventListener('click', this.selectOption);
        });
    }

    open( e ) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if(  e.which === 1  ){
            let parent = this.parentNode;

            if (parent.classList.contains(this.selectReplacementInst.params.openClass)) {
                this.selectReplacementInst.close( parent.querySelector( this.selectReplacementInst.params.optionsContainerSelector ), this.selectReplacementInst );
            }
            else {
                parent.classList.add(this.selectReplacementInst.params.openClass);
                let visibleOptions = this.selectReplacementInst.params.visibleOptions > this.options.length ? this.options.length : this.selectReplacementInst.params.visibleOptions;
                let option = parent.querySelector(this.selectReplacementInst.params.optionSelector);
                let optionHeight = 0;

                if (option !== null) {
                    optionHeight = option.offsetHeight;
                } else {
                    throw new Error('No option in the select-replacement');
                }
                let heightToGo = visibleOptions * optionHeight;

                let openOptions = parent.querySelector(this.selectReplacementInst.params.optionsContainerSelector);
                openOptions.style.height = heightToGo + 'px';
                document.body.addEventListener('mousedown', () => {
                    this.selectReplacementInst.close(openOptions, this.selectReplacementInst)
                }, {once: true});
            }
        }
    }

    selectOption( e ){
        this.previousElementSibling.value = e.target.dataset.value;
        this.previousElementSibling.dispatchEvent(new Event('change'));
        this.previousElementSibling.selectReplacementInst.close( this,  this.previousElementSibling.selectReplacementInst);
    }

    close( nodeToClose, inst ){
        nodeToClose.style.height = '0';
        nodeToClose.closest( inst.params.selector ).classList.remove( inst.params.openClass );
    }
}

/* Set the list mode */
let cookieName = document.querySelector( 'input[name="nasa_archive_grid_view"]' );
if( cookieName !== null ){
    cookieName = cookieName.value;

    if( !CookieManager.exist(cookieName) || CookieManager.get(cookieName) !== 'list' ){
        CookieManager.set( cookieName, 'list', '/', '', 60 * 60 * 24 * 7 );

        document.querySelector('.nasa-content-page-products > ul').className = 'products large-block-grid-3 small-block-grid-1 medium-block-grid-2 list';
    }
}

let priceUpdater = new PriceUpdater();
let customObserver = new CustomObserver();
let quantityObserver = new QuantityObserver();
let retailSaver = new RetailSaver();
let productDetailsManager = new ProductDetails();
let saveOrderButton = new SaveOrderButton();
let modalHandler = new ModalHandler();
let addressObserver = new AddressObserver();
new StepsSwitcher();
let recapManager = new OrderRecapManager();
new SelectReplacement();
