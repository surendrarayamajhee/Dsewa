<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                         <h3 class="pay-inline">Payment List</h3>
                         <div id="filterpayment" class="collapse">
                             <ul class="nav order-nav">
                                 <li class="nav-item">
                                     <label for="">select Account</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">Select</option>
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                     </select>
                                 </li>
                                 <li class="nav-item">
                                     <label for="">Select Users</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">Select</option>
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                     </select>
                                 </li>
                                 <li>
                                     <label for="">Search by Sender or Receiver:</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">search</option>
                                         <option value="Sender">By Sender</option>
                                         <option value="Receiver">By Reeciver</option>
                                     </select>
                                 </li>
                                 <li id="btn" class="nav-item">
                                     <button class="btn btn-primary">filter</button>
                                 </li>
                                  <li class="nav-item">
                                     <button class="btn btn-primary">Reset</button>
                                 </li>
                             </ul>
                         </div>
                        </div>
                        <div id="paymentmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade bd-example-modal-lg">
                            <div role="document"  class="modal-dialog modal-lg">
                                 <form>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 id="exampleModalLable" class="modal-title">payment</h5>
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="Close">
                                                <span aria-hidden="true">*</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="">select Account:</label>
                                                                <select class="form-control">
                                                                    <option value disabled="disabled">select</option>
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">select Account:</label>
                                                                <select class="form-control">
                                                                    <option value disabled="disabled">select</option>
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="jtitle">Payment Type</label>
                                                        <br>
                                                        <label class="radio-inline">
                                                            <input type="radio" value="cash" name="optradio" checked="checked">
                                                            "cash
                                                            
                                                                   "
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" value="Bank Transfer">
                                                            "Bank Transfer
                                                            
                                                                   "
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Description</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Sender Name</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Receiver Name</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="damt">payment Amount:</label>
                                                        <input type="number" id="damt" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for>Orders:</label>
                                                        <select multiple="multiple" id="phone-number" name="order_id" class="form-control">
                                                            <option value disabled="disabled">select</option>
                                                        
                                                        <!--  -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="udv">Upload Payment Voucher:</label>
                                                        <input type="file" id="udv" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for>Outstanding Payment:</label>
                                                        <input type="number" disabled="disabled" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <input type="date" id="date" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>                                  
                                   </div>
                                 </form>
                            </div>
                        </div>
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>payment Type</th>
                                        <th>Amount</th>
                                        <th>Sender Name</th>
                                        <th>Receiver Name</th>
                                        <th>Image</th>
                                        <th>Order</th>
                                        <th>Approvied</th>
                                        <th>Created</th>
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
</template>
<style>
.table-bordered{
    border: 1px solid #dee2e6;
}
.table{
    width: 100%;
    margin-bottom: 1rem;
    color:#212529;
}
table{
     border-collapse: collapse;
}
.pay-inline{
    display: inline-block;
    margin-left: 368px;
}
</style>