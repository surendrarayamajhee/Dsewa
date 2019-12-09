<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
               <div class="container">
                  <div class="card-body">
                      <div class="card-header">
                          <div class="row">
                              <ul class="nav order-nav">
                                  <li class="nav-item">
                                      <h2>Packet List</h2>
                                  </li>
                                   <li>
                                      <button
                                            type="button"
                                            class="btn btn-primary pickup-btn"
                                            data-toggle="modal2"
                                            @click="newPickUp">
                                            Create New Packet</button>
                                  </li>
                                   <li>
                                      <button class="btn btn-primary pickup-btn">
                                        <b-link target="_self" href="./bulkList">Bulk Order List</b-link>
                                      </button>
                                  </li>
                                  <li>
                                      <button class="btn btn-primary pickup-btn">
                                        <b-link target="_self" href="./bulkOrder">Bulk Order Upload</b-link>
                                      </button>
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
                                      <button class="btn btn-primary pickup-btn">Request</button>
                                  </li>
                                  <li>
                                      <button class="btn btn-danger">Delete All</button>
                                  </li>
                                  <li>
                                      <div class="form-group col-md-12">
                                          <select id="phone-number" name="order_pickup_point"  class="form-control" style="margin-left:61%;">
                                              <option value disabled="disabled">Select PickUp Location</option>
                                              <option value="16">Kathmandu Swapanita Sapkota</option>
                                              <option value="17">Kathmandu Suren R.M</option>
                                          </select>
                                      </div>
                                  </li>
                                   <span style="float:right; right;padding-top: 1%;margin-left:19%;">*Mark checkbox to select packets to request pickup service</span>
                              </ul>
                          </div>
                      </div>
                      <div class="card">
                          <div class="row">
                              <div class="table- resposive-md pickup-table" style="padding-left:20px;padding-top:15px;width:98%;">
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
     
      <div class="model2">
      <form
        @submit.prevent="onSubmitUserAddress"
        @keydown="address.errors.clear($event.target.name)"
      >
        <div
          class="modal fade bd-example-modal-lg"
          id="new_picup"
          tabindex="-1"
          role="dialog"
          aria-labelledby="exampleModalScrollableTitle"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
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
<div class="down" style="margin-left:60%; margin-top:-3%;">
<button type="submit" class="btn btn-primary">Submit</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button data-v-174508b8="" type="submit" class="btn btn-danger" style=" background-color: #ec1313;">Cancel</button>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
<!-- {{ --2ndmodelend-- }} -->
                </div>
            </div>
        </div>
</template>
<style scoped>

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
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
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 2% !important;
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
.justify-content-center {
    width: auto;
    height: 700px;
}
a {
    color: white;
    text-decoration: none;
    background-color: transparent;
}
</style>

