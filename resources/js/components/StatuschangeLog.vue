<template>
<div class="content-body">
    <div class="container-fluid mt-3">
    <div class="row judtify-content-center">
        <div class="container">
            <div class="card-body">
                <div class="card-header">
                    <center>
                        <h3>Status change Log</h3>
                    </center>
                </div>  
                <div class="col-md-12">
                    <div class="card-body" align="center">
                        <table class="table table-bordered col-md-6 table-style-vendor-status">
                            <thead>
                                <tr>
                                    <th class="__web-inspecttor-hide-shortcut__">
                                        "
                                        S.N
                                        
                                        "</th>
                                    <th style="width:20%;">Order Id</th>
                                    <th style="width;30%;">product</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
    </div>
</div>
</div>
</template>
<style>
.col-md-12{
flex: 0 0 100%;
max-width: 100%;
}
.container{
    max-width: 95%;
}
.table-bordered{
    border: 1px solid #dee2e6;
}
.table{
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.col-md-6{
    flex: 0 0 50%;
    width: 50%;
}
</style>