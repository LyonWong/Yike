/**
 * Author: LyonWong
 * Date: 2017-06-07
 */
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        search: "Filter"
    },
    paging: false,
    lengthMenu: [20],
    lengthChange:false,
    info: false,
    order: [[0, 'desc']],
    columnDefs: [
        {orderSequence: ["desc", "asc"], targets: '_all'},
        {type: 'numeric', orderDataType: ['dom-data'], targets: 'dom-data'}
    ]
});
