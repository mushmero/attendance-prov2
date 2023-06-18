$(document).ready(function() {

    $.ajax({
        url: "/auth/check",
        datatype: 'json',
        success: function(data){
            if(data == 1){
                disabledLink();
                hideMenu();
            }
        }
    });
    // remove aler on 3000ms
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

    setInterval(refreshTime, 1000);
});

function disabledLink()
{
    $('*').each(function() {
        if ($(this).hasClass('disabled')) {
            $(this).on('click', function(e){
                e.preventDefault();
            });
        }
    });
}

function hideMenu(){
    $('*').each(function() {
        let hideCount = 0;
        if ($(this).hasClass('has-treeview')) {
            var submenuCount = $(this).children().next('.nav-treeview').children().length;
            var submenu = $(this).children().next('.nav-treeview').children();
            for(let i = 0; i < submenuCount; i++){
                if($(submenu[i]).hasClass('hide')){
                    hideCount++;
                }
            }
            if(submenuCount == hideCount){
                $(this).find('.nav-link').addClass('hide');
            }
        }
    });
}

function refreshTime() {
    var formattedString = new moment().local('Asia/Kuala_Lumpur').format('DD/MM/YYYY hh:mm:ss A');
    $('#time').text(formattedString);
}