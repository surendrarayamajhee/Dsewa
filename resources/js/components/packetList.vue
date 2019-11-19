<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
               <div class="container">
                  <div class="card-bady">
                      <div class="card-header">
                          <div class="row">
                              <ul class="nav order-nav">
                                  <li class="nav-item">
                                      <h2>Packet List</h2>
                                  </li>
                                   <li>
                                      <button
                                            type="button"
                                            class="btn btn-success pickup-btn"
                                            data-toggle="modal"
                                            @click="newPickUp">
                                            Create New Packet</button>
                                  </li>
                                   <li>
                                      <button class="btn btn-success">Bulk Order List</button>
                                  </li>
                                  <li>
                                      <button class="btn btn-success">Bulk Order Upload</button>
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
                                      <button class="btn btn-success">Request</button>
                                  </li>
                                  <li>
                                      <button class="btn btn-danger">Delete All</button>
                                  </li>
                                  <li>
                                      <div class="form-group col-md-12">
                                          <select id="phone-number" name="order_pickup_point"  class="form-control">
                                              <option value disabled="disabled">Select PickUp Location</option>
                                              <option value="16">Kathmandu Swapanita Sapkota</option>
                                              <option value="17">Kathmandu Suren R.M</option>
                                          </select>
                                      </div>
                                  </li>
                                   <span style="float:right; right;padding-top: 1%;">*Mark checkbox to select packets to request pickup service</span>
                              </ul>
                          </div>
                      </div>
                      <div class="card">
                          <div class="row">
                              <div class="table- resposive-md pickup-table" style="padding-left:20px;padding-top:15px;">
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
</template>
<style>
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

