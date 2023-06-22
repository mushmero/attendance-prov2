$(document).ready(function() {

    $.ajax({
        url: "/auth/check",
        datatype: 'json',
        success: function(data){
            if(data == 1){
                disabledLink();
                hideMenu();
                defaultDateRangeFilter();
                exportData();
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

function defaultDateRangeFilter(){
    var filter = $('#filter').val();
    if($('.date').length > 0){
        $('.date').datepicker({
            format : 'yyyy-mm-dd',
            maxDate : '+0d',
            endDate : '+0d',
            autoclose: true,
            toggleActive: true,
            orientation: 'bottom',
        });
    
        $('#filter').on('change', function(){
            $('#fromDate').val('');
            $('#toDate').val('');
            $('#attendanceForm').submit();
        });
    
        if(filter != 'Custom'){
            $('.customDate').hide();
            $('#fromDate').val('');
            $('#toDate').val('');
        }else{
            $('.customDate').show();
            $('.filterdate').on('change', function(){
                if($('#fromDate').val() != '' && $('#toDate').val() != ''){
                    $('#attendanceForm').submit();
                }
            });
        }

    }
}

function exportData(){
    $('#reportExport').on('click', function(){    
        var filter = $('#filter').val();
        var dateFrom = $('#fromDate').val();
        var dateTo = $('#toDate').val();

        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('filter', filter);
        formData.append('fromDate', dateFrom);
        formData.append('toDate', dateTo);

        $.ajax({
            url : 'reports/export',
            method : 'POST',
            data : formData,
            processData: false,
            contentType : false,
            cache: false,
            xhrFields:{
                responseType: 'blob'
            },
            success: function(result, status, xhr){
                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /filename=([^"]*)/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'Report Attendance.xlsx');
        
                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.ms-excel'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;        
                document.body.appendChild(link);        
                link.click();
                document.body.removeChild(link);
            },
            error : function(xhr){
                var contentType = xhr.getResponseHeader('content-type');
                if(contentType == 'application/json'){
                    Swal.fire('Error', 'No content found. Unable to export', 'error');
                }
            }
        });

    });
}