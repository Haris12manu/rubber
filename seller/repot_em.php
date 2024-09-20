<style>
    /* ปรับแต่งสำหรับการ์ด */
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        padding: 20px;
    }

    /* ปรับแต่งหัวข้อของการ์ด */
    .card-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    /* ปรับแต่งข้อความในตาราง */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    /* ปรับแต่งแถบสถานะในตาราง */
    .badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 12px;
    }

    /* ปรับแต่งสีแถบสถานะตามระดับความสำเร็จ */
    .badge.bg-success {
        background-color: #28a745;
    }

    .badge.bg-success.high {
        background-color: #218838;
    }

    .badge.bg-success.medium {
        background-color: #17a2b8;
    }

    .badge.bg-success.low {
        background-color: #ffc107;
        color: #212529;
    }

    /* ปรับแต่งสำหรับการจัดวางส่วนต่างๆ ของ DataTable */
    .datatable-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .datatable-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .datatable-input {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px 10px;
        width: 200px;
    }

    .datatable-selector {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px 10px;
    }

    /* ปรับแต่ง Pagination */
    .datatable-pagination-list {
        display: flex;
        list-style: none;
        padding-left: 0;
    }

    .datatable-pagination-list-item {
        margin: 0 5px;
    }

    .datatable-pagination-list-item button {
        background-color: #007bff;
        border: none;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .datatable-pagination-list-item button:hover {
        background-color: #0056b3;
    }

    .datatable-pagination-list-item.datatable-active button {
        background-color: #0069d9;
    }

    .datatable-pagination-list-item.datatable-disabled button {
        background-color: #ddd;
        cursor: not-allowed;
    }
</style>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datatables</h5>
                    <p>Add lightweight datatables to your project using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add the <code>.datatable</code> class name to any table you wish to convert to a datatable. Check out <a href="https://fiduswriter.github.io/simple-datatables/demos/" target="_blank">more examples</a>.</p>

                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th data-sortable="true" style="width: 26%;" aria-sort="ascending" class="datatable-ascending">
                                        <button class="datatable-sorter">Name</button>
                                    </th>
                                    <th data-sortable="true" style="width: 10%;">
                                        <button class="datatable-sorter">Ext.</button>
                                    </th>
                                    <th data-sortable="true" style="width: 25%;">
                                        <button class="datatable-sorter">City</button>
                                    </th>
                                    <th data-sortable="true" style="width: 18%;">
                                        <button class="datatable-sorter">Start Date</button>
                                    </th>
                                    <th data-sortable="true" style="width: 19%;" aria-sort="ascending" class="datatable-ascending">
                                        <button class="datatable-sorter">Completion</button>
                                    </th>
                                </tr>


                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>9958</td>
                                    <td>Curicó</td>
                                    <td>2005/02/11</td>
                                    <td><span class="badge bg-success">37%</span></td>
                                </tr>
                                <tr>
                                    <td>Theodore Duran</td>
                                    <td>8971</td>
                                    <td>Dhanbad</td>
                                    <td>1999/04/07</td>
                                    <td><span class="badge bg-success high">97%</span></td>
                                </tr>
                                <tr>
                                    <td>Kylie Bishop</td>
                                    <td>3147</td>
                                    <td>Norman</td>
                                    <td>2005/09/08</td>
                                    <td><span class="badge bg-success medium">63%</span></td>
                                </tr>
                                <tr>
                                    <td>Willow Gilliam</td>
                                    <td>3497</td>
                                    <td>Amqui</td>
                                    <td>2009/11/29</td>
                                    <td><span class="badge bg-success low">30%</span></td>
                                </tr>
                                <tr>
                                    <td>Blossom Dickerson</td>
                                    <td>5018</td>
                                    <td>Kempten</td>
                                    <td>2006/09/11</td>
                                    <td><span class="badge bg-success low">17%</span></td>
                                </tr>
                                <tr>
                                    <td>Elliott Snyder</td>
                                    <td>3925</td>
                                    <td>Enines</td>
                                    <td>2006/08/03</td>
                                    <td><span class="badge bg-success medium">57%</span></td>
                                </tr>
                                <tr>
                                    <td>Castor Pugh</td>
                                    <td>9488</td>
                                    <td>Neath</td>
                                    <td>2014/12/23</td>
                                    <td><span class="badge bg-success high">93%</span></td>
                                </tr>
                                <tr>
                                    <td>Pearl Carlson</td>
                                    <td>6231</td>
                                    <td>Cobourg</td>
                                    <td>2014/08/31</td>
                                    <td><span class="badge bg-success high">100%</span></td>
                                </tr>
                                <tr>
                                    <td>Deirdre Bridges</td>
                                    <td>1579</td>
                                    <td>Eberswalde-Finow</td>
                                    <td>2014/08/26</td>
                                    <td><span class="badge bg-success medium">44%</span></td>
                                </tr>
                                <tr>
                                    <td>Daniel Baldwin</td>
                                    <td>6095</td>
                                    <td>Moircy</td>
                                    <td>2000/01/11</td>
                                    <td><span class="badge bg-success low">33%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Simple DataTables library -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dataTable = new simpleDatatables.DataTable(".datatable", {
            perPageSelect: [5, 10, 15, 25],
            perPage: 5,
            labels: {
                placeholder: "Search...",
                perPage: "Show {select} entries",
                noRows: "No entries found",
                info: "Showing {start} to {end} of {rows} entries",
                prev: "‹",
                next: "›"
            },
            layout: {
                top: "{select}{search}",
                bottom: "{info}{pager}"
            }
        });
    });
</script>


<!-- Include Simple DataTables library -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dataTable = new simpleDatatables.DataTable(".datatable", {
            perPageSelect: [5, 10, 15, 25],
            perPage: 5,
            labels: {
                placeholder: "Search...",
                perPage: "Show {select} entries",
                noRows: "No entries found",
                info: "Showing {start} to {end} of {rows} entries",
                prev: "‹",
                next: "›"
            },
            layout: {
                top: "{select}{search}",
                bottom: "{info}{pager}"
            }
        });
    });
</script>