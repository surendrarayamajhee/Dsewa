<template>
    <div class="content-body">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Order List</h4>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="datatables_tables-0_Length">
                                                        <label>
                                                            Show 
                                                            <select name="datatables_tables_0_Length" aria-controls="datatables_tables_0" class="form-control form-control-sm">
                                                                <option value="10">10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                            </select>
                                                            
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div id="DataTables_Tables_0_filter" class="datatables_filter" align="right">
                                                        <label>
                                                           <!-- <h5 align="left">Search</h5> -->
                                                            <input type="search" placeholder="Search..." class="form-control form-control-sm"  aria-controls="DataTables_table_0">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>                                                                     
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-striped table-bordered zero-configuration">
                                                        <thead>
                                                            <tr>
                                                                <th>SN</th>
                                                                <th>Order Id</th>
                                                                <th>Created Date</th>
                                                                <th>Receiver</th>
                                                                <th>COD</th>
                                                                <th>Branch</th>
                                                                <th>Delivery Charge</th>
                                                                <th>Status</th>
                                                                <th>Last Comment</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Tiger Nixon</td>
                                                                <td>System Architect</td>
                                                                <td>Edinburgh</td>
                                                                <td>61</td>
                                                                <td>2011/04/25</td>
                                                                <td>$320,800</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>                                                                  
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-5">
                                                    <div class="dataTables-info" id="DataTables_table_0_info" role="status" aria-live="polite">
                                                        Showing 1 to 10 of 57 entries
                                                    </div>
                                                </div>
                                                    <div class="footer">
                                                        <ul role="menubar" aria-disabled="false" aria-label="Pagination" class="pagination mt-4 b-pagination justify-content-center" pages="">
                                                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                                                <span role="menuitem" aria-label="Go to first page" aria-disabled="true" class="page-link">⏮</span>
                                                            </li>
                                                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                                                <span role="menuitem" aria-label="Go to previous page" aria-disabled="true" class="page-link">⏪</span>
                                                            </li>
                                                            <li role="presentation" class="page-item active">
                                                                <a role="menuitemradio" aria-label="Go to page 1" aria-checked="true" aria-posinset="1" aria-setsize="1" tabindex="0" target="_self" href="#" class="page-link">1</a>
                                                                </li>
                                                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                                                <span role="menuitem" aria-label="Go to next page" aria-disabled="true" class="page-link">⏩</span>
                                                            </li>
                                                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                                                <span role="menuitem" aria-label="Go to last page" aria-disabled="true" class="page-link">⏭</span>
                                                            </li>
                                                        </ul>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        
    </div>            
</template>
<style scoped>
.content-body {
    margin-left: 15.1875rem;
    z-index: 0;
}
.container-fluid {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
}
.card {
    margin-bottom: 30px;
    border: 0px;
    border-radius: 0.625rem;
    box-shadow: 6px 11px 41px -28px #a99de7;
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.25rem;
}
.card .card-body {
    padding: 1.88rem 1.81rem;
}
.card-body {
    flex: 1 1 auto;
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: auto;
    padding: 1.25rem;
}
.card-title {
    font-size: 18px;
    font-weight: 500;
    line-height: 18px;
}
.card-title {
    margin-bottom: 0.75rem;
}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.content-body .container-fluid {
    padding: 15px 30px 0;
}
.container-fluid {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.dataTables_length {
    margin-top: 10px;
}
.dataTables_length {
    display: inline-block;
}
div.dataTables_wrapper div.dataTables_length select {
    width: 75px;
    display: inline-block;
}
.dataTables_length select {
    background-color: transparent;
    background-position: center bottom, center calc(100% - 1px);
    background-repeat: no-repeat;
    background-size: 0 2px, 100% 1px;
    border: 0 none;
    padding-bottom: 5px;
    transition: background 0s ease-out 0s;
}
.form-control-sm {
    min-height: 36px;
}
.form-control {
    border-radius: 0;
    box-shadow: none;
    height: 45px;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.dataTables_filter {
    float: right;
    margin-top: 10px;
}
div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
}
.container {
    max-width: 100%;
}
.pagination {
    display: -webkit-box;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    margin: 10px 213px;
}
.justify-content-center {
    -webkit-box-pack: center !important;
    justify-content: center !important;
}
.mt-4, .my-4 {
    margin-top: 1.5rem !important;
}
.footer {
    padding-left: 15%;
    background-color: #fff;
}
</style>

