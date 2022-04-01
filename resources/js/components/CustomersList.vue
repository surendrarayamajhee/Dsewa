<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
          
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <h3 class="user-inline">Customer List</h3>
                            <button type="button" data-toggle="modal2" class="btn btn-primary pickup-btn" @click="newPickUp" style="float:left; margin-top: -4%;margin-left: 20%;">New Customer</button>
                            <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary user-search-btn" style="float: right; margin-top: -38px;">Search</button>
                            <div id="collapseExample" class="collapse">
                                <ul class="nav address-nav">
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="district">District</label>
                                            <select name="district" class="form-control">
                                                <option value selected="selected" disabled="disabled">select</option>
                                                <option value="981">Achham</option>
                                                <option value="669">Arghakhachi</option>
                                                <option value="620">Baglung</option>
                                                <option value="952">Baitadi</option>
                                                <option value="929">Bajhang</option>
                                                <option value="919">Bhajura</option>
                                                <option value="732">Banke</option>
                                                <option value="325">Bara</option>
                                                <option value="741">Bardiya</option>
                                                <option value="465">Bhaktapur</option>
                                                <option value="84">bhojpur</option>
                                                <option value="523">Chitwan</option>

                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                        <label for="municipality">"Municipality"</label>
                                        <select id="municipality" name="municipality" class="form-control" style="width;19px;">
                                            <option value selected="selected" disabled="disabled">select</option>
                                        </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="municipality">ward No.</label>
                                            <select id="tole" name="ward_no" class="form-control">
                                                <option value>select</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                         <label for="area">Area</label>
                                         <div>
                                         <select id="tole" name="area" class="form-control">
                                             <option value>select</option>
                                         </select>    
                                        </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="paginate">paginate</label>
                                            <select type="number" placeholder="pagination" name="paginate" class="form-control">
                                                <option value selected="selected" disabled="disabled">paginate</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="number" name="phone" class="form-control"> 
                                        </div>

                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-parimary search-btn">filter</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-primary reset-btn">Reset</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>First_name</th>
                                            <th>Last_name</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Municipality</th>
                                            <th>Ward_No</th>
                                            <th>Area</th>
                                            <th>mobile1</th>
                                            <th>mobile2</th>
                                            <th>Address Detail</th>
                                            <th style="width: 8%;">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
.card-header:first-child{
    border-radius: calc(0.25rem-1px) 
    calc(0.25rem-1px) 0 0p;
}
.card-header{
    padding: 0.75rem;
    margin-bottom: 0;
    background-color:rgba(0,0,0,0.03);
    border-bottom: 1px solid rgba(0,0,0,0.125);
}
.btn-sm{
padding: 0.25rem 0.5rem;
font-size: 0.875rem;
line-height: 1.5;
border-radius: 0.2rem;
}
.btn-primary{
color:#fff;
background-color:#007bff;
border-color: #007bff;
}
.btn {
    display: inline-block;
    font-weight: 400;
    color: #f8f9fa;
    
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.btn[data-v-174508b8] {
    display: inline-block;
    font-weight: 400;
    color: #f8f9fa;
    background-color: #0062cc;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: #076ede;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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
.pagination {
    display: -webkit-box;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    margin: 10px 213px;
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
