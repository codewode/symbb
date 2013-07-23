window.addEvent('domready', function() {
    
    size1          = $(window).getSize();
    size2          = $('nav').getSize();
    
    $('listContainer').style('height', (size1.y - size2.y)+'px');
    
    var myRequest = new Request({
        url: Routing.generate('_eajaxcrud_list', { configFileName: 'forum', bundleName:'SymBBCoreForumBundle' }),
        method: 'get',
        evalScripts: true,
        onSuccess: function(responseText){
            $('listContainer').set('html', responseText);
        },
        onFailure: function(){
            
        }
    }).send();
});