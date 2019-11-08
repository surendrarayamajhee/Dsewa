<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
               <div class="container">
                  <div class="card-bady">
                      <div class="card-header">
                          <div class="row">
                              <ul class="nav order-nav">
                                  <li class="nav-item">
                                      <h2>Packet List</h2>
                                  </li>
                                  <li>
                                      <div class="form-group col-md-12">
                                          <select id="phone-number" name="order_pickup_point"  class="form-control">
                                              <option value disabled="disabled">Select PickUp Location</option>
                                              <option value="16">Kathmandu Swapanita Sapkota</option>
                                              <option value="17">Kathmandu Suren R.M</option>
                                          </select>
                                      </div>
                                  </li>
                                  <li class="nav-item">
                                      <a data-toggle="tooltip" title="Click Here To Request Pickup Service" class="btn btn-outline-success btn-sm">
                                          
                                          Request
                                          
                                          <span class></span>
                                      </a>
                                      <span class="tooltiptext"></span>
                                  </li>
                                  <li class="nav-item">
                                      <div class="form-group">
                                          <select type="number" placeholder="pagination" class="form-control" style="float: right;">
                                              <option  value selected="selected">select</option>
                                              <option value="10">10</option>
                                              <option value="25">25</option>
                                              <option value="50">50</option>
                                          </select>
                                      </div>
                                  </li>
                                  <li>
                                      <button class="btn btn-success">Delete All</button>
                                  </li>
                              </ul>
                          </div>
                          <p>*Mark checkbox to select packets to request pickup service</p>
                      </div>
                      <div class="card">
                          <div class="row">
                              <div class="table- resposive-md pickup-table">
                                  <table class="table table-bordered">
                                      <thead>
                                          <tr>
                                          <th><input type="checkbox">
                                          </th>
                                          <th>Customer Name</th>
                                          <th>Discription</th>
                                          <th>Handling</th>
                                          <th style="width: 26%">Product type</th>
                                          <th>COD</th>
                                          <th>Wt/Unit</th>
                                          <th>Action</th>
                                          </tr>
                                      </thead> 
                                      <tbody></tbody>    
                                  </table>
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
    </div>
</template>
<style>
#phone-number{
    border-radius: 4px;
}
.nav-item{
    position: relative;
    padding-left: 11px;
}
.form-group{
    border-radius: 4px;
}
.form-control{
    border-radius: 6px;
}
li{
    position: relative;
    padding-left: 11px;
}
</style>