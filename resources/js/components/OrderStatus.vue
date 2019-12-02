<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <center>
                                <h3>Create Exchange Order</h3>
                            </center>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="card-body">
                                <form form>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="order-id">Orders</label>
                                            <input name="order_id" class="form-control">
                                            <div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">Status</label>
                                            <select name="status_id" class="form-control">
                                            <option value disabled="disabled">select</option>
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">product</label>
                                            <select multiple="multiple" name="product_type" class="form-control">
                                                <option value disabled="disabled">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">Comment</label>
                                            <input type="text" list="comment_id" placeholder="Add comment" class="form-control" style="width:76%;">
                                            <datalist id="comment_id"></datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <button class=" btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div> -->

         <div class="row"> 
            <div class="col-md-3">             
                <fieldset class="scheduler-border" style="height: 101%;width: 108%;">
                  <legend class="w-auto">Customer info</legend>
                  <div class="dowm" style="margin-left:85%;">
                <button type="submit" class="btn btn-danger" style=" background-color: #ec1313;">X</button>
                </div>
                <br>
                  <table style="width: 100%;">
                      <tr>
                          <th>Name</th>
                     </tr>
                     <tr>
                          <td>Abhay Sharma</td>
                      </tr>
                      <br>
                      <tr>
                          <th>Contact</th>
                     </tr>
                     <tr>
                          <td>9851225629</td>
                    </tr>
                        <tr>
                          <td>9851225679</td>
                      </tr>
                      <br>
                      <tr>
                        <th>Address</th>
                      <tr>
                        <td>Address 1</td>
                    </tr>
                    <tr>
                        <td> Address 1</td>
                      </tr>
                  </table>
                </fieldset>
            </div>
            <div class="col-md-5">             
                <fieldset class="scheduler-border" style="height: 87%;width: 105%;">
                  <legend class="w-auto">Return</legend>
                    <div class="row" style="margin:-2%;">
                        <input type="text" class="form-control" placeholder="Order Number">
                            <br>
                            <br>
                        <div style="width: 100%;margin-bottom: 2%;">
                        <textarea class="address" rows="3" placeholder="Address Description" style="min-height:135px;border-radius:5px;"></textarea>
                        </div>
                        <br><br>
                        <input type="text" class="form-control" placeholder="New COD Amount">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Comment">
                    </div>
                </fieldset>
            </div>
            <div class="col-md-4">             
                <fieldset class="scheduler-border" style="height: 101%;width: 100%;">
                  <legend class="w-auto">Order Info</legend>
                 <table style="width: 100%;">
                      <tr >
                          <td>Handling</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>Non-Fragile</td>
                     </tr>
                     <tr>
                          <td>Payment<br>Type</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>Cash On Delivery</td>
                      </tr>
                      <tr>
                          <td>COD<br>Amount</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <th>Rs 1498.00</th>
                     </tr>
                     <tr>
                          <td>Delivery Fee</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>Rs 0.00</td>
                     </tr>
                     <tr>
                          <td>Weight</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>1 KG</td>
                     </tr>
                     <tr>
                          <td>Product<br>Type</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>1. Wind Cheater
                              <br>2. Vest
                          </td>
                     </tr>
                     <tr>
                          <td>Description</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>Navy Blue<br>Navy Blue</td>
                     </tr>
                  </table>
                </fieldset>
            </div>
        </div>
            <div class="down" style="margin-left:36%; margin-top:-3%">
                <button type="submit" class="btn btn-primary">Submit</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" style=" background-color: #ec1313;">Cancel</button>
            </div>
        
    </div>
 </div>
</div>
    </div>
    </div>
</template>
<style scoped>
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
	width: 80%;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
            
}

legend.scheduler-border {
    font-size: 16px !important;
    font-weight: bold !important;
    text-align: left !important;
    
}
legend {
    display: block;
    width: 100%;
    max-width: 100%;
    padding: 0;
    margin-bottom: .5rem;
    font-size: 18px;
    line-height: inherit;
    color: inherit;
    white-space: normal;
}
.address {
    border-radius: 1%;
    box-shadow: none;
    width: -webkit-fill-available;
}
.textarea {
    overflow: auto;
    resize: vertical;
    margin-top: 3%;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
</style>