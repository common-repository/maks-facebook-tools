/*
Author:        Maksuel Boni
Author URI:    https://maks.com.br
Creation Date: 24/12/2017
Description:   Replace default comments

Version:       0.0.3
Last Modified: 24/12/2017
*/

class maksFacebookTools {

    constructor($) {

        this.respondForm = $('div#comments').find('div#respond').find('form');
        this.shortlink = $('link[rel="shortlink"]').get(0);
        this.config = $();

        if(this.respondForm.length == 0) {

            throw new Error('Respond Form missing');
            
        } else if( typeof(this.shortlink) == 'undefined' ) {
    
            throw new Error('Shortlink missing');
    
        } else {
    
            this.respondForm.replaceWith(
                '<div class="fb-comments" data-href="' + this.shortlink.href + '" data-width="100%" data-numposts="10"></div>'
            );
        }
    }   
}

new maksFacebookTools(jQuery);