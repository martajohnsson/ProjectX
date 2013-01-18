$(document).ready(function () {
    (function() {
        if (typeof window.janrain !== 'object') window.janrain = {};
        window.janrain.settings = {};
        
        janrain.settings.tokenUrl = 'http://127.0.0.1:8088/?page=login';
    
        function isReady() { janrain.ready = true; };
        if (document.addEventListener) {
          document.addEventListener("DOMContentLoaded", isReady, false);
        } else {
          window.attachEvent('onload', isReady);
        }
    
        var e = document.createElement('script');
        e.type = 'text/javascript';
        e.id = 'janrainAuthWidget';
    
        if (document.location.protocol === 'https:') {
          e.src = 'https://rpxnow.com/js/lib/snippt/engage.js';
        } else {
          e.src = 'http://widget-cdn.rpxnow.com/js/lib/snippt/engage.js';
        }
    
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(e, s);
    })();
});