<?= view::css([
    'resource' => [
        'bootstrap' => [
            'datepicker/css/datepicker',
            'daterangepicker/daterangepicker-bs3',
            'select/bootstrap-select',
            'toastr/toastr',
        ],
        'plugins' => [
            'select2/select2',
            'datatables/plugins/bootstrap/dataTables.bootstrap',
        ],
        'metronic/css/plugins',
    ],
    'style',
]); ?>
<?= view::js([
    'resource' => [
        'bootstrap' => [
            'js/bootstrap',
            'datepicker/js/bootstrap-datepicker',
            'daterangepicker' => ['moment.min', 'daterangepicker'],
            'toastr/toastr.min',
            'editable' => [
                'js/bootstrap-editable',
                'inputs-ext' => [
                    'address/address',
                    'wysihtml5/wysihtml5'
                ]
            ],
            'select/bootstrap-select',
        ],
        'metronic' => [
            'admin' => [
                'layout/scripts' => ['layout', 'quick-sidebar'],
                'pages/scripts' => ['components-pickers']
            ],
            'scripts' => ['metronic']
        ],
        'highcharts' => ['highcharts', 'plot'],
        'plugins' => [
            'uniform/jquery.uniform',
            'select2/select2',            
            'bootbox/bootbox.min',
            'datatables' => [
                'media/js/jquery.dataTables.min',
                'plugins/bootstrap/dataTables.bootstrap',
            ],
        ],
        'vue' => [
            'vue','vue-ext'
        ]
    ],
    'init',
]) ?>
<script type="text/javascript">
    $(document).ready(function () {
        Metronic.init();
        Layout.init();
        QuickSidebar.init();
        ComponentsPickers.init();
    });
</script>
