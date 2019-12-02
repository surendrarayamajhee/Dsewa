<template>
     <div class="model2">
             
<div class="modal-content" style="margin-left:35%">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add New Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body"> 
                <div class="row">
                  <form style="margin-left: 2%; width: 95%;">
                  <fieldset class="scheduler-border">
                    <legend  class="w-auto">Personal Information</legend>
                  <div class="row">
                    <div class="col">
                      <input type="text" class="form-control" placeholder="First name">
                      <br>
                      <input type="text" class="form-control" placeholder="Contact Number 1">
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Last Name">
                      <br>
                       <input type="text" class="form-control" placeholder="Contact Number 2">
                  </div>
               </div>
                </fieldset>
              
    <fieldset class="scheduler-border" style="height: 70%;">
      <legend  class="w-auto">Address Details</legend>
	      <div class="row">
          <div class="col">
          <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
            <option selected>State</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
      &nbsp;
      <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
        <option selected>Municipality</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
    </div>
<br>
    <div class="col">
      <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
        <option selected>District</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
      &nbsp;
      <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
        <option selected>Ward Number</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
    </div>
	</div>
    <br>  
  <div class="row">
	  <div class="col">
      <textarea class="address" rows='3' placeholder="Address Description"></textarea>
	  </div>
    <div class="col">
      <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
        <option selected>Area</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
   </div>
  </div>
</fieldset>
</form>
<div class="down" style="margin-left:57%; margin-top:-3%;">
<button type="submit" class="btn btn-primary">Submit</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button data-v-174508b8="" type="submit" class="btn btn-danger" style=" background-color: #ec1313;">Cancel</button>
                </div>
                </div>
              </div>
          </div>
     </div>
</template>

<style scoped>
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 42%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}
.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding-bottom: 5%;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
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
    max-width: 100%;
    padding: 0;
    margin-bottom: .5rem;
    font-size: 16px;
    font-weight: bold;
    line-height: inherit;
    color: inherit;
    white-space: normal;
}
.address {
    border-radius: 1%;
    box-shadow: none;
    height: 150%;
    width: -webkit-fill-available;
}
.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 2%;
    font-size: 15px;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

</style>