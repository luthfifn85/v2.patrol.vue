import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons-bs5";
import "datatables.net-fixedcolumns";

export default function createDtOptions(moreConfig = {}) {
    const isSmallDevice = window.matchMedia("(max-width: 768px)").matches;

    return {
        ordering: false,
        dom: '<"row justify-between g-2"<"col-7 col-sm-4 text-start"f><"col-5 col-sm-8 text-start"<"datatable-filter"<"d-flex justify-content-end g-2"Bl>>>><"datatable-wrap mb-1"t><"row align-items-center mt-3"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-start text-md-end"i>>',
        lengthMenu: [10, 25, 50, 100],
        lengthInfo: false,
        pageLength: 10,
        lengthChange: {
            label: "",
            title: "",
        },
        scrollX: true,
        pagingType: "simple_numbers",
        responsive: false,
        language: {
            search: "",
            searchPlaceholder: "Type in to Search",
            lengthMenu: "_MENU_",
            info: "_START_ - _END_ of _TOTAL_",
            infoEmpty: "0",
            infoFiltered: "( Total _MAX_  )",
            paginate: {
                next: "Next",
                previous: "Prev",
            },
        },
        buttons: [
            {
                extend: "copyHtml5",
            },
            {
                extend: "csvHtml5",
            },
            {
                extend: "excelHtml5",
            },
            {
                extend: "pdfHtml5",
                text: "Export PDF",
                customize: function (doc) {
                    doc.defaultStyle = {
                        font: "Roboto",
                    };
                },
            },
        ],
        fixedColumns: isSmallDevice
            ? { rightColumns: 1 }
            : { rightColumns: 0, leftColumns: 1 },
        ...moreConfig,
    };
}
