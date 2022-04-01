<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <center>
                                <h3>Order Details</h3>
                            </center>
                        </div>
                        <div class="row">            
                        <fieldset class="scheduler-border" style="width: 110%;">
                        <legend class="w-auto">Receiver</legend>
                        <div class="col">  
                            <table style="width: 40%;">
                                <tr>
                                    <td>Name</td>
                                    <td>Abhay Sharma</td>
                                </tr>
                                <tr>
                                    <td>Contact</td>
                                    <td>9841553384,9841553384</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>Address 1<br>Address 2</td>
                                </tr>
                            </table>
                        <table style="border: 1px solid black;width: 20%;height: 110px;margin-left: 41%;margin-top: -11%;">
                            <tr style="text-align:center;">
                                <th>Order #</th>
                            </tr> 
                            <tr style="text-align:center">
                               <td style="padding:6%;font-size:40px">417</td>
                            </tr>
                        </table>
                        <table style="width: 40%;margin-left:62%;margin-top:-11%;">
                                <tr>
                                    <th>Created Date</th>
                                    <th>30th Nov 2019 12:00:00 am </th>
                                </tr>
                                <tr>
                                    <td>Vendor Order #</td>
                                    <td>61</td>
                                </tr>
                                <tr>
                                    <td>Tracking</td>
                                    <td>2019sgdsfsfadhga</td>
                                </tr>
                                <tr>
                                    <td>Expected Date</td>
                                    <td>27th Nov 2019 12:00:00 am</td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                 </div>
            <div class="row">
            <div class="col-md-4">   
                <fieldset class="scheduler-border" style="height: 108%;width: 110%;">
                  <legend class="w-auto">Order Attributes</legend>
                  <div class="ex1">
                    <table style="width: 100%;">
                      <tr >
                          <td>Handling</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>{{ detail.handling }}</td>
                     </tr>
                     <tr>
                          <td>Payment<br>Type</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>{{ detail.payment_type }}</td>
                      </tr>
                      <tr>
                          <td>COD<br>Amount</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <th>{{ detail.cod }}</th>
                     </tr>
                     <tr>
                          <td>Delivery Fee</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>{{ detail.hubCharge }}</td>
                     </tr>
                     <tr>
                          <td>Weight</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>{{ detail.weight }}</td>
                     </tr>
                     <tr>
                          <td>Product<br>Type</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <ol>
                              <li v-for="(type,i) in detail.product_type" :key="i">{{ type }}</li>
                            </ol>
                     </tr>
                     <tr>
                          <td>Description</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>{{ detail.order_description}}</td>
                     </tr>
                  </table>
                    </div>
                </fieldset>          
            </div>
            <div class="col-md-4">             
                <fieldset class="scheduler-border" style="height: 92%;width: 110%;">
                  <legend class="w-auto">Comment</legend> 
                     <div class="ex3">
                       <ol class="card card-body scroll order-comment">
                          <li
                           v-for="comment in comments" 
                           :key="comment.id"
                           >{{ comment.user_name }}->{{ comment.comment }}
                          </li>
                        </ol>
                     </div>
                       <input type="text" 
                       class="form-control" 
                       placeholder="Add a Comment" 
                       name="comment"
                       style="width:85%;"
                       list="comment_id"
                        id="comment-input"
                        :class="{ 'is-invalid': comment.errors.has('comment') }"

                       />
                       <button 
                       id="submit-btn" 
                       class="btn btn-success" 
                       style="margin-left:87%;margin-top:-22%;"
                       @click="storeOrderComment(details.id)"
                       >Add</button>                    
                </fieldset>
                </div>
            </div>
            <div class="col-md-4">  
                <fieldset class="scheduler-border" style="height: 42%;width: 100%;">
                    <legend class="w-auto">Sender</legend>
                    <div class="ex2">
                    <table style="width:100%;">
                        <tr>
                            <th>Name</th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <th>Easy Shop</th>
                        </tr>
                       <tr>
                          <td>Contact</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>9841553383<br>9841553383</td>
                     </tr>
                       <tr>
                          <td>Address</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <td>Address 1<br>Address 2</td>
                     </tr>
                    </table>
                    </div>
                </fieldset>          
                <fieldset class="scheduler-border" style="height: 50%;width: 100%;">
                  <legend class="w-auto">Log</legend>
                    <div class="ex2">
                     <table style="width:100%;">
                        <tr>
                            <td>Order Created At</td>
                        </tr>
                        <tr>
                            <th>2019 November 25 1:00:00</th>
                        </tr>
                        <tr>
                            <td>Received By Kathmandu at</td>
                        </tr>
                        <tr>
                            <th>2019 November 25 1:00:00</th>
                        </tr>
                        <tr>
                            <td>Shipped From Kathmandu To Nepalgunj in</td>
                        </tr>
                        <tr>
                            <th>2019 November 25 1:00:00</th>
                        </tr>
                    </table>   
                    </div>
                 
                </fieldset>
            </div>
            <table style="border: 1px solid black;width: 33%;margin-left:35%;margin-top:-25px;">
                <tr style="text-align:center;font-size: 40px;font-weight:bold;">
                    Branch Name
                </tr>
            </table>
           <div class="down" style="margin-left:74%; margin-top:-5%">
                <button type="submit" class="btn btn-primary">Update</button>
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
.content-body .container-fluid {
    padding: 0px 30px 0;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
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
div.ex1 {
  background-color:#f8f9fa;
  width: 108%;
  height: 96%;
  overflow: scroll;
}
div.ex2 {
  background-color: #f8f9fa;
  width: 108%;
  height: 93%;
  overflow: scroll;
}
div.ex3 {
  background-color: #f8f9fa;
  width: 107%;
  height: 86%;
  overflow: scroll;
}
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
</style>