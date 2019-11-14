<template>
<div class="content-body">
  <div class="container-fluid mt-3">      
<div class="row justify-content-center">
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2>
          <center>Create New Packet</center>
        </h2>
      </div>
      <div class="card-body">
        <form
          @submit.prevent="onsubmitpicup"
          @keydown="picup.errors.clear($event.target.name)"
          @click="picup.errors.clear($event.target.name)"
        >
          <div class="row">
            <div class="form-group col-md-6">
              <label for="phone-number">Phone Number</label>
              <v-select
                :options="usersphone"
                label="phone1"
                placeholder="Select Phone No"
                v-model="selected"
                @input="setuseraddressid($event)"
              >
                <template slot="selected-option" slot-scope="option">
                  <div class="flex">
                    <div class="col">
                      <span>{{ option.phone1 }} -> {{ option.first_name }} {{ option.last_name }}</span>
                    </div>
                  </div>
                </template>
                <template slot="option" slot-scope="option">
                  <span>{{ option.phone1 }} -> {{ option.first_name }} {{ option.last_name }}</span>
                </template>
              </v-select>

              <span
                class="help is-danger"
                v-if="picup.errors.has('useraddress_id')"
                v-text="picup.errors.get('useraddress_id')"
              ></span>
              <button
                type="button"
                class="btn btn-primary pickup-btn"
                data-toggle="modal"
                @click="newPickUp"
              >New Customer</button>
            </div>

            <hr />
            <h6></h6>
            <div class="form-group col-md-6">
              <label for="mobile2">Product Description</label>

              <textarea
                type="text"
                class="form-control"
                placeholder="Description"
                rows="4"
                name="description"
                v-model="picup.description"
                :class="{ 'is-invalid': picup.errors.has('description') }"
              ></textarea>
              <span
                class="help is-danger"
                v-if="picup.errors.has('description')"
                v-text="picup.errors.get('description')"
              ></span>
            </div>

            <div class="form-group col-md-6">
              <label for="municipality">Handling</label>
              <select
                class="form-control"
                name="handling"
                v-model="picup.handling"
                :class="{ 'is-invalid': picup.errors.has('handling') }"
              >
                <option value disabled>Select</option>
                <option value="NON_FRAGILE" selected>Non-Fragile</option>

                <option value="FRAGILE">Fragile</option>
              </select>
              <span
                class="help is-danger"
                v-if="picup.errors.has('handling')"
                v-text="picup.errors.get('handling')"
              ></span>
            </div>

            <div class="form-group col-md-6">
              <label for="mobile2">COD Value</label>

              <input
                type="number"
                class="form-control"
                placeholder="COD Value"
                name="cod"
                min="0"
                v-model="picup.cod"
                :class="{ 'is-invalid': picup.errors.has('cod') }"
              />
              <span
                class="help is-danger"
                v-if="picup.errors.has('cod')"
                v-text="picup.errors.get('cod')"
              ></span>
            </div>
            <div class="form-group col-md-6">
              <label for="mobile2">Unit/Weight (In Kg)</label>

              <input
                type="number"
                class="form-control"
                placeholder="Unit/Weight"
                name="weight"
                min="1"
                v-model="picup.weight"
                :class="{ 'is-invalid': picup.errors.has('weight') }"
              />
              <span
                class="help is-danger"
                v-if="picup.errors.has('weight')"
                v-text="picup.errors.get('weight')"
              ></span>
            </div>

            <div class="form-group col-md-6" style="position: relative">
              <label for="mobile2">Customer's Expected Delivery date</label>

              <date-picker
                name="expected_date"
                v-model="picup.expected_date"
                :config="options"
                :class="{ 'is-invalid': picup.errors.has('expected_date') }"
              ></date-picker>
              <span
                class="help is-danger"
                v-if="picup.errors.has('expected_date')"
                v-text="picup.errors.get('expected_date')"
              ></span>
            </div>
            <div class="form-group col-md-6">
              <label for="municipality">Product Type</label>
              <select
                class="form-control"
                name="product_type"
                multiple="multiple"
                v-model="picup.product_type"
              >
                <option value disabled>Select</option>
                <option
                  v-for="product in products"
                  :value="product.name"
                  :key="product.id"
                >{{ product.name }}</option>
              </select>
              <span
                class="help is-danger"
                v-if="picup.errors.has('product_type')"
                v-text="picup.errors.get('product_type')"
              ></span>
            </div>
            <div class="form-group col-md-6">
              <label for="mobile2">Vendor Order Id</label>

              <input
                type="text"
                class="form-control"
                placeholder="Vendor Order Id"
                v-model="picup.vendor_order_id"
                :class="{ 'is-invalid': picup.errors.has('vendor_order_id') }"
              />
              <span
                class="help is-danger"
                v-if="picup.errors.has('vendor_order_id')"
                v-text="picup.errors.get('vendor_order_id')"
              ></span>
            </div>
          </div>
          <button class="btn btn-primary btn-load" :disabled="submit_loading">
            <span :class="{ 'spinner-border spinner-border-sm': submit_loading }"></span> Submit
          </button>
        </form>
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">First Name</label>
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
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">last Name</label>

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
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>State</label>
                      <select
                        class="form-control"
                        v-model="address.state"
                        @change="getAddressbyDistrict"
                        :class="{ 'is-invalid': address.errors.has('state') }"
                      >
                        <option value>select</option>
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
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="district">District</label>
                      <select
                        class="form-control"
                        id="district"
                        name="district"
                        v-model="address.district"
                        @change="changeAddressMunicipality"
                        :class="{ 'is-invalid': address.errors.has('district') }"
                      >
                        <option value>select</option>
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
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="municipality">Municipality</label>
                      <select
                        class="form-control"
                        id="municipality"
                        name="municipality"
                        v-model="address.municipality"
                        @change="changeAddressWardno"
                        :class="{ 'is-invalid': address.errors.has('municipality') }"
                      >
                        <option value>select</option>
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
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="municipality">Ward No</label>
                      <select
                        class="form-control"
                        id="tole"
                        name="ward_no"
                        @change="changeAddressArea"
                        v-model="address.ward_no"
                        :class="{ 'is-invalid': address.errors.has('ward_no') }"
                      >
                        <option disabled value>select</option>
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="area">Area</label>
                      <div>
                        <select
                          class="form-control"
                          id="tole"
                          name="area"
                          v-model="address.area"
                          :class="{ 'is-invalid': address.errors.has('area') }"
                        >
                          <option value>select</option>
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
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="description">Description</label>
                      <div>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Description/Street"
                          name="description"
                          v-model="address.description"
                          :class="{ 'is-invalid': address.errors.has('description') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('description')"
                          v-text="address.errors.get('description')"
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile">Mobile Number</label>
                      <div>
                        <input
                          type="number"
                          class="form-control"
                          placeholder="Mobile Number"
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
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile2">Mobile Number2</label>
                      <div>
                        <input
                          type="number"
                          class="form-control"
                          placeholder="Mobile Number 2"
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
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                  <button class="btn btn-primary" :disabled="submituser">
                    <span :class="{ 'spinner-border spinner-border-sm': submituser }"></span>Submit
                  </button>
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
<style>
.justify-content-center{
<<<<<<< HEAD
height:700px;
width: auto;
=======
  height: 700px;
  width: auto;
>>>>>>> 724ead9509e09a36efe3d59da4eeae57be5110d4
}
</style>
<style scoped>
.content-body {
    margin-left: 15.1875rem;
    z-index: 0;
}
.container-fluid {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
}
.card {
    margin-bottom: 30px;
    border: 0px;
    border-radius: 0.625rem;
    box-shadow: 6px 11px 41px -28px #a99de7;
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
.card .card-body {
    padding: 1.88rem 1.81rem;
}
.card-body {
    flex: 1 1 auto;
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: auto;
    padding: 1.25rem;
}
.card-title {
    font-size: 18px;
    font-weight: 500;
    line-height: 18px;
}
.card-title {
    margin-bottom: 0.75rem;
}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.content-body .container-fluid {
    padding: 15px 30px 0;
}
.container-fluid {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.dataTables_length {
    margin-top: 10px;
}
.dataTables_length {
    display: inline-block;
}
div.dataTables_wrapper div.dataTables_length select {
    width: 75px;
    display: inline-block;
}
.dataTables_length select {
    background-color: transparent;
    background-position: center bottom, center calc(100% - 1px);
    background-repeat: no-repeat;
    background-size: 0 2px, 100% 1px;
    border: 0 none;
    padding-bottom: 5px;
    transition: background 0s ease-out 0s;
}
.form-control-sm {
    min-height: 36px;
}
.form-control {
    border-radius: 0;
    box-shadow: none;
    height: 45px;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.dataTables_filter {
    float: right;
    margin-top: 10px;
}
div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
}
.container {
    max-width: 100%;
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
