import { createRouter,createWebHistory } from "vue-router";

 import InvoiceIndex from '../component/invoices/Index.vue'
 import NotFound from '../component/NotFound.vue';
 import New from '../component/invoices/new.vue';
 import show from '../component/invoices/show.vue';
 import Edit from '../component/invoices/edit.vue';


const routes=[
    {

        path:'/',
        component:InvoiceIndex
   },
   {

    path:'/invoice/new',
    component:New
},

   {

    path:'/invoice/show/:id',
    component:show,
    props:true
},
{

    path:'/invoice/edit/:id',
    component:Edit,
    props:true
},
   {
    path:'/:pathMatch(.*)*',
    component:NotFound
   }
]

const router = createRouter({
    history:createWebHistory(),
    routes
})
export default router
