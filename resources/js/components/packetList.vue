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
                                              <option  value selected="selected">50</option>
                                              <option value="100">100</option>
                                              <option value="150">150</option>
                                              <option value="200">200</option>
                                          </select>
                                      </div>
                                  </li>
                                   <li class="nav-item">
                                     <a
                                      @click="onSubmitCheckbox"
                                      class="btn btn-outline-success btn-sm"
                                      data-toggle="tooltip"
                                      title="Click here to Request Pickup Service"
                                      :disabled="submit_loading"
                                      style="height: 83%;font-size: 16px;font-weight: 450; color: aliceblue; background-color: green;"                                             
                                      >
                                         Request
                                        <span :class="{ 'spinner-border spinner-border-sm': submit_loading }"></span>
                                      </a>
                                      <span class="tooltiptext"></span>
                                      <!-- <button class="btn btn-primary pickup-btn">Request</button> -->
                                  </li>
                                  <li>
                                      <button class="btn btn-danger" @click="bulkdelete">Delete All</button>
                                  </li>
                                  <li>
                                      <div class="form-group col-md-12">
                                          <select 
                                              id="phone-number" 
                                              class="form-control" 
                                              placeholder="Select PickUp"
                                              style="margin-left:81%;"
                                              name="order_pickup_point" 
                                               :class="{ 'is-invalid': check.errors.has('order_pickup_point') }"
                                                >
                                             <option>Select PickUp Location</option>
                                              <option
                                                v-for="address in pickupaddresses"
                                                :key="address.id"
                                                :value="address.id"
                                              >{{ address.district}} {{ address.area}} {{ address.description }}</option>
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
     
       <!-- {{--  second model start  --}}-->
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
                      <input
                        type="text"
                        class="form-control"
                        placeholder="First Name"
                        name="first_name"
                        v-model="address.first_name"
                        :class="{ 'is-invalid': address.errors.has('first_name') }"
                      />
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('first_name')"
                        v-text="address.errors.get('first_name')"
                      ></span>
                      <br>
                      <input
                          type="number"
                          class="form-control"
                          placeholder="Contact Number 1"
                          name="phone1"
                          v-model="address.phone1"
                          :class="{ 'is-invalid': address.errors.has('phone1') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('phone1')"
                          v-text="address.errors.get('phone1')"
                        ></span>
                    </div>
                    <div class="col">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="last Name"
                        name="last_name"
                        v-model="address.last_name"
                        :class="{ 'is-invalid': address.errors.has('last_name') }"
                      />
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('last_name')"
                        v-text="address.errors.get('last_name')"
                      ></span>
                      <br>
                       <input
                          type="number"
                          class="form-control"
                          placeholder="Contact Number 2"
                          name="phone2"
                          v-model="address.phone2"
                          :class="{ 'is-invalid': address.errors.has('phone2') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('phone2')"
                          v-text="address.errors.get('phone2')"
                        ></span>
                  </div>
               </div>
                </fieldset>
              
    <fieldset class="scheduler-border" style="height: 75%;">
      <legend  class="w-auto">Address Details</legend>
	      <div class="row">
          <div class="col">
           <select
                        class="form-control"
                        v-model="address.state"
                        @change="getAddressbyDistrict"
                        :class="{ 'is-invalid': address.errors.has('state') }"
                      >
                        <option value>Select State</option>
                        <option
                          v-for="state in states"
                          :key="state.id"
                          :value="state.id"
                        >{{ state.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('state')"
                        v-text="address.errors.get('state')"
                      ></span>
      &nbsp;
      <select
                        class="form-control"
                        id="municipality"
                        name="municipality"
                        v-model="address.municipality"
                        @change="changeAddressWardno"
                        :class="{ 'is-invalid': address.errors.has('municipality') }"
                      >
                        <option value>Select Municipality</option>
                        <option
                          v-for="municipality in municipalitys"
                          :key="municipality.id"
                          :value="municipality.id"
                        >{{ municipality.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('municipality')"
                        v-text="address.errors.get('municipality')"
                      ></span>
    </div>
<br>
    <div class="col">
      <select
                        class="form-control"
                        id="district"
                        name="district"
                        v-model="address.district"
                        @change="changeAddressMunicipality"
                        :class="{ 'is-invalid': address.errors.has('district') }"
                      >
                        <option value>Select District</option>
                        <option
                          v-for="district in districts"
                          :key="district.id"
                          :value="district.id"
                        >{{ district.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('district')"
                        v-text="address.errors.get('district')"
                      ></span>
      &nbsp;
      <select
                        class="form-control"
                        id="tole"
                        name="ward_no"
                        @change="changeAddressArea"
                        v-model="address.ward_no"
                        :class="{ 'is-invalid': address.errors.has('ward_no') }"
                      >
                        <option disabled value>Select Ward No</option>
                        <option
                          v-for="ward in wards"
                          :key="ward.id"
                          :value="ward.id"
                          :disabled="ward.name"
                        >{{ ward.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('ward_no')"
                        v-text="address.errors.get('ward_no')"
                      ></span>
    </div>
	</div>
    <br>  
  <div class="row">
	  <div class="col">
      <input
                          type="text"
                          class="address"
                          placeholder="Description/Street"
                          name="description"
                          style="width: 100%;height: 120%;"
                          v-model="address.description"
                          :class="{ 'is-invalid': address.errors.has('description') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('description')"
                          v-text="address.errors.get('description')"
                        ></span>
	  </div>
    <div class="col">
      <select
                          class="form-control"
                          id="tole"
                          name="area"
                          v-model="address.area"
                          :class="{ 'is-invalid': address.errors.has('area') }"
                        >
                          <option value>Select Area</option>
                          <option
                            v-for="area in areas"
                            :key="area.id"
                            :value="area.id"
                          >{{ area.address }}</option>
                        </select>
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('area')"
                          v-text="address.errors.get('area')"
                        ></span>
   </div>
  </div>
</fieldset>
</form>
<div class="down" style="margin-left:60%;">
<button class="btn btn-primary" :disabled="submituser">
                    <span :class="{ 'spinner-border spinner-border-sm': submituser }"></span>Submit
                  </button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-danger" style=" background-color: #ec1313;" data-dismiss="modal">Close</button>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- {{ --2ndmodelend-- }} -->
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
export default {
  data() {
    return {
      paginate: "",
      pagination: {},

      orders: {},
      order: {},
      check: new Form({
        checkbox: [],
        order_pickup_point: ""
      }),
      editorder: new Form({
        phone: "",
        description: "",
        handling: "",
        product_type: [],
        cod: "",
        expected_date: "",
        order_pickup_point: "",
        weight: ""
      }),
      OrderId: "",
      options: {
        format: "YYYY-MM-DD h:mm:ss",
        useCurrent: true,
        showClear: true,
        showClose: true
      },
      products: {},
      submit_loading: false,
      pickupaddresses: {},
      address: new Form({
        first_name: "",
        last_name: "",
        district: "",
        municipality: "",
        area: "",
        ward_no: "",
        description: "",
        phone1: "",
        phone2: ""
      }),
      districts: {},
      municipalitys: {},
      wards: {},
      areas: {},
      useraddressId: "",
      i: "",
      pickupaddresses: {},
      currentpage: 1
    };
  },

  computed: {
    selectAll: {
      get: function() {
        return this.orders
          ? this.check.checkbox.length == this.orders.length
          : false;
      },
      set: function(value) {
        var checkbox = [];
        if (value) {
          this.orders.forEach(function(address) {
            checkbox.push(address.id);
          });
        }
        this.check.checkbox = checkbox;
      }
    }
  },

  created() {
    this.$root["loading"] = true;
    this.getdefaultpicupaddress();

    this.getPicupOrder();
    // this.UpdateOrder();
    this.getVendorpicupaddress();

    this.producttypebyvendor();
    this.$root["loading"] = false;
  },
  methods: {
    edituseraddress(i, useraddress) {
      this.i = i;
      this.address.fill(useraddress);
      this.useraddressId = useraddress.id;
      this.getAddressbyDistrict();
      this.changeAddressMunicipality_a();
      this.changeAddressWardno_a();
      this.changeAddressArea_a();
      $("#useraddress").modal("show");
    },
    getdefaultpicupaddress() {
      let default_location = "";
      axios
        .get("api/get-default-vendoraddress")
        .then(res => {
          res.data.map(function(value, key) {
            if (value.is_default) {
              default_location = value.id;
            }
          });
          this.check.order_pickup_point = default_location;
        })

        .catch(res => {});
    },
    getPicupOrder(page) {
      if (typeof page === undefined) {
        page = 1;
      }
      this.currentpage = page;
      axios
        .get("/api/getpicuporder", {
          params: { paginate: this.paginate, page: page }
        })
        .then(response => {
          this.orders = response.data.data;
          // console.log(response.data)
          this.pagination = response.data;
        });
    },
    onSubmitCheckbox() {
      this.submit_loading = true;

      this.check
        .post("/api/sendpicorderrequest")
        .then(res => {
          if (res.status == 200) {
            this.check.reset();
            this.getPicupOrder();
            this.success(res.data.success);
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.getdefaultpicupaddress();
          this.submit_loading = false;
        })
        .catch(res => {
          this.submit_loading = false;
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
    },

    OnEditOrder(i, order) {
      this.i = i;
      this.OrderId = order.id;
      this.getVendorpicupaddress();
      this.editorder.fill(order);
      $("#picuporder").modal("show");
    },
    UpdateOrder() {
      this.submit_loading = true;

      this.editorder
        .put("/api/updatepicuporder/" + this.OrderId)
        .then(res => {
          if (res.status == 200) {
            this.check.reset();
            this.success(res.data.success);
            this.orders.splice(this.i,1, res.data.order);
            console.log(res.data.order);
            // this.getPicupOrder();
            $("#picuporder").modal("hide");
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
    bulkdelete() {
      this.check
        .post("/api/drop-all")
        .then(res => {
          if (res.status == 200) {
            this.check.reset();
            this.success(res.data.success);
            this.getPicupOrder();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(err => {
            console.log(err);
        });
    },
    pickupdelete(index, id) {
      swalWithBootstrapButtons
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            axios
              .get("api/droppickup/" + id)
              .then(res => {
                if (res.status == 200) {
                  this.$delete(this.orders, index);
                  swalWithBootstrapButtons.fire(res.data.success);
                }
                if (res.data.error) {
                  this.error(res.data.error);
                }
              })
              .catch(res => {
                this.error(res.data.error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary file is safe :)",
              "error"
            );
          }
        });
    },
    getVendorpicupaddress() {
      axios
        .get("api/getvendoraddress")
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
    getAddressbyDistrict() {
      axios
        .get("api/getaddressbydistrict")
        .then(res => {
          this.districts = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressMunicipality_a() {
      axios
        .get("api/changeaddress/" + this.address.district)
        .then(res => {
          this.municipalitys = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressWardno_a() {
      axios
        .get("api/changeaddress/" + this.address.municipality)
        .then(res => {
          this.wards = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressArea_a() {
      axios
        .get("api/changeaddress/" + this.address.ward_no)
        .then(res => {
          this.areas = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    useraddressupdate() {
      this.address
        .put("/api/useraddressupdate/" + this.useraddressId)
        .then(res => {
          if (res.status == 200) {
            this.address.reset();
            $("#useraddress").modal("hide");
            this.success(res.data.success);
            this.getPicupOrder();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(err => {
          this.submit_loading = false;
        });
    }
  }
};
</script>
<style>
li {
  position: relative;
  padding-left: 11px;
}
#btn {
  margin-top: 32px;
}
.list {
  max-width: 1250px;
}
</style>

