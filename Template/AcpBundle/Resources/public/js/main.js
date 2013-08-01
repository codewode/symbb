var SymBBUtil = new Class({
    goTo: function(url){
        window.location.href=url;
        return false;
    },
    saveSortable: function(sort, saveUrl){
        var myRequest = new Request({
            url: saveUrl,
            data: {entries: sort.serialize()}, 
            onSuccess: function(responseText){
            },
            onFailure: function(e){
                console.debug(e);
            }
        });
        myRequest.post();
    }
});

var SymBBUtil = new SymBBUtil();
