<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="container">
                <div class="row">
                    <div id="pt" class="col-md-4">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="order-inline">Add Product Type</h3>
                                </div>
                                <div class=" card-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="mobile2">Product Type</label>
                                            <input type="text" placeholder="Add Product Type" name="name" class="form-control">
                                            <!--  -->
                                        </div>
                                        <div>
                                            <button class="btn btn-primary">
                                                "Submit
                                                         "
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h2>Product Type</h2>
                                    </div>
                                    <div style="display: inline-block;">
                                        <form>
                                            <button class="btn btn-primary">Delete</button>
                                        </form>
                                    </div>
                                    <div style="display:inline-block">
                                        <input type="text" placeholder="Search.." name="name" class="form-control">
                                    </div>
                                    <div style="display:inline-block">
                                        <button class="btn btn-primary">Search</button>
                                    </div>
                                    <div style="display:inline-block">
                                        <button class="btn btn-primary">Reset</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox">
                                                    " select all"
                                                </th>
                                                <th>Product Type</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="1">
                                                    "   
                                                                  1 
                                                      "
                                                </td>
                                                <td>
                                                    test
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="84">
                                                    "   
                                                                  2 
                                                      "
                                                </td>
                                                <td>
                                                    show
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="85">
                                                    "   
                                                                  3 
                                                      "
                                                </td>
                                                <td>
                                                    tshirt
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="86">
                                                    "   
                                                                  4 
                                                      "
                                                </td>
                                                <td>
                                                    glass
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="87">
                                                    "   
                                                                  5
                                                      "
                                                </td>
                                                <td>
                                                    shoes
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" number value="164">
                                                    "   
                                                                  6
                                                      "
                                                </td>
                                                <td>
                                                    document
                                                </td>
                                                <td>
                                                    <i class="fas fa-edit pointer"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                  <div id="pickuporder" tabindex="1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-label="true" class="modal fade bd-example-modal-lg">
                      
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
<style>
.table-bordered th, .table-bordered td {
    border: 1px solid #dee2e6;
}
.table th, .table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}
.container {
    max-width: 95%;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
#pt {
    margin-top: 10px;
}
.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
}
.col {
    flex-basis: 0;
    flex-grow: 1;
    max-width: 100%;
}
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
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
.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.card-body {
    flex: 1 1 auto;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.fa, .fas {
    font-weight: 900;
}
.fa, .far, .fas {
    font-family: "Font Awesome 5 Free";
}
.fa, .fab, .fal, .far, .fas {
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}
.pointer {
    cursor: pointer;
}
.mt-4, .my-4 {
    margin-top: 1.5rem !important;
}
.justify-content-center {
    justify-content: center !important;
}
.pagination {
    display: -webkit-box;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    margin: 10px 213px;
}
</style>