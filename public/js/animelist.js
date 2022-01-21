$(function(){
    let table = $('#table').DataTable({
        'paging': false,
        'info': false,
        'columns': [
            { 'width': '32px', 'searchable': false, 'orderable': false },
            { 'width': '68px', 'searchable': false, 'orderable': false },
            { 'width': '418px' },
            { 'width': '92px' },
            { 'width': '112px', 'type': 'premiered' },
            { 'width': '112px' },
            { 'width': '112px' },
            { 'width': '72px', 'type': 'priority' },
        ],
        'order': [[ 2, 'asc' ]]
    });

    table.on('order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    $.fn.dataTable.ext.type.order['premiered-pre'] = (data) => {
        let seasons = ['Winter', 'Spring', 'Summer', 'Fall'];

        let split = $(data).html().split(' ');

        return split[1]+' '+seasons.indexOf(split[0]);
    };

    $.fn.dataTable.ext.type.order['priority-pre'] = (data) => {
        let priorities = ['Low', 'Medium', 'High'];

        return priorities.indexOf(data);
    };
});