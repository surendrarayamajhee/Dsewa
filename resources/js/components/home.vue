<template>
<div>
    <div class="content-body">
        <div class="row justify-content-center">
            <div class="container-fluid mt-3">
                <div class="row" style="padding-left: 12px">
                    <!-- first-box -->
                   <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: royalblue;">
                            <i class="fas fa-cart-plus"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Orders</span><br>
                            <span style="font-weight: bold;">100+</span>
                        </div>
                    </div>
                        <!-- second-box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: lightgreen;">
                            <i class="fas fa-clipboard-check"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Order Delivered</span>
                        </div>
                    </div>
                        <!-- Third-Box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: yellow;">
                            <i class="fas fa-shipping-fast"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Orders In Delivery Process</span>
                        </div>
                    </div>
                        <!-- Fouth-Box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: orange;">
                             <i class="fas fa-undo-alt"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Order In Return Process</span>
                        </div>
                    </div>
                    <!-- fifth-box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: green;">
                           <i class="fas fa-people-carry"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Return Order Delivered</span>
                        </div>
                    </div>
                    <!-- Six-Box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: skyblue;">
                            <i class="fas fa-exchange-alt"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Exchange Order</span>
                        </div>
                    </div>
                    <!-- Seven-Box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: grey;">
                            <i class="fas fa-hand-holding-usd"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Refund Order</span>
                        </div>
                    </div>
                    <!-- Eight-Box -->
                    <div class="flex-container">
                        <div class="Cart">
                            <span style="font-size:3em; color: red;">
                            <i class="far fa-times-circle"></i>
                            </span>
                        </div>
                        <div class="headline">
                            <span>Canceled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
                
</template>             
<style scoped>
element.style {
    width: 200px;
    height: 180px;
}
.flex-container {
  display: flex;
  flex-wrap: nowrap;
  background-color:white;
  box-shadow: 3px 3px 5px 6px #ccc;
  margin: 10px;
  padding: 4px;
  padding-left: 7px;
}

.flex-container > div {
  background-color: white;
  width: 100px;
  height: 80px;
  margin: 10px;
  text-align: center;
  box-decoration-break: clone;
}

.headline {
    padding-top: 10px;
    line-height: 1rem;
    font-family: 'Times New Roman', Times, serif;
    font-size: 18px;
    background-color: royalblue;
}
.Cart {
    padding-top: 2%;
    text-align: center;
    color: white;
}

</style>>

