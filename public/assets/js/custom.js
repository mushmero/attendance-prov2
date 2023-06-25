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
                attendanceCount();
                lineChartData();
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

function attendanceCount(){
    var options = ['Today','Weekly','Monthly','Yearly'];
    options.forEach(option => {
        var formdata = new FormData();
        formdata.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formdata.append('option', option);
    
        $.ajax({
            url: 'home/data',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType : false,
            success: function(resp){
                if(resp){
                    generateNormalCounter(resp.result.normal, option);
                    generateLateCounter(resp.result.late, option);
                }
            }
        });
    });
}

function generateNormalCounter(total, option){
    const updateCount = () => {
        const speed = 200;
        const target = parseInt(total);
        const count = parseInt($('#'+option.toLowerCase()+'-normal').text());
        const increment = target / speed;
    
        if (count <= target) {
            $('#'+option.toLowerCase()+'-normal').text(Math.ceil(count+increment));
            setTimeout(updateCount, 1);
        } else {
            $('#'+option.toLowerCase()+'-normal').text(formatNumber(target,','));
        }
    }
    updateCount();
}

function generateLateCounter(total, option){
    const updateCount = () => {
        const speed = 200;
        const target = parseInt(total);
        const count = parseInt($('#'+option.toLowerCase()+'-late').text());
        const increment = target / speed;
    
        if (count <= target) {
            $('#'+option.toLowerCase()+'-late').text(Math.ceil(count+increment));
            setTimeout(updateCount, 1);
        } else {
            $('#'+option.toLowerCase()+'-late').text(formatNumber(target,','));
        }
    }
    updateCount();
}

function formatNumber(x, seperator) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, seperator);
}

function lineChartData(){
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
    
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('filter', filter);
    
        $('#filter').on('change', function(e){
            if($(e.currentTarget).val() != 'Custom'){
                $('.customDate').hide();
                $('#fromDate').val('');
                $('#toDate').val('');    
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('filter', $(e.currentTarget).val());
                processAjax(formData);
            }else if($(e.currentTarget).val() == 'Custom'){
                $('.customDate').show();
            }
        });

        if(filter == 'Custom'){
            $('.customDate').show();
            if($('#fromDate').val() != '' && $('#toDate').val() != ''){    
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('filter', filter);
                formData.append('fromDate', $('#fromDate').val());
                formData.append('toDate', $('#toDate').val());
            }
        }
        processAjax(formData);
    }
}

function processAjax(formData){
    $.ajax({
        url : 'home/chartdata',
        method : 'POST',
        data : formData,
        processData: false,
        contentType : false,
        success: function(resp){
            if(resp.status){
                generateLineChart(resp);
            }
        },
    });
}

var chart = null;
function generateLineChart(resp){
    const labels = resp.result.label;
    const dataset = resp.result.data;
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Normal',
                data: dataset.map(row => row.Normal),
                fill: false,
                borderColor: 'rgb(40,167,69)',
                backgroundColor: 'rgb(40,167,69)',
                tension: 0.4
            },
            {
                label: 'Late',
                data: dataset.map(row => row.Late),
                fill: false,
                borderColor: 'rgb(220,53,69)',
                backgroundColor: 'rgb(220,53,69)',
                tension: 0.4
            },
        ]
    };
    const options = {
        maintainAspectRatio: false,        
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                },
            },
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
        plugins: {
            tooltip: {
                enabled: true,
                position: 'nearest',
            },
        },
    };
    const config = {
        type: 'line',
        data: data,
        options: options,
    };

    var canvas = $('#statistic');
    if(chart){
        chart.clear();
        chart.destroy();
    }
    chart = new Chart(canvas, config);
}