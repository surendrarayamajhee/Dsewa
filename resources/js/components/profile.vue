<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
        <div class=" row justufy-content-center">
            <div class=" container">
                <div class="card">
                    <!--  for header  -->
                    <div class="card-header">
                        <h4>
                             <center>Edit Profile</center>
                         </h4>
                    </div>
                    <!-- for body parts -->
                    <div class="card-body">
                        <div class="row"> 
            <div class="col-md-5">             
                <fieldset class="scheduler-border" style="height: 110%;width: 104%;">
                  <legend class="w-auto">Profile</legend>
                  <table style="width: 100%;">
                      <tr>
                          <td>Company Name</td>
                     </tr>
                     <tr>
                          <td>ABC Store</td>
                      </tr>
                      <tr>
                          <td>Company Email</td>
                     </tr>
                     <tr>
                          <td>abc@dsewa.com.np</td>
                    </tr>
                      <tr>
                        <td>Company Website</td>
                      <tr>
                        <td>www.abc.com.np</td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                    </tr>
                    <tr>
                        <td>9841553383</td>
                    </tr>
                    <tr>
                        <td>9841553383</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                    </tr>
                    <tr>
                        <td>Address 1</td><br>
                        
                    </tr>
                    <tr>
                        <td>Address 2</td>
                    </tr>
                  </table>
                </fieldset>
          </div>
          <div class="col-md-7">             
                <fieldset class="scheduler-border" style="height: 93%;width: 100%;">
                  <legend class="w-auto">Return</legend>
                    <div class="row" style="margin:-2%;">
                        <input type="text" class="form-control" placeholder="Company Name">
                            <br>
                            <br>
                        <input type="text" class="form-control" placeholder="Company Email">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Company Website">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Contact Number">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Alternative Contact Number">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Address Details">
                        <br>
                        <br>
                        <input type="text" class="form-control" placeholder="Complete Address">
                    </div>
                </fieldset>
            </div>
            <div class="down" style="margin-left:62%;">
                <button type="submit" class="btn btn-primary">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" style=" background-color: #ec1313;">Cancel</button>
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
.card-body[data-v-0f8ffe60] {
    flex: 1 1 auto;
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: auto;
    padding: 1.25rem;
}
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #fff;
}
.justify-content-center {
    justify-content: center !important;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
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
.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
.form-group {
    margin-bottom: 1rem;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
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
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
</style>
</style>