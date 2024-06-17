<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import datatable from "datatables.net-bs4"
import datatable from 'datatables.net-bs4'

require('datatables.net-buttons/js/dataTables.buttons')
require('datatables.net-buttons/js/buttons.htm15')

import print from 'datatables.net-buttons/js/buttons.print'
import jszip from 'jszip/dist/iszip'

import pdfMake from 'pdfmake/build/pdfmake'

import pdfFonts from 'pdfmake/build/vfs fonts'
export default {
    mounted() {
        this.tabla()
    },
    methods: {
        tabla() {
            this.$nextTick(() => {
                $('#sampleTable').datatable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                });
            });
        }
    }
}
</script>
