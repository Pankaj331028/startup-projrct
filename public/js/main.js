$(document).ready(function () {
    window._token = $('meta[name="csrf-token"]').attr('content')

    moment.updateLocale('en', {
        week: {dow: 1} // Monday is the first day of the week
    })

    $('.date').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'en',
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
        }
    })

    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        locale: 'en',
        sideBySide: true,
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
        }
    })

    $('.timepicker').datetimepicker({
        format: 'HH:mm:ss',
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
        }
    })

    $('.select-all').click(function () {
        let $select2 = $(this).parent().siblings('.select2')

        $select2.find('option').prop('selected', 'selected')
        $select2.trigger('change')
    })

    $('.deselect-all').click(function () {
        let $select2 = $(this).parent().siblings('.select2')

        $select2.find('option').prop('selected', '')
        $select2.trigger('change')
    })

    $('.select2').select2()

    $('.treeview').each(function () {
        var shouldExpand = false

        $(this).find('li').each(function () {
            if ($(this).hasClass('active')) {
                shouldExpand = true
            }
        })

        if (shouldExpand) {
            $(this).addClass('active')
        }
    })

    $('a[data-widget^="pushmenu"]').click(function () {
        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 350);
    })
})

function selectAllOptionOrOtherOptions(options) {
    let optionsArray = options.split(',');
    if(optionsArray.indexOf('all') > -1) {
        return ['all', 999999];
    } else {
        return optionsArray;
    }
}

function ajaxError(errors) {
    // create alert messages
    let errorArray = [];
    $.each(errors, function(key,value) {
        errorArray.push("- "+value);
    });

    // show alert
    Swal.fire({
        title: 'Error',
        html: errorArray.join("<br />"),
        icon: "error"
    })
}

function generateQueryString(requestParameters) {
    let requestString = '';
    for(let i = 0 ; i < requestParameters.length ; i++) {
        if(i == 0) {
            requestString += '?'+requestParameters[i];
        } else {
            requestString += '&'+requestParameters[i];
        }
    }

    return requestString;
}
