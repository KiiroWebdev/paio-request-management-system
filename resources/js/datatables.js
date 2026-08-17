import DataTable from 'datatables.net-bs5';

// Responsive
import 'datatables.net-responsive-bs5';

// Buttons
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import 'datatables.net-buttons/js/buttons.colVis';

// Dependencies
import JSZip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

window.JSZip = JSZip;

pdfMake.vfs = pdfFonts.vfs;

document.addEventListener('DOMContentLoaded', () => {

    const table = document.querySelector('#requestsTable');

    if (!table) {
        return;
    }

    new DataTable(table, {

        responsive: true,

        pageLength: 10,

        lengthMenu: [10, 25, 50, 100],

        order: [[0, 'desc']],

        layout: {

            topStart: {

                buttons: [

                    'copy',

                    'excel',

                    'pdf',

                    'print',

                    'colvis'

                ]

            }

        },

        language: {

    search: "Search:",

    lengthMenu: "Show _MENU_ entries",

    info: "Showing _START_ to _END_ of _TOTAL_ requests",

    emptyTable: "No requests found",

    zeroRecords: "No matching requests found",

    paginate: {

        previous: "Previous",

        next: "Next"

    }

}

    });

});