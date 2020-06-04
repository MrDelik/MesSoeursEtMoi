class ShopTilter{
    constructor(params = {}){
        this.params = {
            buttonSelector: '.nasa-change-layout'
        };

        for(let param in params){
            if(param in this.params){
                this.params[param] = params[param];
            }
        }

        document.querySelectorAll(this.params.buttonSelector).forEach(button => button.addEventListener('click', e => this.tilt(e)));
    }

    tilt(e){
        e.preventDefault();

        let scrollY = window.scrollY;
        window.scrollTo(0, scrollY + 1);
        window.scrollTo(0, scrollY - 1);
    }
}

new ShopTilter();