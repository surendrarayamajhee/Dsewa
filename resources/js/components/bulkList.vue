<template>
    <div class="content-body">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <ul class="nav order-nav">
                                <li class="nav-item">
                                    <h2>Bulk List</h2>
                                </li>
                            </ul>
                        </div>
                        <br>
                        <div class="card">
                            <div class=" row">
                                <div class="table table-responsive-md pickup-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox">
                                                    <button class="btn btn-outline-danger btn-xs" style="border:1px solid black;">
                                                        <span  class></span>
                                                        <i class="fas fa-trash-alt">
                                                            Del
                                                        </i>
                                                    </button>
                                                </th>
                                                <th>User</th>
                                                <th>Discription</th>
                                                <th>Handling</th>
                                                <th>Product Type</th>
                                                <th>Expected Date</th>
                                                <th>COD</th>
                                                <th>Weight/unit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="card-footer">
                                        <ul class="nav order-nav">
                                            <li class="nav-item" style="float: left;">
                                                <h3>Create Packets</h3>
                                            </li>
                                            <li class="nav-item" style="float:right;">
                                                <form>
                                                    <button class="btn btn-outline-primary btn-sm" style="border:1px solid black;">
                                                        <!-- <span class></span> -->
                                                        <!-- <i class="fas fa-paper-plane">
                                                            
                                                        </i> -->                                                       
                                                        Create Packets                                                       
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                  <ul role="menubar" aria-disabled="false" aria-label="pagination" class=" pagination mt-4 b-pagination justify-content-center" pages>
                      <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to first page" aria-disabled="true" class="page-link">First-page</span>
                      </li>
                      <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to previous page" aria-disabled="true" class="page-link">prev</span>
                      </li>
                      <li role="none presentation" class="page-item">
                          <span role="menubar" aria-label="Go to page 1" aria-checked="false" aria-posinset="1" aria-setsize="1" tabindex="0" target="_self" href="#" class="page-link">page no.1</span>
                      </li>
                       <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to next page" aria-disabled="true" class="page-link">next</span>
                      </li>
                       <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to last page" aria-disabled="true" class="page-link">last-page</span>
                      </li>
                  </ul>
                  <div id="pickuporder" tabindex="1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-label="true" class="modal fade bd-example-modal-lg">
                      
               
               
                    </div>
                </div>
            </div>
    </div>
</template>
<style>
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid black;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>