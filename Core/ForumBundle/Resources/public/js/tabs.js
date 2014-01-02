$(document).ready(function() {
    
    var activeTab = '';
    var lastActivTab; 
    $('.symbb_tab').each(function(key, tab){
        var tabKey = $(tab).data('tab');
        if($(tab).parent().hasClass('active')){
            activeTab = tabKey;
            lastActivTab = tab;
        }
        $(tab).parent().click(function() {
            symBBToggleTab(tabKey);
            if(lastActivTab){
                $(lastActivTab).parent().removeClass('active');
            }
            $(tab).parent().addClass('active');
            lastActivTab = tab;
        });
    });
    
    symBBToggleTab(activeTab);
    
    function symBBToggleTab(currentKey){
        $('.symbb_tabcontent').each(function(keyContent, content){
            var tabContentKey = $(content).data('tab');
            if(currentKey !== tabContentKey){
                $(content).hide();
            } else {
                $(content).show();
            }
            $(content).find('.alert-danger').each(function(key, element){
                $('.symbb_tab').each(function(key, tab){
                    var tabKey = $(tab).data('tab');
                    if(tabKey === tabContentKey){
                        $(tab).addClass('alert');
                        $(tab).addClass('alert-danger');
                    }
                });
            });
        });
    }
    
});