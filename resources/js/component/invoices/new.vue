 <script setup>

import { onMounted,ref } from "vue";
import { stringifyQuery } from "vue-router";
import {useRouter} from "vue-router";
const router = useRouter()

let form=ref([])
let allcustomer = ref([])
let customer_id = ref ([])
let item = ref([])
let listcart = ref([])
const showModel =ref(false)
const hideModel = ref(true)
let listproducts = ref([])
onMounted(async ()=>{
    indexForm()
    getAllCustomers()
    getProducts()
})
const indexForm =async()=>{


   let response = await axios.get('/api/create_invoice');
   form.value = response.data;
}

const getAllCustomers = async () =>{

let response = await axios.get('/api/customers');
  allcustomer.value = response.data.customers;
}

const addcart = (item)=>{



    const itemcart = {

        id:item.id,
        item_code:item.item_code,
        description:item.description,
        unit_price:item.unit_price,
        quantity:item.quantity,
    }
    listcart.value.push(itemcart)
    closeModel()
}
const openModel =()=>{

    showModel.value = !showModel.value
}
const closeModel =()=>{

    showModel.value = !hideModel.value
}
const getProducts = async () =>{

let response = await axios.get('/api/products')
listproducts.value = response.data.products;

}
const removeitem = (item)=>{
 listcart.value.splice(item);

}
const subtotal=()=>{

   let total = 0;
    listcart.value.map((data)=>{

             total= total + (data.quantity*data.unit_price)

    })
    return total
}
const grandtotal  = () =>{
return subtotal() - form.value.discount;

}

const onsave=()=>{

if(listcart.value.length >=1){

    let sub = 0
    sub  = subtotal()

    let tot = 0

    tot = grandtotal()

    const formData= new FormData()
    formData.append('invoice_item',JSON.stringify(listcart.value))
    formData.append('customer_id',customer_id.value)
    formData.append('date',form.value.date)
    formData.append('due_date',form.value.due_date)
    formData.append('number',form.value.number)
    formData.append('reference',form.value.reference)
    formData.append('discount',form.value.discount)
    formData.append('sub_total',sub)
    formData.append('total',tot)
    formData.append('terms_and_conditions',form.value.terms_and_conditions)
   console.log('product',formData);

    axios.post('/api/add_invoice',formData)
    listcart.value = [];
    router.push('/')




}

}
 </script>


<template>
     <!--==================== NEW INVOICE ====================-->
     <div class="container">
    <div class="invoices">

        <div class="card__header">
            <div>
                <h2 class="invoice__title">New Invoice</h2>
            </div>
            <div>

            </div>
        </div>

        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">Customer</p>
                    <select name="" id="" class="input" v-model="customer_id">
                        <option disabled>Select Customer</option>
                        <option v-for="customer in allcustomer" :key="customer" :value="customer.id">{{customer.first_name}}</option>
                    </select>
                </div>
                <div>
                    <p class="my-1">Date</p>
                    <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                    <p class="my-1">Due Date</p>
                    <input id="due_date" type="date" class="input" v-model="form.due_date">
                </div>
                <div>
                    <p class="my-1">Numero</p>
                    <input type="text" class="input" v-model="form.number">
                    <p class="my-1">Reference(Optional)</p>
                    <input type="text" class="input" v-model="form.reference">
                </div>
            </div>
            <br><br>
            <div class="table">

                <div class="table--heading2">
                    <p>Item Description</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                    <p></p>
                </div>

                <!-- item 1 -->
                <div class="table--items2" v-for="(itemcart,i) in listcart" :key=i>
                    <p>{{itemcart.item_code}}{{itemcart.description}}</p>
                    <p>
                        <input type="text" class="input" v-model="itemcart.unit_price" >
                    </p>
                    <p>
                        <input type="text" class="input" v-model="itemcart.quantity" >
                    </p>
                    <p v-if="itemcart.quantity">
                        $ {{(itemcart.quantity)*(itemcart.unit_price)}}
                    </p>
                    <p v-else></p>
                    <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeitem(i)">
                        &times;
                    </p>
                </div>
                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="openModel()">Add New Line</button>
                </div>
            </div>

            <div class="table__footer">
                <div class="document-footer" >
                    <p>Terms and Conditions</p>
                    <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions" ></textarea>
                </div>
                <div>
                    <div class="table__footer--subtotal">
                        <p>Sub Total</p>
                        <span>{{subtotal()}}</span>
                        <input type="hidden" value="0" v-model="form.sub_total">
                    </div>
                    <div class="table__footer--discount">
                        <p>Discount</p>
                        <input type="text" class="input" v-model="form.discount">
                    </div>
                    <div class="table__footer--total">
                        <p>Grand Total</p>
                        <span>$ {{grandtotal()}}</span>
                        <input type="hidden" value="9" v-model="form.total">
                    </div>
                </div>
            </div>


        </div>
        <div class="card__header" style="margin-top: 40px;">
            <div>

            </div>
            <div>
                <a class="btn btn-secondary"  @click="onsave()">
                    Save
                </a>
            </div>
        </div>

    </div>
    <!--==================== add modal items ====================-->
    <div class="modal main__modal  " :class="{show:showModel}">
        <div class="modal__content">
            <span class="modal__close btn__close--modal"  @click="closeModel()">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <div class="modal__items" >
                <ul style="list-style:none">
                <li v-for="(item,i) in listproducts" :key="i" style="display:grid;grid-template-columns:30px 350px 15px;align-items:center;padding-bottom:5px;padding-right:5px;">
                  <p>{{i+1}}</p>
                  <a href="#">{{item.item_code}}{{item.description}}</a>
                  <button @click="addcart(item)" style="border:1px soid black;background-color:red;width:80px;margin-right:5px;color:white">AddtoCart</button>
                </li>
                </ul>
            </div>
            <br><hr>
            <div class="model__footer">
                <button class="btn btn-light mr-2 btn__close--modal"  @click="closeModel()">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal ">Save</button>
            </div>
        </div>
    </div>

    <br><br><br>
    </div>
</template>