<script>
// import pickup from "./PickUpOrder";
export default {
  data() {
    return {
      show: true,
      picup: new Form({
        useraddress_id: "",
        description: "",
        handling: "NON_FRAGILE",
        product_type: [],
        cod: "",
        expected_date: "",
        order_pickup_point: "",
        weight: 1,
        vendor_order_id: ""
      }),
      address: new Form({
        first_name: "",
        last_name: "",
        state: "",
        district: "",
        municipality: "",
        area: "",
        ward_no: "",
        description: "",
        phone1: "",
        phone2: ""
      }),
      pickupaddresses: {},
      usersphone: [],
      districts: {},
      municipalitys: {},
      wards: {},
      areas: {},
      options: {
        format: "YYYY-MM-DD h:mm:ss",
        useCurrent: true,
        showClear: true,
        showClose: true
      },
      products: {},
      submit_loading: false,
      submituser: false,
      edit: false,
      selected: "",
      states: {}
    };
  },

  created() {
    this.$root["loading"] = true;

    this.fetchall();
    this.$root["loading"] = false;
  },
  methods: {
    rrreset() {
      console.log("sdcsd");
      // this.picup.order_pickup_point = e.id;
      this.selected = {
        id: 1,
        phone1: "9801907043",
        first_name: "Sisam",
        last_name: "Kc"
      };
    },
    setuseraddressid(e) {
      if (e) {
        this.picup.useraddress_id = e.id;
      } else {
        this.picup.useraddress_id = "";
      }
    },
    fetchall() {
      this.fetchUsersphone();
      this.getAddressbystate();
      this.getAddressbyDistrict();
      this.producttypebyvendor();
      this.getVendorpicupaddress();
    },
    newPickUp() {
      this.address.reset();
      this.picup.reset();
      this.edit = false;
      this.address.errors.clear();
      $("#new_picup").modal("show");
    },

    fetchUsersphone() {
      axios
        .get("api/getusersphone")
        .then(({ data }) => (this.usersphone = data))
        .catch(res => {
          this.error(res.data.error);
        });
    },

    onSubmitUserAddress() {
      this.submituser = true;
      var submit = "";
      if (this.edit) {
        submit = this.address.put(
          "/api/useraddressupdate/" + this.picup.useraddress_id
        );
      } else {
        submit = this.address.post("/api/useraddress/store");
      }
      submit
        .then(res => {
          if (res.status == 200) {
            $("#new_picup").modal("hide");
            this.success(res.data.success);
            if (!this.edit) {
              this.selected = res.data.id;
              this.picup.useraddress_id = res.data.id.id;
            }
            this.fetchall();
            this.address.reset();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submituser = false;
        })
        .catch(res => {
          this.submituser = false;
        });
    },
    onsubmitpicup() {
      this.submit_loading = true;
      this.picup
        .post("/api/pickupStore")
        .then(res => {
          if (res.status == 200) {
            $("#exampleModalScrollable").modal("hide");

            this.picup.reset();
            this.picup.useraddress_id = null;
            this.selected = null;
            // console.log(res.data.success)
            this.success(res.data.success);
            this.fetchall();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(res => {
          this.submit_loading = false;
        });
    },
    fillUserAddress() {
      this.edit = true;

      axios
        .get("api/useraddress", {
          params: { id: this.picup.useraddress_id }
        })
        .then(data => {
          this.address.fill(data.data);

          this.getAddressbyDistrict();
          this.getAddressbyDistrict();
          this.changeAddressMunicipality();
          this.changeAddressWardno();
          this.changeAddressArea();
          $("#new_picup").modal("show");
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    updateComponent() {
      var self = this;
      self.show = false;

      Vue.nextTick(function() {
        self.show = true;
      });
    },
    getAddressbystate() {
      axios
        .get("api/getaddressbystate")
        .then(res => {
          this.states = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    getAddressbyDistrict() {
      axios
        .get("api/changeaddress/" + this.address.state)

        .then(res => {
          this.districts = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressMunicipality() {
      axios
        .get("api/changeaddress/" + this.address.district)
        .then(res => {
          this.municipalitys = res.data;
          // console.log(this.municipalitys)
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressWardno() {
      axios
        .get("api/changeaddress2/" + this.address.municipality)
        .then(res => {
          this.wards = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressArea() {
      axios
        .get("api/changeaddress/" + this.address.ward_no)
        .then(res => {
          this.areas = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    getVendorpicupaddress() {
      axios
        .get("api/get-default-vendoraddress")
        .then(res => {
          this.pickupaddresses = res.data;
        })
        .catch(res => {});
    },
    success(success) {
      Toast.fire({
        type: "success",
        title: success
      });
      // Try me!
    },
    error(error) {
      Swal.fire({
        position: "center",
        type: "error",
        title: error,
        showConfirmButton: false,
        timer: 1500
      });
    },
    producttypebyvendor() {
      axios
        .get("api/producttypebyvendor")
        .then(res => {
          this.products = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    }
  }
};
</script>

